<?php

/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2026 Teclib' and contributors.
 * http://glpi-project.org
 *
 * based on GLPI - Copyright (C) 2003-2014 by the INDEPNET Development Team.
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * AJAX - Task Lifecycle Actions - Newbase Plugin
 *
 * Manages task status transitions:
 * Workflow: open -> start -> in_progress -> complete -> completed
 *           pause -> paused -> reopen -> resume -> in_progress
 *
 * Validations:
 * - Only valid transitions allowed
 * - Digital signature required for completion (if configured)
 * - Permissions verified
 *
 * @package Plugin
 * @subpackage Newbase
 * @author João Lucas
 * @license GPLv2
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\AjaxHandler;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Common; // Importante: usar Common para validar GPS
use Session;
use Toolbox;
use Exception;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Set security headers
AjaxHandler::setSecurityHeaders();

// ==========================================
// SECURITY VALIDATIONS
// ==========================================

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    AjaxHandler::sendResponse(false, __('Only POST requests are allowed', 'newbase'), [], 405);
}

// CSRF Token validation
if (!AjaxHandler::checkCSRFToken()) {
    Toolbox::logInFile('newbase_plugin', "AJAX task actions: Invalid CSRF token");
    AjaxHandler::sendResponse(false, __('Security token invalid or expired', 'newbase'), [], 403);
}

// ==========================================
// PARAMETER VALIDATION
// ==========================================

