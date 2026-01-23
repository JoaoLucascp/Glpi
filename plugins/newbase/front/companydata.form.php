<?php

/**
 * Company Data Form - Handles create, update, and update operations for companies
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2026 João Lucas
 * @license   GPLv2+
 * @since     2.1.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\Src\CompanyData;

// SECURITY: Incluir apenas uma vez
include('../../../inc/includes.php');

// SECURITY: Proteção contra inclusão direta
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// SECURITY: Verificação de permissões
Session::checkRight('entity', READ);

// OPERAÇÕES POST (CREATE/UPDATE/DELETE)

if (isset($_POST['save'])) {
    // SECURITY: Validação CSRF
    Session::validateCSRF($_POST);

    $entity_id = isset($_POST['entities_id']) ? (int)$_POST['entities_id'] : 0;

    if (isset($_POST['add'])) {
        // Nova empresa - criar entity primeiro
        $entity = new Entity();
        $entity_data = [
            'name'              => $_POST['name'] ?? '',
            'email'             => $_POST['email'] ?? '',
            'phone'             => $_POST['phone'] ?? '',
            'address1'          => $_POST['address'] ?? '',
            'postcode'          => $_POST['cep'] ?? '',
        ];

        if ($entity_data['name']) {
            $entity_id = $entity->add($entity_data);
            if (!$entity_id) {
                Session::addMessageAfterRedirect(
                    __('Error creating company entity', 'newbase'),
                    false,
                    ERROR
                );
                Html::back();
                exit;
            }
        }
    } else {
        // Atualizar entity
        if ($entity_id > 0) {
            $entity = new Entity();
            $entity_data = [
                'id'    => $entity_id,
                'name'  => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
            ];
            $entity->update($entity_data);
        }
    }

    // Salvar dados complementares
    if ($entity_id > 0) {
        $extras = [
            'cnpj'            => $_POST['cnpj'] ?? '',
            'corporate_name'  => $_POST['corporate_name'] ?? '',
            'fantasy_name'    => $_POST['fantasy_name'] ?? '',
            'cep'             => $_POST['cep'] ?? '',
            'website'         => $_POST['website'] ?? '',
            'notes'           => $_POST['notes'] ?? '',
        ];

        CompanyData::saveCompanyExtras($entity_id, array_filter($extras));

        Session::addMessageAfterRedirect(
            __('Company successfully saved', 'newbase'),
            false,
            INFO
        );
    }

    Html::back();

} elseif (isset($_POST['delete'])) {
    // Delete company (soft delete via glpi_entities.is_deleted)
    Session::validateCSRF($_POST);

    $entity_id = isset($_POST['entities_id']) ? (int)$_POST['entities_id'] : 0;

    if ($entity_id > 0) {
        $entity = new Entity();
        if ($entity->delete(['id' => $entity_id])) {
            Session::addMessageAfterRedirect(
                __('Company successfully deleted', 'newbase'),
                false,
                INFO
            );
        }
    }

    Html::redirect(Plugin::getWebDir('newbase') . '/front/companydata.php');
    exit;

} else {
    // EXIBIR FORMULÁRIO (GET)

    // SECURITY: Validação e sanitização do ID
    $entity_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($entity_id === false || $entity_id === null) {
        $entity_id = 0;
    }

    // SECURITY: Validar ID positivo
    $entity_id = max(0, $entity_id);

    // Renderizar cabeçalho
    Html::header(
        CompanyData::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class
    );

    // Exibir formulário
    CompanyData::showForm($entity_id, []);

    Html::footer();
}
