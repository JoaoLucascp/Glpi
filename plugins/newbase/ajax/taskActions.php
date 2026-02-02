<?php

/**
* ---------------------------------------------------------------------
* AJAX - Ações de Ciclo de Vida de Tarefas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo gerencia transições de status de tarefas:
*
* Fluxo de Estados:
* open → start → in_progress → complete → completed
*                     ↓                       ↓
*                   pause → paused          reopen
*                     ↓
*                  resume → in_progress
*
* Validações:
* - Apenas transições válidas são permitidas
* - Assinatura obrigatória para completar (se configurado)
* - Permissões verificadas
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*
* ---------------------------------------------------------------------
* GLPI - Gestionnaire Libre de Parc Informatique
* Copyright (C) 2015-2026 Teclib' and contributors.
*
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

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Config;

// 4 CONFIGURAR RESPOSTA JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// VALIDAÇÕES DE SEGURANÇA

// 5 VERIFICAR SE É REQUISIÇÃO POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => __('Only POST requests are allowed', 'newbase'),
    ]);
    exit;
}

// 6 VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// VALIDAÇÃO DE PARÂMETROS

try {
    // 7 OBTER PARÂMETROS
    $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if (!$task_id || $task_id <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Task ID is required', 'newbase'),
        ]);
        exit;
    }

    if (empty($action)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Action is required', 'newbase'),
        ]);
        exit;
    }

    // 8 CARREGAR TAREFA DO BANCO
    $task = new Task();
    if (!$task->getFromDB($task_id)) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => __('Task not found', 'newbase'),
        ]);
        exit;
    }

    // 9 VERIFICAR PERMISSÕES
    if (!$task->canUpdateItem()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => __('You do not have permission to update this task', 'newbase'),
        ]);
        exit;
    }

    // 10 OBTER STATUS ATUAL
    $current_status = $task->fields['status'];

    // PROCESSAR AÇÕES

    $update_data = ['id' => $task_id];
    $success_message = '';

    // 11 SWITCH DE AÇÕES
    switch ($action) {

        // AÇÃO: INICIAR TAREFA

        case 'start':
            // Validar transição
            if ($current_status !== 'pending') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be in Pending status to start', 'newbase'),
                    'current_status' => $current_status,
                ]);
                exit;
            }

            // Capturar coordenadas de início (se enviadas)
            if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start'])) {
                $update_data['latitude_start'] = (float) $_POST['latitude_start'];
                $update_data['longitude_start'] = (float) $_POST['longitude_start'];
            }

            $update_data['status'] = 'in_progress';
            $update_data['date_begin'] = $_SESSION['glpi_currenttime'];
            $success_message = __('Task started successfully', 'newbase');
            break;

        // AÇÃO: PAUSAR TAREFA

        case 'pause':
            if ($current_status !== 'in_progress') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be in progress to pause', 'newbase'),
                    'current_status' => $current_status,
                ]);
                exit;
            }

            $update_data['status'] = 'paused';
            $success_message = __('Task paused successfully', 'newbase');
            break;

        // AÇÃO: RETOMAR TAREFA

        case 'resume':
            if ($current_status !== 'paused') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => __('Task must be paused to resume', 'newbase'),
                    'current_status' => $current_status,
                ]);
                exit;
            }

            $update_data['status'] = 'in_progress';
            $success_message = __('Task resumed successfully', 'newbase');
            break;

        // AÇÃO: COMPLETAR TAREFA

        case 'complete':
            // Validar transição
            if (!in_array($current_status, ['pending', 'in_progress', 'paused'], true)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => __('Task cannot be completed from current status', 'newbase'),
                    'current_status' => $current_status,
                ]);
                exit;
            }

            // Verificar se assinatura é obrigatória
            if (Config::getConfigValue('require_signature', 0) == 1) {
                $signature = TaskSignature::getForTask($task_id);
                if (!$signature) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => __('Digital signature is required to complete task', 'newbase'),
                        'require_signature' => true,
                    ]);
                    exit;
                }
            }

            // Capturar coordenadas de fim (se enviadas)
            if (!empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
                $update_data['latitude_end'] = (float) $_POST['latitude_end'];
                $update_data['longitude_end'] = (float) $_POST['longitude_end'];

                // Calcular quilometragem se tiver ambas coordenadas
                if (
                    !empty($task->fields['latitude_start'])
                    && !empty($task->fields['longitude_start'])
                ) {
                    $distance = \GlpiPlugin\Newbase\Common::calculateDistance(
                        $task->fields['latitude_start'],
                        $task->fields['longitude_start'],
                        $update_data['latitude_end'],
                        $update_data['longitude_end']
                    );
                    $update_data['mileage'] = $distance;
                }
            }

            $update_data['status'] = 'completed';
            $update_data['is_completed'] = 1;
            $update_data['date_end'] = $_SESSION['glpi_currenttime'];
            $success_message = __('Task completed successfully', 'newbase');
            break;

        // AÇÃO: REABRIR TAREFA

        case 'reopen':
            if ($current_status !== 'completed') {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => __('Only completed tasks can be reopened', 'newbase'),
                    'current_status' => $current_status,
                ]);
                exit;
            }

            $update_data['status'] = 'pending';
            $update_data['is_completed'] = 0;
            $update_data['date_end'] = 'NULL';  // Reset date_end
            $success_message = __('Task reopened successfully', 'newbase');
            break;

        // AÇÃO INVÁLIDA

        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => __('Invalid action', 'newbase'),
                'valid_actions' => ['start', 'pause', 'resume', 'complete', 'reopen'],
            ]);
            exit;
    }

    // EXECUTAR ATUALIZAÇÃO

    // 12 ATUALIZAR TAREFA
    if ($task->update($update_data)) {
        echo json_encode([
            'success' => true,
            'message' => $success_message,
            'data' => [
                'task_id' => $task_id,
                'action' => $action,
                'previous_status' => $current_status,
                'new_status' => $update_data['status'],
                'mileage' => $update_data['mileage'] ?? null,
            ],
        ]);

        // 13 LOG DE SUCESSO
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Task %d: action '%s' executed by user %d (%s → %s)\n",
                $task_id,
                $action,
                Session::getLoginUserID(),
                $current_status,
                $update_data['status']
            )
        );

    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => __('Error updating task', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            "ERROR: Failed to execute action '$action' on task $task_id\n"
        );
    }

} catch (Exception $e) {
    // 14 TRATAMENTO DE ERRO
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => __('Error processing task action', 'newbase'),
        'error' => GLPI_DEBUG ? $e->getMessage() : null,
    ]);

    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in taskActions.php: " . $e->getMessage() . "\n"
    );
}
