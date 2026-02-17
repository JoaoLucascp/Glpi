<?php

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\CompanyData;

// 1. Verificação de Sessão
Session::checkLoginUser();

// 2. Instanciar Objeto
$system = new System();

// --- TRATAMENTO DE AÇÕES (POST) ---

if (isset($_POST["add"])) {
    // O método check() já verifica CSRF e permissões automaticamente
    $system->check(-1, CREATE, $_POST);

    if ($newID = $system->add($_POST)) {
        // Redireciona para a empresa de origem (se houver), senão para o sistema criado
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect(CompanyData::getFormURLWithID($_POST['plugin_newbase_companydata_id']));
        } else {
            Html::redirect($system->getFormURLWithID($newID));
        }
    }
    Html::back();

} elseif (isset($_POST["update"])) {
    $system->check($_POST["id"], UPDATE);
    $system->update($_POST);
    Html::back();

} elseif (isset($_POST["delete"])) {
    $system->check($_POST["id"], DELETE);
    $system->delete($_POST);
    $system->redirectToList();

} elseif (isset($_POST["purge"])) {
    $system->check($_POST["id"], PURGE);
    $system->delete($_POST, 1);
    $system->redirectToList();

} elseif (isset($_POST["restore"])) {
    $system->check($_POST["id"], DELETE);
    $system->restore($_POST);
    Html::back();
}

// --- TRATAMENTO DE EXIBIÇÃO (GET) ---

// Validar parâmetros
$id = (int)($_GET['id'] ?? 0);
$company_id = (int)($_GET['plugin_newbase_companydata_id'] ?? 0);

// Cabeçalho
Html::header(
    System::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'system'
);

// Se estiver criando e vier de uma empresa, pré-preencher
if ($id === 0 && $company_id > 0) {
    $_GET['plugin_newbase_companydata_id'] = $company_id;
}

// Exibir formulário (o método display() gerencia tudo automaticamente)
$system->display($_GET);

Html::footer();
