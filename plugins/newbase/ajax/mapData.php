<?php

/**
* ---------------------------------------------------------------------
* AJAX - Dados para Mapa de Tarefas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo retorna dados de geolocalização para renderizar mapa:
* - Marcadores de início/fim de tarefas
* - Rotas entre pontos
* - Filtros por empresa, usuário, status, período
* - Configurações do mapa (zoom, centro)
*
* Usado pelo Leaflet.js para exibir mapa interativo.
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*
* ---------------------------------------------------------------------
* GLPI - Gestionnaire Libre de Parc Informatique
* Copyright (C) 2015-2026 Teclib' and contributors.
*
* http://glpi-project.org
*
* based on GLPI - Copyright (C) 2003-2014 by the INDEPNET Development Team.
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

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);

// 3 IMPORTAR CLASSES NECESSÁRIAS
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Config;

// 4 CONFIGURAR RESPOSTA JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// VALIDAÇÕES DE SEGURANÇA

// 5 VERIFICAR PERMISSÕES
if (!Task::canView()) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => __('You do not have permission to view tasks', 'newbase'),
    ]);
    exit;
}

// PROCESSAMENTO

try {
    global $DB;

    // 6 OBTER FILTROS OPCIONAIS (via GET)
    $company_id = filter_input(INPUT_GET, 'company_id', FILTER_VALIDATE_INT) ?? 0;
    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT) ?? 0;
    $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) ?? '';
    $date_from = filter_input(INPUT_GET, 'date_from', FILTER_SANITIZE_STRING) ?? '';
    $date_to = filter_input(INPUT_GET, 'date_to', FILTER_SANITIZE_STRING) ?? '';

    // 7 CONSTRUIR CLÁUSULA WHERE
    // Busca tarefas que tenham pelo menos UMA coordenada
    $where = [
        'OR' => [
            ['latitude_start' => ['<>', null]],
            ['latitude_end' => ['<>', null]],
        ],
        'is_deleted' => 0,  // Não buscar tarefas deletadas
    ];

    // 8 APLICAR FILTROS OPCIONAIS
    if ($company_id > 0) {
        $where['entities_id'] = $company_id;
    }

    if ($user_id > 0) {
        $where['users_id'] = $user_id;
    }

    if (!empty($status) && in_array($status, ['pending', 'in_progress', 'completed'], true)) {
        $where['status'] = $status;
    }

    if (!empty($date_from)) {
        $where[] = ['date_creation' => ['>=', $date_from . ' 00:00:00']];
    }

    if (!empty($date_to)) {
        $where[] = ['date_creation' => ['<=', $date_to . ' 23:59:59']];
    }

    // 9 CONSULTAR TAREFAS COM COORDENADAS
    $iterator = $DB->request([
        'SELECT' => [
            't.*',
            'e.name AS company_name',
            'u.realname AS user_realname',
            'u.firstname AS user_firstname',
        ],
        'FROM' => 'glpi_plugin_newbase_tasks AS t',
        'LEFT JOIN' => [
            'glpi_entities AS e' => [
                'ON' => [
                    't' => 'entities_id',
                    'e' => 'id',
                ],
            ],
            'glpi_users AS u' => [
                'ON' => [
                    't' => 'users_id',
                    'u' => 'id',
                ],
            ],
        ],
        'WHERE' => $where,
        'ORDER' => 't.date_creation DESC',
        'LIMIT' => 500,  // Limite de segurança (evita sobrecarga)
    ]);

    $tasks = [];
    $markers = [];

    // 10 PROCESSAR CADA TAREFA
    foreach ($iterator as $row) {
        $task_data = [
            'id' => (int) $row['id'],
            'name' => $row['name'],
            'content' => $row['content'] ?? '',
            'status' => $row['status'],
            'is_completed' => (int) $row['is_completed'],
            'company_name' => $row['company_name'],
            'user_name' => trim(($row['user_firstname'] ?? '') . ' ' . ($row['user_realname'] ?? '')),
            'date_creation' => $row['date_creation'],
            'date_mod' => $row['date_mod'],
            'mileage' => (float) ($row['mileage'] ?? 0),
        ];

        // 11 ADICIONAR MARCADOR DE INÍCIO (verde)
        if ($row['latitude_start'] && $row['longitude_start']) {
            $markers[] = [
                'type' => 'start',
                'task_id' => (int) $row['id'],
                'lat' => (float) $row['latitude_start'],
                'lng' => (float) $row['longitude_start'],
                'title' => $row['name'] . ' (Início)',
                'description' => $row['content'] ?? '',
                'status' => $row['status'],
                'company' => $row['company_name'],
                'user' => $task_data['user_name'],
                'date' => $row['date_creation'],
                'color' => 'green',  // Marcador verde para início
            ];
        }

        // 12 ADICIONAR MARCADOR DE FIM (vermelho)
        if ($row['latitude_end'] && $row['longitude_end']) {
            $markers[] = [
                'type' => 'end',
                'task_id' => (int) $row['id'],
                'lat' => (float) $row['latitude_end'],
                'lng' => (float) $row['longitude_end'],
                'title' => $row['name'] . ' (Fim)',
                'description' => $row['content'] ?? '',
                'status' => $row['status'],
                'company' => $row['company_name'],
                'user' => $task_data['user_name'],
                'date' => $row['date_mod'] ?? $row['date_creation'],
                'color' => 'red',  // Marcador vermelho para fim
            ];
        }

        // 13 ADICIONAR ROTA SE TIVER AMBAS COORDENADAS
        if (
            $row['latitude_start'] && $row['longitude_start']
            && $row['latitude_end'] && $row['longitude_end']
        ) {
            $task_data['route'] = [
                'start' => [
                    'lat' => (float) $row['latitude_start'],
                    'lng' => (float) $row['longitude_start'],
                ],
                'end' => [
                    'lat' => (float) $row['latitude_end'],
                    'lng' => (float) $row['longitude_end'],
                ],
            ];
        }

        $tasks[] = $task_data;
    }

    // 14 CALCULAR BOUNDS (limites geográficos)
    $bounds = null;
    if (count($markers) > 0) {
        $lats = array_column($markers, 'lat');
        $lngs = array_column($markers, 'lng');

        $bounds = [
            'north' => max($lats),
            'south' => min($lats),
            'east' => max($lngs),
            'west' => min($lngs),
        ];
    }

    // 15 OBTER CONFIGURAÇÕES DO MAPA
    $map_config = [
        'provider' => Config::getConfigValue('map_provider', 'leaflet'),
        'default_zoom' => (int) Config::getConfigValue('map_default_zoom', '13'),
        'default_center' => [
            'lat' => (float) Config::getConfigValue('map_default_lat', '-23.5505'),  // São Paulo
            'lng' => (float) Config::getConfigValue('map_default_lng', '-46.6333'),
        ],
        'tile_layer' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    ];

    // 16 RESPOSTA DE SUCESSO
    echo json_encode([
        'success' => true,
        'data' => [
            'markers' => $markers,
            'tasks' => $tasks,
            'bounds' => $bounds,
            'config' => $map_config,
            'count' => count($markers),
            'total_tasks' => count($tasks),
        ],
        'message' => sprintf(
            __('Loaded %d markers from %d tasks', 'newbase'),
            count($markers),
            count($tasks)
        ),
    ]);

    // 17 LOG DE SUCESSO
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "Map data loaded: %d markers, %d tasks (filters: company=%d, user=%d, status=%s)\n",
            count($markers),
            count($tasks),
            $company_id,
            $user_id,
            $status ?: 'all'
        )
    );
} catch (Exception $e) {
    // 18 TRATAMENTO DE ERRO
    http_response_code(500);

    $response = [
        'success' => false,
        'message' => __('Error loading map data', 'newbase'),
    ];

    // Incluir detalhes apenas em debug
    if (defined('GLPI_DEBUG')) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);

    Toolbox::logInFile(
        'newbase_plugin',
        "ERROR in mapData.php: " . $e->getMessage() . "\n"
    );
}
