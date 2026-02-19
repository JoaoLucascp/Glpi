<?php

declare(strict_types=1);

/**
 * AJAX Endpoint - Company Search by CNPJ
 *
 * Estratégia:
 * 1. Buscar na base local (CompanyData)
 * 2. Se não achar, buscar em APIs externas (BrasilAPI + ReceitaWS)
 *
 * Sempre responde com HTTP 200 (sucesso técnico),
 * usando "success" para indicar sucesso ou falha de negócio.
 */

@ini_set('display_errors', '0');
error_reporting(0);

include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\AjaxHandler;
use Session;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Segurança básica
AjaxHandler::setSecurityHeaders();

/**
 * Mask CNPJ for logging (protects privacy)
 */
function maskCNPJ(string $cnpj): string {
    $clean = preg_replace('/[^0-9]/', '', $cnpj);
    if (strlen($clean) !== 14) {
        return '**.***.***/****-**';
    }
    return substr($clean, 0, 2) . '.**.***/****-' . substr($clean, -2);
}

/**
 * Mask person name for logging
 */
function maskName(string $name): string {
    $name = trim($name);
    if (strlen($name) <= 3) {
        return $name;
    }
    return substr($name, 0, 2) . '...' . substr($name, -1);
}

/**
 * Mask email address for logging
 */
function maskEmail(string $email): string {
    $email = trim($email);
    if (strpos($email, '@') === false) {
        return '***';
    }

    [$user, $domain] = explode('@', $email, 2);
    $maskedUser      = (strlen($user) > 3) ? substr($user, 0, 3) . '***' : $user . '***';
    return $maskedUser . '@' . $domain;
}

/**
 * Busca empresa na base local pelo CNPJ
 */
function searchLocalDatabase(string $cnpj): ?array
{
    global $DB;

    $iterator = $DB->request([
        'FROM'  => CompanyData::getTable(),
        'WHERE' => [
            'cnpj'       => $cnpj,
            'is_deleted' => 0,
        ],
        'LIMIT' => 1,
    ]);

    if (count($iterator) === 0) {
        return null;
    }

    $row = $iterator->current();

    return [
        'id'           => (int) ($row['id'] ?? 0),
        'cnpj'         => $row['cnpj']         ?? '',
        'legal_name'   => $row['legal_name']   ?? '',
        'fantasy_name' => $row['fantasy_name'] ?? '',
        'email'        => $row['email']        ?? '',
        'phone'        => $row['phone']        ?? '',
        'postcode'     => $row['postcode']     ?? '',
        'street'       => $row['street']       ?? '',
        'number'       => $row['number']       ?? '',
        'complement'   => $row['complement']   ?? '',
        'neighborhood' => $row['neighborhood'] ?? '',
        'city'         => $row['city']         ?? '',
        'state'        => $row['state']        ?? '',
        'source'       => 'local',
    ];
}

/**
 * Search company data in Brasil API
 */
function searchBrasilAPI(string $cnpj): ?array
{
    $url      = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";
    $response = @file_get_contents($url);

    if ($response === false) {
        Toolbox::logInFile('newbase_plugin', "file_get_contents failed for Brasil API URL: {$url}");
        return null;
    }

    $data = json_decode($response, true);

    if (is_array($data) && isset($data['cnpj'])) {
        Toolbox::logInFile('newbase_plugin', "Brasil API success for CNPJ " . maskCNPJ($cnpj));
        return $data;
    }

    Toolbox::logInFile('newbase_plugin', "Brasil API failed for CNPJ " . maskCNPJ($cnpj));
    return null;
}

/**
 * Search company data in ReceitaWS API
 */
