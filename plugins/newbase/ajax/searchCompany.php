<?php

/**
 * AJAX endpoint for searching company by CNPJ
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2026 João Lucas
 * @license   GPLv2+
 * @since     2.1.0
 */

use GlpiPlugin\Newbase\Src\Common;
use GlpiPlugin\Newbase\Src\CompanyData;

// Include GLPI
define('GLPI_ROOT', dirname(__DIR__, 3));
require_once(GLPI_ROOT . '/inc/includes.php');

// Check if user is logged in
Session::checkLoginUser();

// Check permissions
Session::checkRight('plugin_newbase', READ);

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

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
