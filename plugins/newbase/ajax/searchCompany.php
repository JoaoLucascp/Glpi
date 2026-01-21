<?php

/**
 * AJAX endpoint for searching company by CNPJ
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/

use GlpiPlugin\Newbase\Src\Common;

// Include GLPI
define('GLPI_ROOT', dirname(__DIR__, 3));
require_once(GLPI_ROOT . '/inc/includes.php');

// Check if user is logged in
Session::checkLoginUser();

// Check permissions - CORREÇÃO PRINCIPAL!
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

    // Search company via API
    $companyData = Common::searchCompanyByCNPJ($cnpj);

    if ($companyData === false) {
        echo json_encode([
            'success' => false,
            'message' => __('Company not found or API error', 'newbase'),
        ]);
        Toolbox::logInFile('newbase_plugin', "CNPJ search failed for: $cnpj\n");
        exit;
    }

    // Success response
    echo json_encode([
        'success' => true,
        'data' => [
            'legal_name' => $companyData['legal_name'] ?? '',
            'fantasy_name' => $companyData['fantasy_name'] ?? '',
            'email' => $companyData['email'] ?? '',
            'phone' => Common::formatPhone($companyData['phone'] ?? ''),
        ],
        'message' => __('Company data loaded successfully', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "CNPJ search successful for: $cnpj\n");

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in searchCompany.php: " . $e->getMessage() . "\n");
}
