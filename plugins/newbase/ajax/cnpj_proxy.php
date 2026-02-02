<?php

/**
* ---------------------------------------------------------------------
* AJAX - Proxy para APIs de CNPJ - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo atua como proxy para consultar múltiplas APIs de CNPJ:
* 1. BrasilAPI (prioridade)
* 2. ReceitaWS (fallback)
*
* Funcionalidades:
* - Resolve problema de CORS (Cross-Origin)
* - Mescla dados de múltiplas fontes
* - Fallback automático entre APIs
* - Cache local (opcional)
*
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
use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\CompanyData;

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
        'error' => __('Only POST requests are allowed', 'newbase'),
    ]);
    exit;
}

// 6 VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 7 VERIFICAR PERMISSÕES
if (!CompanyData::canCreate() && !CompanyData::canUpdate()) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => __('You do not have permission to search companies', 'newbase'),
    ]);
    exit;
}

// VALIDAR CNPJ

// 8 VERIFICAR SE CNPJ FOI ENVIADO
if (empty($_POST['cnpj'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => __('CNPJ is required', 'newbase'),
    ]);
    exit;
}

// 9 REMOVER FORMATAÇÃO
$cnpj = preg_replace('/[^0-9]/', '', $_POST['cnpj']);

// 10 VALIDAR TAMANHO
if (strlen($cnpj) !== 14) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => __('Invalid CNPJ: must have 14 digits', 'newbase'),
    ]);
    exit;
}

// 11 VALIDAR DÍGITOS VERIFICADORES
if (!Common::validateCNPJ($cnpj)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => __('Invalid CNPJ: verification digits do not match', 'newbase'),
    ]);
    exit;
}

// FUNÇÕES AUXILIARES

/**
* Busca dados na BrasilAPI
*
* @param string $cnpj CNPJ sem formatação (14 dígitos)
* @return array|null Dados da empresa ou null se não encontrado
*/
function buscarBrasilAPI($cnpj)
{
    $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,  // Segurança: verificar certificado SSL
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI Newbase Plugin/2.0',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log de erro se houver
    if ($error) {
        Toolbox::logInFile('newbase_cnpj', "BrasilAPI CURL Error: $error\n");
    }

    // Se sucesso, retornar dados
    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }

    return null;
}

/**
* Busca dados na ReceitaWS
*
* @param string $cnpj CNPJ sem formatação (14 dígitos)
* @return array|null Dados da empresa ou null se não encontrado
*/
function buscarReceitaWS($cnpj)
{
    $url = "https://receitaws.com.br/v1/cnpj/{$cnpj}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI Newbase Plugin/2.0',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        Toolbox::logInFile('newbase_cnpj', "ReceitaWS CURL Error: $error\n");
    }

    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);

        // ReceitaWS retorna erro dentro do JSON
        if (isset($data['status']) && $data['status'] === 'ERROR') {
            return null;
        }

        return $data;
    }

    return null;
}

