<?php
/**
 * Diagnóstico da tabela company_extras — REMOVER APÓS USO
 * Acesse: http://glpi.test/plugins/newbase/front/tools/debug_company.php
 */
include('../../../../inc/includes.php');
Session::checkLoginUser();

global $DB;

$table = 'glpi_plugin_newbase_company_extras';

echo '<h2>Estrutura da tabela ' . $table . '</h2>';
echo '<pre>';

// Mostrar colunas existentes
$cols = [];
foreach ($DB->request("DESCRIBE `$table`") as $col) {
    $cols[] = $col['Field'];
    echo $col['Field'] . ' | ' . $col['Type'] . ' | Null:' . $col['Null'] . ' | Default:' . $col['Default'] . "\n";
}

echo '</pre>';

// Colunas que deveriam existir (migration 2.1.1)
$needed = ['inscricao_estadual','inscricao_municipal','cep','street','number',
           'complement','neighborhood','city','state','country',
           'latitude','longitude','contract_status','systems_config'];

echo '<h3>Colunas faltando (precisam de migration):</h3><pre>';
foreach ($needed as $n) {
    if (!in_array($n, $cols)) {
        echo "❌ FALTANDO: $n\n";
    } else {
        echo "✅ ok: $n\n";
    }
}
echo '</pre>';

// Testar um insert direto com os campos básicos
echo '<h3>Teste de INSERT básico:</h3><pre>';
try {
    $test = new \GlpiPlugin\Newbase\CompanyData();
    $id = $test->add([
        'corporate_name' => 'EMPRESA TESTE DEBUG ' . date('His'),
        'cnpj'           => '00.000.000/0000-00',
        'entities_id'    => 0,
    ]);
    if ($id) {
        echo "✅ INSERT funcionou! ID=$id\n";
        // Remover o registro de teste
        $test->delete(['id' => $id], 1);
        echo "✅ Registro de teste removido\n";
    } else {
        echo "❌ INSERT falhou (retornou false/0)\n";
        echo "Mensagens da sessão:\n";
        print_r($_SESSION['MESSAGE_AFTER_REDIRECT'] ?? []);
    }
} catch (Exception $e) {
    echo "❌ EXCEÇÃO: " . $e->getMessage() . "\n";
}
echo '</pre>';

// Mostrar últimos erros do GLPI
echo '<h3>Log de erros SQL (últimas 30 linhas):</h3><pre>';
$logfile = GLPI_LOG_DIR . '/sql-errors.log';
if (file_exists($logfile)) {
    $lines = file($logfile);
    echo implode('', array_slice($lines, -30));
} else {
    echo "Arquivo $logfile não encontrado\n";

    // Tentar php_errors.log
    $logfile2 = GLPI_LOG_DIR . '/php-errors.log';
    if (file_exists($logfile2)) {
        $lines = file($logfile2);
        echo "php-errors.log (últimas 30 linhas):\n";
        echo implode('', array_slice($lines, -30));
    } else {
        echo "Nenhum log de erros encontrado em " . GLPI_LOG_DIR . "\n";
        echo "Arquivos em _log:\n";
        foreach (glob(GLPI_LOG_DIR . '/*.log') as $f) {
            echo "  " . basename($f) . " (" . filesize($f) . " bytes)\n";
        }
    }
}
echo '</pre>';
