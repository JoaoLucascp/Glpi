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

use Toolbox;
use Html;
use CommonDBTM;
use Plugin;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Common - Base class with shared functionality for all Newbase entities
 *
 * Provides common methods for:
 * - URL generation (search, form)
 * - Data validation (CNPJ, phone, CEP)
 * - External API integration (Brasil API, ReceitaWS)
 * - Distance calculation for geolocation
 * - Brazilian data formatting (CNPJ, phone, CEP)
 *
 * @package GlpiPlugin\Newbase
 */
abstract class Common extends CommonDBTM
{
    /**
     * Rights management
     * @var string
     */
    public static $rightname = 'plugin_newbase';

    /**
     * Get type name for display
     *
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return static::class;
    }

    /**
     * Get database table name for this class
     *
     * Automatically generates table name based on class name:
     * GlpiPlugin\Newbase\Task -> glpi_plugin_newbase_tasks
     *
     * @param string|null $classname Class name (optional)
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        $classname ??= static::class;
        $class = explode('\\', $classname);
        $class = end($class);
        return 'glpi_plugin_newbase_' . strtolower($class) . 's';
    }

    /**
     * Get short type name without namespace
     *
     * @return string Type name
     */
    public static function getType(): string
    {
        $class = static::class;
        $parts = explode('\\', $class);
        return end($parts);
    }

    /**
     * Get search/list URL for this item type
     *
     * @param bool $full Full path or relative
     * @return string Search URL
     */
    public static function getSearchURL($full = true): string
    {
        $dir = Plugin::getWebDir('newbase', $full);
        $item = strtolower(static::getType());
        return "{$dir}/front/{$item}.php";
    }

    /**
     * Get form URL for this item type
     *
     * @param bool $full Full path or relative
     * @return string Form URL
     */
    public static function getFormURL($full = true): string
    {
        $dir = Plugin::getWebDir('newbase', $full);
        $item = strtolower(static::getType());
        return "{$dir}/front/{$item}.form.php";
    }

    /**
     * Redirect to list view
     *
     * @return void
     */
    public function redirectToList(): void
    {
        Html::redirect($this->getSearchURL());
    }

    // ========== VALIDATION METHODS ==========

    /**
     * Validate CNPJ format and checksum
     *
     * Validates Brazilian CNPJ (Cadastro Nacional da Pessoa Jurídica)
     * using the official checksum algorithm.
     *
     * @param string|null $cnpj CNPJ with or without formatting
     * @return bool True if valid, false otherwise
     */
    public static function validateCNPJ(string $cnpj): bool
    {
        // Remove non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Check length
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Check if all digits are the same (invalid)
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // Calculate first check digit
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : (11 - $remainder);

        if ((int) $cnpj[12] !== $digit1) {
            return false;
        }

        // Calculate second check digit
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : (11 - $remainder);

        return ((int) $cnpj[13] === $digit2);
    }

    /**
     * Calculate CNPJ check digit
     *
     * @param string $base            Base string (12 or 13 digits)
     * @param int    $multiplierStart Starting multiplier (5 or 6)
     * @return int Check digit
     */
    private static function calculateCNPJCheckDigit(string $base, int $multiplierStart): int
    {
        $sum = 0;
        $multiplier = $multiplierStart;

        for ($i = 0; $i < strlen($base); $i++) {
            $sum += (int)$base[$i] * $multiplier;
            $multiplier--;
            if ($multiplier < 2) {
                $multiplier = 9;
            }
        }

        $remainder = $sum % 11;
        return $remainder < 2 ? 0 : 11 - $remainder;
    }

    // ========== EXTERNAL API METHODS ==========

