<?php
/**
 * System list page for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

include('../../../inc/includes.php');

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_system', READ);

// Start page
Html::header(
    __('Communication Systems', 'newbase'),
    $_SERVER['PHP_SELF'],
    "management",
    "PluginNewbaseSystem"
);

// Create search instance
Search::show('PluginNewbaseSystem');

Html::footer();
