<?php

/**
* AddressHandler Class - CEP search handler
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

namespace GlpiPlugin\Newbase;

use Toolbox;

/**
* AddressHandler - Handles CEP (Brazilian ZIP code) searches
* Validates input, calls ViaCEP API, and returns formatted response
*/
class AddressHandler
{

/**
* Search address by CEP (Brazilian ZIP code)
* @param string|null $cep CEP with or without formatting
* @return array Response array with success status and data
*/
    public static function searchByCEP(?string $cep): array
    {

        // VALIDAÇÃO DE ENTRADA
        if (empty($cep)) {
            return [
                'success' => false,
                'message' => __('CEP is required', 'newbase'),
                'data' => null,
            ];
        }

        // Remove formatting - keep only numbers
        $cep = preg_replace('/[^0-9]/', '', trim($cep));

        // Validate CEP length (must be 8 digits)
        if (strlen($cep) !== 8) {
            return [
                'success' => false,
                'message' => __('Invalid CEP: must have 8 digits', 'newbase'),
                'data' => null,
            ];
        }

        // Validate CEP pattern (not all zeros or sequential)
        if (preg_match('/^0+$/', $cep) || preg_match('/^(\d)\1{7}$/', $cep)) {
            return [
                'success' => false,
                'message' => __('Invalid CEP pattern', 'newbase'),
                'data' => null,
            ];
        }

        // BUSCAR ENDEREÇO VIA API
        $addressData = self::callViaCEPAPI($cep);

        if ($addressData === null) {
            Toolbox::logInFile(
                'newbase_plugin',
                "CEP search failed for: $cep (API error or not found)\n"
            );

            return [
                'success' => false,
                'message' => __('Address not found or API temporarily unavailable', 'newbase'),
                'data' => null,
            ];
        }

        // SUCESSO - RETORNAR DADOS
        Toolbox::logInFile(
            'newbase_plugin',
            "CEP search successful for: $cep\n"
        );

        return [
            'success' => true,
            'message' => __('Address loaded successfully', 'newbase'),
            'data' => [
                'cep' => $cep,
                'street' => $addressData['logradouro'] ?? '',
                'complement' => $addressData['complemento'] ?? '',
                'neighborhood' => $addressData['bairro'] ?? '',
                'city' => $addressData['localidade'] ?? '',
                'state' => $addressData['uf'] ?? '',
                'ibge_code' => $addressData['ibge'] ?? '',
            ],
        ];
    }

/**
* Call ViaCEP API to get address data
* @param string $cep CEP without formatting (8 digits)
* @return array|null Address data or null if error/not found
*/
    private static function callViaCEPAPI(string $cep): ?array
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        try {

            // FAZER REQUISIÇÃO HTTP
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'GLPI Newbase Plugin/2.0',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // VERIFICAR RESPOSTA HTTP
            if ($response === false) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "CURL error for CEP {$cep}: {$error}\n"
                );
                return null;
            }

            if ($httpCode !== 200) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "HTTP error for CEP {$cep}: HTTP {$httpCode}\n"
                );
                return null;
            }

            // DECODIFICAR JSON
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "JSON decode error for CEP {$cep}: " . json_last_error_msg() . "\n"
                );
                return null;
            }

            // VERIFICAR SE CEP FOI ENCONTRADO
            if (isset($data['erro']) && $data['erro'] === true) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "CEP not found: {$cep}\n"
                );
                return null;
            }

            return $data;

        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Exception in ViaCEP API for CEP {$cep}: " . $e->getMessage() . "\n"
            );
            return null;
        }
    }

/**
* Format CEP for display (XXXXX-XXX)
* @param string $cep CEP without formatting
* @return string Formatted CEP
*/
    public static function formatCEP(string $cep): string
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        }

        return $cep;
    }

/**
* Validate CEP format
* @param string $cep CEP with or without formatting
* @return bool True if valid, false otherwise
*/
    public static function validateCEP(string $cep): bool
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        // Check length
        if (strlen($cep) !== 8) {
            return false;
        }

        // Check pattern (not all zeros or sequential)
        if (preg_match('/^0+$/', $cep) || preg_match('/^(\d)\1{7}$/', $cep)) {
            return false;
        }

        return true;
    }
}
