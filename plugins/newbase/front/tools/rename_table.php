<?php
/**
 * Migration: renomeia glpi_plugin_newbase_company_extras
 *                  → glpi_plugin_newbase_companydatas
 * Acesse: http://glpi.test/plugins/newbase/front/tools/rename_table.php
 * REMOVA após executar com sucesso.
 */
include('../../../../inc/includes.php');
Session::checkLoginUser();
Session::checkRight('config', UPDATE);

global $DB;

$old = 'glpi_plugin_newbase_company_extras';
$new = 'glpi_plugin_newbase_companydatas';

$steps   = [];
$errors  = [];

// 1. Verificar estado atual
$oldExists = $DB->tableExists($old);
$newExists = $DB->tableExists($new);

if ($newExists && !$oldExists) {
    $steps[] = "✅ Tabela <b>$new</b> já existe — migration já foi aplicada anteriormente.";
} elseif (!$oldExists && !$newExists) {
    $errors[] = "❌ Nenhuma das tabelas encontrada. Instale o plugin primeiro.";
} else {
    // 2. Remover FK constraints antes de renomear (MySQL exige)
    try {
        // Descobrir constraints que apontam para a tabela antiga
        $fks = $DB->request("
            SELECT CONSTRAINT_NAME, TABLE_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_NAME = '$old'
              AND CONSTRAINT_SCHEMA = DATABASE()
        ");
        foreach ($fks as $fk) {
            $DB->query("ALTER TABLE `{$fk['TABLE_NAME']}` DROP FOREIGN KEY `{$fk['CONSTRAINT_NAME']}`");
            $steps[] = "🔧 FK <b>{$fk['CONSTRAINT_NAME']}</b> removida de <b>{$fk['TABLE_NAME']}</b>";
        }
    } catch (\Throwable $e) {
        $steps[] = "⚠️ FK check: " . $e->getMessage();
    }

    // 3. Renomear tabela
    try {
        $DB->queryOrDie("RENAME TABLE `$old` TO `$new`", "Erro ao renomear tabela");
        $steps[] = "✅ Tabela renomeada: <b>$old</b> → <b>$new</b>";
    } catch (\Throwable $e) {
        $errors[] = "❌ Erro ao renomear: " . $e->getMessage();
    }
}

// 4. Verificar colunas obrigatórias na nova tabela e adicionar as que faltam
if (empty($errors) && $DB->tableExists($new)) {
    $needed = [
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
    foreach ($needed as $col => $def) {
        if (!$DB->fieldExists($new, $col)) {
            try {
                $DB->queryOrDie("ALTER TABLE `$new` ADD COLUMN `$col` $def", "Erro ao adicionar $col");
                $steps[] = "✅ Coluna <b>$col</b> adicionada";
            } catch (\Throwable $e) {
                $errors[] = "❌ Coluna $col: " . $e->getMessage();
            }
        } else {
            $steps[] = "✅ Coluna <b>$col</b> já existe";
        }
    }

    // Inicializar systems_config
    $DB->query("UPDATE `$new` SET `systems_config` = '{}' WHERE `systems_config` IS NULL OR `systems_config` = ''");
    $steps[] = "✅ <b>systems_config</b> inicializado onde NULL";
}

?><!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Migration — Renomear Tabela Newbase</title>
<style>
  body { font-family: sans-serif; max-width: 750px; margin: 40px auto; padding: 0 20px; }
  h1   { color: #206bc4; }
  li   { margin: 5px 0; font-size: 15px; }
  .box { background: #f1f5fb; border-radius: 8px; padding: 18px; margin-top: 16px; }
  .ok  { background: #f0fff4; }
  .err { background: #fff1f1; }
  .btn { display:inline-block; margin-top:20px; padding:10px 24px;
         background:#206bc4; color:#fff; border-radius:6px; text-decoration:none; }
</style>
</head>
<body>
<h1>Migration — Renomear Tabela</h1>
<p><code><?= $old ?></code> → <code><?= $new ?></code></p>

<?php if (!empty($errors)): ?>
<div class="box err">
  <b>❌ Erros:</b>
  <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
</div>
<?php endif; ?>

<div class="box <?= empty($errors) ? 'ok' : '' ?>">
  <b>Passos executados:</b>
  <ul><?php foreach ($steps as $s) echo "<li>$s</li>"; ?></ul>
</div>

<?php if (empty($errors)): ?>
<div class="box ok" style="margin-top:16px">
  <b>✅ Migration concluída com sucesso!</b><br><br>
  <a class="btn" href="<?= $CFG_GLPI['root_doc'] ?>/plugins/newbase/front/companydata.php">→ Ver lista de Empresas</a>
</div>
<?php endif; ?>
</body>
</html>
