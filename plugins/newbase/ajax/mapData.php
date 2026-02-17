<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Newbase. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2024-2026 by João Lucas
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/JoaoLucascp/Glpi
 * -------------------------------------------------------------------------
 */

/**
 * AJAX Endpoint - Map Data for Task Geolocation
 *
 * Returns geolocation data for rendering interactive maps:
 * - Task markers (start/end coordinates)
 * - Routes between points
 * - Map configuration (zoom, center, tile layer)
 *
 * Filters:
 * - entities_id: Filter by company/entity
 * - users_id: Filter by assigned user
 * - status: Filter by task status
 * - date_from/date_to: Filter by date range
 *
 * Used by Leaflet.js or other mapping libraries to display task locations.
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Config;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Security headers
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

/**
 * Send JSON response and exit
 * @param bool $success Success status
 * @param string $message Message
 * @param array $data Additional data
 * @param int $http_code HTTP status code
 */
function sendResponse(bool $success, string $message, array $data = [], int $http_code = 200): void
{
    http_response_code($http_code);

    $response = [
        'success' => $success,
        'message' => $message,
    ];

    if (!empty($data)) {
        $response['data'] = $data;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Get map configuration from plugin settings
 * @return array Map configuration
 */
function getMapConfig(): array
{
    $config = Config::getConfig();

    return [
        'default_zoom' => (int) ($config['default_zoom'] ?? 13),
        'default_center' => [
            'lat' => (float) ($config['map_default_lat'] ?? -23.5505), // São Paulo
            'lng' => (float) ($config['map_default_lng'] ?? -46.6333),
        ],
        'tile_layer' => $config['map_tile_layer'] ?? 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'attribution' => $config['map_attribution'] ?? '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        'max_zoom' => 18,
        'min_zoom' => 3,
    ];
}

/**
 * Calculate geographic bounds from coordinates
 * @param array $markers Array of markers with lat/lng
 * @return array|null Bounds or null if no markers
 */
function calculateBounds(array $markers): ?array
{
    if (empty($markers)) {
        return null;
    }

    $lats = array_column($markers, 'lat');
    $lngs = array_column($markers, 'lng');

    if (empty($lats) || empty($lngs)) {
        return null;
    }

    return [
        'north' => max($lats),
        'south' => min($lats),
        'east' => max($lngs),
        'west' => min($lngs),
        'center' => [
            'lat' => (min($lats) + max($lats)) / 2,
            'lng' => (min($lngs) + max($lngs)) / 2,
        ],
    ];
}

// ===== AUTHENTICATION CHECK =====
if (!Session::getLoginUserID()) {
    sendResponse(false, __('Authentication required'), [], 401);
}

// ===== CHECK PERMISSIONS =====
if (!Task::canView()) {
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf("User %d tried to access map data without permission\n", Session::getLoginUserID())
    );
    sendResponse(false, __('You do not have permission to view tasks', 'newbase'), [], 403);
}

// ===== CHECK IF GPS FEATURE IS ENABLED =====
$config = Config::getConfig();
$enable_gps = $config['enable_gps'] ?? 1;

if (!$enable_gps) {
    sendResponse(false, __('GPS tracking feature is disabled', 'newbase'), [], 403);
}

// ===== GET REQUEST METHOD =====
$method = $_SERVER['REQUEST_METHOD'];

// ===== GET REQUEST DATA =====
$input = ($method === 'POST') ? $_POST : $_GET;

// Support JSON input
if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $jsonInput = json_decode($rawInput, true);
    if (is_array($jsonInput)) {
        $input = array_merge($input, $jsonInput);
    }
}

// ===== CSRF TOKEN VALIDATION (for POST) =====
if ($method === 'POST') {
    $csrf_token = $_SERVER['HTTP_X_GLPI_CSRF_TOKEN'] ?? $input['_glpi_csrf_token'] ?? '';

    if (empty($csrf_token)) {
        Toolbox::logInFile('newbase_plugin', "AJAX map data: CSRF token missing\n");
        sendResponse(false, __('CSRF token is required', 'newbase'), [], 403);
    }

    try {
        Session::checkCSRF(['_glpi_csrf_token' => $csrf_token]);
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "AJAX map data: Invalid CSRF token\n");
        sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }
}

