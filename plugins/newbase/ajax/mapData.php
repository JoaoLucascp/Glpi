<?php
/**
* AJAX endpoint for getting map data (tasks with coordinates)
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\Config;

include('../../../inc/includes.php');

// Security check
if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
}

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_task', READ);

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

try {
    global $DB;

    // Get optional filters
    $company_id = intval($_GET['company_id'] ?? 0);
    $user_id = intval($_GET['user_id'] ?? 0);
    $status = strip_tags($_GET['status'] ?? '');
    $date_from = strip_tags($_GET['date_from'] ?? '');
    $date_to = strip_tags($_GET['date_to'] ?? '');

    // Build WHERE clause
    $where = [
        'OR' => [
            ['latitude_start' => ['<>', null]],
            ['latitude_end' => ['<>', null]]
        ]
    ];

    if ($company_id > 0) {
        $where['plugin_newbase_companydata_id'] = $company_id;
    }

    if ($user_id > 0) {
        $where['assigned_to'] = $user_id;
    }

    if (!empty($status) && in_array($status, ['open', 'in_progress', 'paused', 'completed'])) {
        $where['status'] = $status;
    }

    if (!empty($date_from)) {
        $where[] = ['date_start' => ['>=', $date_from . ' 00:00:00']];
    }

    if (!empty($date_to)) {
        $where[] = ['date_start' => ['<=', $date_to . ' 23:59:59']];
    }

    // Query tasks with coordinates
    $iterator = $DB->request([
        'SELECT' => [
            'glpi_plugin_newbase_task.*',
            'glpi_plugin_newbase_companydata.name AS company_name',
            'glpi_users.name AS user_name'
        ],
        'FROM' => 'glpi_plugin_newbase_task',
        'LEFT JOIN' => [
            'glpi_plugin_newbase_companydata' => [
                'ON' => [
                    'glpi_plugin_newbase_task' => 'plugin_newbase_companydata_id',
                    'glpi_plugin_newbase_companydata' => 'id'
                ]
            ],
            'glpi_users' => [
                'ON' => [
                    'glpi_plugin_newbase_task' => 'assigned_to',
                    'glpi_users' => 'id'
                ]
            ]
        ],
        'WHERE' => $where,
        'ORDER' => 'date_start DESC',
        'LIMIT' => 500 // Safety limit
    ]);

    $tasks = [];
    $markers = [];

    foreach ($iterator as $row) {
        $task_data = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'status' => $row['status'],
            'company_name' => $row['company_name'],
            'user_name' => $row['user_name'],
            'date_start' => $row['date_start'],
            'date_end' => $row['date_end'],
            'mileage' => $row['mileage']
        ];

        // Add start point marker
        if ($row['latitude_start'] && $row['longitude_start']) {
            $markers[] = [
                'type' => 'start',
                'task_id' => $row['id'],
                'lat' => floatval($row['latitude_start']),
                'lng' => floatval($row['longitude_start']),
                'title' => $row['title'] . ' (Start)',
                'description' => $row['description'],
                'status' => $row['status'],
                'company' => $row['company_name'],
                'user' => $row['user_name'],
                'date' => $row['date_start']
            ];
        }

        // Add end point marker
        if ($row['latitude_end'] && $row['longitude_end']) {
            $markers[] = [
                'type' => 'end',
                'task_id' => $row['id'],
                'lat' => floatval($row['latitude_end']),
                'lng' => floatval($row['longitude_end']),
                'title' => $row['title'] . ' (End)',
                'description' => $row['description'],
                'status' => $row['status'],
                'company' => $row['company_name'],
                'user' => $row['user_name'],
                'date' => $row['date_end'] ?? $row['date_start']
            ];
        }

        // Add route if both coordinates exist
        if ($row['latitude_start'] && $row['longitude_start'] &&
            $row['latitude_end'] && $row['longitude_end']) {
            $task_data['route'] = [
                'start' => [
                    'lat' => floatval($row['latitude_start']),
                    'lng' => floatval($row['longitude_start'])
                ],
                'end' => [
                    'lat' => floatval($row['latitude_end']),
                    'lng' => floatval($row['longitude_end'])
                ]
            ];
        }

        $tasks[] = $task_data;
    }

    // Calculate bounds if markers exist
    $bounds = null;
    if (count($markers) > 0) {
        $lats = array_column($markers, 'lat');
        $lngs = array_column($markers, 'lng');

        $bounds = [
            'north' => max($lats),
            'south' => min($lats),
            'east' => max($lngs),
            'west' => min($lngs)
        ];
    }

    // Get map configuration
    $map_config = [
        'provider' => Config::getConfigValue('map_provider', 'leaflet'),
        'default_zoom' => intval(Config::getConfigValue('map_default_zoom', '13')),
        'default_center' => [
            'lat' => floatval(Config::getConfigValue('map_default_lat', '-23.5505')),
            'lng' => floatval(Config::getConfigValue('map_default_lng', '-46.6333'))
        ]
    ];

    // Success response
    echo json_encode([
        'success' => true,
        'data' => [
            'markers' => $markers,
            'tasks' => $tasks,
            'bounds' => $bounds,
            'config' => $map_config,
            'count' => count($markers)
        ]
    ]);

    Toolbox::logInFile('newbase_plugin', "Map data loaded: " . count($markers) . " markers\n");

} catch (Exception $e) {
    // Error response
    echo json_encode([
        'success' => false,
        'message' => __('Server error', 'newbase')
    ]);

    Toolbox::logInFile('newbase_plugin', "ERROR in mapData.php: " . $e->getMessage() . "\n");
}
