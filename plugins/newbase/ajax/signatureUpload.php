<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Newbase. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2024-2026 by João Lucas
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/JoaoLucascp/Glpi
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * AJAX Endpoint - Digital Signature Upload/Delete
 *
 * Handles digital signature operations for field tasks:
 *
 * POST: Save signature
 * - Receives base64 image data (data URI) from HTML5 canvas
 * - Validates format (PNG/JPEG) and size (max 500KB)
 * - Stores in database as base64
 * - Links to corresponding task
 *
 * DELETE: Remove signature
 * - Soft deletes signature record
 * - Clears task signature field
 *
 * GET: Get signature info
 * - Returns signature metadata (without image data)
 *
 * Security:
 * - CSRF token validation (header or body)
 * - User authentication required
 * - Permission checks on task
 * - Input validation and sanitization
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\AjaxHandler;
use Session;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Set security headers
AjaxHandler::setSecurityHeaders();

// ===== MAIN EXECUTION =====

try {
    // ===== AUTHENTICATION CHECK =====
    if (!Session::getLoginUserID()) {
        AjaxHandler::sendResponse(false, __('Authentication required'), [], 401);
    }

    // ===== GET REQUEST METHOD =====
    $method = $_SERVER['REQUEST_METHOD'];

    // ===== GET REQUEST BODY =====
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    // Handle non-JSON requests (fallback to POST data)
    if ($input === null && !empty($_POST)) {
        $input = $_POST;
    }

    if (!is_array($input)) {
        $input = [];
    }

    // ===== CSRF TOKEN VALIDATION =====
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX signature upload: Invalid CSRF token\n");
        AjaxHandler::sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }

    // ===== GET TASK ID =====
    $task_id = isset($input['task_id']) ? (int) $input['task_id'] : 0;

    if ($task_id <= 0) {
        AjaxHandler::sendResponse(false, __('Task ID is required', 'newbase'), [], 400);
    }

    // ===== LOAD TASK =====
    $task = new Task();
    if (!$task->getFromDB($task_id)) {
        AjaxHandler::sendResponse(false, __('Task not found', 'newbase'), [], 404);
    }

    // ===== CHECK PERMISSIONS =====
    if (!$task->canUpdateItem()) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf("User %d tried to modify signature for task %d without permission\n", Session::getLoginUserID(), $task_id)
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to modify this task', 'newbase'), [], 403);
    }

    // ===== CHECK IF FEATURE IS ENABLED =====
    $config = Config::getConfig();
    $enable_signature = $config['enable_signature'] ?? 1;

    if (!$enable_signature) {
        AjaxHandler::sendResponse(false, __('Digital signature feature is disabled', 'newbase'), [], 403);
    }

    // ===== PROCESS REQUEST BASED ON METHOD =====
    switch ($method) {
        case 'POST':

            // ===== SAVE SIGNATURE =====

            // Get signature data
            $signature_data = $input['signature_data'] ?? '';
            $signer_name = trim($input['signer_name'] ?? '');

            // Validate signature data
            if (empty($signature_data)) {
                AjaxHandler::sendResponse(false, __('Signature data is required', 'newbase'), [], 400);
            }

            // Validate signer name
            if (empty($signer_name)) {
                AjaxHandler::sendResponse(false, __('Signer name is required', 'newbase'), [], 400);
            }

            // Validate signer name length
            if (mb_strlen($signer_name) < 3) {
                AjaxHandler::sendResponse(false, __('Signer name must be at least 3 characters', 'newbase'), [], 400);
            }

            if (mb_strlen($signer_name) > 255) {
                AjaxHandler::sendResponse(false, __('Signer name is too long (max 255 characters)', 'newbase'), [], 400);
            }

            // Validate signature format (must be data URI)
            if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $signature_data)) {
                AjaxHandler::sendResponse(false, __('Invalid signature format. Expected PNG or JPEG data URI.', 'newbase'), [], 400);
            }

            // Validate size
            $size = strlen($signature_data);
            if ($size > TaskSignature::MAX_SIGNATURE_SIZE) {
                AjaxHandler::sendResponse(
                    false,
                    sprintf(
                        __('Signature too large: %s KB (max: %s KB)', 'newbase'),
                        round($size / 1024, 2),
                        round(TaskSignature::MAX_SIGNATURE_SIZE / 1024, 2)
                    ),
                    [],
                    413
                );
            }

            // Validate actual image data
            $base64_data = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $signature_data);
            $image_data = base64_decode($base64_data, true);

            if ($image_data === false) {
                AjaxHandler::sendResponse(false, __('Invalid base64 encoding', 'newbase'), [], 400);
            }

            // Verify it's a valid image
            $image_info = @getimagesizefromstring($image_data);
            if ($image_info === false) {
                AjaxHandler::sendResponse(false, __('Invalid image data', 'newbase'), [], 400);
            }

            // Save signature using TaskSignature class
            $result = TaskSignature::saveSignature($task_id, $signature_data, $signer_name);

            if ($result === false) {
                Toolbox::logInFile('newbase_plugin', "Failed to save signature for task {$task_id}\n");
                AjaxHandler::sendResponse(false, __('Failed to save signature', 'newbase'), [], 500);
            }

            // Success
            Toolbox::logInFile(
                'newbase_plugin',
                sprintf(
                    "Signature saved for task %d by user %d: %s (%s, %dx%d)\n",
                    $task_id,
                    Session::getLoginUserID(),
                    $signer_name,
                    Toolbox::getSize($size),
                    $image_info[0],
                    $image_info[1]
                )
            );

            AjaxHandler::sendResponse(
                true,
                __('Signature saved successfully', 'newbase'),
                [
                    'signature_id' => $result,
                    'signer_name'  => $signer_name,
                    'size'         => Toolbox::getSize($size),
                    'dimensions'   => sprintf('%dx%d', $image_info[0], $image_info[1]),
                ],
                200
            );
            break;

        case 'DELETE':

            // ===== DELETE SIGNATURE =====

            // Check if signature exists
            $signature = TaskSignature::getForTask($task_id);
            if (!$signature) {
                AjaxHandler::sendResponse(false, __('No signature found for this task', 'newbase'), [], 404);
            }

            // Delete signature
            $result = TaskSignature::deleteSignature($task_id);

            if (!$result) {
                Toolbox::logInFile('newbase_plugin', "Failed to delete signature for task {$task_id}\n");
                AjaxHandler::sendResponse(false, __('Failed to delete signature', 'newbase'), [], 500);
            }

            // Success
            Toolbox::logInFile(
                'newbase_plugin',
                sprintf(
                    "Signature deleted for task %d by user %d\n",
                    $task_id,
                    Session::getLoginUserID()
                )
            );

            AjaxHandler::sendResponse(true, __('Signature deleted successfully', 'newbase'), [], 200);
            break;

        case 'GET':

            // ===== GET SIGNATURE INFO =====

            $signature = TaskSignature::getForTask($task_id);

            if (!$signature) {
                AjaxHandler::sendResponse(false, __('No signature found for this task', 'newbase'), [], 404);
            }

            AjaxHandler::sendResponse(
                true,
                __('Signature found', 'newbase'),
                [
                    'signature_id' => $signature['id'],
                    'signer_name'  => $signature['signer_name'],
                    'date_created' => $signature['date_creation'],
                    'user_id'      => $signature['users_id'],
                    'size'         => Toolbox::getSize(strlen($signature['signature_data'])),
                ],
                200
            );
            break;

        default:
            // ===== METHOD NOT ALLOWED =====
            AjaxHandler::sendResponse(
                false,
                sprintf(__('Method %s not allowed', 'newbase'), $method),
                [],
                405
            );
            break;
    }
} catch (\Exception $e) {

    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in signatureUpload.php: %s\n",
            $e->getMessage()
        )
    );

    $response_data = [];

    // Include error details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $response_data['error'] = $e->getMessage();
        $response_data['trace'] = $e->getTraceAsString();
    }

    AjaxHandler::sendResponse(
        false,
        __('An error occurred while processing the signature', 'newbase'),
        $response_data,
        500
    );
}
