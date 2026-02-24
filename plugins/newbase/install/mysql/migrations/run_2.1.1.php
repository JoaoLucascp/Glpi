<?php
/**
 * Script para executar migration 2.1.1 - Adicionar campos company_extras
 * Executar via: php migrations/run_2.1.1.php
 */

// Definir caminho do GLPI
define('GLPI_ROOT', realpath(__DIR__ . '/../../../../'));

// Incluir bootstrap do GLPI
include_once(GLPI_ROOT . '/inc/includes.php');

global $DB;

echo "=== NEWBASE PLUGIN - MIGRATION 2.1.1 ===\n\n";

// Ler arquivo SQL
$sql_file = __DIR__ . '/2.1.1-add_company_fields.sql';

if (!file_exists($sql_file)) {
    die("ERRO: Arquivo SQL não encontrado: $sql_file\n");
}

$sql_content = file_get_contents($sql_file);

// Separar comandos SQL (dividir por ponto e vírgula)
$commands = array_filter(
    array_map('trim', explode(';', $sql_content)),
    function($cmd) {
        return !empty($cmd) && !preg_match('/^--/', $cmd);
    }
);

echo "Total de comandos SQL a executar: " . count($commands) . "\n\n";

$success = 0;
$errors = 0;

foreach ($commands as $index => $command) {
    $num = $index + 1;
    echo "[$num/" . count($commands) . "] Executando...\n";
    
    try {
        if ($DB->query($command)) {
            echo "  ✓ Sucesso\n";
            $success++;
        } else {
            echo "  ✗ Erro: " . $DB->error() . "\n";
            $errors++;
        }
    } catch (Exception $e) {
        echo "  ✗ Exceção: " . $e->getMessage() . "\n";
        $errors++;
    }
    
    echo "\n";
}

echo "=== RESULTADO ===\n";
echo "Sucesso: $success\n";
echo "Erros: $errors\n";

if ($errors === 0) {
    echo "\n✓ Migration concluída com sucesso!\n";
    
    // Atualizar versão do plugin
    $config_values = [
        'version' => '2.1.1',
        'migration_2.1.1' => date('Y-m-d H:i:s')
    ];
    
    foreach ($config_values as $key => $value) {
        Config::setConfigurationValues('plugin:newbase', [$key => $value]);
    }
    
    echo "✓ Versão atualizada para 2.1.1\n";
} else {
    echo "\n✗ Migration finalizada com erros.\n";
    echo "Verifique os erros acima e corrija antes de continuar.\n";
}
