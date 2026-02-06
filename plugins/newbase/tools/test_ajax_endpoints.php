#!/usr/bin/env php
<?php
/**
 * Script de Teste Automatizado - Plugin Newbase
 * Testa busca de CNPJ e CEP sem navegador
 * 
 * Uso: php test_ajax_endpoints.php
 */

echo "=========================================\n";
echo "TESTE AJAX ENDPOINTS - NEWBASE PLUGIN\n";
echo "=========================================\n\n";

// Configurações
$baseUrl = 'http://glpi.test';
$pluginPath = '/plugins/newbase/ajax';

// ATENÇÃO: Este script NÃO funciona perfeitamente pois precisa de sessão GLPI
// Use apenas para testes de conectividade

// Cores para terminal
$colors = [
    'green' => "\033[32m",
    'red' => "\033[31m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'reset' => "\033[0m"
];

function printColor($text, $color = 'reset') {
    global $colors;
    echo $colors[$color] . $text . $colors['reset'] . "\n";
}

function testEndpoint($url, $data) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: Newbase-Test-Script/1.0'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// TESTE 1: CEP via ViaCEP (API pública - não precisa autenticação)
printColor("\n[TESTE 1] ViaCEP API Direta", 'blue');
printColor("URL: https://viacep.com.br/ws/01310100/json/", 'yellow');

$result = testEndpoint('https://viacep.com.br/ws/01310100/json/', []);

if ($result['code'] === 200) {
    printColor("[OK] HTTP {$result['code']}", 'green');
    $data = json_decode($result['response'], true);
    if (isset($data['logradouro'])) {
        echo "   Endereço: {$data['logradouro']}, {$data['bairro']}\n";
        echo "   Cidade: {$data['localidade']}/{$data['uf']}\n";
    }
} else {
    printColor("[ERRO] HTTP {$result['code']}", 'red');
    if ($result['error']) {
        echo "   cURL Error: {$result['error']}\n";
    }
}

// TESTE 2: CNPJ via BrasilAPI (API pública - não precisa autenticação)
printColor("\n[TESTE 2] BrasilAPI CNPJ Direta", 'blue');
printColor("URL: https://brasilapi.com.br/api/cnpj/v1/00000000000191", 'yellow');

$result = testEndpoint('https://brasilapi.com.br/api/cnpj/v1/00000000000191', []);

if ($result['code'] === 200) {
    printColor("[OK] HTTP {$result['code']}", 'green');
    $data = json_decode($result['response'], true);
    if (isset($data['razao_social'])) {
        echo "   Razão Social: {$data['razao_social']}\n";
        echo "   Nome Fantasia: {$data['nome_fantasia']}\n";
        echo "   Município: {$data['municipio']}/{$data['uf']}\n";
    }
} else {
    printColor("[ERRO] HTTP {$result['code']}", 'red');
    if ($result['error']) {
        echo "   cURL Error: {$result['error']}\n";
    }
}

// TESTE 3: Endpoint searchAddress.php (REQUER AUTENTICAÇÃO GLPI)
printColor("\n[TESTE 3] Plugin Endpoint - searchAddress.php", 'blue');
printColor("URL: {$baseUrl}{$pluginPath}/searchAddress.php", 'yellow');
printColor("[ATENÇÃO] Este teste vai FALHAR pois não há sessão GLPI", 'yellow');

$result = testEndpoint($baseUrl . $pluginPath . '/searchAddress.php', [
    'cep' => '01310100',
    '_glpi_csrf_token' => 'fake_token_for_test'
]);

if ($result['code'] === 200) {
    printColor("[OK] HTTP {$result['code']}", 'green');
    echo $result['response'] . "\n";
} else {
    printColor("[ESPERADO] HTTP {$result['code']} - Sem autenticação", 'yellow');
    if ($result['code'] === 302) {
        echo "   → Redirecionado para login (normal)\n";
    }
}

// TESTE 4: Endpoint searchCompany.php (REQUER AUTENTICAÇÃO GLPI)
printColor("\n[TESTE 4] Plugin Endpoint - searchCompany.php", 'blue');
printColor("URL: {$baseUrl}{$pluginPath}/searchCompany.php", 'yellow');
printColor("[ATENÇÃO] Este teste vai FALHAR pois não há sessão GLPI", 'yellow');

$result = testEndpoint($baseUrl . $pluginPath . '/searchCompany.php', [
    'cnpj' => '00000000000191',
    '_glpi_csrf_token' => 'fake_token_for_test'
]);

if ($result['code'] === 200) {
    printColor("[OK] HTTP {$result['code']}", 'green');
    echo $result['response'] . "\n";
} else {
    printColor("[ESPERADO] HTTP {$result['code']} - Sem autenticação", 'yellow');
    if ($result['code'] === 302) {
        echo "   → Redirecionado para login (normal)\n";
    }
}

// CONCLUSÃO
printColor("\n=========================================", 'blue');
printColor("CONCLUSÃO", 'blue');
printColor("=========================================", 'blue');

echo "\nAPIs Externas (devem funcionar):\n";
printColor("  ✓ ViaCEP - para busca de CEP", 'green');
printColor("  ✓ BrasilAPI - para busca de CNPJ", 'green');

echo "\nEndpoints do Plugin (precisam de navegador):\n";
printColor("  ⚠ searchAddress.php - requer login GLPI", 'yellow');
printColor("  ⚠ searchCompany.php - requer login GLPI", 'yellow');

echo "\nPara testar completamente:\n";
echo "  1. Acesse http://glpi.test no navegador\n";
echo "  2. Faça login\n";
echo "  3. Abra: Plugins > Newbase > Empresas > Nova Empresa\n";
echo "  4. Teste os botões de lupa CNPJ/CEP\n";
echo "  5. Verifique Console (F12)\n";

printColor("\n=========================================\n", 'blue');
