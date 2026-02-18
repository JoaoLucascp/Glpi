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
 * Acts as a server-side proxy to query multiple Brazilian CNPJ APIs
 *
 * 1. Brasil API (https://brasilapi.com.br) - Primary source
 * 2. ReceitaWS (https://receitaws.com.br) - Fallback source
 *
 * Features:
 * - Solves CORS (Cross-Origin Resource Sharing) issues
 * - Merges data from multiple sources for completeness
 * - Automatic fallback between APIs
 * - CNPJ validation with check digits
 * - Response caching (optional)
 * - Comprehensive error handling (with masked logs for LGPD)
 *
 * Used by CompanyData forms to auto-fill company information from CNPJ.
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\AjaxHandler;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\Common;
use Session;
use Toolbox;
use Exception;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Set security headers
AjaxHandler::setSecurityHeaders();

// ==========================================
// FUNÇÕES DE MASCARAMENTO (LGPD)
// ==========================================

/**
 * Mask CNPJ for logging (protects privacy)
 * @param string $cnpj Full CNPJ
 * @return string Masked CNPJ safe for logging
 */
function maskCNPJ(string $cnpj): string {
    $clean = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($clean) !== 14) return '**.***.***/****-**';
    return substr($clean, 0, 2) . '.**.***/****-' . substr($clean, -2);
}

/**
 * Mask person name for logging
 * Example: João da Silva -> Jo...a
 */
function maskName(string $name): string {
    $name = trim($name);
    if (strlen($name) <= 3) return $name;
    return substr($name, 0, 2) . '...' . substr($name, -1);
}

/**
 * Mask email address for logging
 * Example: usuario@empresa.com.br -> usu***@empresa.com.br
 */
function maskEmail(string $email): string {
    $email = trim($email);
    if (strpos($email, '@') === false) return '***';

    list($user, $domain) = explode('@', $email, 2);
    $maskedUser = (strlen($user) > 3) ? substr($user, 0, 3) . '***' : $user . '***';
    return $maskedUser . '@' . $domain;
}

// ==========================================
// FUNÇÕES DE BUSCA (API)
// ==========================================

/**
 * Search company data in Brasil API
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
        Toolbox::logInFile('newbase_plugin', "Brasil API success for CNPJ " . maskCNPJ($cnpj));
        return $data;
    }

    Toolbox::logInFile('newbase_plugin', "Brasil API failed for CNPJ " . maskCNPJ($cnpj));
    return null;
}

/**
 * Search company data in ReceitaWS API
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

    $data = json_decode($response, true); // ReceitaWS returns errors within JSON

    if (is_array($data) && isset($data['status']) && $data['status'] === 'OK') {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS success for CNPJ " . maskCNPJ($cnpj));
        return $data;
    }

    if (isset($data['message'])) {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS error for CNPJ " . maskCNPJ($cnpj) . ": " . $data['message']);
    } else {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS failed for CNPJ " . maskCNPJ($cnpj));
    }

    return null;
}

/**
 * Merge data from multiple APIs prioritizing Brasil API
 */
function mergeAPIData(?array $brasilAPI, ?array $receitaWS): array
{
    $result = [
        'cnpj'         => '',
        'legal_name'   => '', // razao_social
        'fantasy_name' => '', // nome_fantasia
        'email'        => '',
        'phone'        => '',
        'postcode'     => '', // cep
        'street'       => '', // logradouro
        'number'       => '',
        'complement'   => '',
        'neighborhood' => '', // bairro
        'city'         => '', // municipio
        'state'        => '', // uf
        'sources'      => '',
    ];

    // Priority 1: Brasil API
    if ($brasilAPI !== null) {
        $result['cnpj']         = $brasilAPI['cnpj'] ?? '';
        $result['legal_name']   = $brasilAPI['razao_social'] ?? '';
        $result['fantasy_name'] = $brasilAPI['nome_fantasia'] ?? '';
        $result['email']        = $brasilAPI['email'] ?? '';

        // Phone: concatenate DDD + phone if available
        if (!empty($brasilAPI['ddd_telefone_1'])) {
            $result['phone'] = $brasilAPI['ddd_telefone_1'];
        }

        $result['postcode']     = preg_replace('/[^0-9]/', '', $brasilAPI['cep'] ?? '');

        // Street: concatenate type + name
        $result['street']       = trim(($brasilAPI['descricao_tipo_logradouro'] ?? '') . ' ' . ($brasilAPI['logradouro'] ?? ''));
        $result['number']       = $brasilAPI['numero'] ?? '';
        $result['complement']   = $brasilAPI['complemento'] ?? '';
        $result['neighborhood'] = $brasilAPI['bairro'] ?? '';
        $result['city']         = $brasilAPI['municipio'] ?? '';
        $result['state']        = $brasilAPI['uf'] ?? '';
        $result['sources']      = 'Brasil API';
    }

    // Priority 2: ReceitaWS (fill missing data)
    if ($receitaWS !== null) {
        if (empty($result['cnpj']))         $result['cnpj']         = $receitaWS['cnpj'] ?? '';
        if (empty($result['legal_name']))   $result['legal_name']   = $receitaWS['nome'] ?? '';
        if (empty($result['fantasy_name'])) $result['fantasy_name'] = $receitaWS['fantasia'] ?? '';

        if (empty($result['email']) && !empty($receitaWS['email'])) {
            $result['email']   = $receitaWS['email'];
            $result['sources'] .= ($result['sources'] ? ' + ' : '') . 'ReceitaWS (email)';
        }

        if (empty($result['phone']) && !empty($receitaWS['telefone'])) {
            $result['phone']   = $receitaWS['telefone'];
            $result['sources'] .= ($result['sources'] ? ' + ' : '') . 'ReceitaWS (phone)';
        }

        if (empty($result['postcode']))     $result['postcode']     = preg_replace('/[^0-9]/', '', $receitaWS['cep'] ?? '');
        if (empty($result['street']))       $result['street']       = $receitaWS['logradouro'] ?? '';
        if (empty($result['number']))       $result['number']       = $receitaWS['numero'] ?? '';
        if (empty($result['complement']))   $result['complement']   = $receitaWS['complemento'] ?? '';
        if (empty($result['neighborhood'])) $result['neighborhood'] = $receitaWS['bairro'] ?? '';
        if (empty($result['city']))         $result['city']         = $receitaWS['municipio'] ?? '';
        if (empty($result['state']))        $result['state']        = $receitaWS['uf'] ?? '';

        if (empty($result['sources']))      $result['sources']      = 'ReceitaWS';
        else if (strpos($result['sources'], 'ReceitaWS') === false) $result['sources'] .= ' + ReceitaWS (address)';
    }

    // Remove empty sources entry
    if (empty($result['sources'])) unset($result['sources']);

    return $result;
}

// ==========================================
// MAIN EXECUTION
// ==========================================

try {
    // AUTHENTICATION & AUTHORIZATION
    if (!Session::getLoginUserID()) {
        AjaxHandler::sendResponse(false, __('Authentication required', 'newbase'), [], 401);
    }

    // Check permissions
    if (!AjaxHandler::checkPermissions('plugin_newbase')) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf("User %d tried to search CNPJ without permission", Session::getLoginUserID())
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to access this feature', 'newbase'), [], 403);
    }

    // CHECK IF FEATURE IS ENABLED config
    $config = Config::getConfig();
    $enable_cnpj_search = $config['enable_cnpj_search'] ?? 1;

    if (!$enable_cnpj_search) {
        AjaxHandler::sendResponse(false, __('CNPJ search feature is disabled', 'newbase'), [], 403);
    }

    // CSRF TOKEN VALIDATION
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX CNPJ search: Invalid CSRF token");
        AjaxHandler::sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }

    // GET REQUEST DATA
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
        $input = []; // Safety fallback
    }

    // GET AND VALIDATE CNPJ
    $cnpj = $input['cnpj'] ?? '';

    if (empty($cnpj)) {
        AjaxHandler::sendResponse(false, __('CNPJ is required', 'newbase'), [], 400);
    }

    // Remove formatting
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    $maskedCNPJ = maskCNPJ($cnpj); // Prepare masked version for logs

    // Validate using Common class
    if (!Common::validateCNPJ($cnpj)) {
        AjaxHandler::sendResponse(
            false,
            __('Invalid CNPJ (check digits verification failed)', 'newbase'),
            ['cnpj' => $cnpj],
            400
        );
    }

    // SEARCH IN APIs

    // Try Brasil API first (primary source)
    $brasilAPIData = searchBrasilAPI($cnpj);

    // Try ReceitaWS as fallback or complement
    $receitaWSData = null;
    if ($brasilAPIData === null || empty($brasilAPIData['email'])) {
        $receitaWSData = searchReceitaWSAPI($cnpj);
    }

    // Check if at least one API returned data
    if ($brasilAPIData === null && $receitaWSData === null) {
        Toolbox::logInFile('newbase_plugin', "CNPJ {$maskedCNPJ} not found in any API");

        AjaxHandler::sendResponse(
            false,
            __('CNPJ not found or APIs are temporarily unavailable', 'newbase'),
            ['cnpj' => $cnpj],
            404
        );
    }

    // Merge data from both APIs
    $result = mergeAPIData($brasilAPIData, $receitaWSData);

    // Log success (Masked!)
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "CNPJ search success: %s | Name: %s | Email: %s | Source: %s",
            $maskedCNPJ,
            maskName($result['legal_name']),
            maskEmail($result['email']),
            $result['sources'] ?? 'Unknown'
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
    // ERROR HANDLING
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf("ERROR in cnpj_proxy.php: %s", $e->getMessage())
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
