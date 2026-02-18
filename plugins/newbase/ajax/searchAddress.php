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
 * AJAX Endpoint - Address Search by Brazilian Postal Code (CEP)
 *
 * Searches address data by CEP using external APIs:
 * - ViaCEP API (primary)
 * - BrasilAPI (fallback)
 *
 * Features:
 * - Validates CEP format (8 digits)
 * - Returns street, neighborhood, city, state
 * - Optional caching for performance
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
 * Search address in ViaCEP API (primary source)
 *
 * @param string $cep CEP without formatting
 * @return array|null Address data or null if not found
 */
function searchViaCEP(string $cep): ?array
{
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    $response = AjaxHandler::fetchCurl($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    // ViaCEP returns {"erro": true} when CEP not found
    if (!is_array($data) || isset($data['erro'])) {
        return null;
    }

    Toolbox::logInFile('newbase_plugin', "ViaCEP success for CEP {$cep}\n");

    return [
        'cep'          => $data['cep'] ?? '',
        'street'       => $data['logradouro'] ?? '',
        'complement'   => $data['complemento'] ?? '',
        'neighborhood' => $data['bairro'] ?? '',
        'city'         => $data['localidade'] ?? '',
        'state'        => $data['uf'] ?? '',
        'ibge'         => $data['ibge'] ?? '',
        'ddd'          => $data['ddd'] ?? '',
        'source'       => 'ViaCEP',
    ];
}

/**
 * Search address in BrasilAPI (fallback source)
 *
 * @param string $cep CEP without formatting
 * @return array|null Address data or null if not found
 */
function searchBrasilAPI(string $cep): ?array
{
    $url = "https://brasilapi.com.br/api/cep/v2/{$cep}";
    $response = AjaxHandler::fetchCurl($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    if (!is_array($data) || !isset($data['cep'])) {
        return null;
    }

    Toolbox::logInFile('newbase_plugin', "BrasilAPI success for CEP {$cep}\n");

    return [
        'cep'          => $data['cep'] ?? '',
        'street'       => $data['street'] ?? '',
        'complement'   => '',
        'neighborhood' => $data['neighborhood'] ?? '',
        'city'         => $data['city'] ?? '',
        'state'        => $data['state'] ?? '',
        'ibge'         => '',
        'ddd'          => '',
        'source'       => 'BrasilAPI',
    ];
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
            sprintf("User %d tried to search CEP without permission\n", Session::getLoginUserID())
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to search addresses', 'newbase'), [], 403);
    }

    // ===== CHECK IF FEATURE IS ENABLED =====
    $config = Config::getConfig();
    $enable_cep_search = $config['enable_cep_search'] ?? 1;

    if (!$enable_cep_search) {
        AjaxHandler::sendResponse(false, __('CEP search feature is disabled', 'newbase'), [], 403);
    }

    // ===== CSRF TOKEN VALIDATION =====
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX CEP search: Invalid CSRF token\n");
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

    // ===== GET AND VALIDATE CEP =====
    $cep = $input['cep'] ?? '';

    if (empty($cep)) {
        AjaxHandler::sendResponse(false, __('CEP is required', 'newbase'), [], 400);
    }

    // Remove formatting
    $cep = preg_replace('/[^0-9]/', '', $cep);

    // Validate CEP using Common class
    if (!Common::validateCEP($cep)) {
        AjaxHandler::sendResponse(
            false,
            __('Invalid CEP format (must be 8 digits)', 'newbase'),
            ['cep' => $cep, 'length' => strlen($cep)],
            400
        );
    }

    // ===== SEARCH IN APIs =====

    // Try ViaCEP first (primary source)
    $addressData = searchViaCEP($cep);

    // Fallback to BrasilAPI if ViaCEP failed
    if ($addressData === null) {
        $addressData = searchBrasilAPI($cep);
    }

    // If neither API returned data
    if ($addressData === null) {
        Toolbox::logInFile('newbase_plugin', "CEP {$cep} not found in any API\n");
        AjaxHandler::sendResponse(
            false,
            __('CEP not found or address services are temporarily unavailable', 'newbase'),
            ['cep' => $cep],
            404
        );
    }

    // Format CEP for display
    $addressData['cep_formatted'] = Common::formatCEP($cep);

    // Log success
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "CEP search success: %s - %s, %s - %s/%s (source: %s)\n",
            $cep,
            $addressData['street'] ?: 'N/A',
            $addressData['neighborhood'] ?: 'N/A',
            $addressData['city'] ?: 'N/A',
            $addressData['state'] ?: 'N/A',
            $addressData['source']
        )
    );

    // Success response
    AjaxHandler::sendResponse(
        true,
        __('Address loaded successfully', 'newbase'),
        $addressData,
        200
    );
} catch (\Exception $e) {
    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in searchAddress.php: %s\n",
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
        __('An error occurred while searching address', 'newbase'),
        $error_data,
        500
    );
}
