<?php

/**
* Proxy para buscar dados de CNPJ
* Este arquivo resolve o problema de CORS fazendo a requisição do lado do servidor.
* Busca em múltiplas APIs para garantir o preenchimento do email.
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

// Verifica se o CNPJ foi enviado
if (empty($_POST['cnpj'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'CNPJ não informado',
    ]);
    exit;
}

$cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);

if (strlen($cnpj) !== 14) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'CNPJ invalido',
    ]);
    exit;
}

/**
* Busca na API 1: BrasilAPI
*/
function buscarBrasilAPI($cnpj)
{
    $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }

    return null;
}

/**
* Busca na API 2: ReceitaWS (via proxy PHP - resolve CORS)
*/
function buscarReceitaWS($cnpj)
{
    $url = "https://receitaws.com.br/v1/cnpj/{$cnpj}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['status']) && $data['status'] === 'ERROR') {
            return null;
        }
        return $data;
    }

    return null;
}

/**
* Mesclar dados de mÃºltiplas APIs
*/
function mesclarDados($api1, $api2)
{
    $resultado = [
        'razao_social' => '',
        'nome_fantasia' => '',
        'email' => '',
        'telefone' => '',
        'fonte' => [],
    ];

    // Prioriza BrasilAPI
    if ($api1) {
        $resultado['razao_social'] = $api1['razao_social'] ?? '';
        $resultado['nome_fantasia'] = $api1['nome_fantasia'] ?? '';
        $resultado['email'] = $api1['email'] ?? '';
        $resultado['telefone'] = $api1['ddd_telefone_1'] ?? '';
        $resultado['fonte'][] = 'BrasilAPI';
    }

    // Complementa com ReceitaWS se necessÃ¡rio
    if ($api2) {
        if (empty($resultado['razao_social'])) {
            $resultado['razao_social'] = $api2['nome'] ?? '';
        }
        if (empty($resultado['nome_fantasia'])) {
            $resultado['nome_fantasia'] = $api2['fantasia'] ?? '';
        }
        if (empty($resultado['email']) && !empty($api2['email'])) {
            $resultado['email'] = $api2['email'];
            $resultado['fonte'][] = 'ReceitaWS (email)';
        }
        if (empty($resultado['telefone']) && !empty($api2['telefone'])) {
            $resultado['telefone'] = $api2['telefone'];
        }
    }

    return $resultado;
}

// Tenta buscar nas APIs
$dadosBrasilAPI = buscarBrasilAPI($cnpj);
$dadosReceitaWS = null;

// Se BrasilAPI nÃ£o retornou email, busca na ReceitaWS
if (!$dadosBrasilAPI || empty($dadosBrasilAPI['email'])) {
    $dadosReceitaWS = buscarReceitaWS($cnpj);
}

// Se nenhuma API funcionou
if (!$dadosBrasilAPI && !$dadosReceitaWS) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'CNPJ não encontrado em nenhuma API',
    ]);
    exit;
}

// Mescla os dados
$resultado = mesclarDados($dadosBrasilAPI, $dadosReceitaWS);

// Log para debug
Toolbox::logInFile(
    'newbase_cnpj',
    "CNPJ: {$cnpj} | Email: " . ($resultado['email'] ?: 'NÃO ENCONTRADO')
    . " | Fontes: " . implode(', ', $resultado['fonte']) . "\n"
);

// Retorna resultado
echo json_encode([
    'success' => true,
    'data' => $resultado,
]);
