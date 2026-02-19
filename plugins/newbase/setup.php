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

// Plugin version
define('PLUGIN_NEWBASE_VERSION', '2.1.0');

// Minimal GLPI version, inclusive
define('PLUGIN_NEWBASE_MIN_GLPI_VERSION', '10.0.0');
// Maximum GLPI version, exclusive
define('PLUGIN_NEWBASE_MAX_GLPI_VERSION', '11.0.99');

// Minimum PHP version, inclusive
define('PLUGIN_NEWBASE_MIN_PHP_VERSION', '8.1.0');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_newbase(): void
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    // Plugin requires login
    $plugin = new \Plugin();
    if (!$plugin->isInstalled('newbase') || !$plugin->isActivated('newbase')) {
        return;
    }

    // Add specific CSS
    // we may include several stylesheets used by the plugin
    $PLUGIN_HOOKS['add_css']['newbase'] = [
        'css/newbase.css',
        'css/forms.css',
        'css/responsive.css'
    ];

    // Add specific JavaScript files
    $PLUGIN_HOOKS['add_javascript']['newbase'] = [
        'js/forms.js',
        'js/mileage.js',
        'js/map.js',
        'js/signature.js'
    ];

    // Register classes for autoload and rights management
    \Plugin::registerClass('GlpiPlugin\\Newbase\\Address', [
        'addtabon' => ['Entity']
    ]);
    \Plugin::registerClass('GlpiPlugin\\Newbase\\CompanyData', [
        'addtabon' => ['Entity']
    ]);
    \Plugin::registerClass('GlpiPlugin\\Newbase\\System');
    \Plugin::registerClass('GlpiPlugin\\Newbase\\Task');
    \Plugin::registerClass('GlpiPlugin\\Newbase\\TaskSignature');
    \Plugin::registerClass('GlpiPlugin\\Newbase\\Config');
    \Plugin::registerClass('GlpiPlugin\\Newbase\\Menu');

    // Menu entries - Check if user has rights
    if (\Session::haveRight('plugin_newbase', READ)) {
        // Add to Plugins menu group
        $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
            'plugins' => 'GlpiPlugin\\Newbase\\Menu'
        ];
    }

    // Configuration page
    if (\Session::haveRight('config', UPDATE)) {
        $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';
    }

    // Add custom stylesheet and javascript only after checks
    if ($plugin->isInstalled('newbase') && $plugin->isActivated('newbase')) {
        // CSS already added above
        // JS already added above
    }
}

/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array{name: string, version: string, author: string, license: string, homepage: string, requirements: array{glpi: array{min: string, max: string, dev: bool}, php: array{min: string}, plugins: array<string>}}
 */
function plugin_version_newbase(): array
{
    return [
        'name'         => 'Newbase',
        'version'      => PLUGIN_NEWBASE_VERSION,
        'author'       => 'João Lucas',
        'license'      => 'GPLv2+',
        'homepage'     => 'https://github.com/JoaoLucascp/Glpi',
        'requirements' => [
            'glpi'    => [
                'min' => PLUGIN_NEWBASE_MIN_GLPI_VERSION,
                'max' => PLUGIN_NEWBASE_MAX_GLPI_VERSION,
                'dev' => false
            ],
            'php'     => [
                'min' => PLUGIN_NEWBASE_MIN_PHP_VERSION,
            ],
            'plugins' => []
        ]
    ];
}

/**
 * Check pre-requisites before install
 * OPTIONAL, but recommended
 *
 * @return bool
 */
function plugin_newbase_check_prerequisites(): bool
{
    // Check GLPI version
    if (version_compare(GLPI_VERSION, PLUGIN_NEWBASE_MIN_GLPI_VERSION, '<')) {
        echo sprintf(
            __('This plugin requires GLPI %s or higher', 'newbase'),
            PLUGIN_NEWBASE_MIN_GLPI_VERSION
        );
        return false;
    }

    if (version_compare(GLPI_VERSION, PLUGIN_NEWBASE_MAX_GLPI_VERSION, '>=')) {
        echo sprintf(
            __('This plugin is not compatible with GLPI %s', 'newbase'),
            GLPI_VERSION
        );
        return false;
    }

    // Check PHP version
    if (version_compare(PHP_VERSION, PLUGIN_NEWBASE_MIN_PHP_VERSION, '<')) {
        echo sprintf(
            __('This plugin requires PHP %s or higher', 'newbase'),
            PLUGIN_NEWBASE_MIN_PHP_VERSION
        );
        return false;
    }

    // Check required PHP extensions
    $required_extensions = ['json', 'curl', 'gd', 'mysqli', 'mbstring'];
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            echo sprintf(
                __('PHP extension "%s" is required', 'newbase'),
                $ext
            );
            return false;
        }
    }

    return true;
}

/**
 * Check configuration process
 *
 * @param bool $verbose Whether to display message on failure
 * @return bool
 */
function plugin_newbase_check_config(bool $verbose = false): bool
{
    global $DB;

    // 1) Verifica se as tabelas essenciais existem (sem a antiga tabela de config)
    $required_tables = [
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_task_signatures',
        'glpi_plugin_newbase_company_extras',
    ];

    foreach ($required_tables as $table) {
        if (!$DB->tableExists($table)) {
            if ($verbose) {
                echo sprintf(
                    __('Required table "%s" does not exist', 'newbase'),
                    $table
                );
            }
            return false;
        }
    }

    // 2) Verifica se existe configuração gravada em glpi_configs (contexto plugin:newbase)
    $conf = \Config::getConfigurationValues('plugin:newbase');


    if (empty($conf)) {
        if ($verbose) {
            echo __('Plugin is installed but not configured yet', 'newbase');
        }
        return false;
    }

    return true;
}