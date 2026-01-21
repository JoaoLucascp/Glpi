<?php
/**
* Endpoint AJAX para busca de endereço por CEP
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\AddressHandler;

include('../../../inc/includes.php');

// Verificação de segurança
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

// Verificar autenticação
Session::checkLoginUser();

// Verificar permissões
Session::checkRight('plugin_newbase_companydata', READ);

// Validar token CSRF
Session::checkCSRF($_POST);

// Definir cabeçalho JSON
header('Content-Type: application/json; charset=utf-8');

try {
    $handler = new AddressHandler();
    $response = $handler->handleSearch(); // Chamar o método na nova classe
    echo json_encode($response);

} catch (Exception $e) {
    // Resposta de erro
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase')
    ]);

    // Manter Toolbox::logInFile por enquanto, conforme estava no arquivo original
    \Toolbox::logInFile('newbase_plugin', "ERROR in ajax/searchAddress.php (main handler): " . $e->getMessage() . "\n");
}

exit;