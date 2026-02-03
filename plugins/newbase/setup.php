<?php

/**
* Setup for Newbase Plugin
* @package   GlpiPlugin\Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

declare(strict_types=1);

// Prevent direct access
if (!defined('GLPI_ROOT')) {
    die('Direct access not allowed');
}

/**
* Newbase Plugin for GLPI
* System for complete company documentation management
*/

define('NEWBASE_VERSION', '2.1.0');
define('NEWBASE_MIN_GLPI', '10.0.20');
define('NEWBASE_MAX_GLPI', '10.0.99');
define('NEWBASE_MIN_PHP', '8.3');

/**
* Plugin Version Declaration - REQUIRED BY GLPI
*
* @return array Plugin information required by GLPI
*/
function plugin_version_newbase(): array
{
    return [
        'name'           => 'Newbase',
        'version'        => NEWBASE_VERSION,
        'author'         => 'João Lucas',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://github.com/JoaoLucascp/Glpi',
        'description'    => 'Complete system for managing company documentation in GLPI with support for telephone systems (Asterisk, CloudPBX), field tasks with geolocation, digital signatures and mileage calculation',
        'requirements'   => [
            'glpi' => NEWBASE_MIN_GLPI,
            'php'  => NEWBASE_MIN_PHP
        ]
    ];
}

/**
* Check Prerequisites - REQUIRED BY GLPI
* Called before installation
*
* @return bool Whether prerequisites are met
*/
function plugin_newbase_check_prerequisites(): bool
{
    // Check GLPI version
    if (version_compare(GLPI_VERSION, NEWBASE_MIN_GLPI, '<')) {
        echo sprintf(
            __('This plugin requires GLPI %s or higher', 'newbase'),
            NEWBASE_MIN_GLPI
        );
        return false;
    }

    // Check GLPI max version (optional but recommended)
    if (version_compare(GLPI_VERSION, NEWBASE_MAX_GLPI, '>')) {
        echo sprintf(
            __('This plugin is not tested with GLPI %s', 'newbase'),
            GLPI_VERSION
        );
        // Don't return false here - let it install anyway with warning
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, NEWBASE_MIN_PHP, '<')) {
        echo sprintf(
            __('This plugin requires PHP %s or higher', 'newbase'),
            NEWBASE_MIN_PHP
        );
        return false;
    }

    // Check required PHP extensions
    $required_extensions = ['json', 'curl', 'gd', 'mysqli'];
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            echo sprintf(
                __('PHP extension "%s" is required but not installed', 'newbase'),
                $ext
            );
            return false;
        }
    }

    return true;
}

/**
* Check Configuration - REQUIRED BY GLPI
* Called during plugin activation
*
* @return bool Whether configuration is valid
*/
function plugin_newbase_check_config(): bool
{
    // Basic configuration check
    // More comprehensive checks could be added here
    return true;
}