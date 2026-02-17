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
 * AJAX Endpoint - Mileage Calculation
 *
 * Calculates distance between two GPS coordinates using Haversine formula.
 *
 * POST: Calculate distance
 * - Receives two coordinate pairs (lat/lng)
 * - Validates coordinate ranges
 * - Returns distance in kilometers
 *
 * Formula: Haversine (great-circle distance)
 * - Accounts for Earth's curvature
 * - Precision: ~0.5% (suitable for field tasks)
 * - Earth radius: 6371 km
 *
 * Used by Task forms to automatically calculate mileage from GPS data.
 */

// Load GLPI core
include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Common;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Config;

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
        $response = array_merge($response, $data);
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Validate GPS coordinate
 * @param float $lat Latitude
 * @param float $lng Longitude
 * @return bool Valid coordinate
 */
function validateCoordinate(float $lat, float $lng): bool
{
    // Validate latitude range (-90 to 90)
    if ($lat < -90 || $lat > 90) {
        return false;
    }

    // Validate longitude range (-180 to 180)
    if ($lng < -180 || $lng > 180) {
        return false;
    }

    return true;
}

// ===== AUTHENTICATION CHECK =====
if (!Session::getLoginUserID()) {
    sendResponse(false, __('Authentication required'), [], 401);
}

// ===== CHECK PERMISSIONS =====
if (!Task::canView()) {
    Toolbox::logInFile(
        'newbase_plugin',
        sprintf("User %d tried to calculate mileage without permission\n", Session::getLoginUserID())
    );
    sendResponse(false, __('You do not have permission to perform this action', 'newbase'), [], 403);
}

// ===== GET REQUEST METHOD =====
$method = $_SERVER['REQUEST_METHOD'];

// ===== GET REQUEST DATA =====
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

// Handle non-JSON requests (fallback to POST/GET data)
if ($input === null) {
    $input = ($method === 'POST') ? $_POST : $_GET;
}

if (!is_array($input)) {
    $input = [];
}

// ===== CSRF TOKEN VALIDATION (for POST) =====
if ($method === 'POST') {
    $csrf_token = $_SERVER['HTTP_X_GLPI_CSRF_TOKEN'] ?? $input['_glpi_csrf_token'] ?? '';

    if (empty($csrf_token)) {
        Toolbox::logInFile('newbase_plugin', "AJAX mileage calculation: CSRF token missing\n");
        sendResponse(false, __('CSRF token is required', 'newbase'), [], 403);
    }

    // Validate CSRF token
    try {
        Session::checkCSRF(['_glpi_csrf_token' => $csrf_token]);
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "AJAX mileage calculation: Invalid CSRF token\n");
        sendResponse(false, __('Invalid or expired security token', 'newbase'), [], 403);
    }
}

// ===== CHECK IF FEATURE IS ENABLED =====
$config = Config::getConfig();
$calculate_mileage = $config['calculate_mileage'] ?? 1;

if (!$calculate_mileage) {
    sendResponse(false, __('Automatic mileage calculation is disabled', 'newbase'), [], 403);
}

// ===== PROCESS REQUEST =====

try {
    // Only allow POST and GET methods
    if (!in_array($method, ['POST', 'GET'], true)) {
        sendResponse(
            false,
            sprintf(__('Method %s not allowed', 'newbase'), $method),
            [],
            405
        );
    }

    // ===== GET COORDINATES =====
    $lat1 = $input['lat1'] ?? $input['start_lat'] ?? null;
    $lng1 = $input['lng1'] ?? $input['start_lng'] ?? null;
    $lat2 = $input['lat2'] ?? $input['end_lat'] ?? null;
    $lng2 = $input['lng2'] ?? $input['end_lng'] ?? null;

    // Validate all coordinates are present
    if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
        sendResponse(
            false,
            __('All coordinates are required (lat1, lng1, lat2, lng2)', 'newbase'),
            [
                'required_fields' => ['lat1', 'lng1', 'lat2', 'lng2'],
                'received' => [
                    'lat1' => $lat1 !== null,
                    'lng1' => $lng1 !== null,
                    'lat2' => $lat2 !== null,
                    'lng2' => $lng2 !== null,
                ],
            ],
            400
        );
    }

    // Convert to float
    $lat1 = (float) $lat1;
    $lng1 = (float) $lng1;
    $lat2 = (float) $lat2;
    $lng2 = (float) $lng2;

    // Validate start coordinate
    if (!validateCoordinate($lat1, $lng1)) {
        sendResponse(
            false,
            __('Invalid start coordinate', 'newbase'),
            [
                'lat1' => $lat1,
                'lng1' => $lng1,
                'valid_range' => 'Latitude: -90 to 90, Longitude: -180 to 180',
            ],
            400
        );
    }

    // Validate end coordinate
    if (!validateCoordinate($lat2, $lng2)) {
        sendResponse(
            false,
            __('Invalid end coordinate', 'newbase'),
            [
                'lat2' => $lat2,
                'lng2' => $lng2,
                'valid_range' => 'Latitude: -90 to 90, Longitude: -180 to 180',
            ],
            400
        );
    }

    // Check if coordinates are identical (distance would be 0)
    if ($lat1 === $lat2 && $lng1 === $lng2) {
        sendResponse(
            true,
            __('Coordinates are identical', 'newbase'),
            [
                'distance' => 0.0,
                'distance_km' => 0.0,
                'distance_m' => 0,
                'formatted' => '0,00 km',
            ],
            200
        );
    }

    // Calculate distance using Haversine formula
    if (!class_exists('GlpiPlugin\\Newbase\\Common')) {
        throw new Exception(__('Common class not found', 'newbase'));
    }

    $distance = Common::calculateDistance($lat1, $lng1, $lat2, $lng2);

    // Validate result
    if (!is_numeric($distance) || $distance < 0) {
        throw new Exception(__('Invalid distance calculation result', 'newbase'));
    }

    // Success response
    sendResponse(
        true,
        __('Mileage calculated successfully', 'newbase'),
        [
            'distance' => round($distance, 2),
            'distance_km' => round($distance, 2),
            'distance_m' => (int) round($distance * 1000),
            'formatted' => number_format($distance, 2, ',', '.') . ' km',
            'coordinates' => [
                'start' => ['lat' => $lat1, 'lng' => $lng1],
                'end' => ['lat' => $lat2, 'lng' => $lng2],
            ],
        ],
        200
    );

    // Log success (optional, only in debug mode to avoid log spam)
    if (defined('GLPI_DEBUG') && GLPI_DEBUG) {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Mileage calculated: %.2f km from (%.6f, %.6f) to (%.6f, %.6f) by user %d\n",
                $distance,
                $lat1,
                $lng1,
                $lat2,
                $lng2,
                Session::getLoginUserID()
            )
        );
    }
} catch (Exception $e) {
    // ===== ERROR HANDLING =====

    Toolbox::logInFile(
        'newbase_plugin',
        sprintf(
            "ERROR in calculateMileage.php (%s): %s\n",
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
        __('An error occurred while calculating mileage', 'newbase'),
        $error_data,
        500
    );
}
