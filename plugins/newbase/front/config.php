<?php

/**
 * Plugin Configuration Page - Newbase Plugin
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @copyright 2026 João Lucas
 * @license   GPLv2+
 * @version   2.1.0
 */

declare(strict_types=1);

// SECURITY: Load GLPI core
include '../../../inc/includes.php';

// Import classes
use GlpiPlugin\Newbase\Config;

// SECURITY: Check authentication
Session::checkLoginUser();

// SECURITY: Check rights
Session::checkRight('config', READ);

// Handle configuration update
if (isset($_POST['update_config'])) {
    // SECURITY: Check CSRF token
    Session::checkCSRF($_POST);

    // Check write permission
    Session::checkRight('config', WRITE);

    // Handle form submission here if needed
    Html::back();
}

// Start page
Html::header(
    __('Newbase Configuration', 'newbase'),
    $_SERVER['PHP_SELF'],
    'config',
    'plugins'
$config = new Config();
$config->showConfigForm();

Html::footer();