// ===== PROCESS REQUEST =====

try {
    global $DB;

    // Only allow POST and GET methods
    if (!in_array($method, ['POST', 'GET'], true)) {
        sendResponse(
            false,
            sprintf(__('Method %s not allowed', 'newbase'), $method),
            [],
            405
        );
    }

    // ===== GET AND VALIDATE FILTERS =====

    // Entity filter
    $entities_id = isset($input['entities_id']) ? (int) $input['entities_id'] : 0;

    // User filter
    $users_id = isset($input['users_id']) ? (int) $input['users_id'] : 0;

    // Status filter
    $status = isset($input['status']) ? trim($input['status']) : '';
    $valid_statuses = array_keys(Task::getStatuses());
    if (!empty($status) && !in_array($status, $valid_statuses, true)) {
        sendResponse(
            false,
            __('Invalid status filter', 'newbase'),
            ['valid_statuses' => $valid_statuses],
            400
        );
    }

    // Date filters
    $date_from = isset($input['date_from']) ? trim($input['date_from']) : '';
    $date_to = isset($input['date_to']) ? trim($input['date_to']) : '';

    // Validate date format
    if (!empty($date_from) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from)) {
        sendResponse(false, __('Invalid date_from format (expected: YYYY-MM-DD)', 'newbase'), [], 400);
    }

    if (!empty($date_to) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
        sendResponse(false, __('Invalid date_to format (expected: YYYY-MM-DD)', 'newbase'), [], 400);
    }

    // Limit (max 1000 for performance)
    $limit = isset($input['limit']) ? min((int) $input['limit'], 1000) : 500;

    // ===== BUILD WHERE CLAUSE =====

    // Base condition: tasks with at least one GPS coordinate
    $where = [
        'OR' => [
            ['gps_start_lat' => ['IS NOT', null], 'gps_start_lng' => ['IS NOT', null]],
            ['gps_end_lat' => ['IS NOT', null], 'gps_end_lng' => ['IS NOT', null]],
        ],
        'is_deleted' => 0,
    ];

    // Apply filters
    if ($entities_id > 0) {
        $where['entities_id'] = $entities_id;
    }

    if ($users_id > 0) {
        $where['users_id'] = $users_id;
    }

    if (!empty($status)) {
        $where['status'] = $status;
    }

    if (!empty($date_from)) {
        $where[] = ['date_start' => ['>=', $date_from . ' 00:00:00']];
    }

    if (!empty($date_to)) {
        $where[] = ['date_start' => ['<=', $date_to . ' 23:59:59']];
    }

    // ===== QUERY TASKS WITH GPS DATA =====

    $iterator = $DB->request([
        'SELECT' => [
            't.*',
            'e.name AS entity_name',
            'u.realname AS user_realname',
            'u.firstname AS user_firstname',
        ],
        'FROM' => Task::getTable() . ' AS t',
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
        'ORDER' => ['t.date_start DESC'],
        'LIMIT' => $limit,
    ]);

    $tasks = [];
    $markers = [];
    $routes = [];

    // ===== PROCESS EACH TASK =====

    foreach ($iterator as $row) {
        $task_id = (int) $row['id'];
        $user_name = trim(($row['user_firstname'] ?? '') . ' ' . ($row['user_realname'] ?? ''));

        $task_data = [
            'id' => $task_id,
            'title' => $row['title'] ?? '',
            'description' => $row['description'] ?? '',
            'status' => $row['status'],
            'entity_name' => $row['entity_name'] ?? '',
            'user_name' => $user_name ?: __('Unassigned'),
            'date_start' => $row['date_start'] ?? null,
            'date_end' => $row['date_end'] ?? null,
            'mileage' => (float) ($row['mileage'] ?? 0),
        ];

        // START MARKER (green)
        if (!empty($row['gps_start_lat']) && !empty($row['gps_start_lng'])) {
            $markers[] = [
                'id' => 'start_' . $task_id,
                'type' => 'start',
                'task_id' => $task_id,
                'lat' => (float) $row['gps_start_lat'],
                'lng' => (float) $row['gps_start_lng'],
                'title' => $task_data['title'] . ' (' . __('Start', 'newbase') . ')',
                'description' => $task_data['description'],
                'status' => $row['status'],
                'entity' => $task_data['entity_name'],
                'user' => $user_name,
                'date' => $row['date_start'],
                'icon_color' => 'green',
            ];

            $task_data['gps_start'] = [
                'lat' => (float) $row['gps_start_lat'],
                'lng' => (float) $row['gps_start_lng'],
            ];
        }

        // END MARKER (red)
        if (!empty($row['gps_end_lat']) && !empty($row['gps_end_lng'])) {
            $markers[] = [
                'id' => 'end_' . $task_id,
                'type' => 'end',
                'task_id' => $task_id,
                'lat' => (float) $row['gps_end_lat'],
                'lng' => (float) $row['gps_end_lng'],
                'title' => $task_data['title'] . ' (' . __('End', 'newbase') . ')',
                'description' => $task_data['description'],
                'status' => $row['status'],
                'entity' => $task_data['entity_name'],
                'user' => $user_name,
                'date' => $row['date_end'],
                'icon_color' => 'red',
            ];

            $task_data['gps_end'] = [
                'lat' => (float) $row['gps_end_lat'],
                'lng' => (float) $row['gps_end_lng'],
            ];
        }

        // ROUTE (if both coordinates exist)
        if (
            !empty($row['gps_start_lat']) && !empty($row['gps_start_lng']) &&
            !empty($row['gps_end_lat']) && !empty($row['gps_end_lng'])
        ) {
            $routes[] = [
                'task_id' => $task_id,
                'coordinates' => [
                    [(float) $row['gps_start_lat'], (float) $row['gps_start_lng']],
                    [(float) $row['gps_end_lat'], (float) $row['gps_end_lng']],
                ],
                'color' => '#3388ff',
                'weight' => 3,
                'opacity' => 0.7,
                'distance' => $task_data['mileage'],
            ];
        }

        $tasks[] = $task_data;
    }

    // ===== CALCULATE BOUNDS =====
    $bounds = calculateBounds($markers);

    // ===== GET MAP CONFIGURATION =====
    $map_config = getMapConfig();

    // ===== STATISTICS =====
    $stats = [
        'total_tasks' => count($tasks),
        'total_markers' => count($markers),
        'total_routes' => count($routes),
        'tasks_with_start' => count(array_filter($tasks, fn($t) => isset($t['gps_start']))),
        'tasks_with_end' => count(array_filter($tasks, fn($t) => isset($t['gps_end']))),
        'total_mileage' => array_sum(array_column($tasks, 'mileage')),
    ];

    // ===== SUCCESS RESPONSE =====

    sendResponse(
        true,
        sprintf(
            __('Loaded %d markers from %d tasks', 'newbase'),
            count($markers),
            count($tasks)
        ),
        [
            'markers' => $markers,
            'routes' => $routes,
            'tasks' => $tasks,
            'bounds' => $bounds,
            'config' => $map_config,
            'stats' => $stats,
            'filters' => [
                'entities_id' => $entities_id,
                'users_id' => $users_id,
                'status' => $status,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'limit' => $limit,
            ],
        ],
        200
    );

    // Log success (only in debug mode to avoid log spam)
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Map data loaded: %d markers, %d routes, %d tasks (entity=%d, user=%d, status=%s)\n",
                count($markers),
                count($routes),
                count($tasks),
                $entities_id,
                $users_id,
                $status ?: 'all'
            )
        );
    }
} catch (Exception $e) {

    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in mapData.php (%s): %s\n",
            $method,
            $e->getMessage()
        )
    );

    $error_data = [];

    // Include error details only in debug mode
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        $error_data['error'] = $e->getMessage();
        $error_data['trace'] = $e->getTraceAsString();
    }

    sendResponse(
        false,
        __('An error occurred while loading map data', 'newbase'),
        $error_data,
        500
    );
}
