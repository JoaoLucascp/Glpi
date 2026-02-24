<?php
/**
 * Migration Tool - Execute Database Migrations
 * Acesse via: http://glpi.test/plugins/newbase/front/tools/migrate.php
 */

include('../../../../inc/includes.php');

Session::checkLoginUser();
Session::checkRight('config', UPDATE);

// Só validar CSRF se houver POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Session::checkCSRF($_POST);
}

Html::header(__('Newbase - Database Migration', 'newbase'));

global $DB;

echo "<div class='center'>";
echo "<h2>🔧 Newbase Plugin - Migration 2.1.1</h2>";
echo "<p>Esta migration adiciona campos faltantes na tabela company_extras</p>";

$migration_file = __DIR__ . '/../../install/mysql/migrations/2.1.1-add_company_fields.sql';

if (!file_exists($migration_file)) {
    echo "<div class='alert alert-danger'>";
    echo "❌ Arquivo de migration não encontrado: $migration_file";
    echo "</div>";
    Html::footer();
    exit;
}

if (isset($_POST['execute'])) {
    // CSRF já validado acima
    
    echo "<div class='alert alert-info'>";
    echo "⏳ Executando migration...";
    echo "</div>";
    
    $sql_content = file_get_contents($migration_file);
    $commands = array_filter(
        array_map('trim', explode(';', $sql_content)),
        function($cmd) {
            return !empty($cmd) && !preg_match('/^--/', $cmd);
        }
    );
    
    $success = 0;
    $errors = 0;
    $messages = [];
    
    foreach ($commands as $command) {
        try {
            if ($DB->query($command)) {
                $success++;
            } else {
                $errors++;
                $messages[] = "❌ Erro: " . $DB->error();
            }
        } catch (Exception $e) {
            $errors++;
            $messages[] = "❌ Exceção: " . $e->getMessage();
        }
    }
    
    if ($errors === 0) {
        echo "<div class='alert alert-success'>";
        echo "✅ Migration concluída com sucesso!<br>";
        echo "Total de comandos executados: $success";
        echo "</div>";
        
        Config::setConfigurationValues('plugin:newbase', [
            'version' => '2.1.1',
            'migration_2.1.1' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo "<div class='alert alert-warning'>";
        echo "⚠️ Migration finalizada com $errors erro(s) e $success sucesso(s)<br>";
        foreach ($messages as $msg) {
            echo "$msg<br>";
        }
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>";
    echo "⚠️ <strong>ATENÇÃO:</strong> Esta operação irá modificar a estrutura do banco de dados.";
    echo "</div>";
    
    echo "<form method='post'>";
    echo Html::hidden('_glpi_csrf_token');
    echo "<button type='submit' name='execute' class='btn btn-primary'>";
    echo "▶️ Executar Migration 2.1.1";
    echo "</button>";
    echo "</form>";
}

echo "</div>";

Html::footer();
