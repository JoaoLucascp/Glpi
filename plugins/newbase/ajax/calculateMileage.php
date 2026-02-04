<?php

/**
* ---------------------------------------------------------------------
* AJAX - Cálculo de Quilometragem - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo calcula a distância entre dois pontos GPS usando a
* fórmula de Haversine e retorna o resultado em JSON.
*
* Chamado via JavaScript do formulário de tarefas.
* @package   GlpiPlugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\Task;

// 4 CONFIGURAR RESPOSTA JSON
header('Content-Type: application/json; charset=utf-8');

// 5 BLOQUEAR CACHE (importante para AJAX)
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// VALIDAÇÕES DE SEGURANÇ

// 6 VERIFICAR SE É REQUISIÇÃO POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => __('Only POST requests are allowed', 'newbase'),
    ]);
    exit;
}

// 7 VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 8 VERIFICAR PERMISSÕES
if (!Task::canView()) {
    http_response_code(403); // Forbidden
    echo json_encode([
        'success' => false,
        'message' => __('You do not have permission to perform this action', 'newbase'),
    ]);
    exit;
}

// PROCESSAMENTO

try {
    // 9️ OBTER COORDENADAS DO POST
    $lat1 = $_POST['lat1'] ?? null;
    $lng1 = $_POST['lng1'] ?? null;
    $lat2 = $_POST['lat2'] ?? null;
    $lng2 = $_POST['lng2'] ?? null;

    // 10 VALIDAR SE TODAS AS COORDENADAS FORAM ENVIADAS
    if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
        echo json_encode([
            'success' => false,
            'message' => __('All coordinates are required', 'newbase'),
        ]);
        exit;
    }

    // 11 CONVERTER PARA FLOAT
    $lat1 = floatval($lat1);
    $lng1 = floatval($lng1);
    $lat2 = floatval($lat2);
    $lng2 = floatval($lng2);

    // 12 VALIDAR RANGE DE LATITUDE (-90 a 90)
    if ($lat1 < -90 || $lat1 > 90 || $lat2 < -90 || $lat2 > 90) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid latitude value (must be between -90 and 90)', 'newbase'),
        ]);
        exit;
    }

    // 13 VALIDAR RANGE DE LONGITUDE (-180 a 180)
    if ($lng1 < -180 || $lng1 > 180 || $lng2 < -180 || $lng2 > 180) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid longitude value (must be between -180 and 180)', 'newbase'),
        ]);
        exit;
    }

    // 14 CALCULAR DISTÂNCIA USANDO FÓRMULA DE HAVERSINE
    $distance = Common::calculateDistance($lat1, $lng1, $lat2, $lng2);

    // 15 RESPOSTA DE SUCESSO
    echo json_encode([
        'success' => true,
        'mileage' => number_format($distance, 2, '.', ''),  // 15.50 (para banco)
        'formatted_mileage' => number_format($distance, 2, ',', '.') . ' km',  // 15,50 km (para exibir)
        'message' => __('Mileage calculated successfully', 'newbase'),
    ]);

    // 16 LOG DE SUCESSO (opcional)
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "Mileage calculated: %.2f km between (%.6f, %.6f) and (%.6f, %.6f)\n",
            $distance,
            $lat1,
            $lng1,
            $lat2,
            $lng2
        )
    );

} catch (Exception $e) {
    // 17 RESPOSTA DE ERRO
    http_response_code(500); // Internal Server Error

    $response = [
        'success' => false,
        'message' => __('Error calculating mileage', 'newbase'),
    ];

    // Incluir detalhes apenas em debug
    if (defined('GLPI_DEBUG')) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);

    // 18 LOG DE ERRO
    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in calculateMileage.php: " . $e->getMessage() . "\n"
    );
}
