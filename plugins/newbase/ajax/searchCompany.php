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
 * AJAX Endpoint - Company Search by CNPJ
 *
 * Searches for company data by CNPJ (Brazilian company registration number):
 *
 * Strategy 1: Local Database
 * - Searches existing companies in GLPI database
 * - Returns cached data if company already registered
 *
 * Strategy 2: External APIs (via cnpj_proxy.php)
 * - Queries Brasil API and ReceitaWS
 * - Returns fresh data from government databases
 *
 * Used by CompanyData forms for auto-filling company information.
 *
 * Note: This endpoint wraps both local search and external API calls.
 * For direct API access, use cnpj_proxy.php instead.
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\CompanyData;
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
 * Search company in local database by CNPJ
 * @param string $cnpj CNPJ without formatting (14 digits)
 * @return array|null Company data or null if not found
 */
function searchLocalDatabase(string $cnpj): ?array
{
    global $DB;

    $iterator = $DB->request([
        'FROM'  => CompanyData::getTable(),
        'WHERE' => [
            'cnpj'       => $cnpj,
            'is_deleted' => 0,
        ],
        'LIMIT' => 1,
    ]);

    if (count($iterator) === 0) {
        return null;
    }

    $row = $iterator->current();

    return [
        'id'            => (int) $row['id'],
        'cnpj'          => $row['cnpj'] ?? '',
        'legal_name'    => $row['legal_name'] ?? '',
        'fantasy_name'  => $row['fantasy_name'] ?? '',
        'email'         => $row['email'] ?? '',
        'phone'         => $row['phone'] ?? '',
        'postcode'      => $row['postcode'] ?? '',
        'street'        => $row['street'] ?? '',
        'number'        => $row['number'] ?? '',
        'complement'    => $row['complement'] ?? '',
        'neighborhood'  => $row['neighborhood'] ?? '',
        'city'          => $row['city'] ?? '',
        'state'         => $row['state'] ?? '',
        'source'        => 'local',
    ];
}

/**
 * Search company in external APIs via cnpj_proxy.php
 *
 * @param string $cnpj CNPJ without formatting (14 digits)
 * @return array|null Company data or null if not found
 */
function searchExternalAPIs(string $cnpj): ?array
{
    // Use internal proxy to avoid CORS and centralize API logic
    $proxyUrl = PLUGIN_NEWBASE_WEB_DIR . '/ajax/cnpj_proxy.php';

    // Make internal request to proxy
    $response = AjaxHandler::fetchCurl(
        $proxyUrl,
        [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'cnpj'             => $cnpj,
                '_glpi_csrf_token' => Session::getNewCSRFToken(),
            ]),
            CURLOPT_HTTPHEADER => [
                'X-Glpi-Csrf-Token: ' . Session::getNewCSRFToken(),
            ],
        ],
        'POST',
        20,
        10
    );

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    if (!is_array($data) || !$data['success']) {
        return null;
    }

    return $data['data'] ?? null;
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
            sprintf("User %d tried to search company without permission\n", Session::getLoginUserID())
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to search companies', 'newbase'), [], 403);
    }

    // ===== CHECK IF FEATURE IS ENABLED =====
    $config = Config::getConfig();
    $enable_cnpj_search = $config['enable_cnpj_search'] ?? 1;

    if (!$enable_cnpj_search) {
        AjaxHandler::sendResponse(false, __('Company search feature is disabled', 'newbase'), [], 403);
    }

    // ===== CSRF TOKEN VALIDATION =====
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX company search: Invalid CSRF token\n");
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

    // Validate CNPJ using Common class
    if (!Common::validateCNPJ($cnpj)) {
        AjaxHandler::sendResponse(
            false,
            __('Invalid CNPJ: check digits verification failed', 'newbase'),
            ['cnpj' => $cnpj],
            400
        );
    }

    // ===== STRATEGY 1: SEARCH IN LOCAL DATABASE =====
    $companyData = searchLocalDatabase($cnpj);

    if ($companyData !== null) {
        // Company found in local database
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Company found in local database: CNPJ %s (ID: %d)\n",
                $cnpj,
                $companyData['id']
            )
        );

        AjaxHandler::sendResponse(
            true,
            __('Company data loaded from local database', 'newbase'),
            $companyData,
            200
        );
    }

    // ===== STRATEGY 2: SEARCH IN EXTERNAL APIs =====
    $companyData = searchExternalAPIs($cnpj);

    if ($companyData === null) {
        Toolbox::logInFile('newbase_plugin', "Company not found for CNPJ {$cnpj}\n");
        AjaxHandler::sendResponse(
            false,
            __('Company not found in any database or API', 'newbase'),
            ['cnpj' => $cnpj],
            404
        );
    }

    // Format CNPJ for display
    $companyData['cnpj_formatted'] = Common::formatCNPJ($cnpj);

    // Success response
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "Company found in external API: CNPJ %s - %s (sources: %s)\n",
            $cnpj,
            $companyData['legal_name'] ?? 'N/A',
            implode(', ', $companyData['sources'] ?? ['Unknown'])
        )
    );

    AjaxHandler::sendResponse(
        true,
        __('Company data loaded from external API', 'newbase'),
        $companyData,
        200
    );
} catch (\Exception $e) {

    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in searchCompany.php: %s\n",
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
