<?php

/**
* ---------------------------------------------------------------------
* AJAX - Busca de Empresa por CNPJ - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo busca dados de empresa pelo CNPJ:
* 1. Primeiro busca no banco de dados local
* 2. Se não encontrar, consulta API externa (ReceitaWS)
* 3. Retorna dados formatados em JSON
*
* Usado no formulário de cadastro de empresas para preenchimento automático.
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

// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\CompanyData;

// 4 CONFIGURAR RESPOSTA JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

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

// 7 VERIFICAR PERMISSÕES
if (!CompanyData::canCreate() && !CompanyData::canUpdate()) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => __('You do not have permission to search companies', 'newbase'),
    ]);
    exit;
}

// PROCESSAMENTO

try {
    // 8 OBTER CNPJ DO POST
    $cnpj = $_POST['cnpj'] ?? '';

    if (empty($cnpj)) {
        echo json_encode([
            'success' => false,
            'message' => __('CNPJ is required', 'newbase'),
        ]);
        exit;
    }

    // 9 REMOVER FORMATAÇÃO (pontos, traços, barras)
    // Exemplo: "12.345.678/0001-90" -> "12345678000190"
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // 10 VALIDAR TAMANHO DO CNPJ (deve ter 14 dígitos)
    if (strlen($cnpj) !== 14) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid CNPJ: must have 14 digits', 'newbase'),
        ]);
        exit;
    }

    // 11 VALIDAR DÍGITOS VERIFICADORES
    if (!Common::validateCNPJ($cnpj)) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid CNPJ: verification digits do not match', 'newbase'),
        ]);
        exit;
    }

    // ESTRATÉGIA 1: BUSCAR NO BANCO LOCAL

    // 12 TENTAR ENCONTRAR EMPRESA JÁ CADASTRADA
    $company = CompanyData::getCompanyByCNPJ($cnpj);

    if ($company) {
        // Empresa já existe no banco, retornar dados
        echo json_encode([
            'success' => true,
            'source' => 'local',  // Indica que veio do banco local
            'data' => [
                'corporate_name' => $company['name'] ?? '',
                'fantasy_name' => $company['fantasy_name'] ?? '',
                'email' => $company['email'] ?? '',
                'phone' => Common::formatPhone($company['phone'] ?? ''),
                'address' => $company['address'] ?? '',
                'city' => $company['city'] ?? '',
                'state' => $company['state'] ?? '',
                'postcode' => $company['postcode'] ?? '',
            ],
            'message' => __('Company data loaded from database', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            "Company found in database: CNPJ $cnpj\n"
        );

        exit;
    }

    // ESTRATÉGIA 2: CONSULTAR API EXTERNA

    // 13 BUSCAR NA API DA RECEITA FEDERAL
    // $companyData = Common::searchCompanyByCNPJ($cnpj);

    // FIX: Inline cURL with SSL disabled for localhost
    $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, // Localhost fix
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI-Newbase/2.1.0',
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $companyData = false;
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['cnpj'])) {
            $companyData = [
                'legal_name' => $data['razao_social'] ?? '',
                'fantasy_name' => $data['nome_fantasia'] ?? '',
                'address' => trim(($data['descricao_tipo_logradouro'] ?? '') . ' ' . ($data['logradouro'] ?? '') . ', ' . ($data['numero'] ?? '')),
                'city' => $data['municipio'] ?? '',
                'state' => $data['uf'] ?? '',
                'postcode' => $data['cep'] ?? '',
                'email' => $data['email'] ?? '',
                'phone' => $data['ddd_telefone_1'] ?? ''
            ];
        }
    }

    if ($companyData === false) {
        echo json_encode([
            'success' => false,
            'message' => __('Company not found in government database or API error', 'newbase'),
        ]);

        Toolbox::logInFile(
            'newbase_plugin',
            "API search failed for CNPJ: $cnpj\n"
        );

        exit;
    }

    // 14 BUSCAR DADOS COMPLEMENTARES (telefone, email)
    // APIs públicas geralmente não retornam esses dados
    // BrasilAPI already returns email and phone, so we use them directly
    $additionalData = [
        'email' => $companyData['email'] ?? '',
        'phone' => $companyData['phone'] ?? ''
    ];

    // 15 RESPOSTA DE SUCESSO COM DADOS DA API
    echo json_encode([
        'success' => true,
        'source' => 'api',  // Indica que veio da API externa
        'data' => [
            'corporate_name' => $companyData['legal_name'] ?? '',
            'fantasy_name' => $companyData['fantasy_name'] ?? '',
            'email' => $companyData['email'] ?? '',
            'phone' => Common::formatPhone($companyData['phone'] ?? ''),
            'address' => $companyData['address'] ?? '',
            'city' => $companyData['city'] ?? '',
            'state' => $companyData['state'] ?? '',
            'postcode' => $companyData['postcode'] ?? '',
        ],
        'message' => __('Company data loaded from government database', 'newbase'),
    ]);

    Toolbox::logInFile(
        'newbase_plugin',
        "API search successful for CNPJ: $cnpj\n"
    );
} catch (Exception $e) {
    // 16 RESPOSTA DE ERRO
    http_response_code(500);

    $response = [
        'success' => false,
        'message' => __('Error searching company data', 'newbase'),
    ];

    // Incluir detalhes apenas em debug
    if (defined('GLPI_DEBUG')) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);

    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in searchCompany.php: " . $e->getMessage() . "\n"
    );
}
