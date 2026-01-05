<?php
/**
 * Configuration page for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\Config;

include('../../../inc/includes.php');

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_config', UPDATE);

// Handle configuration update
if (isset($_POST['update_config'])) {
    if (Config::handleConfigUpdate($_POST)) {
        Html::back();
    } else {
        Html::back();
    }
}

// Start page
Html::header(
    __('Newbase Configuration', 'newbase'),
    $_SERVER['PHP_SELF'],
    "config",
    "plugins"
);

// Display configuration form
$config = new Config();
$config->showConfigForm();

Html::footer();
