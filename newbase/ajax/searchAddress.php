<?php
/**
 * AJAX endpoint for searching address by CEP
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

// Security check
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

include(GLPI_ROOT . "/inc/includes.php");

// Autoload composer dependencies if not already loaded (e.g., in a standalone AJAX file)
require_once __DIR__ . '/../vendor/autoload.php';

use GlpiPlugin\Newbase\Ajax\AddressHandler;

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_companydata', READ);

// Validate CSRF token
Session::checkCSRF($_POST);

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

try {
    $handler = new AddressHandler();
    $response = $handler->handleSearch(); // Call the method in the new class
    echo json_encode($response);

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase')
    ]);

    // Keep Toolbox::logInFile for now, as it's what was in the original file
    \Toolbox::logInFile('newbase_plugin', "ERROR in ajax/searchAddress.php (main handler): " . $e->getMessage() . "\n");
}

exit;