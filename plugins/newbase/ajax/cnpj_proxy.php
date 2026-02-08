<?php

/**
 * AJAX Proxy for CNPJ APIs - Newbase Plugin
 *
 * Acts as a proxy to query multiple CNPJ APIs:
 * 1. Brasil API (priority)
 * 2. ReceitaWS (fallback)
 *
 * Features:
 * - Solves CORS (Cross-Origin) issues
 * - Merges data from multiple sources
 * - Automatic fallback between APIs
 * - Local caching (optional)
 *
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @license   GPLv2+
 * @version   2.1.0
 */

// SECURITY: Load GLPI core
include '../../../inc/includes.php';

// SECURITY: Check authentication
Session::checkLoginUser();

// Import required classes
use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\CompanyData;

// Set JSON response headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

/**
 * Validate HTTP method and exit with error if not POST
 *
 * @return void
 */
function validateRequestMethod(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit(json_encode([
            'success' => false,
            'error' => __('Only POST requests are allowed', 'newbase'),
        ]));
    }
}

/**
 * Validate CSRF token and exit with error if invalid
 *
 * @return void
 */
function validateCSRFToken(): void
{
    // Aceitar ambos os nomes de token CSRF
    if (!isset($_POST['glpi_csrf_token']) && !isset($_POST['_glpi_csrf_token'])) {
        http_response_code(403);
        exit(json_encode([
            'success' => false,
            'error' => __('CSRF token is missing', 'newbase'),
        ]));
    }

    // Normalizar nome do token para validação
    if (isset($_POST['glpi_csrf_token']) && !isset($_POST['_glpi_csrf_token'])) {
        $_POST['_glpi_csrf_token'] = $_POST['glpi_csrf_token'];
    }

    Session::checkCSRF($_POST);
}


/**
 * Check user permissions for company operations
 *
 * @return void
 */
function checkPermissions(): void
{
    // Check if user has permission to create or update companies
    if (!Session::haveRight('plugin_newbase', CREATE)
        && !Session::haveRight('plugin_newbase', UPDATE)) {
        http_response_code(403);
        exit(json_encode([
            'success' => false,
            'error' => __('You do not have permission to access this feature', 'newbase'),
        ]));
    }
}

/**
 * Validate and sanitize CNPJ input
 *
 * @return string Validated and cleaned CNPJ
 * @throws Exception If validation fails
 */
function validateAndSanitizeCNPJ(): string
{
    // Check if CNPJ was provided
    if (empty($_POST['cnpj'])) {
        http_response_code(400);
        exit(json_encode([
            'success' => false,
            'error' => __('CNPJ is required', 'newbase'),
        ]));
    }

    // Remove formatting
    $cnpj = preg_replace('/[^0-9]/', '', (string)$_POST['cnpj']);

    // Validate size
    if (strlen($cnpj) !== 14) {
        http_response_code(400);
        exit(json_encode([
            'success' => false,
            'error' => __('Invalid CNPJ: must have 14 digits', 'newbase'),
        ]));
    }

    // Validate check digits
    if (!Common::validateCNPJ($cnpj)) {
        http_response_code(400);
        exit(json_encode([
            'success' => false,
            'error' => __('Invalid CNPJ: check digits do not match', 'newbase'),
        ]));
    }

    return $cnpj;
}

/**
 * Search company data in Brasil API
 *
 * @param string $cnpj CNPJ without formatting (14 digits)
 *
 * @return array|null Company data or null if not found
 */
function searchBrasilAPI(string $cnpj): ?array
{
    $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, // Localhost fix
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI-Newbase/2.1.0',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log error if any
    if ($error) {
        Toolbox::logInFile('newbase_cnpj', "Brasil API CURL Error: {$error}");
    }

    // Return data on success
    if ($httpCode === 200 && $response !== false) {
        $data = json_decode($response, true);
        if (is_array($data) && isset($data['cnpj'])) {
            return $data;
        }
    }

    return null;
}

/**
 * Search company data in ReceitaWS API
 *
 * @param string $cnpj CNPJ without formatting (14 digits)
 *
 * @return array|null Company data or null if not found
 */
function searchReceitaWSAPI(string $cnpj): ?array
{
    $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false, // Localhost fix
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'GLPI-Newbase/2.1.0',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        Toolbox::logInFile('newbase_cnpj', "ReceitaWS CURL Error: {$error}");
    }

    if ($httpCode === 200 && $response !== false) {
        $data = json_decode($response, true);

        // ReceitaWS returns error within JSON
        if (isset($data['status']) && $data['status'] === 'OK') {
            return $data;
        }
    }

    return null;
}

