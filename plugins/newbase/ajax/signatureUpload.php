<?php

/**
* ---------------------------------------------------------------------
* AJAX - Upload de Assinatura Digital - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo processa upload de assinaturas digitais:
* - Recebe imagem em base64 (data URI) do canvas HTML5
* - Valida formato (PNG/JPEG) e tamanho (max 2MB)
* - Salva como arquivo no sistema
* - Registra no banco de dados
* - Vincula à tarefa correspondente
*
* Usado em formulários de tarefas para captura de assinatura do cliente.
* @package   GlpiPlugin\Newbase
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

// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Config;

// 4 CONFIGURAR RESPOSTA JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// VALIDAÇÕES DE SEGURANÇ

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

// VALIDAÇÃO DE PARÂMETRO

try {
    // 7 OBTER ID DA TAREFA
    $task_id = filter_input(INPUT_POST, 'task_id', FILTER_VALIDATE_INT);

    if (!$task_id || $task_id <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Task ID is required', 'newbase'),
        ]);
        exit;
    }

    // 8 OBTER DADOS DA ASSINATURA
    $signature_data = $_POST['signature'] ?? '';

    if (empty($signature_data)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Signature data is required', 'newbase'),
        ]);
        exit;
    }

    // 9 VERIFICAR SE FUNCIONALIDADE ESTÁ HABILITADA
    if (!Config::getConfigValue('enable_signature', 1)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => __('Digital signature feature is disabled', 'newbase'),
        ]);
        exit;
    }

    // 10 CARREGAR TAREFA
    $task = new Task();
    if (!$task->getFromDB($task_id)) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => __('Task not found', 'newbase'),
        ]);
        exit;
    }

    // 11 VERIFICAR PERMISSÕES
    if (!$task->canUpdateItem()) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => __('You do not have permission to update this task', 'newbase'),
        ]);
        exit;
    }

    // VALIDAÇÃO DA ASSINATURA

    // 12 VALIDAR FORMATO (data:image/png;base64,iVBORw0KGgo...)
    if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $signature_data)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Invalid signature format. Expected PNG or JPEG in base64.', 'newbase'),
        ]);
        exit;
    }

    // 13 EXTRAIR MIME TYPE E DADOS BASE64
    $signature_parts = explode(',', $signature_data, 2);

    if (count($signature_parts) !== 2) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Invalid signature format', 'newbase'),
        ]);
        exit;
    }

    // 14 OBTER MIME TYPE
    preg_match('/data:([^;]+);base64/', $signature_parts[0], $mime_matches);
    $mime_type = $mime_matches[1] ?? 'image/png';

    // Determinar extensão
    $extension = ($mime_type === 'image/jpeg' || $mime_type === 'image/jpg') ? 'jpg' : 'png';

    // 15 DECODIFICAR BASE64
    $image_data = base64_decode($signature_parts[1], true);

    if ($image_data === false) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Invalid base64 encoding', 'newbase'),
        ]);
        exit;
    }

    // 16 VALIDAR TAMANHO (max 2MB)
    $image_size = strlen($image_data);
    $max_size = 2 * 1024 * 1024; // 2MB

    if ($image_size > $max_size) {
        http_response_code(413); // Payload Too Large
        echo json_encode([
            'success' => false,
            'message' => sprintf(
                __('Signature file too large: %s (max %s)', 'newbase'),
                Toolbox::getSize($image_size),
                Toolbox::getSize($max_size)
            ),
        ]);
        exit;
    }

    // 17 VALIDAR SE É IMAGEM VÁLIDA
    $image_info = @getimagesizefromstring($image_data);
    if ($image_info === false) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => __('Invalid image data', 'newbase'),
        ]);
        exit;
    }

    // SALVAR ARQUIVO

    // 18 CRIAR DIRETÓRIO SE NÃO EXISTIR
    $upload_dir = GLPI_PLUGIN_DOC_DIR . '/newbase/signatures';

    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => __('Failed to create upload directory', 'newbase'),
            ]);
            exit;
        }
    }

    // 19 GERAR NOME DE ARQUIVO ÚNICO
    $filename = sprintf(
        'signature_task_%d_%s.%s',
        $task_id,
        date('YmdHis'),
        $extension
    );

    $filepath = $upload_dir . '/' . $filename;

    // 20 SALVAR ARQUIVO
    if (file_put_contents($filepath, $image_data) === false) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => __('Failed to save signature file', 'newbase'),
        ]);
        exit;
    }

    // SALVAR NO BANCO DE DADOS

    // 21 VERIFICAR SE JÁ EXISTE ASSINATURA
    $existing_signature = TaskSignature::getForTask($task_id);

    $signature = new TaskSignature();

    if ($existing_signature) {
        // 22 ATUALIZAR ASSINATURA EXISTENTE

        // Remover arquivo antigo
        $old_filepath = GLPI_PLUGIN_DOC_DIR . '/newbase/signatures/' . $existing_signature['filename'];
        if (file_exists($old_filepath)) {
            @unlink($old_filepath);
        }

        $result = $signature->update([
            'id' => $existing_signature['id'],
            'filename' => $filename,
            'filepath' => $filepath,
            'filesize' => $image_size,
            'mime_type' => $mime_type,
            'date_mod' => $_SESSION['glpi_currenttime'],
        ]);

        $action = 'updated';
    } else {
        // 23 CRIAR NOVA ASSINATURA
        $result = $signature->add([
            'plugin_newbase_tasks_id' => $task_id,
            'filename' => $filename,
            'filepath' => $filepath,
            'filesize' => $image_size,
            'mime_type' => $mime_type,
            'users_id' => Session::getLoginUserID(),
            'date_creation' => $_SESSION['glpi_currenttime'],
        ]);

        $action = 'created';
    }

    // 24 VERIFICAR RESULTADO
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => __('Signature saved successfully', 'newbase'),
            'data' => [
                'filename' => $filename,
                'size' => Toolbox::getSize($image_size),
                'dimensions' => sprintf('%dx%d', $image_info[0], $image_info[1]),
            ],
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Signature %s for task %d: %s (%s)\n",
                $action,
                $task_id,
                $filename,
                Toolbox::getSize($image_size)
            )
        );
    } else {
        // Falhou ao salvar no banco, remover arquivo
        @unlink($filepath);

        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => __('Failed to save signature to database', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            "ERROR: Failed to save signature to database for task $task_id\n"
        );
    }
} catch (Exception $e) {
    // 25 TRATAMENTO DE ERRO
    http_response_code(500);

    $response = [
        'success' => false,
        'message' => __('Error processing signature', 'newbase'),
    ];

    // Incluir detalhes apenas em debug
    if (defined('GLPI_DEBUG')) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);

    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in signatureUpload.php: " . $e->getMessage() . "\n"
    );
}
