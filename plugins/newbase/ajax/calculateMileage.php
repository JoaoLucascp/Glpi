<?php

/**
* AJAX endpoint for calculating mileage between two coordinates
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*
* ---------------------------------------------------------------------
* GLPI - Gestionnaire Libre de Parc Informatique
* Copyright (C) 2015-2026 Teclib' and contributors.
*
* http://glpi-project.org
*
* based on GLPI - Copyright (C) 2003-2014 by the INDEPNET Development Team.
*
* ---------------------------------------------------------------------
*
* LICENSE
*
* This file is part of GLPI.
*
* GLPI is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* GLPI is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with GLPI. If not, see <http://www.gnu.org/licenses/>.
* ---------------------------------------------------------------------
*/

// Carrega o GLPI core
include('../../../inc/includes.php');

// Security check
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

// Evita acesso direto
if (!defined('GLPI_ROOT')) {
    include('../../../inc/includes.php');
}

// Verifica sessão ativa
Session::checkLoginUser();
// Check rights
Session::checkRight('plugin_newbase_task', READ);
// Verifica token CSRF (OBRIGATÓRIO para GLPI 10+)
Session::checkCSRF($_POST);
// Força modo AJAX
header('Content-Type: application/json; charset=utf-8');

use GlpiPlugin\Newbase\Src\Common;

try {
    // Get coordinates from POST
    $lat1 = floatval($_POST['lat1'] ?? 0);
    $lng1 = floatval($_POST['lng1'] ?? 0);
    $lat2 = floatval($_POST['lat2'] ?? 0);
    $lng2 = floatval($_POST['lng2'] ?? 0);

    // Validate coordinates
    if ($lat1 === 0.0 || $lng1 === 0.0 || $lat2 === 0.0 || $lng2 === 0.0) {
        echo json_encode([
            'success' => false,
            'message' => __('All coordinates are required', 'newbase'),
        ]);
        exit;
    }

    // Validate latitude range
    if ($lat1 < -90 || $lat1 > 90 || $lat2 < -90 || $lat2 > 90) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid latitude value (must be between -90 and 90)', 'newbase'),
        ]);
        exit;
    }

    // Validate longitude range
    if ($lng1 < -180 || $lng1 > 180 || $lng2 < -180 || $lng2 > 180) {
        echo json_encode([
            'success' => false,
            'message' => __('Invalid longitude value (must be between -180 and 180)', 'newbase'),
        ]);
        exit;
    }

    // Calculate distance using Haversine formula
    $distance = Common::calculateDistance($lat1, $lng1, $lat2, $lng2);

    // Success response
    echo json_encode([
        'success' => true,
        'mileage' => number_format($distance, 2, '.', ''),
        'formatted_mileage' => number_format($distance, 2, ',', '.') . ' km',
        'message' => __('Mileage calculated successfully', 'newbase'),
    ]);

    Toolbox::logInFile(
        'newbase_plugin',
        "Mileage calculated: $distance km between ($lat1, $lng1) and ($lat2, $lng2)\n"
    );

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase'),
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in calculateMileage.php: " . $e->getMessage() . "\n");
}