/**
 * Merge data from multiple APIs prioritizing Brasil API
 *
 * @param array|null $brasilAPI Brasil API data
 * @param array|null $receitaWS ReceitaWS data
 *
 * @return array Merged data
 */
function mergeAPIData(?array $brasilAPI, ?array $receitaWS): array
{
    $result = [
        'razao_social' => '',
        'nome_fantasia' => '',
        'email' => '',
        'telefone' => '',
        'cep' => '',
        'logradouro' => '',
        'numero' => '',
        'complemento' => '',
        'bairro' => '',
        'municipio' => '',
        'uf' => '',
        'fonte' => [],
    ];

    // Prioritize Brasil API
    if ($brasilAPI !== null) {
        $result['razao_social'] = $brasilAPI['razao_social'] ?? '';
        $result['nome_fantasia'] = $brasilAPI['nome_fantasia'] ?? '';
        $result['email'] = $brasilAPI['email'] ?? '';
        $result['telefone'] = $brasilAPI['ddd_telefone_1'] ?? '';
        $result['cep'] = $brasilAPI['cep'] ?? '';
        $result['logradouro'] = trim(($brasilAPI['descricao_tipo_logradouro'] ?? '')
            . ' ' . ($brasilAPI['logradouro'] ?? ''));
        $result['numero'] = $brasilAPI['numero'] ?? '';
        $result['complemento'] = $brasilAPI['complemento'] ?? '';
        $result['bairro'] = $brasilAPI['bairro'] ?? '';
        $result['municipio'] = $brasilAPI['municipio'] ?? '';
        $result['uf'] = $brasilAPI['uf'] ?? '';
        $result['fonte'][] = 'Brasil API';
    }

    // Complement with ReceitaWS if necessary
    if ($receitaWS !== null) {
        if (empty($result['razao_social'])) {
            $result['razao_social'] = $receitaWS['nome'] ?? '';
        }

        if (empty($result['nome_fantasia'])) {
            $result['nome_fantasia'] = $receitaWS['fantasia'] ?? '';
        }

        if (empty($result['email']) && !empty($receitaWS['email'])) {
            $result['email'] = $receitaWS['email'];
            $result['fonte'][] = 'ReceitaWS (email)';
        }

        if (empty($result['telefone']) && !empty($receitaWS['telefone'])) {
            $result['telefone'] = $receitaWS['telefone'];
            $result['fonte'][] = 'ReceitaWS (telefone)';
        }

        if (empty($result['cep'])) {
            $result['cep'] = $receitaWS['cep'] ?? '';
            $result['logradouro'] = $receitaWS['logradouro'] ?? '';
            $result['numero'] = $receitaWS['numero'] ?? '';
            $result['complemento'] = $receitaWS['complemento'] ?? '';
            $result['bairro'] = $receitaWS['bairro'] ?? '';
            $result['municipio'] = $receitaWS['municipio'] ?? '';
            $result['uf'] = $receitaWS['uf'] ?? '';
        }
    }

    return $result;
}

// ==================== MAIN PROCESSING ====================

try {
    // Validate HTTP method
    validateRequestMethod();

    // Validate CSRF token
    validateCSRFToken();

    // Check permissions
    checkPermissions();

    // Validate and sanitize CNPJ
    $cnpj = validateAndSanitizeCNPJ();

    // Try Brasil API first
    $brasilAPIData = searchBrasilAPI($cnpj);
    $receitaWSData = null;

    // If Brasil API didn't return email, try ReceitaWS
    if ($brasilAPIData === null || empty($brasilAPIData['email'])) {
        $receitaWSData = searchReceitaWSAPI($cnpj);
    }

    // If neither API worked
    if ($brasilAPIData === null && $receitaWSData === null) {
        http_response_code(404);
        exit(json_encode([
            'success' => false,
            'error' => __('CNPJ not found in any API', 'newbase'),
        ]));
    }

    // Merge data from both APIs
    $result = mergeAPIData($brasilAPIData, $receitaWSData);

    // Log success
    Toolbox::logInFile(
        'newbase_cnpj',
        sprintf(
            "CNPJ: %s | Email: %s | Sources: %s",
            $cnpj,
            $result['email'] ?: 'NOT FOUND',
            implode(', ', $result['fonte'])
        )
    );

    // Return result
    echo json_encode([
        'success' => true,
        'data' => $result,
        'message' => __('Company data loaded successfully', 'newbase'),
    ]);

} catch (Exception $e) {
    // Error handling
    http_response_code(500);

    $response = [
        'success' => false,
        'error' => __('Error searching company data', 'newbase'),
    ];

    // Include details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $response['details'] = $e->getMessage();
    }

    echo json_encode($response);

    Toolbox::logInFile(
        'newbase_cnpj',
        "ERROR in cnpj_proxy.php: " . $e->getMessage()
    );
}
