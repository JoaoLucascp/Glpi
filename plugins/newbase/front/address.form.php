<?php

/**
* ---------------------------------------------------------------------
* Formulário de Gerenciamento de Endereços - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo processa ações CRUD (Create, Read, Update, Delete) para endereços
* e exibe o formulário correspondente.
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1️ SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2️ SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3️ IMPORTAR CLASSES DO PLUGIN (com namespace)
use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\CompanyData;

// 4️ CRIAR INSTÂNCIA DO OBJETO ADDRESS
$address = new Address();

// ========================================
// PROCESSAMENTO DE AÇÕES (POST)
// ========================================

// 5️ AÇÃO: ADICIONAR NOVO ENDEREÇO
if (isset($_POST['add'])) {

    // CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    // Pegar ID da empresa vinculada
    $company_id = (int) ($_POST['plugin_newbase_companydata_id'] ?? 0);

    // Validar se empresa existe
    $company = new CompanyData();
    if (!$company->getFromDB($company_id)) {
        Session::addMessageAfterRedirect(
            __('The selected company does not exist', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Validar permissões do usuário
    if (!$company->canUpdate()) {
        Session::addMessageAfterRedirect(
            __('You do not have permission to manage this company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Verificar direitos de criação
    $address->check(-1, CREATE, $_POST);

    // Tentar adicionar endereço
    if ($newID = $address->add($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address added successfully', 'newbase'),
            false,
            INFO
        );

        // Redirecionar para o novo endereço criado (se configurado)
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($address->getLinkURL());
        }

        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 6️ AÇÃO: ATUALIZAR ENDEREÇO EXISTENTE
} elseif (isset($_POST['update'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de atualização
    $address->check($_POST['id'], UPDATE);

    if ($address->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address updated successfully', 'newbase'),
            false,
            INFO
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 7️ AÇÃO: DELETAR ENDEREÇO (soft delete - vai para lixeira)
} elseif (isset($_POST['delete'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de deleção
    $address->check($_POST['id'], DELETE);

    if ($address->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address deleted successfully', 'newbase'),
            false,
            INFO
        );

        // Redirecionar para a página da empresa
        $company_id = (int) ($_POST['plugin_newbase_companydata_id'] ?? 0);
        if ($company_id > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $company_id);
        }

        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 8️ AÇÃO: PURGAR ENDEREÇO (hard delete - remove permanentemente)
} elseif (isset($_POST['purge'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de purga
    $address->check($_POST['id'], PURGE);

    if ($address->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Address purged successfully', 'newbase'),
            false,
            INFO
        );

        // Redirecionar para a página da empresa
        $company_id = (int) ($_POST['plugin_newbase_companydata_id'] ?? 0);
        if ($company_id > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $company_id);
        }

        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
}

// ========================================
// EXIBIÇÃO DO FORMULÁRIO (GET)
// ========================================

// 9️ PEGAR PARÂMETROS DA URL
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$company_id = (int) ($_GET['plugin_newbase_companydata_id'] ?? $_POST['plugin_newbase_companydata_id'] ?? 0);

// 10 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    Address::getTypeName(1),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'address'
);

// 11 CARREGAR DADOS DO ENDEREÇO (se estiver editando)
if ($id > 0) {
    $address->getFromDB($id);
} elseif ($company_id > 0) {
    // Se estiver criando novo, pré-preencher ID da empresa
    $address->fields['plugin_newbase_companydata_id'] = $company_id;
}

// 12 EXIBIR FORMULÁRIO
$address->showForm($id);

// 13 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
