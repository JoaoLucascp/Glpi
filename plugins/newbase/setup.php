<?php
/**
 * Setup file for Newbase Plugin
 *
 * This file handles plugin installation, configuration, and hooks registration
 * Compatible with GLPI 10.0.20
 *
 * @package   PluginNewbase
 * @author    JoÃ£o Lucas
 * @copyright Copyright (c) 2025 JoÃ£o Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\Common;

// Minimal GLPI version
const newbase_MIN_GLPI = '10.0.20';
// Maximum GLPI version
const newbase_MAX_GLPI = '10.0.99';
// Plugin version
const newbase_VERSION = '2.0.0';

/**
 * Initialize plugin
 *
 * @return array Plugin information
 */
function plugin_init_newbase(): array
{
    global $PLUGIN_HOOKS, $CFG_GLPI;

    // Composer autoloader
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once(__DIR__ . '/vendor/autoload.php');
    }

    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    $plugin = new Plugin();
    if ($plugin->isActivated('newbase')) {

        // Register classes for autoload
        Plugin::registerClass(CompanyData::class, [
            'addtabon' => ['Entity']
        ]);
        Plugin::registerClass(Address::class);
        Plugin::registerClass(System::class);
        Plugin::registerClass(Task::class);
        Plugin::registerClass(TaskSignature::class);
        Plugin::registerClass(Config::class, [
            'notificationtemplates_types' => true
        ]);
        Plugin::registerClass(Common::class);

        // Add to Management menu
        $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
            'management' => CompanyData::class
        ];

        // Configuration page
        $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

        // Register item actions
        $PLUGIN_HOOKS['use_massive_action']['newbase'] = 1;

        // Add CSS files
        $PLUGIN_HOOKS['add_css']['newbase'] = [
            'css/newbase.css',
            'css/responsive.css',
            'css/forms.css'
        ];

        // Add JavaScript files
        $PLUGIN_HOOKS['add_javascript']['newbase'] = [
            'js/jquery.mask.min.js',
            'js/newbase.js',
            'js/forms.js',
            'js/map.js',
            'js/signature.js',
            'js/mileage.js',
            'js/mobile.js'
        ];

        // Add rights
        $PLUGIN_HOOKS['item_purge']['newbase'] = [
            'Entity' => [CompanyData::class, 'cleanForEntity']
        ];
    }

    return [
        'name'           => __('Newbase - Personal Data Management', 'newbase'),
        'version'        => newbase_VERSION,
        'author'         => 'JoÃ£o Lucas',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://github.com/joaolucas/newbase',
        'requirements'   => [
            'glpi' => [
                'min' => newbase_MIN_GLPI,
                'max' => newbase_MAX_GLPI
            ],
            'php' => [
                'min' => '8.1'
            ]
        ]
    ];
}

/**
 * Get plugin version (GLPI calls this function)
 *
 * @return array Plugin information
 */
function plugin_version_newbase(): array
{
    return [
        'name'           => __('Newbase - Personal Data Management', 'newbase'),
        'version'        => newbase_VERSION,
        'author'         => 'JoÃ£o Lucas',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://github.com/joaolucas/newbase',
        'requirements'   => [
            'glpi' => [
                'min' => newbase_MIN_GLPI,
                'max' => newbase_MAX_GLPI
            ],
            'php' => [
                'min' => '8.1'
            ]
        ]
    ];
}

/**
 * Check plugin prerequisites before installation
 *
 * @return bool True if prerequisites are met
 */
function plugin_newbase_check_prerequisites(): bool
{
    // Check PHP version
    if (version_compare(PHP_VERSION, '8.1', '<')) {
        echo "This plugin requires PHP >= 8.1";
        return false;
    }

    // Check GLPI version using GLPI_VERSION constant
    if (!defined('GLPI_VERSION')) {
        echo "GLPI_VERSION constant not defined";
        return false;
    }

    if (version_compare(GLPI_VERSION, newbase_MIN_GLPI, '<')) {
        echo "This plugin requires GLPI >= " . newbase_MIN_GLPI;
        return false;
    }

    if (version_compare(GLPI_VERSION, newbase_MAX_GLPI, '>=')) {
        echo "This plugin requires GLPI < " . newbase_MAX_GLPI;
        return false;
    }

    return true;
}

/**
 * Check plugin configuration
 *
 * @param bool $verbose Display message if config not ok
 * @return bool True if configuration is OK
 */
function plugin_newbase_check_config($verbose = false): bool
{
    if ($verbose && !defined('GLPI_PRUNE_LOGS')) {
        echo __('Installed / not configured', 'newbase');
    }
    return true;
}

/**
 * Install plugin
 *
 * @return bool True on success
 */

/**
 * Uninstall plugin
 *
 * @return bool True on success
 */

