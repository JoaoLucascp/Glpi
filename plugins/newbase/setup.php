<?php
/**
 * Setup file for Newbase Plugin
 *
 * This file handles plugin installation, configuration, and hooks registration
 * Compatible with GLPI 10.0.20
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
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
    global $PLUGIN_HOOKS;

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

        // Add to main menu
        $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
            'management' => CompanyData::class
        ];

        // Add specific menu entries
        $PLUGIN_HOOKS['menu_entry']['newbase'] = 'front/index.php';

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
            'js/jquery.mask.min.js',  // jQuery Mask Plugin - DEVE SER O PRIMEIRO!
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
        'author'         => 'João Lucas',
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
        'author'         => 'João Lucas',
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
 * FUNÇÃO OBRIGATÓRIA RENOMEADA
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
 * FUNÇÃO OBRIGATÓRIA RENOMEADA
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
 * FUNÇÃO OBRIGATÓRIA - RENOMEADA DE newbase_install() PARA plugin_newbase_install()
 *
 * @return bool True on success
 */
function plugin_newbase_install(): bool
{
    global $DB;

    // VERIFICAÇÃO IMPORTANTE: se migration ou classes não existem, falha graciosamente
    try {
        // Create CompanyData table
        if (!$DB->tableExists('glpi_plugin_newbase_companydata')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_companydata` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `entities_id` INT UNSIGNED NOT NULL DEFAULT '0',
                `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
                `cnpj` varchar(18) DEFAULT NULL,
                `name` varchar(255) NOT NULL,
                `email` varchar(255) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `legal_name` varchar(255) DEFAULT NULL,
                `fantasy_name` varchar(255) DEFAULT NULL,
                `state_registration` varchar(30) DEFAULT NULL,
                `city_registration` varchar(30) DEFAULT NULL,
                `contract_status` enum('active','inactive','cancelled') NOT NULL DEFAULT 'active',
                `date_creation` timestamp NULL DEFAULT NULL,
                `date_mod` timestamp NULL DEFAULT NULL,
                `created_by` INT UNSIGNED DEFAULT NULL,
                `modified_by` INT UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `cnpj` (`cnpj`),
                KEY `entities_id` (`entities_id`),
                KEY `date_creation` (`date_creation`),
                KEY `date_mod` (`date_mod`),
                KEY `created_by` (`created_by`),
                KEY `modified_by` (`modified_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_companydata: " . $DB->error(), E_USER_WARNING);
                return false;
            }
        }

        // Create Address table
        if (!$DB->tableExists('glpi_plugin_newbase_address')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_address` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
                `cep` varchar(9) DEFAULT NULL,
                `street` varchar(255) DEFAULT NULL,
                `number` varchar(10) DEFAULT NULL,
                `complement` varchar(255) DEFAULT NULL,
                `neighborhood` varchar(100) DEFAULT NULL,
                `city` varchar(100) DEFAULT NULL,
                `state` varchar(2) DEFAULT NULL,
                `country` varchar(100) DEFAULT 'Brasil',
                `latitude` decimal(10,8) DEFAULT NULL,
                `longitude` decimal(11,8) DEFAULT NULL,
                `date_creation` timestamp NULL DEFAULT NULL,
                `date_mod` timestamp NULL DEFAULT NULL,
                `created_by` INT UNSIGNED DEFAULT NULL,
                `modified_by` INT UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
                KEY `cep` (`cep`),
                CONSTRAINT `fk_address_company` FOREIGN KEY (`plugin_newbase_companydata_id`)
                    REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_address: " . $DB->error(), E_USER_WARNING);
                return false;
            }
        }

        // Create System table
        if (!$DB->tableExists('glpi_plugin_newbase_system')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_system` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
                `name` varchar(255) NOT NULL,
                `type` enum('ipbx','pabx','chatbot','ipbx_cloud','telephone_line') NOT NULL DEFAULT 'ipbx',
                `description` text DEFAULT NULL,
                `status` enum('active','inactive') NOT NULL DEFAULT 'active',
                `date_creation` timestamp NULL DEFAULT NULL,
                `date_mod` timestamp NULL DEFAULT NULL,
                `created_by` INT UNSIGNED DEFAULT NULL,
                `modified_by` INT UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
                KEY `type` (`type`),
                KEY `status` (`status`),
                CONSTRAINT `fk_system_company` FOREIGN KEY (`plugin_newbase_companydata_id`)
                    REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_system: " . $DB->error(), E_USER_WARNING);
                return false;
            }
        }

        // Create Task table
        if (!$DB->tableExists('glpi_plugin_newbase_task')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_task` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
                `title` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `status` enum('open','in_progress','paused','completed') NOT NULL DEFAULT 'open',
                `assigned_to` INT UNSIGNED DEFAULT NULL,
                `date_start` timestamp NULL DEFAULT NULL,
                `date_end` timestamp NULL DEFAULT NULL,
                `latitude_start` decimal(10,8) DEFAULT NULL,
                `longitude_start` decimal(11,8) DEFAULT NULL,
                `latitude_end` decimal(10,8) DEFAULT NULL,
                `longitude_end` decimal(11,8) DEFAULT NULL,
                `mileage` decimal(10,2) DEFAULT NULL,
                `date_creation` timestamp NULL DEFAULT NULL,
                `date_mod` timestamp NULL DEFAULT NULL,
                `created_by` INT UNSIGNED DEFAULT NULL,
                `modified_by` INT UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
                KEY `assigned_to` (`assigned_to`),
                KEY `status` (`status`),
                KEY `date_start` (`date_start`),
                CONSTRAINT `fk_task_company` FOREIGN KEY (`plugin_newbase_companydata_id`)
                    REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE,
                CONSTRAINT `fk_task_user` FOREIGN KEY (`assigned_to`)
                    REFERENCES `glpi_users` (`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_task: " . $DB->error(), E_USER_WARNING);
                return false;
            }
        }

        // Create TaskSignature table
        if (!$DB->tableExists('glpi_plugin_newbase_tasksignature')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_tasksignature` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `plugin_newbase_task_id` INT UNSIGNED NOT NULL,
                `signature_data` longblob DEFAULT NULL,
                `signature_filename` varchar(255) DEFAULT NULL,
                `signature_mime` varchar(100) DEFAULT NULL,
                `date_creation` timestamp NULL DEFAULT NULL,
                `created_by` INT UNSIGNED DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `plugin_newbase_task_id` (`plugin_newbase_task_id`),
                CONSTRAINT `fk_signature_task` FOREIGN KEY (`plugin_newbase_task_id`)
                    REFERENCES `glpi_plugin_newbase_task` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_tasksignature: " . $DB->error(), E_USER_WARNING);
                return false;
            }
        }

        // Create Config table
        if (!$DB->tableExists('glpi_plugin_newbase_config')) {
            $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_config` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `config_key` varchar(100) NOT NULL,
                `config_value` text DEFAULT NULL,
                `date_mod` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `config_key` (`config_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;";

            if (!$DB->query($query)) {
                trigger_error("Error creating glpi_plugin_newbase_config: " . $DB->error(), E_USER_WARNING);
                return false;
            }

            // Insert default config
            $DB->insert('glpi_plugin_newbase_config', [
                'config_key' => 'enable_cnpj_api',
                'config_value' => '1',
                'date_mod' => date('Y-m-d H:i:s')
            ]);
            $DB->insert('glpi_plugin_newbase_config', [
                'config_key' => 'enable_cep_api',
                'config_value' => '1',
                'date_mod' => date('Y-m-d H:i:s')
            ]);
            $DB->insert('glpi_plugin_newbase_config', [
                'config_key' => 'enable_geolocation',
                'config_value' => '1',
                'date_mod' => date('Y-m-d H:i:s')
            ]);
        }

        // Add display preferences
        $preferences = [
            'GlpiPlugin\Newbase\CompanyData' => [2, 3, 4, 5, 6],
            'GlpiPlugin\Newbase\Task' => [2, 3, 4, 5, 6]
        ];

        foreach ($preferences as $itemtype => $columns) {
            foreach ($columns as $num => $column) {
                if (!countElementsInTable('glpi_displaypreferences', [
                    'itemtype' => $itemtype,
                    'num' => $column
                ])) {
                    $DB->insert('glpi_displaypreferences', [
                        'itemtype' => $itemtype,
                        'num' => $column,
                        'rank' => $num + 1,
                        'users_id' => 0
                    ]);
                }
            }
        }

        // Add rights to glpi_profiles
        $rights = [
            'plugin_newbase_companydata' => READ | CREATE | UPDATE | DELETE | PURGE,
            'plugin_newbase_task' => READ | CREATE | UPDATE | DELETE | PURGE,
            'plugin_newbase_system' => READ | CREATE | UPDATE | DELETE | PURGE,
            'plugin_newbase_config' => READ | UPDATE
        ];

        // Get all profiles
        $profiles = $DB->request(['FROM' => 'glpi_profiles']);
        foreach ($profiles as $profile) {
            foreach ($rights as $rightname => $rightvalue) {
                // Check if right already exists
                if (!countElementsInTable('glpi_profilerights', [
                    'profiles_id' => $profile['id'],
                    'name' => $rightname
                ])) {
                    // Add right based on profile type
                    $value = 0;

                    // Super-Admin profile (id 4) gets all rights
                    if ($profile['id'] == 4 || $profile['interface'] == 'central') {
                        $value = $rightvalue;
                    }

                    $DB->insert('glpi_profilerights', [
                        'profiles_id' => $profile['id'],
                        'name' => $rightname,
                        'rights' => $value
                    ]);
                }
            }
        }

        return true;

    } catch (Exception $e) {
        trigger_error("Plugin newbase installation error: " . $e->getMessage(), E_USER_WARNING);
        return false;
    }
}

