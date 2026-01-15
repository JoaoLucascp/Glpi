<?php

/**
* Newbase Plugin - CompanyData Front Page
* @version 2.0.0
* @license GPLv2+
*/
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Src\CompanyData;

// Verificar autenticação
Session::checkLoginUser();

// Verificar permissões
if (!CompanyData::canView()) {
    Html::displayRightError();
    exit;
}

// Cabeçalho HTML
Html::header(
    __('Company Data', 'newbase'),
    $_SERVER['PHP_SELF'],
    'tools',
    'pluginnewbasemenu',
    'companydata'
);

// Verificar se a tabela existe
global $DB;
$table_exists = false;

try {
    $result = $DB->query("SHOW TABLES LIKE 'glpi_plugin_newbase_companydata'");
    $table_exists = ($result && $result->num_rows > 0);
} catch (Exception $e) {
    $table_exists = false;
}

if (!$table_exists) {
    echo "<div class='center'>";
    echo "<div class='alert alert-warning' style='padding: 20px; margin: 20px;'>";
    echo "<h2>" . __('Database Error', 'newbase') . "</h2>";
    echo "<p>" . __('The plugin tables are not installed correctly.', 'newbase') . "</p>";
    echo "<p><strong>" . __('Solution:', 'newbase') . "</strong></p>";
    echo "<ol style='text-align: left; display: inline-block;'>";
    echo "<li>" . __('Go to Setup > Plugins', 'newbase') . "</li>";
    echo "<li>" . __('Find the Newbase plugin', 'newbase') . "</li>";
    echo "<li>" . __('Click "Uninstall" if it is installed', 'newbase') . "</li>";
    echo "<li>" . __('Click "Install"', 'newbase') . "</li>";
    echo "<li>" . __('Click "Activate"', 'newbase') . "</li>";
    echo "</ol>";
    echo "</div>";
    echo "</div>";
} else {
    // Exibir lista de empresas
    Search::show('GlpiPlugin\Newbase\CompanyData');
}

// Rodapé HTML
Html::footer();
