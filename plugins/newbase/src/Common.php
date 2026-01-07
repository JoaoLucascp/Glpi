<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use GlpiPlugin\Newbase\Config as PluginConfig;
use Toolbox;
use Exception;

/**
 * Common utility functions for Newbase Plugin
 *
 * Provides shared utility functions for the plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class Common
{
    /**
     * Calculate distance between two coordinates using Haversine formula
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lng1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lng2 Longitude of point 2
     * @return float Distance in kilometers
     */
    public static function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Search company by CNPJ via external API with fallback
     *
     * @param string $cnpj CNPJ without formatting
     * @return array|null Company data or null on error
     */
    public static function searchCompanyByCNPJ(string $cnpj): ?array
    {
        // ✅ CORRIGIDO - usar PluginConfig ao invés de Config
        if (!PluginConfig::isCNPJApiEnabled()) {
            return null;
        }

        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return null;
        }

        $companyData = [
            'legal_name' => '',
            'fantasy_name' => '',
            'email' => '',
            'phone' => ''
        ];

        // 1. Try BrasilAPI (first source)
        $brasilApiUrl = "https://brasilapi.com.br/api/cnpj/v1/" . $cnpj;
        $data = self::fetchJson($brasilApiUrl);

        if ($data) {
            $companyData['legal_name'] = $data['razao_social'] ?? $data['nome'] ?? '';
            $companyData['fantasy_name'] = $data['nome_fantasia'] ?? $data['fantasia'] ?? '';
            $companyData['email'] = $data['email'] ?? '';
            $companyData['phone'] = $data['telefone'] ?? $data['ddd_telefone_1'] ?? '';
        }

        // 2. Try ReceitaWS if any important field is missing (especially email)
        if (empty($companyData['email']) || empty($companyData['legal_name'])) {
            $receitaWsUrl = "https://receitaws.com.br/v1/cnpj/" . $cnpj;
            $data = self::fetchJson($receitaWsUrl);

            if ($data && (!isset($data['status']) || ($data['status'] ?? '') !== 'ERROR')) {
                if (empty($companyData['legal_name'])) {
                    $companyData['legal_name'] = $data['nome'] ?? '';
                }
                if (empty($companyData['fantasy_name'])) {
                    $companyData['fantasy_name'] = $data['fantasia'] ?? '';
                }
                if (empty($companyData['email'])) {
                    $companyData['email'] = $data['email'] ?? '';
                }
                if (empty($companyData['phone'])) {
                    $companyData['phone'] = $data['telefone'] ?? '';
                }
            }
        }

        // 3. Try Minha Receita as third fallback if email is still empty
        if (empty($companyData['email'])) {
            $minhaReceitaUrl = "https://minhareceita.org/" . $cnpj;
            $data = self::fetchJson($minhaReceitaUrl);

            if ($data && !isset($data['error'])) {
                if (empty($companyData['email'])) {
                    $companyData['email'] = $data['email'] ?? '';
                }
                if (empty($companyData['phone']) && !empty($data['telefone'])) {
                    $companyData['phone'] = $data['telefone'];
                }
            }
        }

        // Return null if we couldn't get at least the legal name
        if (empty($companyData['legal_name'])) {
            return null;
        }

        return $companyData;
    }

    /**
     * Fetch JSON from URL using cURL
     *
     * @param string $url URL to fetch
     * @return array|null Decoded JSON or null on error
     */
    private static function fetchJson(string $url): ?array
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 && $response) {
                return json_decode($response, true);
            }
        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "Fetch error for $url: " . $e->getMessage() . "\n");
        }
        return null;
    }

    /**
     * Search address by CEP via ViaCEP API
     *
     * @param string $cep CEP without formatting
     * @return array|null Address data or null on error
     */
    public static function searchAddressByCEP(string $cep): ?array
    {
        // ✅ CORRIGIDO - usar PluginConfig
        if (!PluginConfig::isCEPApiEnabled()) {
            return null;
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }

        try {
            // ✅ CORRIGIDO - usar PluginConfig
            $api_url = PluginConfig::getCEPApiUrl() . $cep . '/json/';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

            // SSL options - more flexible for local development
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            $response = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($curl_errno !== 0) {
                Toolbox::logInFile('newbase_plugin', "CEP API curl error ($curl_errno)\n");
                return null;
            }

            if ($http_code !== 200 || !$response) {
                Toolbox::logInFile('newbase_plugin', "CEP API error: HTTP $http_code\n");
                return null;
            }

            $data = json_decode($response, true);

            if (!$data || isset($data['erro'])) {
                return null;
            }

            // Get coordinates from CEP (approximate)
            $coordinates = self::getCoordinatesFromAddress(
                $data['logradouro'] ?? '',
                $data['bairro'] ?? '',
                $data['localidade'] ?? '',
                $data['uf'] ?? ''
            );

            return [
                'street' => $data['logradouro'] ?? '',
                'neighborhood' => $data['bairro'] ?? '',
                'city' => $data['localidade'] ?? '',
                'state' => $data['uf'] ?? '',
                'latitude' => $coordinates['lat'] ?? null,
                'longitude' => $coordinates['lng'] ?? null
            ];

        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "ERROR in searchAddressByCEP(): " . $e->getMessage() . "\n");
            return null;
        }
    }

    /**
     * Get coordinates from address (geocoding)
     * Using Nominatim (OpenStreetMap) - free and no API key required
     *
     * @param string $street Street name
     * @param string $neighborhood Neighborhood
     * @param string $city City
     * @param string $state State
     * @return array|null Coordinates or null
     */
    public static function getCoordinatesFromAddress(string $street, string $neighborhood, string $city, string $state): ?array
    {
        // ✅ CORRIGIDO - usar PluginConfig
        if (!PluginConfig::isGeolocationEnabled()) {
            return null;
        }

        try {
            $address = implode(', ', array_filter([$street, $neighborhood, $city, $state, 'Brasil']));
            $address = urlencode($address);

            $api_url = "https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

            // SSL options - more flexible for local development
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            $response = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            curl_close($ch);

            if ($curl_errno !== 0) {
                Toolbox::logInFile('newbase_plugin', "Nominatim API curl error ($curl_errno)\n");
                return null;
            }

            if (!$response) {
                return null;
            }

            $data = json_decode($response, true);

            if (!$data || count($data) === 0) {
                return null;
            }

            return [
                'lat' => floatval($data[0]['lat']),
                'lng' => floatval($data[0]['lon'])
            ];

        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "ERROR in getCoordinatesFromAddress(): " . $e->getMessage() . "\n");
            return null;
        }
    }

    /**
     * Format CNPJ for display
     *
     * @param string $cnpj CNPJ without formatting
     * @return string Formatted CNPJ
     */
    public static function formatCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $cnpj
        );
    }

    /**
     * Format CEP for display
     *
     * @param string $cep CEP without formatting
     * @return string Formatted CEP
     */
    public static function formatCEP(string $cep): string
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            return $cep;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    /**
     * Format phone for display
     *
     * @param string $phone Phone without formatting
     * @return string Formatted phone
     */
    public static function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 11) {
            // Mobile: (XX) XXXXX-XXXX
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            // Landline: (XX) XXXX-XXXX
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }

        return $phone;
    }

    /**
     * Validate CNPJ
     *
     * @param string $cnpj CNPJ without formatting
     * @return bool
     */
    public static function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        // Validate first check digit
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if (intval($cnpj[12]) !== $digit1) {
            return false;
        }

        // Validate second check digit
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (intval($cnpj[13]) === $digit2);
    }
}
