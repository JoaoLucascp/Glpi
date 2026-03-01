<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI — ajax/systemsConfig.php
 * Endpoint AJAX dedicado para salvar systems_config da CompanyData
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\AjaxHandler;

include('../../../inc/includes.php');

Session::checkLoginUser();
AjaxHandler::setSecurityHeaders();

// Aceitar apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    AjaxHandler::sendResponse(false, 'Method not allowed', [], 405);
    exit;
}

// Validar CSRF
if (!AjaxHandler::checkCSRFToken()) {
    AjaxHandler::sendResponse(false, 'Token CSRF inválido. Recarregue a página.', [], 403);
    exit;
}

// Validar ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    AjaxHandler::sendResponse(false, 'ID inválido.', [], 400);
    exit;
}

// Carregar registro
$item = new CompanyData();
if (!$item->getFromDB($id)) {
    AjaxHandler::sendResponse(false, 'Registro não encontrado.', [], 404);
    exit;
}

// Verificar permissão de UPDATE
$item->check($id, UPDATE);

// Obter systems_config (deve ser array)
$systems_config = $_POST['systems_config'] ?? [];
if (!is_array($systems_config)) {
    AjaxHandler::sendResponse(false, 'Dados inválidos.', [], 400);
    exit;
}

// Sanitizar recursivamente
array_walk_recursive($systems_config, function (&$val) {
    if (is_string($val)) {
        $val = strip_tags(trim($val));
    }
});

// Executar update — prepareInputForUpdate converte array → JSON
$result = $item->update([
    'id'             => $id,
    'systems_config' => $systems_config,
]);

if ($result) {
    AjaxHandler::sendResponse(true, 'Configurações salvas com sucesso!');
} else {
    AjaxHandler::sendResponse(false, 'Não foi possível salvar. Verifique os logs do GLPI.', [], 500);
}
