<?php

/**
* AJAX endpoint for task actions (pause, resume, complete, etc)
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
    $action = strip_tags($_POST['action'] ?? '');

    if ($task_id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => __('Task ID is required', 'newbase'),
        ]);
        exit;
    }

    if (empty($action)) {
        echo json_encode([
            'success' => false,
            'message' => __('Action is required', 'newbase'),
        ]);
        exit;
    }

    // Load task from database
    global $DB;
    $task_result = $DB->request([
        'FROM' => 'glpi_plugin_newbase_tasks',
        'WHERE' => ['id' => $task_id]
    ]);

    if ($task_result->count() === 0) {
        echo json_encode([
            'success' => false,
            'message' => __('Task not found', 'newbase'),
        ]);
        exit;
    }

    $task_data = $task_result->current();

    // Verify user has permission to update
    $task = new Task();
    if (!$task->canUpdate()) {
        echo json_encode([
            'success' => false,
            'message' => __('You do not have permission to update this task', 'newbase'),
        ]);
        exit;
    }

    $update_data = ['id' => $task_id];
    $success_message = '';

    // Handle actions
    switch ($action) {
        case 'start':
            if ($task_data['status'] !== 'open') {
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be in Open status to start', 'newbase'),
                ]);
                exit;
            }
            $update_data['status'] = 'in_progress';
            $update_data['date_start'] = date('Y-m-d H:i:s');
            $success_message = __('Task started successfully', 'newbase');
            break;

        case 'pause':
            if ($task_data['status'] !== 'in_progress') {
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be in progress to pause', 'newbase'),
                ]);
                exit;
            }
            $update_data['status'] = 'paused';
            $success_message = __('Task paused successfully', 'newbase');
            break;

        case 'resume':
            if ($task_data['status'] !== 'paused') {
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be paused to resume', 'newbase'),
                ]);
                exit;
            }
            $update_data['status'] = 'in_progress';
            $success_message = __('Task resumed successfully', 'newbase');
            break;

        case 'complete':
            if (!in_array($task_data['status'], ['open', 'in_progress', 'paused'])) {
                echo json_encode([
                    'success' => false,
                    'message' => __('Task cannot be completed from current status', 'newbase'),
                ]);
                exit;
            }

            // Check if signature is required
            if (Config::getConfigValue('require_signature', '0') === '1') {
                $signature = TaskSignature::getForTask($task_id);
                if (!$signature) {
                    echo json_encode([
                        'success' => false,
                        'message' => __('Signature is required to complete task', 'newbase'),
                    ]);
                    exit;
                }
            }

            $update_data['status'] = 'completed';
            $update_data['date_end'] = date('Y-m-d H:i:s');
            $success_message = __('Task completed successfully', 'newbase');
            break;

        case 'reopen':
            if ($task_data['status'] !== 'completed') {
                echo json_encode([
                    'success' => false,
                    'message' => __('Only completed tasks can be reopened', 'newbase'),
                ]);
                exit;
            }
            $update_data['status'] = 'open';
            $update_data['date_end'] = null;
            $success_message = __('Task reopened successfully', 'newbase');
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => __('Invalid action', 'newbase'),
            ]);
            exit;
    }

    // Update task
    if ($task->update($update_data)) {
        echo json_encode([
            'success' => true,
            'message' => $success_message,
            'data' => [
                'status' => $update_data['status'],
            ],
        ]);

        Toolbox::logInFile('newbase_plugin', "Task $task_id action '$action' executed successfully\n");
    } else {
        echo json_encode([
            'success' => false,
            'message' => __('Error updating task', 'newbase'),
        ]);

        Toolbox::logInFile('newbase_plugin', "ERROR updating task $task_id with action '$action'\n");
    }

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in taskActions.php: " . $e->getMessage() . "\n");
}
