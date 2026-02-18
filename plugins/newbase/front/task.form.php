<?php

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\CompanyData;

// 1. Verificação de Sessão
\Session::checkLoginUser();

// 2. Instanciar Objeto
$task = new Task();

// --- TRATAMENTO DE AÇÕES (POST) ---

if (isset($_POST["add"])) {
    // O método check() já verifica CSRF e permissões automaticamente
    $task->check(-1, CREATE, $_POST);

    if ($newID = $task->add($_POST)) {
        // Redireciona para a empresa de origem (se houver), senão para a tarefa criada
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            \Html::redirect(CompanyData::getFormURLWithID($_POST['plugin_newbase_companydata_id']));
        } else {
            \Html::redirect($task->getFormURLWithID($newID));
        }
    }
    \Html::back();

} elseif (isset($_POST["update"])) {
    $task->check($_POST["id"], UPDATE);
    $task->update($_POST);
    \Html::back();

} elseif (isset($_POST["delete"])) {
    $task->check($_POST["id"], DELETE);
    $task->delete($_POST);
    $task->redirectToList();

} elseif (isset($_POST["purge"])) {
    $task->check($_POST["id"], PURGE);
    $task->delete($_POST, 1);
    $task->redirectToList();

} elseif (isset($_POST["restore"])) {
    $task->check($_POST["id"], DELETE);
    $task->restore($_POST);
    \Html::back();
}

// --- TRATAMENTO DE EXIBIÇÃO (GET) ---

// Validar parâmetros
$id = (int)($_GET['id'] ?? 0);
$company_id = (int)($_GET['plugin_newbase_companydata_id'] ?? 0);

// Cabeçalho
\Html::header(
    Task::getTypeName(\Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'task'
);

// Se estiver criando e vier de uma empresa, pré-preencher
if ($id === 0 && $company_id > 0) {
    $_GET['plugin_newbase_companydata_id'] = $company_id;
}

// Exibir formulário (o método display() gerencia tudo automaticamente)
$task->display($_GET);

\Html::footer();
