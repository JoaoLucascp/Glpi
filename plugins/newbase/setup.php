<?php

/**
* Newbase Plugin Setup
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

declare(strict_types=1);

// Load Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Import classes with full namespace
use GlpiPlugin\Newbase\Src\CompanyData;
use GlpiPlugin\Newbase\Src\Address;
use GlpiPlugin\Newbase\Src\System;
use GlpiPlugin\Newbase\Src\Task;
use GlpiPlugin\Newbase\Src\TaskSignature;
use GlpiPlugin\Newbase\Src\Config;

// Define constants
define('PLUGIN_NEWBASE_VERSION', '2.0.0');
define('PLUGIN_NEWBASE_DIR', __DIR__);
define('NEWBASE_MIN_GLPI', '10.0.0');
define('NEWBASE_MAX_GLPI', '10.1.0');

/**
 * Initialize plugin
 */
function plugin_init_newbase()
{
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    if (Plugin::isPluginActive('newbase')) {

        // Register classes with full namespace
        Plugin::registerClass(CompanyData::class, [
            'addtabon' => ['Entity'],
        ]);

        Plugin::registerClass(Address::class);
        Plugin::registerClass(System::class);
        Plugin::registerClass(Task::class);
        Plugin::registerClass(TaskSignature::class);
        Plugin::registerClass(Config::class);

        // Add to management menu
        $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
            'management' => CompanyData::class,
        ];

        $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';
        $PLUGIN_HOOKS['use_massive_action']['newbase'] = 1;

        // Add CSS
        $PLUGIN_HOOKS['add_css']['newbase'] = [
            'css/newbase.css',
            'css/responsive.css',
            'css/forms.css',
        ];

        // Add JavaScript
        $PLUGIN_HOOKS['add_javascript']['newbase'] = [
            'js/jquery.mask.min.js',
            'js/newbase.js',
            'js/forms.js',
            'js/map.js',
            'js/signature.js',
            'js/mileage.js',
            'js/mobile.js',
        ];

        // Item purge hook
        $PLUGIN_HOOKS['item_purge']['newbase'] = [
            'Entity' => [CompanyData::class, 'cleanForEntity'],
        ];
    }
}

/**
 * Get plugin version
 */
function plugin_version_newbase(): array
{
    return [
        'name'           => __('Newbase - Company Management', 'newbase'),
        'version'        => PLUGIN_NEWBASE_VERSION,
        'author'         => 'João Lucas',
        'license'        => 'GPLv2+',
        'homepage'       => 'https://github.com/newtel/newbase',
        'requirements'   => [
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
 * Install plugin rights
 */
function plugin_newbase_install_rights()
{
    global $DB;

    $rights = [
        'plugin_newbase' => ALLSTANDARDRIGHT,
    ];

    $profile = new Profile();
    $profiles = $profile->find();

    foreach ($rights as $right => $default_value) {
        ProfileRight::addProfileRights([$right]);

        foreach ($profiles as $profile_id => $profile_data) {
            $value = 0;

            // Super-admin has all rights
            if ($profile_data['name'] === 'Super-Admin') {
                $value = ALLSTANDARDRIGHT;
            } elseif ($profile_data['name'] === 'Admin') {
                $value = ALLSTANDARDRIGHT;
            } elseif ($profile_data['name'] === 'Technician') {
                $value = READ + CREATE + UPDATE;
            }

            if ($value > 0) {
                ProfileRight::updateProfileRights($profile_id, [$right => $value]);
            }
        }
    }

    return true;
}

/**
 * Install plugin
 */
function plugin_newbase_install(): bool
{
    global $DB;

    $migration = new Migration(PLUGIN_NEWBASE_VERSION);

    // Disable foreign key checks
    $DB->query("SET FOREIGN_KEY_CHECKS = 0");

    $sqlFile = PLUGIN_NEWBASE_DIR . '/install/mysql/2.0.0.sql';

    if (!file_exists($sqlFile)) {
        echo "SQL file not found: $sqlFile\n";
        $DB->query("SET FOREIGN_KEY_CHECKS = 1");
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
            echo "Installation error: " . $e->getMessage() . "\n";
            $DB->query("SET FOREIGN_KEY_CHECKS = 1");
            return false;
        }
    }

    // Re-enable foreign key checks
    $DB->query("SET FOREIGN_KEY_CHECKS = 1");

    $migration->executeMigration();

    plugin_newbase_install_rights();
    return true;
}

/**
 * Uninstall plugin
 */
function plugin_newbase_uninstall(): bool
{
    global $DB;

    try {
        // Disable foreign key checks
        $DB->query("SET FOREIGN_KEY_CHECKS = 0");

        // List of tables in reverse dependency order
        $tables = [
            'glpi_plugin_newbase_tasksignatures',
            'glpi_plugin_newbase_tasks',
            'glpi_plugin_newbase_systems',
            'glpi_plugin_newbase_addresses',
            'glpi_plugin_newbase_configs',
            'glpi_plugin_newbase_companydata',
        ];

        // Drop each table
        foreach ($tables as $table) {
            if ($DB->tableExists($table)) {
                $DB->query("DROP TABLE `$table`");
            }
        }

        // Re-enable foreign key checks
        $DB->query("SET FOREIGN_KEY_CHECKS = 1");

        // Clean profile rights
        $DB->query("DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'plugin_newbase%'");

        // Clean display preferences
        $DB->query("DELETE FROM `glpi_displaypreferences` WHERE `itemtype` LIKE '%Newbase%'");

        // Clean logs
        $DB->query("DELETE FROM `glpi_logs` WHERE `itemtype` LIKE '%Newbase%'");

        return true;
    } catch (Throwable $e) {
        $DB->query("SET FOREIGN_KEY_CHECKS = 1");
        Toolbox::logError('Newbase uninstall error: ' . $e->getMessage());
        return false;
    }
}