    /**
     * Search company data by CNPJ using Brasil API
     *
     * @param string|null $cnpj CNPJ without formatting
     * @return array|false Company data or false if not found
     */
    public static function searchCompanyByCNPJ(?string $cnpj): array|false
    {
        if ($cnpj === null || $cnpj === '') {
            return false;
        }

        // Remove non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Validate CNPJ first
        if (!self::validateCNPJ($cnpj)) {
            return false;
        }

        try {
            // Use Brasil API (free, no authentication required)
            $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT      => 'GLPI-Newbase/2.1.0',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false || $httpCode !== 200) {
                return false;
            }

            $data = json_decode($response, true);

            if (isset($data['status']) && $data['status'] === 400) {
                return false; // CNPJ not found
            }

            // Return standardized data
            return [
                'legal_name'   => $data['razao_social'] ?? '',
                'fantasy_name' => $data['nome_fantasia'] ?? '',
                'cnpj'         => $data['cnpj'] ?? $cnpj,
                'status'       => $data['descricao_situacao_cadastral'] ?? '',
                'opening_date' => $data['data_inicio_atividade'] ?? '',
                'email'        => $data['email'] ?? '',
                'phone'        => $data['ddd_telefone_1'] ?? '',
            ];
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error searching CNPJ {$cnpj}: " . $e->getMessage() . "\n"
            );
            return false;
        }
    }

    /**
     * Search for additional company data via ReceitaWS API
     *
     * @param string|null $cnpj CNPJ number
     * @return array Company data with email, phone, and website
     */
    public static function searchCompanyAdditionalData(?string $cnpj): array
    {
        $result = [
            'email'   => '',
            'phone'   => '',
            'website' => '',
        ];

        if ($cnpj === null || $cnpj === '') {
            return $result;
        }

        // Remove formatting
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        try {
            // ReceitaWS API - Brazilian public data
            $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 8,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT      => 'GLPI-Newbase/2.1.0',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false || $httpCode !== 200) {
                return $result;
            }

            $data = json_decode($response, true);

            // Check if request was successful
            if (isset($data['status']) && $data['status'] === 'OK') {
                $result['email']   = $data['email'] ?? '';
                $result['phone']   = $data['telefone'] ?? '';
                $result['website'] = $data['site'] ?? '';

                Toolbox::logInFile(
                    'newbase_plugin',
                    "ReceitaWS API found data for CNPJ {$cnpj}\n"
                );
            }
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ReceitaWS API Error for CNPJ {$cnpj}: " . $e->getMessage() . "\n"
            );
        }

        return $result;
    }

    // ========== FORMATTING METHODS ==========

    /**
     * Format phone number to Brazilian format
     *
     * Converts phone to (XX) XXXXX-XXXX or (XX) XXXX-XXXX
     *
     * @param string|null $phone Phone number
     * @return string Formatted phone number
     */
    public static function formatPhone(?string $phone = ''): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Format as (XX) XXXXX-XXXX or (XX) XXXX-XXXX
        if (strlen($phone) === 11) {
            // Cell phone: (XX) XXXXX-XXXX
            return sprintf(
                '(%s) %s-%s',
                substr($phone, 0, 2),
                substr($phone, 2, 5),
                substr($phone, 7)
            );
        } elseif (strlen($phone) === 10) {
            // Landline: (XX) XXXX-XXXX
            return sprintf(
                '(%s) %s-%s',
                substr($phone, 0, 2),
                substr($phone, 2, 4),
                substr($phone, 6)
            );
        }

        return $phone;
    }

    /**
     * Format CEP (Brazilian ZIP code)
     *
     * Converts CEP to XXXXX-XXX format
     *
     * @param string|null $cep CEP to format
     * @return string Formatted CEP (XXXXX-XXX)
     */
    public static function formatCEP(?string $cep): string
    {
        if ($cep === null || $cep === '') {
            return '';
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        }

        return $cep;
    }

    /**
     * Format CNPJ to Brazilian format
     *
     * Converts CNPJ to XX.XXX.XXX/XXXX-XX format
     *
     * @param string|null $cnpj CNPJ to format
     * @return string Formatted CNPJ (XX.XXX.XXX/XXXX-XX)
     */
    public static function formatCNPJ(?string $cnpj): string
    {
        if ($cnpj === null || $cnpj === '') {
            return '';
        }

        // Remove all non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Format as XX.XXX.XXX/XXXX-XX
        if (strlen($cnpj) === 14) {
            return sprintf(
                '%s.%s.%s/%s-%s',
                substr($cnpj, 0, 2),
                substr($cnpj, 2, 3),
                substr($cnpj, 5, 3),
                substr($cnpj, 8, 4),
                substr($cnpj, 12, 2)
            );
        }

        return $cnpj;
    }

    /**
     * Validate CEP format (Brazilian ZIP code)
     *
     * @param string $cep CEP with or without formatting
     * @return bool True if valid, false otherwise
     */
    public static function validateCEP(string $cep): bool
    {
        if (empty($cep)) {
            return false;
        }

        // Remove non-numeric characters
        $cep = preg_replace('/[^0-9]/', '', $cep);

        // CEP must have exactly 8 digits
        return strlen($cep) === 8 && is_numeric($cep);
    }

    /**
     * Validate email format
     *
     * @param string $email Email address
     * @return bool True if valid, false otherwise
     */
    public static function validateEmail(string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone format (Brazilian)
     *
     * Accepts phone numbers with 10 or 11 digits (after removing non-numeric chars)
     *
     * @param string $phone Phone number
     * @return bool True if valid, false otherwise
     */
    public static function validatePhone(string $phone): bool
    {
        if (empty($phone)) {
            return false;
        }

        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Valid Brazilian phone has 10 (landline) or 11 (mobile) digits
        return in_array(strlen($phone), [10, 11], true) && is_numeric($phone);
    }

    /**
     * Validate GPS coordinates
     *
     * @param float $latitude  Latitude (-90 to 90)
     * @param float $longitude Longitude (-180 to 180)
     * @return bool True if valid, false otherwise
     */
    public static function validateCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude >= -90
            && $latitude <= 90
            && $longitude >= -180
            && $longitude <= 180;
    }

    /**
     * Search address by CEP using ViaCEP API
     *
     * Queries the ViaCEP public API to fetch address information from a CEP code.
     *
     * @param string $cep CEP code with or without formatting
     * @return array|false Address data or false if not found
     */
    public static function fetchAddressByCEP(string $cep): array|false
    {
        if (!self::validateCEP($cep)) {
            return false;
        }

        // Remove formatting
        $cep = preg_replace('/[^0-9]/', '', $cep);

        try {
            // ViaCEP is a free Brazilian CEP lookup API
            $url = "https://viacep.com.br/ws/{$cep}/json/";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT      => 'GLPI-Newbase/2.1.0',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false || $httpCode !== 200) {
                return false;
            }

            $data = json_decode($response, true);

            // Check if response contains error
            if (isset($data['erro']) && $data['erro'] === true) {
                return false;
            }

            // Return standardized data
            return [
                'cep'          => $data['cep'] ?? $cep,
                'street'       => $data['logradouro'] ?? '',
                'neighborhood' => $data['bairro'] ?? '',
                'city'         => $data['localidade'] ?? '',
                'state'        => $data['uf'] ?? '',
                'complement'   => $data['complemento'] ?? '',
            ];
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error fetching CEP {$cep}: " . $e->getMessage() . "\n"
            );
            return false;
        }
    }

    /**
     * Fetch GPS coordinates (latitude/longitude) by CEP
     *
     * Uses OpenStreetMap Nominatim service to convert address to coordinates.
     * Falls back to a default city coordinate if specific address is not found.
     *
     * @param string $cep CEP code
     * @param string $city City name (optional, for context)
     * @return array|false Array with 'latitude' and 'longitude' keys, or false on error
     */
    public static function fetchCoordinatesByCEP(string $cep, string $city = ''): array|false
    {
        if (!self::validateCEP($cep)) {
            return false;
        }

        // First try to get address details
        $address = self::fetchAddressByCEP($cep);

        if (!$address) {
            return false;
        }

        // Construct full address for geocoding
        $fullAddress = sprintf(
            '%s, %s, %s, Brazil',
            $address['street'] ?? '',
            $address['city'] ?? $city,
            $address['state'] ?? ''
        );

        try {
            // Using Nominatim (OpenStreetMap) - free geolocation service
            $url = 'https://nominatim.openstreetmap.org/search';
            $params = [
                'q'      => trim($fullAddress),
                'format' => 'json',
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url . '?' . http_build_query($params),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT      => 'GLPI-Newbase/2.1.0',
                CURLOPT_HTTPHEADER     => [
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false || $httpCode !== 200) {
                return false;
            }

            $data = json_decode($response, true);

            if (empty($data) || !is_array($data)) {
                return false;
            }

            $first = $data[0];

            return [
                'latitude'  => (float) $first['lat'] ?? 0.0,
                'longitude' => (float) $first['lon'] ?? 0.0,
            ];
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error fetching coordinates for CEP {$cep}: " . $e->getMessage() . "\n"
            );
            return false;
        }
    }

    // ========== GEOLOCATION METHODS ==========

    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     *
     * Uses the Haversine formula to calculate the great-circle distance
     * between two points on a sphere given their longitudes and latitudes.
     *
     * @param float $lat1 Latitude of first point (in degrees)
     * @param float $lng1 Longitude of first point (in degrees)
     * @param float $lat2 Latitude of second point (in degrees)
     * @param float $lng2 Longitude of second point (in degrees)
     * @return float Distance in kilometers (rounded to 2 decimals)
     */
    public static function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        // Earth radius in kilometers
        $earthRadius = 6371.0;

        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        // Haversine formula
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        // Round to 2 decimal places
        return round($distance, 2);
    }
}
