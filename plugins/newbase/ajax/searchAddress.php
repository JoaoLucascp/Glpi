<?php

/**
* ---------------------------------------------------------------------
* AJAX - Busca de Endereço por CEP - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo busca dados de endereço pelo CEP usando ViaCEP API:
* - Valida formato do CEP
* - Consulta API ViaCEP
* - Retorna logradouro, bairro, cidade e estado
*
* Usado em formulários para preenchimento automático de endereço.
*
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
use GlpiPlugin\Newbase\AddressHandler;

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

// 7 VERIFICAR SE CEP FOI ENVIADO
if (empty($_POST['cep'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => __('CEP is required', 'newbase'),
    ]);
    exit;
}

// VALIDAÇÃO DO CEP

// 8 REMOVER FORMATAÇÃO (traço)
// Exemplo: "01310-100" -> "01310100"
$cep = preg_replace('/[^0-9]/', '', $_POST['cep']);

// 9 VALIDAR TAMANHO (CEP brasileiro tem 8 dígitos)
if (strlen($cep) !== 8) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => __('Invalid CEP: must have 8 digits', 'newbase'),
    ]);
    exit;
}

// 10 VALIDAR SE É UM CEP REAL (não todos zeros)
if (preg_match('/^0+$/', $cep)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => __('Invalid CEP: cannot be all zeros', 'newbase'),
    ]);
    exit;
}

// PROCESSAMENTO

try {
    // 11 CONSULTAR API ViaCEP
    $url = "https://viacep.com.br/ws/{$cep}/json/";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, // Localhost fix
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI Newbase Plugin/2.0',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // 12 VERIFICAR ERRO DE CURL
    if ($error) {
        Toolbox::logInFile(
            'newbase_plugin',
            "ViaCEP CURL Error for CEP $cep: $error\n"
        );

        http_response_code(503); // Service Unavailable
        echo json_encode([
            'success' => false,
            'message' => __('Error connecting to address service', 'newbase'),
        ]);
        exit;
    }

    // 13 VERIFICAR CÓDIGO HTTP
    if ($httpCode !== 200) {
        http_response_code($httpCode);
        echo json_encode([
            'success' => false,
            'message' => __('Error searching CEP', 'newbase'),
        ]);
        exit;
    }

    // 14 DECODIFICAR RESPOSTA JSON
    $data = json_decode($response, true);

    if (!$data) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => __('Invalid response from address service', 'newbase'),
        ]);
        exit;
    }

    // 15 VERIFICAR SE CEP NÃO FOI ENCONTRADO
    // ViaCEP retorna {"erro": true} quando CEP não existe
    if (isset($data['erro']) && $data['erro'] === true) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => __('CEP not found', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            "CEP not found: $cep\n"
        );

        exit;
    }

    // 16 RESPOSTA DE SUCESSO
    echo json_encode([
        'success' => true,
        'data' => [
            'cep' => $data['cep'] ?? '',                    // "01310-100"
            'logradouro' => $data['logradouro'] ?? '',      // "Avenida Paulista"
            'complemento' => $data['complemento'] ?? '',     // "lado ímpar"
            'bairro' => $data['bairro'] ?? '',              // "Bela Vista"
            'localidade' => $data['localidade'] ?? '',      // "São Paulo"
            'uf' => $data['uf'] ?? '',                      // "SP"
            'ibge' => $data['ibge'] ?? '',                  // "3550308"
            'gia' => $data['gia'] ?? '',                    // "1004"
            'ddd' => $data['ddd'] ?? '',                    // "11"
            'siafi' => $data['siafi'] ?? '',                // "7107"
        ],
        'message' => __('Address loaded successfully', 'newbase'),
    ]);

    // 17 LOG DE SUCESSO
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "CEP found: %s - %s, %s - %s/%s\n",
            $cep,
            $data['logradouro'] ?? '',
            $data['bairro'] ?? '',
            $data['localidade'] ?? '',
            $data['uf'] ?? ''
        )
    );

} catch (Exception $e) {
    // 18 TRATAMENTO DE ERRO
    http_response_code(500);

    $response = [
        'success' => false,
        'message' => __('Error searching address', 'newbase'),
    ];

    // Incluir detalhes apenas em debug
    if (defined('GLPI_DEBUG')) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);

    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in searchAddress.php: " . $e->getMessage() . "\n"
    );
}