function searchReceitaWSAPI(string $cnpj): ?array
{
    $url      = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";
    $response = AjaxHandler::fetchCurl($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    if (is_array($data) && isset($data['status']) && $data['status'] === 'OK') {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS success for CNPJ " . maskCNPJ($cnpj));
        return $data;
    }

    if (isset($data['message'])) {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS error for CNPJ " . maskCNPJ($cnpj) . ": " . $data['message']);
    } else {
        Toolbox::logInFile('newbase_plugin', "ReceitaWS failed for CNPJ " . maskCNPJ($cnpj));
    }

    return null;
}

/**
 * Merge data from multiple APIs prioritizing Brasil API
 */
function mergeAPIData(?array $brasilAPI, ?array $receitaWS): array
{
    $result = [
        'cnpj'         => '',
        'legal_name'   => '',
        'fantasy_name' => '',
        'email'        => '',
        'phone'        => '',
        'postcode'     => '',
        'street'       => '',
        'number'       => '',
        'complement'   => '',
        'neighborhood' => '',
        'city'         => '',
        'state'        => '',
        'sources'      => '',
    ];

    // Priority 1: Brasil API
    if ($brasilAPI !== null) {
        $result['cnpj']         = $brasilAPI['cnpj']         ?? '';
        $result['legal_name']   = $brasilAPI['razao_social'] ?? '';
        $result['fantasy_name'] = $brasilAPI['nome_fantasia'] ?? '';
        $result['email']        = $brasilAPI['email']        ?? '';

        if (!empty($brasilAPI['ddd_telefone_1'])) {
            $result['phone'] = $brasilAPI['ddd_telefone_1'];
        }

        $result['postcode'] = preg_replace('/[^0-9]/', '', $brasilAPI['cep'] ?? '');

        $result['street']       = trim(($brasilAPI['descricao_tipo_logradouro'] ?? '') . ' ' . ($brasilAPI['logradouro'] ?? ''));
        $result['number']       = $brasilAPI['numero']       ?? '';
        $result['complement']   = $brasilAPI['complemento']  ?? '';
        $result['neighborhood'] = $brasilAPI['bairro']       ?? '';
        $result['city']         = $brasilAPI['municipio']    ?? '';
        $result['state']        = $brasilAPI['uf']           ?? '';
        $result['sources']      = 'Brasil API';
    }

    // Priority 2: ReceitaWS (fill missing data)
    if ($receitaWS !== null) {
        if (empty($result['cnpj']))         $result['cnpj']         = $receitaWS['cnpj']      ?? '';
        if (empty($result['legal_name']))   $result['legal_name']   = $receitaWS['nome']      ?? '';
        if (empty($result['fantasy_name'])) $result['fantasy_name'] = $receitaWS['fantasia']  ?? '';

        if (empty($result['email']) && !empty($receitaWS['email'])) {
            $result['email']   = $receitaWS['email'];
            $result['sources'] .= ($result['sources'] ? ' + ' : '') . 'ReceitaWS (email)';
        }

        if (empty($result['phone']) && !empty($receitaWS['telefone'])) {
            $result['phone']   = $receitaWS['telefone'];
            $result['sources'] .= ($result['sources'] ? ' + ' : '') . 'ReceitaWS (phone)';
        }

        if (empty($result['postcode']))     $result['postcode']     = preg_replace('/[^0-9]/', '', $receitaWS['cep'] ?? '');
        if (empty($result['street']))       $result['street']       = $receitaWS['logradouro'] ?? '';
        if (empty($result['number']))       $result['number']       = $receitaWS['numero']     ?? '';
        if (empty($result['complement']))   $result['complement']   = $receitaWS['complemento'] ?? '';
        if (empty($result['neighborhood'])) $result['neighborhood'] = $receitaWS['bairro']     ?? '';
        if (empty($result['city']))         $result['city']         = $receitaWS['municipio']  ?? '';
        if (empty($result['state']))        $result['state']        = $receitaWS['uf']         ?? '';

        if (empty($result['sources'])) {
            $result['sources'] = 'ReceitaWS';
        } elseif (strpos($result['sources'], 'ReceitaWS') === false) {
            $result['sources'] .= ' + ReceitaWS (address)';
        }
    }

    if (empty($result['sources'])) {
        unset($result['sources']);
    }

    return $result;
}

// ============ MAIN ============

try {
    // Autenticação
    if (!Session::getLoginUserID()) {
        AjaxHandler::sendResponse(false, __('Authentication required', 'newbase'), [], 401);
    }

    // Permissão
    if (!AjaxHandler::checkPermissions('plugin_newbase')) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf("User %d tried to search company without permission\n", Session::getLoginUserID())
        );
        AjaxHandler::sendResponse(false, __('You do not have permission to search companies', 'newbase'), [], 403);
    }

    // Config: recurso habilitado?
    $config             = Config::getConfig();
    $enable_cnpj_search = $config['enable_cnpj_search'] ?? 1;

    if (!$enable_cnpj_search) {
        AjaxHandler::sendResponse(false, __('Company search feature is disabled', 'newbase'), [], 403);
    }

    // CSRF
    if (!AjaxHandler::checkCSRFToken()) {
        Toolbox::logInFile('newbase_plugin', "AJAX company search: Invalid CSRF token\n");
        AjaxHandler::sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }

    // Método
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if (!in_array($method, ['POST', 'GET'], true)) {
        AjaxHandler::sendResponse(
            false,
            sprintf(__('Method %s not allowed', 'newbase'), $method),
            [],
            405
        );
    }

    // Entrada
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        $input = ($method === 'POST') ? $_POST : $_GET;
    }
    if (!is_array($input)) {
        $input = [];
    }

    // CNPJ
    $cnpj = $input['cnpj'] ?? '';

    if (empty($cnpj)) {
        AjaxHandler::sendResponse(false, __('CNPJ is required', 'newbase'), [], 400);
    }

    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    if (!Common::validateCNPJ($cnpj)) {
        AjaxHandler::sendResponse(
            false,
            __('Invalid CNPJ: check digits verification failed', 'newbase'),
            ['cnpj' => $cnpj],
            400
        );
    }

    // 1) Tenta base local
    $companyData = searchLocalDatabase($cnpj);

    if ($companyData !== null) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Company found in local database: CNPJ %s (ID: %d)\n",
                $cnpj,
                $companyData['id']
            )
        );

        AjaxHandler::sendResponse(
            true,
            __('Company data loaded from local database', 'newbase'),
            $companyData,
            200
        );
    }

    // 2) Tenta APIs externas (BrasilAPI + ReceitaWS)
    $brasilAPIData  = searchBrasilAPI($cnpj);
    $receitaWSData  = null;

    if ($brasilAPIData === null || empty($brasilAPIData['email'])) {
        $receitaWSData = searchReceitaWSAPI($cnpj);
    }

    if ($brasilAPIData === null && $receitaWSData === null) {
        Toolbox::logInFile('newbase_plugin', "CNPJ " . maskCNPJ($cnpj) . " not found in any API");

        AjaxHandler::sendResponse(
            false,
            __('Company not found in any database or API', 'newbase'),
            ['cnpj' => $cnpj],
            200
        );
    }

    $companyData = mergeAPIData($brasilAPIData, $receitaWSData);

    $companyData['cnpj_formatted'] = Common::formatCNPJ($cnpj);

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "CNPJ search success: %s | Name: %s | Email: %s | Source: %s",
            maskCNPJ($cnpj),
            maskName($companyData['legal_name'] ?? ''),
            maskEmail($companyData['email'] ?? ''),
            $companyData['sources'] ?? 'Unknown'
        )
    );

    AjaxHandler::sendResponse(
        true,
        __('Company data loaded from external API', 'newbase'),
        $companyData,
        200
    );
} catch (\Exception $e) {
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in searchCompany.php: %s\n",
            $e->getMessage()
        )
    );

    $error_data = [];

    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $error_data['error'] = $e->getMessage();
        $error_data['trace'] = $e->getTraceAsString();
    }

    AjaxHandler::sendResponse(
        false,
        __('An error occurred while searching company data', 'newbase'),
        $error_data,
        500
    );
}
