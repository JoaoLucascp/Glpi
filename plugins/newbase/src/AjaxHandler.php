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

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use Session;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * AjaxHandler - Centralized AJAX response and security utilities
 *
 * Provides common functionality for all AJAX endpoints:
 * - Standardized JSON responses
 * - CSRF token validation
 * - Permission checking
 * - HTTP client requests via cURL
 * - Input validation
 *
 * @package GlpiPlugin\Newbase
 */
class AjaxHandler
{
    /**
     * Send JSON response and exit
     *
     * @param bool   $success    Success status
     * @param string $message    Response message
     * @param array  $data       Additional data to include in response
     * @param int    $http_code  HTTP status code (default: 200)
     *
     * @return void (exits execution)
     */
    public static function sendResponse(
        bool $success,
        string $message,
        array $data = [],
        int $http_code = 200
    ): void {
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
     * Check and validate CSRF token from request
     *
     * Supports both:
     * - Header: X-Glpi-Csrf-Token (standard for AJAX in GLPI 10.0.20+)
     * - POST data: _glpi_csrf_token (fallback)
     *
     * @return bool True if CSRF token is valid, false otherwise
     */
    public static function checkCSRFToken(): bool
    {
        try {
            $token = $_SERVER['HTTP_X_GLPI_CSRF_TOKEN'] ?? $_POST['_glpi_csrf_token'] ?? $_GET['_glpi_csrf_token'] ?? '';

            if (empty($token)) {
                return false;
            }

            Session::checkCSRF(['_glpi_csrf_token' => $token]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check user permissions for an action
     *
     * @param string $right Permission to check (e.g., 'plugin_newbase', 'READ')
     *
     * @return bool True if user has permission, false otherwise
     */
    public static function checkPermissions(string $right): bool
    {
        if (empty($right)) {
            return false;
        }

        if (!defined('READ') || !defined('UPDATE')) {
            return false;
        }

        // Check for specific permissions based on right name
        if ($right === 'plugin_newbase') {
            return (bool) Session::haveRight('plugin_newbase', READ);
        }

        return (bool) Session::haveRight($right, READ);
    }

    /**
     * Validate user authentication and CSRF token
     *
     * Combines checkCSRFToken() and user session check
     *
     * @return bool True if authenticated and CSRF valid, false otherwise
     */
    public static function validateRequest(): bool
    {
        // Check if user is authenticated
        if (!Session::getLoginUserID()) {
            return false;
        }

        // Check CSRF token
        return self::checkCSRFToken();
    }

    /**
     * Execute HTTP request via cURL
     *
     * Provides standardized cURL configuration with timeouts,
     * SSL verification, and error logging
     *
     * @param string        $url            Target URL
     * @param array         $options        cURL options (CURLOPT_*)
     * @param string|null   $method         HTTP method (GET, POST, default: GET)
     * @param int           $timeout        Request timeout in seconds
     * @param int           $connect_timeout Connection timeout in seconds
     *
     * @return string|false Response body on success, false on failure
     */
    public static function fetchCurl(
        string $url,
        array $options = [],
        ?string $method = 'GET',
        int $timeout = 15,
        int $connect_timeout = 10
    ): string|false {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $ch = curl_init();

        // Merge default options with user-provided options
        $curl_options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => $connect_timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'GLPI-Newbase-Plugin/2.1.0',
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Accept-Encoding: gzip, deflate',
            ],
        ];

        // Set HTTP method
        if ($method === 'POST') {
            $curl_options[CURLOPT_POST] = true;
        }

        // Merge with provided options (user options override defaults)
        $curl_options = array_replace($curl_options, $options);

        curl_setopt_array($ch, $curl_options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Toolbox::logInFile(
                'newbase_plugin',
                "cURL Error for {$url}: {$error} (HTTP {$httpCode})\n"
            );
            return false;
        }

        if ($httpCode === 200 && $response !== false) {
            return $response;
        }

        Toolbox::logInFile(
            'newbase_plugin',
            "HTTP Error {$httpCode} for {$url}\n"
        );
        return false;
    }

    /**
     * Validate input against rules
     *
     * Simple validation helper that checks common input patterns
     *
     * @param array $input Input data to validate
     * @param array $rules Validation rules with format ['field' => 'type']
     *                      Supported types: 'string', 'int', 'email', 'url', 'cep', 'cnpj'
     *
     * @return array|bool Cleaned input array on success, false on validation failure
     */
    public static function validateInput(array $input, array $rules): array|bool
    {
        if (empty($input) || empty($rules)) {
            return false;
        }

        $cleaned = [];

        foreach ($rules as $field => $type) {
            if (!isset($input[$field])) {
                continue;
            }

            $value = $input[$field];

            switch ($type) {
                case 'string':
                    $cleaned[$field] = (string) $value;
                    break;

                case 'int':
                    $cleaned[$field] = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                    if ($cleaned[$field] === null) {
                        return false;
                    }
                    break;

                case 'email':
                    $cleaned[$field] = filter_var($value, FILTER_VALIDATE_EMAIL);
                    if ($cleaned[$field] === false) {
                        return false;
                    }
                    break;

                case 'url':
                    $cleaned[$field] = filter_var($value, FILTER_VALIDATE_URL);
                    if ($cleaned[$field] === false) {
                        return false;
                    }
                    break;

                case 'cep':
                    // CEP validation: 12345-678 or 12345678
                    $cleaned[$field] = preg_replace('/[^0-9]/', '', (string) $value);
                    if (strlen($cleaned[$field]) !== 8) {
                        return false;
                    }
                    break;

                case 'cnpj':
                    // CNPJ validation: remove non-digits
                    $cleaned[$field] = preg_replace('/[^0-9]/', '', (string) $value);
                    if (strlen($cleaned[$field]) !== 14) {
                        return false;
                    }
                    break;

                default:
                    $cleaned[$field] = $value;
                    break;
            }
        }

        return $cleaned;
    }

    /**
     * Set standard security headers for AJAX responses
     *
     * Should be called at the beginning of AJAX endpoint scripts
     *
     * @return void
     */
    public static function setSecurityHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
    }
}
