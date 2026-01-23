<?php

/**
* Newbase Plugin - Installation Hooks
* IMPORTANTE: As funções plugin_newbase_install() e plugin_newbase_uninstall()
* estão declaradas no setup.php. Este arquivo contém apenas funções auxiliares.
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

// Prevenir acesso direto
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Definir constantes de diretório se não estiverem presentes (compatibilidade GLPI 10)
if (!defined('GLPI_VAR_DIR')) {
    define('GLPI_VAR_DIR', GLPI_ROOT . '/files');
}
if (!defined('GLPI_PLUGIN_DOC_DIR')) {
    define('GLPI_PLUGIN_DOC_DIR', GLPI_VAR_DIR . '/_plugins');
}
if (!defined('GLPI_LOG_DIR')) {
    define('GLPI_LOG_DIR', GLPI_VAR_DIR . '/_log');
}

/**
* Função auxiliar: Criar diretórios necessários
*
* @return boolean
*/
function plugin_newbase_createDirectories()
{
    $directories = [
        GLPI_PLUGIN_DOC_DIR . '/newbase',
        GLPI_PLUGIN_DOC_DIR . '/newbase/signatures',
    ];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0o755, true)) {
                return false;
            }
        }
    }

    return true;
}

/**
* Função auxiliar: Verificar se tabelas existem
*
* @return array Array com status das tabelas
*/
function plugin_newbase_checkTables()
{
    global $DB;

    $required_tables = [
        'glpi_plugin_newbase_companydata',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_configs',
    ];

    $status = [];
    foreach ($required_tables as $table) {
        $result = $DB->query("SHOW TABLES LIKE '$table'");
        $status[$table] = ($result && $result->num_rows > 0);
    }

    return $status;
}

/**
* Função auxiliar: Limpar cache do GLPI
*
* @return void
*/
function plugin_newbase_clearCache()
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
* Função auxiliar: Validar estrutura das tabelas
*
* @return array Array com erros encontrados
*/
function plugin_newbase_validateSchema()
{
    global $DB;

    $errors = [];

    // Verificar se tabela companydata tem os campos necessários
    $result = $DB->query("SHOW COLUMNS FROM glpi_plugin_newbase_companydata");
    if (!$result) {
        $errors[] = "Não foi possível verificar estrutura da tabela companydata";
    } else {
        $required_fields = ['id', 'name', 'cnpj', 'corporate_name', 'fantasy_name'];
        $existing_fields = [];

        while ($row = $result->fetch_assoc()) {
            $existing_fields[] = $row['Field'];
        }

        foreach ($required_fields as $field) {
            if (!in_array($field, $existing_fields)) {
                $errors[] = "Campo '$field' não encontrado na tabela companydata";
            }
        }
    }

    return $errors;
}

/**
* Função auxiliar: Remover diretórios do plugin
*
* @param string $dir Diretório a ser removido
* @return boolean
*/
function plugin_newbase_removeDirectory($dir)
{
    if (!is_dir($dir)) {
        return true;
    }

    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? plugin_newbase_removeDirectory($path) : unlink($path);
    }

    return rmdir($dir);
}

/**
* Função auxiliar: Log de operações do plugin
*
* @param string $message Mensagem para log
* @param string $level Nível do log (info, warning, error)
* @return void
*/
function plugin_newbase_log($message, $level = 'info')
{
    $log_file = GLPI_LOG_DIR . '/newbase.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] [$level] $message\n";

    file_put_contents($log_file, $log_message, FILE_APPEND);
}

/**
* Função auxiliar: Executar migração de dados se necessário
*
* @param string $from_version Versão de origem
* @param string $to_version Versão de destino
* @return boolean
*/
function plugin_newbase_migrate($from_version, $to_version)
{
    // Implementar lógica de migração futura aqui
    plugin_newbase_log("Migração de $from_version para $to_version não necessária");
    return true;
}
