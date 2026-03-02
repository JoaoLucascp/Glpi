<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI — ajax/systemsConfig.php
 * Endpoint AJAX para salvar UMA seção do systems_config de CompanyData.
 *
 * Recebe via POST:
 *   id            int    — ID do registro CompanyData
 *   section_key   string — Chave da seção: ipbx | ipbx_cloud | chatbot | linha
 *   systems_config array — Apenas a seção enviada pelo formulário ativo
 *   _glpi_csrf_token string — Token CSRF
 *
 * Estratégia de merge:
 *   1. Carrega o JSON atual do campo systems_config do banco.
 *   2. Substitui APENAS a seção correspondente a section_key.
 *   3. Salva o JSON completo de volta, preservando as outras seções intactas.
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\AjaxHandler;

include('../../../inc/includes.php');

Session::checkLoginUser();
AjaxHandler::setSecurityHeaders();

// ── Aceitar apenas POST ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    AjaxHandler::sendResponse(false, 'Method not allowed.', [], 405);
    exit;
}

// ── Validar CSRF ─────────────────────────────────────────────────────────────
if (!AjaxHandler::checkCSRFToken()) {
    AjaxHandler::sendResponse(false, 'Token CSRF inválido. Recarregue a página.', [], 403);
    exit;
}

// ── Validar ID do registro ───────────────────────────────────────────────────
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    AjaxHandler::sendResponse(false, 'ID inválido.', [], 400);
    exit;
}

// ── Validar section_key ──────────────────────────────────────────────────────
$allowed_sections = ['ipbx', 'ipbx_cloud', 'chatbot', 'linha'];
$section_key      = filter_input(INPUT_POST, 'section_key', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($section_key) || !in_array($section_key, $allowed_sections, true)) {
    AjaxHandler::sendResponse(
        false,
        'Seção inválida. Valores aceitos: ' . implode(', ', $allowed_sections),
        [],
        400
    );
    exit;
}

// ── Carregar registro e verificar permissões ─────────────────────────────────
$item = new CompanyData();
if (!$item->getFromDB($id)) {
    AjaxHandler::sendResponse(false, 'Registro não encontrado.', [], 404);
    exit;
}

$item->check($id, UPDATE);

// ── Obter dados da seção enviada ─────────────────────────────────────────────
$submitted_config = $_POST['systems_config'] ?? [];
if (!is_array($submitted_config)) {
    AjaxHandler::sendResponse(false, 'Dados de configuração inválidos.', [], 400);
    exit;
}

// ── Extrair apenas a seção correspondente ────────────────────────────────────
$section_data = $submitted_config[$section_key] ?? [];

// Sanitizar recursivamente (strip_tags + trim)
array_walk_recursive($section_data, function (&$val) {
    if (is_string($val)) {
        $val = strip_tags(trim($val));
    }
});

// ── Merge: carrega o config atual e substitui apenas a seção enviada ─────────
$current_json   = $item->fields['systems_config'] ?? '{}';
$current_config = json_decode($current_json, true);

// Garantir que é um array mesmo se o JSON estava vazio ou corrompido
if (!is_array($current_config)) {
    $current_config = [];
}

// Substituir apenas a seção que o formulário atual gerencia
$current_config[$section_key] = $section_data;

// ── Executar o update com o JSON completo mesclado ───────────────────────────
// prepareInputForUpdate() converte o array inteiro para JSON e salva no campo
$result = $item->update([
    'id'             => $id,
    'systems_config' => $current_config, // array completo (todas as seções)
]);

if ($result) {
    AjaxHandler::sendResponse(true, 'Configurações salvas com sucesso!');
} else {
    AjaxHandler::sendResponse(false, 'Não foi possível salvar. Verifique os logs do GLPI.', [], 500);
}
