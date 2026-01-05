<?php

/**
 * Setup file for Newbase Plugin
 *
 * @package   PluginNewbase
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\Common;

// Plugin dir e versao
define('PLUGIN_NEWBASE_VERSION', '2.0.0');
define('PLUGIN_NEWBASE_DIR', __DIR__);

// GLPI version range
const NEWBASE_MIN_GLPI = '10.0.20';
const NEWBASE_MAX_GLPI = '10.0.99';

/**
 * Inicializa o plugin
 */
function plugin_init_newbase(): array
{
    global $PLUGIN_HOOKS, $CFG_GLPI;

    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    $plugin = new Plugin();
    if ($plugin->isActivated('newbase')) {
        Plugin::registerClass(CompanyData::class, [
            'addtabon' => ['Entity'],
        ]);
        Plugin::registerClass(Address::class);
        Plugin::registerClass(System::class);
        Plugin::registerClass(Task::class);
        Plugin::registerClass(TaskSignature::class);
        Plugin::registerClass(Config::class, [
            'notificationtemplates_types' => true,
        ]);
        Plugin::registerClass(Common::class);

        $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
            'management' => CompanyData::class,
        ];

        $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

        $PLUGIN_HOOKS['use_massive_action']['newbase'] = 1;

        $PLUGIN_HOOKS['add_css']['newbase'] = [
            'css/newbase.css',
            'css/responsive.css',
            'css/forms.css',
        ];

        $PLUGIN_HOOKS['add_javascript']['newbase'] = [
            'js/jquery.mask.min.js',
            'js/newbase.js',
            'js/forms.js',
            'js/map.js',
            'js/signature.js',
            'js/mileage.js',
            'js/mobile.js',
        ];

        $PLUGIN_HOOKS['item_purge']['newbase'] = [
            'Entity' => [CompanyData::class, 'cleanForEntity'],
        ];
    }

    return [
        'name'  => __('Newbase - Personal Data Management', 'newbase'),
        'version' => PLUGIN_NEWBASE_VERSION,
        'author'  => 'Joao Lucas',
        'license' => 'GPLv2+',
        'homepage' => 'https://github.com/joaolucas/newbase',
        'requirements' => [
            'glpi' => [
                'min' => NEWBASE_MIN_GLPI,
                'max' => NEWBASE_MAX_GLPI,
            ],
            'php' => [
                'min' => '8.1',
            ],
        ],
    ];
}

/**
 * Versao para GLPI
 */
function plugin_version_newbase(): array
{
    return [
        'name'  => __('Newbase - Personal Data Management', 'newbase'),
        'version' => PLUGIN_NEWBASE_VERSION,
        'author'  => 'Joao Lucas',
        'license' => 'GPLv2+',
        'homepage' => 'https://github.com/joaolucas/newbase',
        'requirements' => [
            'glpi' => [
                'min' => NEWBASE_MIN_GLPI,
                'max' => NEWBASE_MAX_GLPI,
            ],
            'php' => [
                'min' => '8.1',
            ],
        ],
    ];
}

/**
 * Check prerequisites
 */
function plugin_newbase_check_prerequisites(): bool
{
    if (version_compare(PHP_VERSION, '8.1', '<')) {
        echo "This plugin requires PHP >= 8.1";
        return false;
    }

    if (!defined('GLPI_VERSION')) {
        echo "GLPI_VERSION constant not defined";
        return false;
    }

    if (version_compare(GLPI_VERSION, NEWBASE_MIN_GLPI, '<')) {
        echo "This plugin requires GLPI >= " . NEWBASE_MIN_GLPI;
        return false;
    }

    if (version_compare(GLPI_VERSION, NEWBASE_MAX_GLPI, '>=')) {
        echo "This plugin requires GLPI < " . NEWBASE_MAX_GLPI;
        return false;
    }

    return true;
}

/**
 * Check config
 */
function plugin_newbase_check_config($verbose = false): bool
{
    if ($verbose && !defined('GLPI_PRUNE_LOGS')) {
        echo __('Installed / not configured', 'newbase');
    }
    return true;
}

/**
 * Install
 */
function plugin_newbase_install(): bool
{
    global $DB;

    $migration = new Migration(PLUGIN_NEWBASE_VERSION);

    $sqlFile = PLUGIN_NEWBASE_DIR . '/install/mysql/2.0.0.sql';
    if (!file_exists($sqlFile)) {
        echo "Arquivo SQL nao encontrado: $sqlFile\n";
        return false;
    }

    $sql = file_get_contents($sqlFile);
    $commands = array_filter(
        array_map('trim', explode(';', $sql)),
        static function ($cmd) {
            return $cmd !== '';
        }
    );

    foreach ($commands as $command) {
        try {
            $DB->query($command);
        } catch (Throwable $e) {
            echo "Erro na instalacao: " . $e->getMessage() . "\n";
            return false;
        }
    }

    $migration->executeMigration();
    return true;
}

/**
 * Uninstall
 */
function plugin_newbase_uninstall(): bool
{
    global $DB;

    $tables = [
        'glpi_plugin_newbase_companydatas',
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_tasksignatures',
        'glpi_plugin_newbase_configs',
    ];

    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`");
    }

    $DB->query("DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'plugin_newbase_%'");
    $DB->query("DELETE FROM `glpi_displaypreferences` WHERE `itemtype` LIKE 'PluginNewbase%'");

    return true;
}
