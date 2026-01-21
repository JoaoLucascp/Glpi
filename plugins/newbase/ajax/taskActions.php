<?php

/**
* AJAX endpoint for task actions (pause, resume, complete, etc)
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\Task;
use GlpiPlugin\Newbase\Src\TaskSignature;
use GlpiPlugin\Newbase\Src\Config;

include('../../../inc/includes.php');

// Security check
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_task', UPDATE);

// Validate CSRF token
Session::checkCSRF($_POST);

// Set JSON header
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

    $update_data = ['id' => $task_id];
    $success_message = '';

    // Handle actions
    switch ($action) {
        case 'start':
            if ($task->fields['status'] !== 'open') {
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
            if ($task->fields['status'] !== 'in_progress') {
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
            if ($task->fields['status'] !== 'paused') {
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
            if (!in_array($task->fields['status'], ['open', 'in_progress', 'paused'])) {
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
            if ($task->fields['status'] !== 'completed') {
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