try {
    // Get parameters
    $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);
    $action  = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if (!$task_id || $task_id <= 0) {
        AjaxHandler::sendResponse(false, __('Task ID is required', 'newbase'), [], 400);
    }

    if (empty($action)) {
        AjaxHandler::sendResponse(false, __('Action is required', 'newbase'), [], 400);
    }

    // LOAD TASK FROM DATABASE
    $task = new Task();
    if (!$task->getFromDB($task_id)) {
        AjaxHandler::sendResponse(false, __('Task not found', 'newbase'), [], 404);
    }

    // CHECK PERMISSIONS
    if (!$task->canUpdateItem()) {
        AjaxHandler::sendResponse(false, __('You do not have permission to update this task', 'newbase'), [], 403);
    }

    // GET CURRENT STATUS
    $current_status = $task->fields['status'];
    $update_data    = [
        'id' => $task_id
    ];

    $success_message = '';

    // PROCESS ACTIONS
    switch ($action) {

        // ACTION: START TASK
        case 'start':
            // Validate transition
            if ($current_status !== 'pending') {
                AjaxHandler::sendResponse(
                    false,
                    __('Task must be in Pending status to start', 'newbase'),
                    ['current_status' => $current_status],
                    400
                );
            }

            // Capture start coordinates if sent
            if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start'])) {
                $lat_start = (float) $_POST['latitude_start'];
                $lng_start = (float) $_POST['longitude_start'];

                // ERRO 11: Validar se as coordenadas estão dentro do range válido
                if (!Common::validateCoordinates($lat_start, $lng_start)) {
                    AjaxHandler::sendResponse(
                        false,
                        __('Invalid GPS coordinates for start position. Latitude must be between -90 and 90, longitude between -180 and 180.', 'newbase'),
                        [
                            'latitude_start' => $lat_start,
                            'longitude_start' => $lng_start,
                            'valid_ranges' => ['latitude' => [-90, 90], 'longitude' => [-180, 180]]
                        ],
                        400 // Bad Request
                    );
                }

                $update_data['gps_start_lat'] = $lat_start;
                $update_data['gps_start_lng'] = $lng_start;
            }

            $update_data['status']     = 'inprogress';
            $update_data['date_begin'] = $_SESSION['glpi_currenttime'];
            $success_message           = __('Task started successfully', 'newbase');
            break;

        // ACTION: PAUSE TASK
        case 'pause':
            if ($current_status !== 'inprogress') {
                AjaxHandler::sendResponse(
                    false,
                    __('Task must be in progress to pause', 'newbase'),
                    ['current_status' => $current_status],
                    400
                );
            }

            $update_data['status'] = 'paused';
            $success_message       = __('Task paused successfully', 'newbase');
            break;

        // ACTION: RESUME TASK
        case 'resume':
            if ($current_status !== 'paused') {
                AjaxHandler::sendResponse(
                    false,
                    __('Task must be paused to resume', 'newbase'),
                    ['current_status' => $current_status],
                    400
                );
            }

            $update_data['status'] = 'inprogress';
            $success_message       = __('Task resumed successfully', 'newbase');
            break;

        // ACTION: COMPLETE TASK
        case 'complete':
            // Validate transition
            if (!in_array($current_status, ['pending', 'inprogress', 'paused'], true)) {
                AjaxHandler::sendResponse(
                    false,
                    __('Task cannot be completed from current status', 'newbase'),
                    ['current_status' => $current_status],
                    400
                );
            }

            // Check if signature is required
            if (Config::getConfigValue('require_signature', 0) == 1) {
                $signature = TaskSignature::getForTask($task_id);
                if (!$signature) {
                    AjaxHandler::sendResponse(
                        false,
                        __('Digital signature is required to complete task', 'newbase'),
                        ['require_signature' => true],
                        400
                    );
                }
            }

            // Capture end coordinates if sent
            if (!empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
                $lat_end = (float) $_POST['latitude_end'];
                $lng_end = (float) $_POST['longitude_end'];

                // ERRO 11: VALIDAR COORDENADAS
                if (!Common::validateCoordinates($lat_end, $lng_end)) {
                    AjaxHandler::sendResponse(
                        false,
                        __('Invalid GPS coordinates for end position. Latitude must be between -90 and 90, longitude between -180 and 180.', 'newbase'),
                        [
                            'latitude_end' => $lat_end,
                            'longitude_end' => $lng_end,
                            'valid_ranges' => ['latitude' => [-90, 90], 'longitude' => [-180, 180]]
                        ],
                        400 // Bad Request
                    );
                }

                $update_data['gps_end_lat'] = $lat_end;
                $update_data['gps_end_lng'] = $lng_end;

                // CALCULAR QUILOMETRAGEM (agora com dados válidos)
                if (
                    !empty($task->fields['gps_start_lat'])
                    && !empty($task->fields['gps_start_lng'])
                ) {
                    $distance = Common::calculateDistance(
                        (float) $task->fields['gps_start_lat'],
                        (float) $task->fields['gps_start_lng'],
                        $lat_end,
                        $lng_end
                    );
                    $update_data['mileage'] = $distance;
                }
            }

            $update_data['status']       = 'completed';
            $update_data['is_completed'] = 1;
            $update_data['date_end']     = $_SESSION['glpi_currenttime'];
            $success_message             = __('Task completed successfully', 'newbase');
            break;

        // ACTION: REOPEN TASK
        case 'reopen':
            if ($current_status !== 'completed') {
                AjaxHandler::sendResponse(
                    false,
                    __('Only completed tasks can be reopened', 'newbase'),
                    ['current_status' => $current_status],
                    400
                );
            }

            $update_data['status']       = 'pending';
            $update_data['is_completed'] = 0;
            
            // ERRO 12 CORRIGIDO: null (valor real) em vez de 'NULL' (string)
            $update_data['date_end']     = null; 
            
            $success_message             = __('Task reopened successfully', 'newbase');
            break;

        // INVALID ACTION
        default:
            AjaxHandler::sendResponse(
                false,
                __('Invalid action', 'newbase'),
                ['valid_actions' => ['start', 'pause', 'resume', 'complete', 'reopen']],
                400
            );
            break;
    }

    // EXECUTE UPDATE
    if ($task->update($update_data)) {
        // Log success
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Task %d action '%s' executed by user %d (%s -> %s)",
                $task_id,
                $action,
                Session::getLoginUserID(),
                $current_status,
                $update_data['status']
            )
        );

        AjaxHandler::sendResponse(
            true,
            $success_message,
            [
                'task_id'         => $task_id,
                'action'          => $action,
                'previous_status' => $current_status,
                'new_status'      => $update_data['status'],
                'mileage'         => $update_data['mileage'] ?? null
            ],
            200
        );
    } else {
        Toolbox::logInFile(
            'newbase_plugin',
            "ERROR: Failed to execute action {$action} on task {$task_id}"
        );
        AjaxHandler::sendResponse(false, __('Error updating task', 'newbase'), [], 500);
    }

} catch (Exception $e) {
    // ERROR HANDLING
    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in taskActions.php: " . $e->getMessage()
    );

    $error_data = [];

    // Include error details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $error_data['error'] = $e->getMessage();
    }

    AjaxHandler::sendResponse(false, __('Error processing task action', 'newbase'), $error_data, 500);
}
