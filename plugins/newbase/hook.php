<?php

/**
 * Installation and Uninstallation Hooks for Newbase Plugin
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

// Load autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load setup to get constants
require_once __DIR__ . '/setup.php';

/**
 * Plugin Installation - REQUIRED BY GLPI
 *
 * Creates all necessary database tables for the Newbase plugin
 *
 * @return bool Success
 */
function plugin_newbase_install(): bool
{
    global $DB;

    try {
        $migration = new Migration(NEWBASE_VERSION);

        // TABLE 1: Addresses
        if (!$DB->tableExists('glpi_plugin_newbase_addresses')) {
            $migration->displayMessage('Creating addresses table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_addresses` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
                `name` VARCHAR(255) NOT NULL,
                `cep` VARCHAR(10) DEFAULT NULL,
                `street` VARCHAR(255) DEFAULT NULL,
                `number` VARCHAR(20) DEFAULT NULL,
                `complement` VARCHAR(255) DEFAULT NULL,
                `neighborhood` VARCHAR(255) DEFAULT NULL,
                `city` VARCHAR(255) DEFAULT NULL,
                `state` VARCHAR(2) DEFAULT NULL,
                `latitude` DECIMAL(10, 8) DEFAULT NULL,
                `longitude` DECIMAL(11, 8) DEFAULT NULL,
                `is_recursive` TINYINT NOT NULL DEFAULT 0,
                `is_deleted` TINYINT NOT NULL DEFAULT 0,
                `date_creation` TIMESTAMP NULL DEFAULT NULL,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `cep` (`cep`),
                KEY `is_deleted` (`is_deleted`),
                KEY `date_mod` (`date_mod`),
                CONSTRAINT `fk_addresses_entities`
                    FOREIGN KEY (`entities_id`)
                    REFERENCES `glpi_entities`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating addresses table');
        }

        // TABLE 2: Systems (Asterisk, CloudPBX, etc)
        if (!$DB->tableExists('glpi_plugin_newbase_systems')) {
            $migration->displayMessage('Creating systems table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_systems` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
                `name` VARCHAR(255) NOT NULL,
                `system_type` VARCHAR(50) NOT NULL DEFAULT 'pabx',
                `status` VARCHAR(50) NOT NULL DEFAULT 'active',
                `description` TEXT,
                `configuration` LONGTEXT,
                `is_recursive` TINYINT NOT NULL DEFAULT 0,
                `is_deleted` TINYINT NOT NULL DEFAULT 0,
                `date_creation` TIMESTAMP NULL DEFAULT NULL,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `system_type` (`system_type`),
                KEY `is_deleted` (`is_deleted`),
                KEY `date_mod` (`date_mod`),
                CONSTRAINT `fk_systems_entities`
                    FOREIGN KEY (`entities_id`)
                    REFERENCES `glpi_entities`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating systems table');
        }

        // TABLE 3: Tasks with Geolocation
        if (!$DB->tableExists('glpi_plugin_newbase_tasks')) {
            $migration->displayMessage('Creating tasks table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_tasks` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
                `users_id` INT UNSIGNED DEFAULT NULL,
                `plugin_newbase_addresses_id` INT UNSIGNED DEFAULT NULL,
                `plugin_newbase_systems_id` INT UNSIGNED DEFAULT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `status` VARCHAR(50) NOT NULL DEFAULT 'new',
                `date_start` TIMESTAMP NULL DEFAULT NULL,
                `date_end` TIMESTAMP NULL DEFAULT NULL,
                `gps_start_lat` DECIMAL(10, 8) DEFAULT NULL,
                `gps_start_lng` DECIMAL(11, 8) DEFAULT NULL,
                `gps_end_lat` DECIMAL(10, 8) DEFAULT NULL,
                `gps_end_lng` DECIMAL(11, 8) DEFAULT NULL,
                `mileage` DECIMAL(10, 2) DEFAULT NULL,
                `is_recursive` TINYINT NOT NULL DEFAULT 0,
                `is_deleted` TINYINT NOT NULL DEFAULT 0,
                `date_creation` TIMESTAMP NULL DEFAULT NULL,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `users_id` (`users_id`),
                KEY `addresses_id` (`plugin_newbase_addresses_id`),
                KEY `systems_id` (`plugin_newbase_systems_id`),
                KEY `status` (`status`),
                KEY `is_deleted` (`is_deleted`),
                KEY `date_mod` (`date_mod`),
                CONSTRAINT `fk_tasks_entities`
                    FOREIGN KEY (`entities_id`)
                    REFERENCES `glpi_entities`(`id`)
                    ON DELETE CASCADE,
                CONSTRAINT `fk_tasks_users`
                    FOREIGN KEY (`users_id`)
                    REFERENCES `glpi_users`(`id`)
                    ON DELETE SET NULL,
                CONSTRAINT `fk_tasks_addresses`
                    FOREIGN KEY (`plugin_newbase_addresses_id`)
                    REFERENCES `glpi_plugin_newbase_addresses`(`id`)
                    ON DELETE SET NULL,
                CONSTRAINT `fk_tasks_systems`
                    FOREIGN KEY (`plugin_newbase_systems_id`)
                    REFERENCES `glpi_plugin_newbase_systems`(`id`)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating tasks table');
        }

        // TABLE 4: Task Signatures
        if (!$DB->tableExists('glpi_plugin_newbase_task_signatures')) {
            $migration->displayMessage('Creating task signatures table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_task_signatures` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `plugin_newbase_tasks_id` INT UNSIGNED NOT NULL,
                `signature_data` LONGTEXT NOT NULL,
                `signer_name` VARCHAR(255) DEFAULT NULL,
                `users_id` INT UNSIGNED DEFAULT NULL,
                `is_deleted` TINYINT NOT NULL DEFAULT 0,
                `date_creation` TIMESTAMP NULL DEFAULT NULL,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `tasks_id` (`plugin_newbase_tasks_id`),
                KEY `users_id` (`users_id`),
                KEY `is_deleted` (`is_deleted`),
                KEY `date_mod` (`date_mod`),
                CONSTRAINT `fk_signatures_tasks`
                    FOREIGN KEY (`plugin_newbase_tasks_id`)
                    REFERENCES `glpi_plugin_newbase_tasks`(`id`)
                    ON DELETE CASCADE,
                CONSTRAINT `fk_signatures_users`
                    FOREIGN KEY (`users_id`)
                    REFERENCES `glpi_users`(`id`)
                    ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating task signatures table');
        }

        // TABLE 5: Company Extras
        if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
            $migration->displayMessage('Creating company extras table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_company_extras` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `entities_id` INT UNSIGNED NOT NULL,
                `cnpj` VARCHAR(18) DEFAULT NULL,
                `corporate_name` VARCHAR(255) DEFAULT NULL,
                `fantasy_name` VARCHAR(255) DEFAULT NULL,
                `contact_person` VARCHAR(255) DEFAULT NULL,
                `phone` VARCHAR(20) DEFAULT NULL,
                `email` VARCHAR(255) DEFAULT NULL,
                `notes` LONGTEXT,
                `is_deleted` TINYINT NOT NULL DEFAULT 0,
                `date_creation` TIMESTAMP NULL DEFAULT NULL,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `cnpj` (`cnpj`),
                KEY `is_deleted` (`is_deleted`),
                KEY `date_mod` (`date_mod`),
                UNIQUE KEY `unique_entities_id` (`entities_id`),
                CONSTRAINT `fk_company_extras_entities`
                    FOREIGN KEY (`entities_id`)
                    REFERENCES `glpi_entities`(`id`)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating company extras table');
        }

        // TABLE 6: Configuration
        if (!$DB->tableExists('glpi_plugin_newbase_config')) {
            $migration->displayMessage('Creating configuration table...');

            $query = "CREATE TABLE `glpi_plugin_newbase_config` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `config_key` VARCHAR(255) NOT NULL,
                `config_value` LONGTEXT,
                `date_mod` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_config_key` (`config_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            $DB->queryOrDie($query, 'Error creating configuration table');

            // Insert default configurations
            $default_configs = [
                ['config_key' => 'enable_signature', 'config_value' => '1'],
                ['config_key' => 'require_signature', 'config_value' => '0'],
                ['config_key' => 'enable_gps', 'config_value' => '1'],
                ['config_key' => 'calculate_mileage', 'config_value' => '1'],
                ['config_key' => 'default_map_zoom', 'config_value' => '13'],
            ];

            foreach ($default_configs as $config) {
                $DB->insert('glpi_plugin_newbase_config', $config);
            }
        }

        $migration->executeMigration();
        plugin_newbase_log('Plugin installed successfully', 'info');

        return true;
    } catch (Exception $e) {
        plugin_newbase_log('Installation error: ' . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * Plugin Uninstallation - REQUIRED BY GLPI
 *
 * Removes all database tables created by the plugin
 *
 * @return bool Success
 */
function plugin_newbase_uninstall(): bool
{
    global $DB;

    try {
        // CORRECT ORDER: children first, parents last
        $tables = [
            'glpi_plugin_newbase_task_signatures',  // Child of tasks
            'glpi_plugin_newbase_tasks',            // Child of addresses, systems
            'glpi_plugin_newbase_addresses',        // Independent
            'glpi_plugin_newbase_systems',          // Independent
            'glpi_plugin_newbase_company_extras',   // Independent
            'glpi_plugin_newbase_config',           // Independent
        ];

        foreach ($tables as $table) {
            if ($DB->tableExists($table)) {
                $DB->query("DROP TABLE IF EXISTS `{$table}`");
            }
        }

        plugin_newbase_log('Plugin uninstalled successfully', 'info');

        return true;
    } catch (Exception $e) {
        plugin_newbase_log('Uninstallation error: ' . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * Plugin Initialization - REQUIRED BY GLPI
 *
 * Initializes plugin hooks and menus
 *
 * @return void
 */
function plugin_init_newbase(): void
{
    global $PLUGIN_HOOKS;

    // Register plugin classes
    Plugin::registerClasses('GlpiPlugin\\Newbase', [
        'Address',
        'AddressHandler',
        'CompanyData',
        'Config',
        'Menu',
        'System',
        'Task',
        'TaskSignature',
    ]);

    // Menu
    $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
        'tools' => 'GlpiPlugin\\Newbase\\Menu'
    ];

    // Configuration page
    $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

    // CSS assets
    $PLUGIN_HOOKS['add_css']['newbase'] = [
        'css/newbase.css',
        'css/forms.css',
        'css/responsive.css',
    ];

    // JavaScript assets
    $PLUGIN_HOOKS['add_javascript']['newbase'] = [
        'js/newbase.js',
        'js/forms.js',
        'js/map.js',
        'js/signature.js',
        'js/mileage.js',
        'js/mobile.js',
        'js/jquery.mask.min.js',
    ];

    // CSRF Compliance - GLPI 10.0.20
    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;
}

/**
 * Log plugin operations to file
 *
 * @param string $message Log message
 * @param string $level   Log level (info, warning, error)
 *
 * @return void
 */
function plugin_newbase_log(string $message, string $level = 'info'): void
{
    $log_dir = defined('GLPI_LOG_DIR') ? GLPI_LOG_DIR : GLPI_ROOT . '/files/_log';

    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0o755, true);
    }

    $log_file = $log_dir . '/newbase.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[{$timestamp}] [{$level}] {$message}\n";

    @error_log($log_message, 3, $log_file);
}

/**
 * Validate plugin tables schema
 *
 * @return array Array with found errors
 */
function plugin_newbase_validateSchema(): array
{
    global $DB;
    $errors = [];

    $required_tables = [
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_company_extras',
    ];

    foreach ($required_tables as $table) {
        if (!$DB->tableExists($table)) {
            $errors[] = "Table '{$table}' was not created";
        }
    }

    return $errors;
}

/**
 * Check tables status
 *
 * @return array Array with table status
 */
function plugin_newbase_checkTableStatus(): array
{
    global $DB;

    $tables = [
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_task_signatures',
        'glpi_plugin_newbase_company_extras',
        'glpi_plugin_newbase_config',
    ];

    $status = [];

    foreach ($tables as $table) {
        $status[$table] = $DB->tableExists($table);
    }

    return $status;
}
