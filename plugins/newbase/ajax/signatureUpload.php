<?php

/**
* AJAX endpoint for uploading digital signature
*
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*
* ---------------------------------------------------------------------
* GLPI - Gestionnaire Libre de Parc Informatique
* Copyright (C) 2015-2026 Teclib' and contributors.
*
* http://glpi-project.org
*
* based on GLPI - Copyright (C) 2003-2014 by the INDEPNET Development Team.
*
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

// Carrega o GLPI core
include('../../../inc/includes.php');

// Security check
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

// Evita acesso direto
if (!defined('GLPI_ROOT')) {
    include('../../../inc/includes.php');
}

// Verifica sessão ativa
Session::checkLoginUser();
// Check rights
Session::checkRight('plugin_newbase_task', READ);
// Verifica token CSRF (OBRIGATÓRIO para GLPI 10+)
Session::checkCSRF($_POST);
// Força modo AJAX
header('Content-Type: application/json; charset=utf-8');

try {
    // Get parameters
    $task_id = intval($_POST['task_id'] ?? 0);
    $signature_data = $_POST['signature'] ?? '';

    if ($task_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => __('Task ID is required', 'newbase'),
        ]);
        exit;
    }

    if (empty($signature_data)) {
        echo json_encode([
            'success' => false,
            'message' => __('Signature data is required', 'newbase'),
        ]);
        exit;
    }

    // Check if signature feature is enabled
    if (!Config::isSignatureEnabled()) {
        echo json_encode([
            'success' => false,
            'message' => __('Digital signature feature is disabled', 'newbase'),
        ]);
        exit;
    }

    // Load task
    $task = new Task();
    if (!$task->getFromDB($task_id)) {
        echo json_encode([
            'success' => false,
            'message' => __('Task not found', 'newbase'),
        ]);
        exit;
    }

    // Check if user can update this task
    if (!$task->canUpdate()) {
        echo json_encode([
            'success' => false,
            'message' => __('You do not have permission to update this task', 'newbase'),
        ]);
        exit;
    }

    // Validate signature data format (data:image/png;base64,...)
    if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $signature_data)) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid signature format', 'newbase'),
        ]);
        exit;
    }

    // Extract MIME type and base64 data
    $signature_parts = explode(',', $signature_data);
    if (count($signature_parts) !== 2) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid signature format', 'newbase'),
        ]);
        exit;
    }

    // Get MIME type
    preg_match('/data:([^;]+);base64/', $signature_parts[0], $mime_matches);
    $mime_type = $mime_matches[1] ?? 'image/png';

    // Decode base64
    $image_data = base64_decode($signature_parts[1]);
    if ($image_data === false) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid base64 encoding', 'newbase'),
        ]);
        exit;
    }

    // Validate image size (max 2MB)
    if (strlen($image_data) > 2 * 1024 * 1024) {
        echo json_encode([
            'success' => false,
            'message' => __('Signature file too large (max 2MB)', 'newbase'),
        ]);
        exit;
    }

    // Check if signature already exists
    $existing_signature = TaskSignature::getForTask($task_id);

    $signature = new TaskSignature();

    if ($existing_signature) {
        // Update existing signature
        $result = $signature->update([
            'id' => $existing_signature['id'],
            'signature_data' => $image_data,
            'signature_mime' => $mime_type,
            'signature_filename' => 'signature_task_' . $task_id . '.png',
        ]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => __('Signature updated successfully', 'newbase'),
            ]);

            Toolbox::logInFile('newbase_plugin', "Signature updated for task $task_id\n");
        } else {
            echo json_encode([
                'success' => false,
                'message' => __('Error updating signature', 'newbase'),
            ]);

            Toolbox::logInFile('newbase_plugin', "ERROR updating signature for task $task_id\n");
        }
    } else {
        // Create new signature
        $result = $signature->add([
            'plugin_newbase_task_id' => $task_id,
            'signature_data' => $image_data,
            'signature_mime' => $mime_type,
            'signature_filename' => 'signature_task_' . $task_id . '.png',
            'date_creation' => date('Y-m-d H:i:s'),
            'created_by' => Session::getLoginUserID(),
        ]);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => __('Signature saved successfully', 'newbase'),
            ]);

            Toolbox::logInFile('newbase_plugin', "Signature created for task $task_id\n");
        } else {
            echo json_encode([
                'success' => false,
                'message' => __('Error saving signature', 'newbase'),
            ]);

            Toolbox::logInFile('newbase_plugin', "ERROR creating signature for task $task_id\n");
        }
    }

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in signatureUpload.php: " . $e->getMessage() . "\n");
}
