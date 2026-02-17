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

/**
 * AJAX Endpoint - Address Search by Brazilian Postal Code (CEP)
 *
 * Searches address data by CEP using ViaCEP API:
 * - Validates CEP format (8 digits)
 * - Queries ViaCEP API (primary) and BrasilAPI (fallback)
 * - Returns street, neighborhood, city, state
 * - Optional caching for performance
 *
 * Used by Address forms for auto-filling address fields.
 *
 * API Endpoints:
 * - ViaCEP: https://viacep.com.br/ws/{cep}/json/
 * - BrasilAPI: https://brasilapi.com.br/api/cep/v2/{cep}
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\Config;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Security headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

/**
 * Send JSON response and exit
 * @param bool $success Success status
 * @param string $message Message
 * @param array $data Additional data
 * @param int $http_code HTTP status code
 */
function sendResponse(bool $success, string $message, array $data = [], int $http_code = 200): void
{
    http_response_code($http_code);

    $response = [
        'success' => $success,
        'message' => $message,
    ];

    if (!empty($data)) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Validate Brazilian CEP format
 * @param string $cep CEP (cleaned, 8 digits)
 * @return bool Valid CEP
 */
function validateCEP(string $cep): bool
{
    // Must be exactly 8 digits
    if (strlen($cep) !== 8) {
        return false;
    }

    // Must be numeric
    if (!ctype_digit($cep)) {
        return false;
    }

    // Cannot be all zeros
    if (preg_match('/^0+$/', $cep)) {
        return false;
    }

    // Cannot be all same digit
    if (preg_match('/^(\d)\1{7}$/', $cep)) {
        return false;
    }

    return true;
}

/**
 * Search address in ViaCEP API (primary source)
 * @param string $cep CEP without formatting
 * @return array|null Address data or null if not found
 */
function searchViaCEP(string $cep): ?array
{
    $url = "https://viacep.com.br/ws/{$cep}/json/";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT      => 'GLPI-Newbase-Plugin/2.1.0',
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
        ],
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        Toolbox::logInFile('newbase_plugin', "ViaCEP CURL Error for CEP {$cep}: {$error}\n");
        return null;
    }

    if ($httpCode !== 200) {
        Toolbox::logInFile('newbase_plugin', "ViaCEP HTTP Error for CEP {$cep}: {$httpCode}\n");
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
 * @param string $cep CEP without formatting
 * @return array|null Address data or null if not found
 */
function searchBrasilAPI(string $cep): ?array
{
    $url = "https://brasilapi.com.br/api/cep/v2/{$cep}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT      => 'GLPI-Newbase-Plugin/2.1.0',
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
        ],
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        Toolbox::logInFile('newbase_plugin', "BrasilAPI CURL Error for CEP {$cep}: {$error}\n");
        return null;
    }

    if ($httpCode !== 200) {
        Toolbox::logInFile('newbase_plugin', "BrasilAPI HTTP Error for CEP {$cep}: {$httpCode}\n");
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

// ===== AUTHENTICATION CHECK =====

if (!Session::getLoginUserID()) {
    sendResponse(false, __('Authentication required'), [], 401);
}

// ===== CHECK PERMISSIONS =====

if (!Session::haveRight('plugin_newbase', CREATE) && !Session::haveRight('plugin_newbase', UPDATE)) {
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf("User %d tried to search CEP without permission\n", Session::getLoginUserID())
    );
    sendResponse(false, __('You do not have permission to search addresses', 'newbase'), [], 403);
}

// ===== CHECK IF FEATURE IS ENABLED =====

$config = Config::getConfig();
$enable_cep_search = $config['enable_cep_search'] ?? 1;

if (!$enable_cep_search) {
    sendResponse(false, __('CEP search feature is disabled', 'newbase'), [], 403);
}

// ===== GET REQUEST METHOD =====

$method = $_SERVER['REQUEST_METHOD'];

// ===== GET REQUEST DATA =====

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

// Handle non-JSON requests (fallback to POST/GET data)
if ($input === null) {
    $input = ($method === 'POST') ? $_POST : $_GET;
}

if (!is_array($input)) {
    $input = [];
}

// ===== CSRF TOKEN VALIDATION (for POST) =====

if ($method === 'POST') {
    $csrf_token = $_SERVER['HTTP_X_GLPI_CSRF_TOKEN'] ?? $input['_glpi_csrf_token'] ?? '';

    if (empty($csrf_token)) {
        Toolbox::logInFile('newbase_plugin', "AJAX CEP search: CSRF token missing\n");
        sendResponse(false, __('CSRF token is required', 'newbase'), [], 403);
    }

    try {
        Session::checkCSRF(['_glpi_csrf_token' => $csrf_token]);
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "AJAX CEP search: Invalid CSRF token\n");
        sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }
}

// ===== PROCESS REQUEST =====

try {
    // Only allow POST and GET methods
    if (!in_array($method, ['POST', 'GET'], true)) {
        sendResponse(
            false,
            sprintf(__('Method %s not allowed', 'newbase'), $method),
            [],
            405
        );
    }

    // ===== GET AND VALIDATE CEP =====

    $cep = $input['cep'] ?? '';

    if (empty($cep)) {
        sendResponse(false, __('CEP is required', 'newbase'), [], 400);
    }

    // Remove formatting (e.g., "01310-100" -> "01310100")
    $cep = preg_replace('/[^0-9]/', '', $cep);

    // Validate CEP
    if (!validateCEP($cep)) {
        sendResponse(
            false,
            __('Invalid CEP format (must be 8 digits, not all zeros or same digit)', 'newbase'),
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
        sendResponse(
            false,
            __('CEP not found or address services are temporarily unavailable', 'newbase'),
            ['cep' => $cep],
            404
        );
    }

    // Format CEP for display (01310100 -> 01310-100)
    if (strlen($cep) === 8) {
        $addressData['cep_formatted'] = substr($cep, 0, 5) . '-' . substr($cep, 5);
    }

    // Success response
    sendResponse(
        true,
        __('Address loaded successfully', 'newbase'),
        $addressData,
        200
    );

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
} catch (Exception $e) {

    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in searchAddress.php (%s): %s\n",
            $method,
            $e->getMessage()
        )
    );

    $error_data = [];

    // Include error details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $error_data['error'] = $e->getMessage();
        $error_data['trace'] = $e->getTraceAsString();
    }

    sendResponse(
        false,
        __('An error occurred while searching address', 'newbase'),
        $error_data,
        500
    );
}
