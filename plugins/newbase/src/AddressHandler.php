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

use GlpiPlugin\Newbase\Common; // Importante: usar a classe Common

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * AddressHandler - Facade for Address operations
 *
 * This class now acts as a wrapper/facade for address operations,
 * delegating the core logic to the Common class to avoid code duplication (DRY).
 *
 * @package GlpiPlugin\Newbase
 */
class AddressHandler
{
    /**
     * Search address by CEP (Brazilian ZIP code)
     *
     * Delegates to Common::fetchAddressByCEP to use centralized logic and caching.
     *
     * @param string|null $cep CEP with or without formatting
     * @return array Standardized response array
     */
    public static function searchByCEP(?string $cep): array
    {
        // 1. Validação básica de entrada
        if (empty($cep)) {
            return [
                'success' => false,
                'message' => __('CEP is required', 'newbase'),
                'data'    => null,
            ];
        }

        // 2. Chama a função centralizada no Common (com cache ativado por padrão)
        $addressData = Common::fetchAddressByCEP($cep);

        // 3. Verifica se falhou
        if ($addressData === false) {
            return [
                'success' => false,
                'message' => __('Address not found or API temporarily unavailable', 'newbase'),
                'data'    => null,
            ];
        }

        // 4. Sucesso - Retorna estrutura padronizada para o Frontend
        return [
            'success' => true,
            'message' => __('Address loaded successfully', 'newbase'),
            'data'    => [
                'cep'          => $addressData['cep'],
                'street'       => $addressData['street'],
                'complement'   => $addressData['complement'],
                'neighborhood' => $addressData['neighborhood'],
                'city'         => $addressData['city'],
                'state'        => $addressData['state'],
                'ibge_code'    => $addressData['ibge_code'] ?? '',
            ],
        ];
    }

    /**
     * Format CEP for display (XXXXX-XXX)
     * Delegates to Common::formatCEP
     */
    public static function formatCEP(string $cep): string
    {
        return Common::formatCEP($cep);
    }

    /**
     * Validate CEP format
     * Delegates to Common::validateCEP
     */
    public static function validateCEP(string $cep): bool
    {
        return Common::validateCEP($cep);
    }

    /**
     * Get CEP pattern regex for validation
     */
    public static function getCEPPattern(): string
    {
        return '[0-9]{5}-?[0-9]{3}';
    }
}
