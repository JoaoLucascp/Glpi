<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Newbase. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2024-2026 by João Lucas
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/JoaoLucascp/Glpi
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * AJAX Proxy for CNPJ APIs
 *
 * Acts as a server-side proxy to query multiple Brazilian CNPJ APIs:
 * 1. Brasil API (https://brasilapi.com.br) - Primary source
 * 2. ReceitaWS (https://receitaws.com.br) - Fallback source
 *
 * Features:
 * - Solves CORS (Cross-Origin Resource Sharing) issues
 * - Merges data from multiple sources for completeness
 * - Automatic fallback between APIs
 * - CNPJ validation with check digits
 * - Response caching (optional)
 * - Comprehensive error handling
 *
 * Used by CompanyData forms to auto-fill company information from CNPJ.
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\AjaxHandler;
use Session;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Set security headers
AjaxHandler::setSecurityHeaders();

/**
 * Search company data in Brasil API
 *
 * @param string $cnpj CNPJ without formatting (14 digits)
 * @return array|null Company data or null if not found
 */
function searchBrasilAPI(string $cnpj): ?array
{
    $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";
    $response = AjaxHandler::fetchCurl($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    if (is_array($data) && isset($data['cnpj'])) {
        Toolbox::logInFile('newbase_plugin', "Brasil API success for CNPJ {$cnpj}\n");
        return $data;
    }

    Toolbox::logInFile('newbase_plugin', "Brasil API failed for CNPJ {$cnpj}\n");
    return null;
}

/**
 * Search company data in ReceitaWS API
 *
 * @param string $cnpj CNPJ without formatting (14 digits)
 * @return array|null Company data or null if not found
 */
function searchReceitaWSAPI(string $cnpj): ?array
{
    $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";
    $response = AjaxHandler::fetchCurl($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    // ReceitaWS returns errors within JSON
    if (is_array($data) && isset($data['status']) && $data['status'] === 'OK') {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS success for CNPJ {$cnpj}\n");
        return $data;
    }

    if (isset($data['message'])) {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS error for CNPJ {$cnpj}: {$data['message']}\n");
    }

    Toolbox::logInFile('newbase_plugin', "ReceitaWS failed for CNPJ {$cnpj}\n");
    return null;
}

/**
 * Merge data from multiple APIs prioritizing Brasil API
 *
 * @param array|null $brasilAPI Brasil API data
 * @param array|null $receitaWS ReceitaWS data
 * @return array Merged and normalized data
 */
function mergeAPIData(?array $brasilAPI, ?array $receitaWS): array
{
    $result = [
        'cnpj'           => '',
        'legal_name'     => '', // razao_social
        'fantasy_name'   => '', // nome_fantasia
        'email'          => '',
        'phone'          => '',
        'postcode'       => '', // cep
        'street'         => '', // logradouro
        'number'         => '',
        'complement'     => '',
        'neighborhood'   => '', // bairro
        'city'           => '', // municipio
        'state'          => '', // uf
        'sources'        => [],
    ];

    // Priority 1: Brasil API
    if ($brasilAPI !== null) {
        $result['cnpj'] = $brasilAPI['cnpj'] ?? '';
        $result['legal_name'] = $brasilAPI['razao_social'] ?? '';
        $result['fantasy_name'] = $brasilAPI['nome_fantasia'] ?? '';
        $result['email'] = $brasilAPI['email'] ?? '';

        // Phone: concatenate DDD + phone
        if (!empty($brasilAPI['ddd_telefone_1'])) {
            $result['phone'] = $brasilAPI['ddd_telefone_1'];
        }

        $result['postcode'] = preg_replace('/[^0-9]/', '', $brasilAPI['cep'] ?? '');

        // Street: concatenate type + name
        $result['street'] = trim(
            ($brasilAPI['descricao_tipo_logradouro'] ?? '') . ' '
            . ($brasilAPI['logradouro'] ?? '')
        );

        $result['number'] = $brasilAPI['numero'] ?? '';
        $result['complement'] = $brasilAPI['complemento'] ?? '';
        $result['neighborhood'] = $brasilAPI['bairro'] ?? '';
        $result['city'] = $brasilAPI['municipio'] ?? '';
        $result['state'] = $brasilAPI['uf'] ?? '';
        $result['sources'][] = 'Brasil API';
    }

    // Priority 2: ReceitaWS (fill missing data)
    if ($receitaWS !== null) {
        if (empty($result['cnpj'])) {
            $result['cnpj'] = $receitaWS['cnpj'] ?? '';
        }

        if (empty($result['legal_name'])) {
            $result['legal_name'] = $receitaWS['nome'] ?? '';
        }

        if (empty($result['fantasy_name'])) {
            $result['fantasy_name'] = $receitaWS['fantasia'] ?? '';
        }

        if (empty($result['email']) && !empty($receitaWS['email'])) {
            $result['email'] = $receitaWS['email'];
            $result['sources'][] = 'ReceitaWS (email)';
        }

        if (empty($result['phone']) && !empty($receitaWS['telefone'])) {
            $result['phone'] = $receitaWS['telefone'];
            $result['sources'][] = 'ReceitaWS (phone)';
        }

        if (empty($result['postcode'])) {
            $result['postcode'] = preg_replace('/[^0-9]/', '', $receitaWS['cep'] ?? '');
            $result['street'] = $receitaWS['logradouro'] ?? '';
            $result['number'] = $receitaWS['numero'] ?? '';
            $result['complement'] = $receitaWS['complemento'] ?? '';
            $result['neighborhood'] = $receitaWS['bairro'] ?? '';
            $result['city'] = $receitaWS['municipio'] ?? '';
            $result['state'] = $receitaWS['uf'] ?? '';
            $result['sources'][] = 'ReceitaWS (address)';
        }
    }

    // Remove empty sources entry
    if (empty($result['sources'])) {
        unset($result['sources']);
    }

    return $result;
}

// ===== MAIN EXECUTION =====

try {
    // ===== AUTHENTICATION & AUTHORIZATION =====
    if (!Session::getLoginUserID()) {
        AjaxHandler::sendResponse(false, __('Authentication required'), [], 401);
    }

    // Check permissions
    if (!AjaxHandler::checkPermissions('plugin_newbase')) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf("User %d tried to search CNPJ without permission\n", Session::getLoginUserID())
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to access this feature', 'newbase'), [], 403);
    }

    // ===== CHECK IF FEATURE IS ENABLED =====
    $config = Config::getConfig();
    $enable_cnpj_search = $config['enable_cnpj_search'] ?? 1;

    if (!$enable_cnpj_search) {
        AjaxHandler::sendResponse(false, __('CNPJ search feature is disabled', 'newbase'), [], 403);
    }

    // ===== CSRF TOKEN VALIDATION =====
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX CNPJ search: Invalid CSRF token\n");
        AjaxHandler::sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }

    // ===== GET REQUEST DATA =====
    $method = $_SERVER['REQUEST_METHOD'];

    if (!in_array($method, ['POST', 'GET'], true)) {
        AjaxHandler::sendResponse(
            false,
            sprintf(__('Method %s not allowed', 'newbase'), $method),
            [],
            405
        );
    }

    // Parse input
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        $input = ($method === 'POST') ? $_POST : $_GET;
    }

    if (!is_array($input)) {
        $input = [];
    }

    // ===== GET AND VALIDATE CNPJ =====
    $cnpj = $input['cnpj'] ?? '';

    if (empty($cnpj)) {
        AjaxHandler::sendResponse(false, __('CNPJ is required', 'newbase'), [], 400);
    }

    // Remove formatting
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // Validate using Common class
    if (!Common::validateCNPJ($cnpj)) {
        AjaxHandler::sendResponse(
            false,
            __('Invalid CNPJ: check digits verification failed', 'newbase'),
            ['cnpj' => $cnpj],
            400
        );
    }

    // ===== SEARCH IN APIs =====

    // Try Brasil API first (primary source)
    $brasilAPIData = searchBrasilAPI($cnpj);

    // Try ReceitaWS as fallback or complement
    $receitaWSData = null;
    if ($brasilAPIData === null || empty($brasilAPIData['email'])) {
        $receitaWSData = searchReceitaWSAPI($cnpj);
    }

    // Check if at least one API returned data
    if ($brasilAPIData === null && $receitaWSData === null) {
        Toolbox::logInFile('newbase_plugin', "CNPJ {$cnpj} not found in any API\n");
        AjaxHandler::sendResponse(
            false,
            __('CNPJ not found or APIs are temporarily unavailable', 'newbase'),
            ['cnpj' => $cnpj],
            404
        );
    }

    // Merge data from both APIs
    $result = mergeAPIData($brasilAPIData, $receitaWSData);

    // Log success
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "CNPJ search success: %s | Legal Name: %s | Email: %s | Sources: %s\n",
            $cnpj,
            $result['legal_name'] ?: 'N/A',
            $result['email'] ?: 'NOT FOUND',
            implode(', ', $result['sources'] ?? ['Unknown'])
        )
    );

    // Success response
    AjaxHandler::sendResponse(
        true,
        __('Company data loaded successfully', 'newbase'),
        $result,
        200
    );
} catch (Exception $e) {
    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in cnpj_proxy.php: %s\n",
            $e->getMessage()
        )
    );

    $error_data = [];

    // Include error details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $error_data['error'] = $e->getMessage();
        $error_data['trace'] = $e->getTraceAsString();
    }

    AjaxHandler::sendResponse(
        false,
        __('An error occurred while searching company data', 'newbase'),
        $error_data,
        500
    );
}