/**
* Mescla dados de múltiplas APIs priorizando BrasilAPI
*
* @param array|null $brasilAPI Dados da BrasilAPI
* @param array|null $receitaWS Dados da ReceitaWS
* @return array Dados mesclados
*/
function mesclarDados($brasilAPI, $receitaWS)
{
    $resultado = [
        'razao_social' => '',
        'nome_fantasia' => '',
        'email' => '',
        'telefone' => '',
        'cep' => '',
        'logradouro' => '',
        'numero' => '',
        'complemento' => '',
        'bairro' => '',
        'municipio' => '',
        'uf' => '',
        'fonte' => [],
    ];

    // 12 PRIORIZAR BrasilAPI
    if ($brasilAPI) {
        $resultado['razao_social'] = $brasilAPI['razao_social'] ?? '';
        $resultado['nome_fantasia'] = $brasilAPI['nome_fantasia'] ?? '';
        $resultado['email'] = $brasilAPI['email'] ?? '';
        $resultado['telefone'] = $brasilAPI['ddd_telefone_1'] ?? '';
        $resultado['cep'] = $brasilAPI['cep'] ?? '';
        $resultado['logradouro'] = $brasilAPI['descricao_tipo_logradouro'] . ' ' . ($brasilAPI['logradouro'] ?? '');
        $resultado['numero'] = $brasilAPI['numero'] ?? '';
        $resultado['complemento'] = $brasilAPI['complemento'] ?? '';
        $resultado['bairro'] = $brasilAPI['bairro'] ?? '';
        $resultado['municipio'] = $brasilAPI['municipio'] ?? '';
        $resultado['uf'] = $brasilAPI['uf'] ?? '';
        $resultado['fonte'][] = 'BrasilAPI';
    }

    // 13 COMPLEMENTAR COM ReceitaWS (se necessário)
    if ($receitaWS) {
        if (empty($resultado['razao_social'])) {
            $resultado['razao_social'] = $receitaWS['nome'] ?? '';
        }

        if (empty($resultado['nome_fantasia'])) {
            $resultado['nome_fantasia'] = $receitaWS['fantasia'] ?? '';
        }

        if (empty($resultado['email']) && !empty($receitaWS['email'])) {
            $resultado['email'] = $receitaWS['email'];
            $resultado['fonte'][] = 'ReceitaWS (email)';
        }

        if (empty($resultado['telefone']) && !empty($receitaWS['telefone'])) {
            $resultado['telefone'] = $receitaWS['telefone'];
            $resultado['fonte'][] = 'ReceitaWS (telefone)';
        }

        if (empty($resultado['cep'])) {
            $resultado['cep'] = $receitaWS['cep'] ?? '';
            $resultado['logradouro'] = $receitaWS['logradouro'] ?? '';
            $resultado['numero'] = $receitaWS['numero'] ?? '';
            $resultado['complemento'] = $receitaWS['complemento'] ?? '';
            $resultado['bairro'] = $receitaWS['bairro'] ?? '';
            $resultado['municipio'] = $receitaWS['municipio'] ?? '';
            $resultado['uf'] = $receitaWS['uf'] ?? '';
        }
    }

    return $resultado;
}

// PROCESSAMENTO PRINCIPAL

try {
    // 14 TENTAR BrasilAPI PRIMEIRO
    $dadosBrasilAPI = buscarBrasilAPI($cnpj);
    $dadosReceitaWS = null;

    // 15 SE BrasilAPI NÃO RETORNOU EMAIL, TENTAR ReceitaWS
    if (!$dadosBrasilAPI || empty($dadosBrasilAPI['email'])) {
        $dadosReceitaWS = buscarReceitaWS($cnpj);
    }

    // 16 SE NENHUMA API FUNCIONOU
    if (!$dadosBrasilAPI && !$dadosReceitaWS) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => __('CNPJ not found in any API', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_cnpj',
            "CNPJ not found in any API: $cnpj\n"
        );

        exit;
    }

    // 17 MESCLAR DADOS DAS DUAS APIs
    $resultado = mesclarDados($dadosBrasilAPI, $dadosReceitaWS);

    // 18 LOG DE SUCESSO
    Toolbox::logInFile(
        'newbase_cnpj',
        sprintf(
            "CNPJ: %s | Email: %s | Fontes: %s\n",
            $cnpj,
            $resultado['email'] ?: 'NOT FOUND',
            implode(', ', $resultado['fonte'])
        )
    );

    // 19 RETORNAR RESULTADO
    echo json_encode([
        'success' => true,
        'data' => $resultado,
        'message' => __('Company data loaded successfully', 'newbase'),
    ]);

} catch (Exception $e) {
    // 20 TRATAMENTO DE ERRO
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => __('Error searching company data', 'newbase'),
        'details' => GLPI_DEBUG ? $e->getMessage() : null,
    ]);

    Toolbox::logInFile(
        'newbase_cnpj',
        "ERROR in cnpj_proxy.php: " . $e->getMessage() . "\n"
    );
}