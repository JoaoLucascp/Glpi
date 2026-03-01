<?php
/**
 * Migration 2.1.1 — Adiciona colunas faltantes em company_extras
 * Acesse: http://glpi.test/plugins/newbase/front/tools/run_migration.php
 * REMOVA este arquivo após executar com sucesso.
 */
include('../../../../inc/includes.php');
Session::checkLoginUser();
Session::checkRight('config', UPDATE);

global $DB;

$table = 'glpi_plugin_newbase_company_extras';

// Colunas a adicionar: [nome, definição SQL]
$columns = [
    'inscricao_estadual'  => "VARCHAR(50) DEFAULT NULL AFTER `fantasy_name`",
    'inscricao_municipal' => "VARCHAR(50) DEFAULT NULL AFTER `inscricao_estadual`",
    'cep'                 => "VARCHAR(10) DEFAULT NULL AFTER `email`",
    'street'              => "VARCHAR(255) DEFAULT NULL AFTER `cep`",
    'number'              => "VARCHAR(20) DEFAULT NULL AFTER `street`",
    'complement'          => "VARCHAR(255) DEFAULT NULL AFTER `number`",
    'neighborhood'        => "VARCHAR(255) DEFAULT NULL AFTER `complement`",
    'city'                => "VARCHAR(255) DEFAULT NULL AFTER `neighborhood`",
    'state'               => "VARCHAR(2) DEFAULT NULL AFTER `city`",
    'country'             => "VARCHAR(100) DEFAULT 'Brasil' AFTER `state`",
    'latitude'            => "DECIMAL(10,8) DEFAULT NULL AFTER `country`",
    'longitude'           => "DECIMAL(11,8) DEFAULT NULL AFTER `latitude`",
    'contract_status'     => "VARCHAR(50) DEFAULT 'active' AFTER `longitude`",
    'systems_config'      => "LONGTEXT DEFAULT NULL AFTER `contract_status`",
];

$results = [];
$errors  = [];

foreach ($columns as $col => $definition) {
    if ($DB->fieldExists($table, $col)) {
        $results[] = "✅ <b>$col</b> — já existe, pulado";
        continue;
    }
    try {
        $sql = "ALTER TABLE `$table` ADD COLUMN `$col` $definition";
        $DB->queryOrDie($sql, "Erro ao adicionar coluna $col");
        $results[] = "✅ <b>$col</b> — adicionado com sucesso";
    } catch (\Throwable $e) {
        $errors[] = "❌ <b>$col</b> — ERRO: " . $e->getMessage();
    }
}

// Inicializar systems_config como JSON vazio onde for NULL
try {
    $DB->query("UPDATE `$table` SET `systems_config` = '{}' WHERE `systems_config` IS NULL");
    $results[] = "✅ <b>systems_config</b> — valores NULL inicializados como '{}'";
} catch (\Throwable $e) {
    // Coluna pode não ter sido criada se deu erro acima
}

?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Migration 2.1.1 — Newbase</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; }
        h1   { color: #206bc4; }
        .ok  { color: #2fb344; }
        .err { color: #d63939; }
        li   { margin: 6px 0; font-size: 15px; }
        .box { background: #f1f5fb; border-radius: 8px; padding: 20px; margin-top: 20px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 24px;
               background: #206bc4; color: #fff; border-radius: 6px;
               text-decoration: none; font-size: 15px; }
    </style>
</head>
<body>
<h1>Migration 2.1.1 — Plugin Newbase</h1>

<?php if (!empty($errors)): ?>
    <div class="box" style="background:#fff1f1">
        <h3 class="err">Erros encontrados:</h3>
        <ul><?php foreach ($errors as $e) echo "<li class='err'>$e</li>"; ?></ul>
    </div>
<?php endif; ?>

<div class="box">
    <h3 class="ok">Resultado:</h3>
    <ul><?php foreach ($results as $r) echo "<li>$r</li>"; ?></ul>
</div>

<?php if (empty($errors)): ?>
    <div class="box" style="background:#f0fff4">
        <b class="ok">✅ Migration concluída com sucesso!</b><br><br>
        Agora você pode cadastrar empresas normalmente.
        <br><br>
        <a class="btn" href="<?= htmlspecialchars($CFG_GLPI['root_doc']) ?>/plugins/newbase/front/companydata.form.php">
            → Cadastrar empresa
        </a>
    </div>
<?php endif; ?>

</body>
</html>
