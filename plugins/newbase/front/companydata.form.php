<?php

/**
* ---------------------------------------------------------------------
* Formulário de Gerenciamento de Empresas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo processa ações CRUD (Create, Read, Update, Delete) para empresas
* e exibe o formulário correspondente. Empresas são baseadas em Entidades do GLPI.
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\CompanyData;

// 4 CRIAR INSTÂNCIA DO OBJETO
$companydata = new CompanyData();

// PROCESSAMENTO DE AÇÕES (POST)

// 5 AÇÃO: SALVAR EMPRESA (criar ou atualizar)
if (isset($_POST['add']) || isset($_POST['update'])) {
    // CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    // Determinar se é criação ou atualização
    $is_new = isset($_POST['add']);
    $entity_id = filter_input(INPUT_POST, 'entities_id', FILTER_VALIDATE_INT);
    if ($entity_id === false || $entity_id === null || $entity_id < 0) {
        $entity_id = 0;
    }

    // ETAPA 1: GERENCIAR ENTIDADE DO GLPI

    $entity = new Entity();

    if ($is_new) {
        // CRIAR NOVA ENTIDADE

        // Verificar permissão de criação
        if (!Entity::canCreate()) {
            Session::addMessageAfterRedirect(
                __('You do not have permission to create companies', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }

        // Preparar dados da entidade
        $entity_data = [
            'name'         => $_POST['name'] ?? '',
            'completename' => $_POST['name'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'phone'        => $_POST['phone'] ?? '',
            'address'      => $_POST['address'] ?? '',
            'postcode'     => $_POST['cep'] ?? '',
            'town'         => $_POST['city'] ?? '',
            'state'        => $_POST['state'] ?? '',
            'country'      => $_POST['country'] ?? 'BR',
            'entities_id'  => $_SESSION['glpiactive_entity'] ?? 0,
            'comment'      => $_POST['notes'] ?? '',
        ];

        // Validar nome obrigatório
        if (empty($entity_data['name'])) {
            Session::addMessageAfterRedirect(
                __('Company name is required', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }

        // Criar entidade
        $entity_id = $entity->add($entity_data);

        if (!$entity_id) {
            Session::addMessageAfterRedirect(
                __('Error creating company entity', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }
    } else {
        // ATUALIZAR ENTIDADE EXISTENTE

        // Verificar se entidade existe
        if (!$entity->getFromDB($entity_id)) {
            Session::addMessageAfterRedirect(
                __('Company not found', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }

        // Verificar permissão de atualização
        if (!$entity->canUpdate()) {
            Session::addMessageAfterRedirect(
                __('You do not have permission to update this company', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }

        // Preparar dados para atualização
        $entity_data = [
            'id'       => $entity_id,
            'name'     => $_POST['name'] ?? $entity->fields['name'],
            'email'    => $_POST['email'] ?? '',
            'phone'    => $_POST['phone'] ?? '',
            'address'  => $_POST['address'] ?? '',
            'postcode' => $_POST['cep'] ?? '',
            'town'     => $_POST['city'] ?? '',
            'state'    => $_POST['state'] ?? '',
            'comment'  => $_POST['notes'] ?? '',
        ];

        // Atualizar entidade
        if (!$entity->update($entity_data)) {
            Session::addMessageAfterRedirect(
                __('Error updating company', 'newbase'),
                false,
                ERROR
            );
            Html::back();
        }
    }

    // ETAPA 2: SALVAR DADOS COMPLEMENTARES

    // Dados específicos do plugin (não existem na tabela glpi_entities)
    $extras = [
        'cnpj'           => $_POST['cnpj'] ?? '',
        'corporate_name' => $_POST['corporate_name'] ?? '',
        'fantasy_name'   => $_POST['fantasy_name'] ?? '',
        'contact_person' => $_POST['contact_person'] ?? '',
        'website'        => $_POST['website'] ?? '',
        'notes'          => $_POST['notes'] ?? '',
    ];

    // Salvar apenas campos preenchidos
    $extras = array_filter($extras, function ($value) {
        return !empty($value);
    });

    if (!empty($extras)) {
        CompanyData::saveCompanyExtras($entity_id, $extras);
    }

    // Mensagem de sucesso
    Session::addMessageAfterRedirect(
        $is_new
            ? __('Company successfully created', 'newbase')
            : __('Company successfully updated', 'newbase'),
        false,
        INFO
    );

    // Redirecionar para o formulário da empresa criada/atualizada
    Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $entity_id);

// 6 AÇÃO: DELETAR EMPRESA (soft delete)
} elseif (isset($_POST['delete'])) {
    // CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    $entity_id = filter_input(INPUT_POST, 'entities_id', FILTER_VALIDATE_INT);
    if ($entity_id === false || $entity_id === null || $entity_id < 0) {
        $entity_id = 0;
    }

    if ($entity_id <= 0) {
        Session::addMessageAfterRedirect(
            __('Invalid company ID', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    $entity = new Entity();

    // Verificar se entidade existe
    if (!$entity->getFromDB($entity_id)) {
        Session::addMessageAfterRedirect(
            __('Company not found', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Verificar permissão de deleção
    if (!$entity->canDelete()) {
        Session::addMessageAfterRedirect(
            __('You do not have permission to delete this company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Deletar (soft delete)
    if ($entity->delete(['id' => $entity_id])) {
        Session::addMessageAfterRedirect(
            __('Company successfully deleted', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 7 AÇÃO: PURGAR EMPRESA (hard delete - remove permanentemente)
} elseif (isset($_POST['purge'])) {
    // CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    $entity_id = filter_input(INPUT_POST, 'entities_id', FILTER_VALIDATE_INT);
    if ($entity_id === false || $entity_id === null || $entity_id < 0) {
        $entity_id = 0;
    }

    if ($entity_id <= 0) {
        Session::addMessageAfterRedirect(
            __('Invalid company ID', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    $entity = new Entity();

    // Verificar se entidade existe
    if (!$entity->getFromDB($entity_id)) {
        Session::addMessageAfterRedirect(
            __('Company not found', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Verificar permissão de purga
    if (!$entity->canPurge()) {
        Session::addMessageAfterRedirect(
            __('You do not have permission to permanently delete this company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Purgar (hard delete)
    if ($entity->delete(['id' => $entity_id], 1)) {
        Session::addMessageAfterRedirect(
            __('Company permanently deleted', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error permanently deleting company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
}

// EXIBIÇÃO DO FORMULÁRIO (GET)

// 8 VALIDAR E SANITIZAR O ID DA URL
$entity_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Se ID inválido ou não fornecido, assume 0 (novo registro)
if ($entity_id === false || $entity_id === null) {
    $entity_id = 0;
}

// Garantir que ID não seja negativo
$entity_id = max(0, $entity_id);

// 9 RENDERIZAR CABEÇALHO DO GLPI
Html::header('Newbase', $_SERVER['PHP_SELF'], "plugins", "newbase", "menu_slug");

// GLPI 10.0.20: Injetar variáveis JavaScript (incluindo CSRF token)
echo Html::getCoreVariablesForJavascript();


// 10 CARREGAR DADOS DA EMPRESA (se estiver editando)
if ($entity_id > 0) {
    if (!$companydata->getFromDB($entity_id)) {
        Session::addMessageAfterRedirect(
            __('Company not found', 'newbase'),
            false,
            ERROR
        );
        Html::displayErrorAndDie(__('Company not found', 'newbase'));
    }
}

// 11 EXIBIR FORMULÁRIO
$companydata->showForm($entity_id);

// 12 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
