<?php

/**
* ---------------------------------------------------------------------
* Formulário de Gerenciamento de Sistemas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo processa ações CRUD para sistemas telefônicos:
* - Asterisk (IPBX local)
* - CloudPBX (IPBX em nuvem)
* - Chatbot (Sistema omnichannel)
* - VoIP (Linha fixa)

* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR CLASSES DO PLUGIN
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\CompanyData;

// 4 CRIAR INSTÂNCIA DO OBJETO SYSTEM
$system = new System();

// PROCESSAMENTO DE AÇÕES (POST)

// 5 AÇÃO: ADICIONAR NOVO SISTEMA
if (isset($_POST['add'])) {

    // CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    // Verificar direitos de criação
    $system->check(-1, CREATE, $_POST);

    // Tentar adicionar sistema
    $newID = $system->add($_POST);

    if ($newID) {
        Session::addMessageAfterRedirect(
            __('System added successfully', 'newbase'),
            false,
            INFO
        );

        // Redirecionar de volta para a empresa (se veio de lá)
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $_POST['plugin_newbase_companydata_id']);
        } else {
            // Ou redirecionar para o novo sistema criado
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.form.php?id=' . $newID);
        }
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 6 AÇÃO: ATUALIZAR SISTEMA EXISTENTE
} elseif (isset($_POST['update'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de atualização
    $system->check($_POST['id'], UPDATE);

    if ($system->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('System updated successfully', 'newbase'),
            false,
            INFO
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 7 AÇÃO: DELETAR SISTEMA (soft delete - vai para lixeira)
} elseif (isset($_POST['delete'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de deleção
    $system->check($_POST['id'], DELETE);

    if ($system->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('System deleted successfully', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 8 AÇÃO: PURGAR SISTEMA (hard delete - remove permanentemente)
} elseif (isset($_POST['purge'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de purga
    $system->check($_POST['id'], PURGE);

    if ($system->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('System purged successfully', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
}

// EXIBIÇÃO DO FORMULÁRIO (GET)

// 9 VALIDAR E SANITIZAR PARÂMETROS DA URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    $id = 0;
}
$id = max(0, $id);

$company_id = filter_input(INPUT_GET, 'plugin_newbase_companydata_id', FILTER_VALIDATE_INT);
if ($company_id === false || $company_id === null) {
    $company_id = 0;
}
$company_id = max(0, $company_id);

// 10 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    System::getTypeName(1),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'system'
);

// GLPI 10.0.20: Injetar variáveis JavaScript (incluindo CSRF token)
echo Html::getCoreVariablesForJavascript();

// 11 CARREGAR DADOS DO SISTEMA (se estiver editando)
if ($id > 0) {
    // Modo edição: carregar dados existentes
    if (!$system->getFromDB($id)) {
        Session::addMessageAfterRedirect(
            __('System not found', 'newbase'),
            false,
            ERROR
        );
        Html::displayErrorAndDie(__('System not found', 'newbase'));
    }
} elseif ($company_id > 0) {
    // Modo criação: pré-preencher ID da empresa
    $system->fields['plugin_newbase_companydata_id'] = $company_id;
}

// 12 EXIBIR FORMULÁRIO
$system->showForm($id, [
    'plugin_newbase_companydata_id' => $company_id
]);

// 13 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
