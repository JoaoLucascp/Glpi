<?php

/**
* AddressHandler - AJAX handler for address lookup by CEP
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/

namespace GlpiPlugin\Newbase;

/**
* Undocumented class
*/
class AddressHandler
{
    /**
     * Handles the AJAX request to search for an address by CEP.
     *
     * @return array The response data to be encoded as JSON.
     */
    public function handleSearch(): array
    {
        // Modern input filtering - using FILTER_UNSAFE_RAW
        $cep = filter_input(INPUT_POST, 'cep', FILTER_UNSAFE_RAW);

        if ($cep === false || $cep === null || empty(trim($cep))) {
            return [
                'success' => false,
                'message' => __('CEP is required', 'newbase'),
            ];
        }

        // Remove formatting - keep only numbers
        $cep = preg_replace('/[^0-9]/', '', trim($cep));

        // Validate CEP length
        if (strlen($cep) !== 8) {
            return [
                'success' => false,
                'message' => __('Invalid CEP length', 'newbase'),
            ];
        }

        // Search address via ViaCEP API
        $addressData = Common::searchAddressByCEP($cep);

        if ($addressData === null) {
            \Toolbox::logInFile('newbase_plugin', "CEP search failed for: $cep\n");
            return [
                'success' => false,
                'message' => __('Address not found or API error', 'newbase'),
            ];
        }

        // Success response
        $response = [
            'success' => true,
            'data' => [
                'street' => $addressData['street'] ?? '',
                'neighborhood' => $addressData['neighborhood'] ?? '',
                'city' => $addressData['city'] ?? '',
                'state' => $addressData['state'] ?? '',
                'latitude' => $addressData['latitude'] ?? '',
                'longitude' => $addressData['longitude'] ?? '',
            ],
            'message' => __('Address loaded successfully', 'newbase'),
        ];

        \Toolbox::logInFile('newbase_plugin', "CEP search successful for: $cep\n");
        return $response;
    }
}