/**
 * Uninstall plugin
 * FUNÇÃO OBRIGATÓRIA RENOMEADA
 *
 * @return bool True on success
 */
function plugin_newbase_uninstall(): bool
{
    global $DB;

    try {
        // ORDEM CORRETA: FILHAS PRIMEIRO, PAI DEPOIS
        $tables = [
            'glpi_plugin_newbase_tasksignature',  // ✅ Mais dependente (FK -> task)
            'glpi_plugin_newbase_task',           // ✅ Dependente (FK -> companydata)
            'glpi_plugin_newbase_system',         // ✅ Dependente (FK -> companydata)
            'glpi_plugin_newbase_address',        // ✅ Dependente (FK -> companydata)
            'glpi_plugin_newbase_companydata',    // ✅ PAI (referenciado por todos acima)
            'glpi_plugin_newbase_config'          // ✅ Independente
        ];

        // Desativa temporariamente foreign keys
        $DB->query("SET FOREIGN_KEY_CHECKS = 0");

        foreach ($tables as $table) {
            if ($DB->tableExists($table)) {
                if (!$DB->query("DROP TABLE IF EXISTS `$table`")) {
                    trigger_error("Error dropping $table: " . $DB->error(), E_USER_WARNING);
                    return false;
                }
            }
        }

        // Reativa foreign keys
        $DB->query("SET FOREIGN_KEY_CHECKS = 1");

        // Remove display preferences
        $DB->delete('glpi_displaypreferences', [
            'itemtype' => ['LIKE', 'GlpiPlugin\\Newbase\\%']
        ]);

        // Remove rights
        $DB->delete('glpi_profilerights', [
            'name' => ['LIKE', 'plugin_newbase_%']
        ]);

        return true;

    } catch (Exception $e) {
        trigger_error("Plugin newbase uninstallation error: " . $e->getMessage(), E_USER_WARNING);
        return false;
    }
}