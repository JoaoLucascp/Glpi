<?php
/**
 * Script de desinstalação limpa do plugin Newbase
 * Remove todas as tabelas na ordem correta (respeitando FK)
 */

define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
include(GLPI_ROOT . "/inc/includes.php");

global $DB;

echo "=== INICIANDO DESINSTALAÇÃO LIMPA DO NEWBASE ===\n\n";

// Ordem CORRETA de remoção (filhos antes dos pais)
$tables = [
    'glpi_plugin_newbase_tasksignature',  // Referencia task
    'glpi_plugin_newbase_task',           // Referencia companydata
    'glpi_plugin_newbase_system',         // Referencia companydata
    'glpi_plugin_newbase_address',        // Referencia companydata
    'glpi_plugin_newbase_companydata',    // Tabela principal
    'glpi_plugin_newbase_config',         // Sem FK
];

foreach ($tables as $table) {
    echo "Verificando tabela: $table... ";

    if ($DB->tableExists($table)) {
        // Primeiro desabilita FK checks
        $DB->query("SET FOREIGN_KEY_CHECKS = 0");

        // Drop a tabela
        $result = $DB->query("DROP TABLE IF EXISTS `$table`");

        // Reabilita FK checks
        $DB->query("SET FOREIGN_KEY_CHECKS = 1");

        if ($result) {
            echo "✓ REMOVIDA\n";
        } else {
            echo "✗ ERRO: " . $DB->error() . "\n";
        }
    } else {
        echo "- não existe\n";
    }
}

echo "\n=== DESINSTALAÇÃO CONCLUÍDA ===\n";
