<?php

/**
* AJAX endpoint for searching company by CNPJ
*
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.1.0
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

use GlpiPlugin\Newbase\Src\Common;
use GlpiPlugin\Newbase\Src\CompanyData;

try {
    // Get CNPJ from POST
    $cnpj = $_POST['cnpj'] ?? '';

    if (empty($cnpj)) {
        echo json_encode([
            'success' => false,
            'message' => __('CNPJ is required', 'newbase'),
        ]);
        exit;
    }

    // Remove formatting
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // Validate CNPJ length
    if (strlen($cnpj) !== 14) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid CNPJ length', 'newbase'),
        ]);
        exit;
    }

    // Validate CNPJ check digits
    if (!Common::validateCNPJ($cnpj)) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid CNPJ', 'newbase'),
        ]);
        exit;
    }

    // First: try to find company in database
    $company = CompanyData::getCompanyByCNPJ($cnpj);

    if ($company) {
        // Company already in database, return its data
        echo json_encode([
            'success' => true,
            'data' => [
                'corporate_name' => $company['name'] ?? '',
                'fantasy_name' => $company['fantasy_name'] ?? '',
                'email' => $company['email'] ?? '',
                'phone' => Common::formatPhone($company['phone'] ?? ''),
            ],
            'message' => __('Company data loaded successfully', 'newbase'),
        ]);
    } else {
        // Company not in database, search via API
        $companyData = Common::searchCompanyByCNPJ($cnpj);

        if ($companyData === false) {
            echo json_encode([
                'success' => false,
                'message' => __('Company not found or API error', 'newbase'),
            ]);
            Toolbox::logInFile('newbase_plugin', "CNPJ search failed for: $cnpj\n");
            exit;
        }

        // Search for additional data (email and phone)
        $additionalData = Common::searchCompanyAdditionalData($cnpj, $companyData['legal_name'] ?? '');

        // Success response
        echo json_encode([
            'success' => true,
            'data' => [
                'corporate_name' => $companyData['legal_name'] ?? '',
                'fantasy_name' => $companyData['fantasy_name'] ?? '',
                'email' => $additionalData['email'] ?? '',
                'phone' => Common::formatPhone($additionalData['phone'] ?? ''),
            ],
            'message' => __('Company data loaded successfully', 'newbase'),
        ]);

        Toolbox::logInFile('newbase_plugin', "CNPJ search successful for: $cnpj\n");
    }
} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in searchCompany.php: " . $e->getMessage() . "\n");
}
