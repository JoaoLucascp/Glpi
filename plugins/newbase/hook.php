<?php

/**
 * Hook File - Plugin Newbase
 *
 * Funções de inicialização, instalação e desinstalação do plugin
 *
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @license   GPLv2+
 * @since     2.1.0
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// ========================================
// FUNÇÃO PRINCIPAL: INSTALAÇÃO DO PLUGIN
// ========================================

function plugin_newbase_install(): bool
{
    global $DB;

    $migration = new Migration(210); // versão 2.1.0

    // ========================================
    // TABELA 1: Sistemas (Asterisk, CloudPBX, etc)
    // ========================================

    if (!$DB->tableExists('glpi_plugin_newbase_systems')) {
        $migration->displayMessage('Criando tabela de sistemas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_systems` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL DEFAULT 0,
            `name` varchar(255) NOT NULL,
            `type` enum('asterisk','cloudpbx','chatbot','voip') NOT NULL,
            `documentation` longtext,
            `is_active` tinyint NOT NULL DEFAULT 1,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_systems_entities` FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // ========================================
    // TABELA 2: Tarefas com Geolocalização
    // ========================================

    if (!$DB->tableExists('glpi_plugin_newbase_tasks')) {
        $migration->displayMessage('Criando tabela de tarefas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_tasks` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL DEFAULT 0,
            `name` varchar(255) NOT NULL,
            `description` longtext,
            `latitude` decimal(10, 8),
            `longitude` decimal(11, 8),
            `address` varchar(500),
            `mileage` int DEFAULT 0,
            `is_completed` tinyint NOT NULL DEFAULT 0,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `is_deleted` (`is_deleted`),
            CONSTRAINT `fk_tasks_entities` FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // ========================================
    // TABELA 3: Assinaturas de Tarefas
    // ========================================

    if (!$DB->tableExists('glpi_plugin_newbase_task_signatures')) {
        $migration->displayMessage('Criando tabela de assinaturas...');

        $query = "CREATE TABLE `glpi_plugin_newbase_task_signatures` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `tasks_id` int UNSIGNED NOT NULL,
            `signature_data` longtext NOT NULL,
            `signed_by` int UNSIGNED,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_signature` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `tasks_id` (`tasks_id`),
            KEY `signed_by` (`signed_by`),
            CONSTRAINT `fk_signatures_tasks` FOREIGN KEY (`tasks_id`)
                REFERENCES `glpi_plugin_newbase_tasks`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // ========================================
    // TABELA 4: Complementos de Empresa
    // ========================================

    if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
        $migration->displayMessage('Criando tabela de complementos de empresa...');

        $query = "CREATE TABLE `glpi_plugin_newbase_company_extras` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `entities_id` int UNSIGNED NOT NULL,
            `cnpj` varchar(18),
            `corporate_name` varchar(255),
            `fantasy_name` varchar(255),
            `contact_person` varchar(255),
            `phone` varchar(20),
            `email` varchar(255),
            `notes` longtext,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `cnpj` (`cnpj`),
            KEY `is_deleted` (`is_deleted`),
            UNIQUE KEY `unique_entities_id` (`entities_id`),
            CONSTRAINT `fk_company_extras_entities` FOREIGN KEY (`entities_id`)
                REFERENCES `glpi_entities`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    // ========================================
    // TABELA 5: Configuração do Plugin
    // ========================================

    if (!$DB->tableExists('glpi_plugin_newbase_config')) {
        $migration->displayMessage('Criando tabela de configuração...');

        $query = "CREATE TABLE `glpi_plugin_newbase_config` (
            `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `config_key` varchar(255) NOT NULL,
            `config_value` longtext,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `date_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE
            CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_config_key` (`config_key`),
            KEY `is_deleted` (`is_deleted`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->queryOrDie($query, $DB->error());
    }

    $migration->executeMigration();

    plugin_newbase_log('Plugin instalado com sucesso', 'info');

    return true;
}

// ========================================
// FUNÇÃO PRINCIPAL: DESINSTALAÇÃO
// ========================================

function plugin_newbase_uninstall(): bool
{
    global $DB;

    // Remover APENAS tabelas do plugin (nunca remover glpi_entities!)
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_newbase_task_signatures`");
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_newbase_tasks`");
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_newbase_systems`");
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_newbase_company_extras`");
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_newbase_config`");

    plugin_newbase_log('Plugin desinstalado com sucesso', 'info');

    return true;
}

// NOTA: plugin_newbase_getConfig() e plugin_newbase_getDatabase() estão definidas em setup.php

// ========================================
// FUNÇÕES AUXILIARES
// ========================================

/**
 * Limpar cache do GLPI e sessions
 *
 * @return void
 */
function plugin_newbase_clearCache(): void
{
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }

    // Limpar cache de sessão
    if (isset($_SESSION['glpi_plugins'])) {
        unset($_SESSION['glpi_plugins']);
    }
}

/**
 * Log de operações do plugin
 *
 * @param string $message Mensagem para log
 * @param string $level   Nível do log (info, warning, error)
 * @return void
 */
function plugin_newbase_log(string $message, string $level = 'info'): void
{
    // Verificar se a constante GLPI_LOG_DIR existe
    if (defined('GLPI_LOG_DIR') && GLPI_LOG_DIR) {
        $log_dir = GLPI_LOG_DIR;
    } else {
        // Fallback seguro
        $log_dir = GLPI_ROOT . '/files/_log';
    }

    // Criar diretório se não existir
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0o755, true);
    }

    $log_file = $log_dir . '/newbase.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[{$timestamp}] [{$level}] {$message}\n";

    @error_log($log_message, 3, $log_file);
}


/**
 * Validar estrutura das tabelas do plugin
 *
 * @return array Array com erros encontrados
 */
function plugin_newbase_validateSchema(): array
{
    global $DB;
    $errors = [];

    // Verificar tabelas críticas
    $required_tables = [
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
 * Verificar status das tabelas
 *
 * @return array Array com status de cada tabela
 */
function plugin_newbase_checkTableStatus(): array
{
    global $DB;

    $tables = [
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_task_signatures',
        'glpi_plugin_newbase_company_extras',
    ];

    $status = [];

    foreach ($tables as $table) {
        $status[$table] = $DB->tableExists($table);
    }

    return $status;
}
