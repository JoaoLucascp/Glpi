<?php

/**
* Installation and Uninstallation Hooks
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

// Prevenir acesso direto
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// FUNÇÃO PRINCIPAL: INSTALAÇÃO
function plugin_newbase_install(): bool
{
    global $DB;

    $migration = new Migration(PLUGIN_NEWBASE_VERSION);

    // TABELA 1: Endereço
    if (!$DB->tableExists('glpi_plugin_newbase_addresses')) {
        $migration->displayMessage('Criando tabela de endereços...');

        $query = "CREATE TABLE `glpi_plugin_newbase_addresses` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL DEFAULT 0,
            `name` varchar(255) NOT NULL,
            `cep` varchar(10) DEFAULT NULL,
            `street` varchar(255) DEFAULT NULL,
            `number` varchar(20) DEFAULT NULL,
            `complement` varchar(255) DEFAULT NULL,
            `neighborhood` varchar(255) DEFAULT NULL,
            `city` varchar(255) DEFAULT NULL,
            `state` varchar(2) DEFAULT NULL,
            `latitude` decimal(10, 8) DEFAULT NULL,
            `longitude` decimal(11, 8) DEFAULT NULL,
            `is_recursive` tinyint NOT NULL DEFAULT 0,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `cep` (`cep`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_addresses_entities`
                FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // TABELA 2: Sistemas (Asterisk, CloudPBX, etc)
    if (!$DB->tableExists('glpi_plugin_newbase_systems')) {
        $migration->displayMessage('Criando tabela de sistemas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_systems` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL DEFAULT 0,
            `name` varchar(255) NOT NULL,
            `system_type` varchar(50) NOT NULL DEFAULT 'pabx',
            `status` varchar(50) NOT NULL DEFAULT 'active',
            `description` text,
            `configuration` longtext,
            `is_recursive` tinyint NOT NULL DEFAULT 0,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `system_type` (`system_type`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_systems_entities`
                FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // TABELA 3: Tarefas com Geolocalização
    if (!$DB->tableExists('glpi_plugin_newbase_tasks')) {
        $migration->displayMessage('Criando tabela de tarefas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_tasks` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL DEFAULT 0,
            `users_id` int UNSIGNED DEFAULT 0,
            `plugin_newbase_addresses_id` int UNSIGNED DEFAULT NULL,
            `plugin_newbase_systems_id` int UNSIGNED DEFAULT NULL,
            `title` varchar(255) NOT NULL,
            `description` text,
            `status` varchar(50) NOT NULL DEFAULT 'new',
            `date_start` timestamp NULL DEFAULT NULL,
            `date_end` timestamp NULL DEFAULT NULL,
            `gps_start_lat` decimal(10, 8) DEFAULT NULL,
            `gps_start_lng` decimal(11, 8) DEFAULT NULL,
            `gps_end_lat` decimal(10, 8) DEFAULT NULL,
            `gps_end_lng` decimal(11, 8) DEFAULT NULL,
            `mileage` decimal(10, 2) DEFAULT NULL,
            `is_recursive` tinyint NOT NULL DEFAULT 0,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `users_id` (`users_id`),
            KEY `addresses_id` (`plugin_newbase_addresses_id`),
            KEY `systems_id` (`plugin_newbase_systems_id`),
            KEY `status` (`status`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_tasks_entities`
                FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`)
                ON DELETE CASCADE,
            CONSTRAINT `fk_tasks_users`
                FOREIGN KEY (`users_id`)
                REFERENCES `glpi_users`(`id`)
                ON DELETE SET NULL
            CONSTRAINT `fk_tasks_addresses`
                FOREIGN KEY (`plugin_newbase_addresses_id`)
                REFERENCES `glpi_plugin_newbase_addresses`(`id`)
                ON DELETE SET NULL,
            CONSTRAINT `fk_tasks_systems`
                FOREIGN KEY (`plugin_newbase_systems_id`)
                REFERENCES `glpi_plugin_newbase_systems`(`id`)
                ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // TABELA 4: Assinaturas de Tarefas
    if (!$DB->tableExists('glpi_plugin_newbase_task_signatures')) {
        $migration->displayMessage('Criando tabela de assinaturas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_task_signatures` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `plugin_newbase_tasks_id` int UNSIGNED NOT NULL,
            `signature_data` longtext NOT NULL,
            `signer_name` varchar(255) DEFAULT NULL,
            `users_id` int UNSIGNED DEFAULT NULL,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `tasks_id` (`plugin_newbase_tasks_id`),
            KEY `users_id` (`users_id`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_signatures_tasks`
                FOREIGN KEY (`plugin_newbase_tasks_id`)
                REFERENCES `glpi_plugin_newbase_tasks`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // TABELA 5: Complementos de Empresa
    if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
        $migration->displayMessage('Criando tabela de complementos de empresa...');

        $query = "CREATE TABLE `glpi_plugin_newbase_company_extras` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL,
            `cnpj` varchar(18) DEFAULT NULL,
            `corporate_name` varchar(255) DEFAULT NULL,
            `fantasy_name` varchar(255) DEFAULT NULL,
            `contact_person` varchar(255) DEFAULT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `email` varchar(255) DEFAULT NULL,
            `notes` longtext,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT NULL,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `cnpj` (`cnpj`),
            KEY `is_deleted` (`is_deleted`),
            UNIQUE KEY `unique_entities_id` (`entities_id`),
            CONSTRAINT `fk_company_extras_entities`
                FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // TABELA 6: Configuração
    if (!$DB->tableExists('glpi_plugin_newbase_config')) {
        $migration->displayMessage('Criando tabela de configuração...');

        $query = "CREATE TABLE `glpi_plugin_newbase_config` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `config_key` varchar(255) NOT NULL,
            `config_value` longtext,
            `date_mod` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_config_key` (`config_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());

        // Inserir configurações padrão
        $default_configs = [
            ['config_key' => 'enable_signature', 'config_value' => '1'],
            ['config_key' => 'require_signature', 'config_value' => '0'],
            ['config_key' => 'enable_gps', 'config_value' => '1'],
            ['config_key' => 'calculate_mileage', 'config_value' => '1'],
            ['config_key' => 'default_map_zoom', 'config_value' => '13'],
        ];

        foreach ($default_configs as $config) {
            $DB->insertOrDie(
                'glpi_plugin_newbase_config',
                $config,
                $DB->error()
            );
        }
    }

    $migration->executeMigration();
    plugin_newbase_log('Plugin instalado com sucesso', 'info');

    return true;
}

// FUNÇÃO PRINCIPAL: DESINSTALAÇÃO

/**
* Uninstall plugin - Remove all tables
* @return bool Success
*/
function plugin_newbase_uninstall(): bool
{
    global $DB;

    // ORDEM CORRETA: filhos primeiro, pais depois
    $tables = [
        'glpi_plugin_newbase_task_signatures',  // Filho de tasks
        'glpi_plugin_newbase_tasks',            // Filho de addresses, systems
        'glpi_plugin_newbase_addresses',        // Independente
        'glpi_plugin_newbase_systems',          // Independente
        'glpi_plugin_newbase_company_extras',   // Independente
        'glpi_plugin_newbase_config',           // Independente
    ];

    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`");
    }

    plugin_newbase_log('Plugin desinstalado com sucesso', 'info');

    return true;
}

// FUNÇÕES AUXILIARES

/**
* Log plugin operations
* @param string $message Log message
* @param string $level Log level (info, warning, error)
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
* Clear plugin cache
* @return void
*/
function plugin_newbase_clearCache(): void
{
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }

    if (isset($_SESSION['glpi_plugins'])) {
        unset($_SESSION['glpi_plugins']);
    }
}

/**
* Validate plugin tables schema
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
            $errors[] = "Tabela '{$table}' não foi criada";
        }
    }

    return $errors;
}

/**
* Check tables status
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
