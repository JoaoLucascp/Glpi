<?php

include ('../../../inc/includes.php');

// Importar a classe corretamente
use GlpiPlugin\Newbase\CompanyData;

// Verificar sessão
Session::checkLoginUser();

// Instanciar o objeto principal
$plugin_item = new CompanyData();

// --- TRATAMENTO DE AÇÕES (POST) ---

if (isset($_POST["add"])) {
    // Verifica permissão de CRIAÇÃO na classe
    $plugin_item->check(-1, CREATE, $_POST);

    // O método .add() da classe CommonDBTM faz todo o trabalho sujo (CSRF, SQL, Hooks)
    if ($newID = $plugin_item->add($_POST)) {
        // Redireciona para a edição do item criado
        Html::redirect($plugin_item->getFormURLWithID($newID));
    }
    Html::back();

} elseif (isset($_POST["update"])) {
    // Verifica permissão de ATUALIZAÇÃO e ID
    $plugin_item->check($_POST["id"], UPDATE);

    // O método .update() trata tudo
    $plugin_item->update($_POST);
    Html::back();

} elseif (isset($_POST["delete"])) {
    // Verifica permissão de DELEÇÃO (Lixeira)
    $plugin_item->check($_POST["id"], DELETE);

    $plugin_item->delete($_POST);

    // Redireciona para a lista após deletar
    $plugin_item->redirectToList();

} elseif (isset($_POST["purge"])) {
    // Verifica permissão de PURGA (Exclusão permanente)
    $plugin_item->check($_POST["id"], PURGE);

    $plugin_item->delete($_POST, 1);

    $plugin_item->redirectToList();

} elseif (isset($_POST["restore"])) {
    // Verifica permissão de RESTAURAÇÃO
    $plugin_item->check($_POST["id"], DELETE); // Geralmente usa permissão de delete ou purge

    $plugin_item->restore($_POST);
    Html::back();
}

// --- TRATAMENTO DE EXIBIÇÃO (GET) ---

// Cabeçalho Padrão
// O 3º parâmetro define o menu ativo (plugins > newbase > companydata)
Html::header(
    CompanyData::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",
    "newbase",
    "companydata"
);

// O método .display() resolve se deve mostrar o formulário vazio, preenchido ou erro 404
// Ele chama internamente o $plugin_item->showForm($_GET['id'])
$plugin_item->display($_GET);

// Rodapé Padrão
Html::footer();
