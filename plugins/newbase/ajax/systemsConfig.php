<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI — ajax/systemsConfig.php
 * -------------------------------------------------------------------------
 *
 * Endpoint AJAX para salvar a seção ativa do formulário de CompanyData.
 *
 * Recebe via POST:
 *   id               int    — ID do registro CompanyData
 *   section_key      string — Identifica qual seção/tabela salvar
 *   _glpi_csrf_token string — Token CSRF
 *   + campos específicos de cada seção (ver cada Section::save())
 *
 * Seções suportadas:
 *   ipbx             → SectionIpbxPabx::save()
 *   ipbx_cloud       → SectionIpbxCloud::save()
 *   dispositivos     → SectionDispositivos::save()
 *   rede             → SectionRede::save()
 *   chatbot          → SectionChatbot::save()
 *   linha_telefonica → SectionLinhaTelefonica::save()
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\AjaxHandler;
use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Sections\SectionIpbxPabx;
use GlpiPlugin\Newbase\Sections\SectionIpbxCloud;
use GlpiPlugin\Newbase\Sections\SectionDispositivos;
use GlpiPlugin\Newbase\Sections\SectionRede;
use GlpiPlugin\Newbase\Sections\SectionChatbot;
use GlpiPlugin\Newbase\Sections\SectionLinhaTelefonica;

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
$allowedSections = [
    SectionIpbxPabx::SECTION_KEY,
    SectionIpbxCloud::SECTION_KEY,
    SectionDispositivos::SECTION_KEY,
    SectionRede::SECTION_KEY,
    SectionChatbot::SECTION_KEY,
    SectionLinhaTelefonica::SECTION_KEY,
];

$sectionKey = filter_input(INPUT_POST, 'section_key', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($sectionKey) || !in_array($sectionKey, $allowedSections, true)) {
    AjaxHandler::sendResponse(
        false,
        'Seção inválida. Valores aceitos: ' . implode(', ', $allowedSections),
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

// ── Rotear para a Section correta e salvar ───────────────────────────────────
try {
    $result = match ($sectionKey) {
        SectionIpbxPabx::SECTION_KEY        => SectionIpbxPabx::save($id, $_POST),
        SectionIpbxCloud::SECTION_KEY       => SectionIpbxCloud::save($id, $_POST),
        SectionDispositivos::SECTION_KEY    => SectionDispositivos::save($id, $_POST),
        SectionRede::SECTION_KEY            => SectionRede::save($id, $_POST),
        SectionChatbot::SECTION_KEY         => SectionChatbot::save($id, $_POST),
        SectionLinhaTelefonica::SECTION_KEY => SectionLinhaTelefonica::save($id, $_POST),
    };
} catch (\Throwable $e) {
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "[systemsConfig] Exceção ao salvar seção '%s' para company_id %d: %s\n",
            $sectionKey,
            $id,
            $e->getMessage()
        )
    );
    AjaxHandler::sendResponse(false, 'Erro interno ao salvar. Verifique os logs do GLPI.', [], 500);
    exit;
}

// ── Responder ────────────────────────────────────────────────────────────────
if ($result) {
    AjaxHandler::sendResponse(true, 'Configurações salvas com sucesso!');
} else {
    AjaxHandler::sendResponse(false, 'Não foi possível salvar. Verifique os logs do GLPI.', [], 500);
}
