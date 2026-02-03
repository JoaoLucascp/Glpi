<?php

/**
 * Common Class - Base class for all Newbase entities
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @copyright 2026 João Lucas
 * @license   GPLv2+
 * @version   2.1.0
 */

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use Session;
use Toolbox;
use Html;
use CommonGLPI;
use CommonDBTM;
use Plugin;

/**
 * Common - Base class with shared functionality for all Newbase entities
 *
 * Provides common methods for:
 * - URL generation (search, form)
 * - Form display (header, buttons)
 * - Data validation (CNPJ, phone, CEP)
 * - External API integration (Brasil API, ReceitaWS)
 * - Distance calculation for geolocation
 *
 * @method int getID() Get item ID
 * @method bool canView() Check if user can view
 * @method bool canCreate() Check if user can create
 * @method bool canUpdate() Check if user can update
 * @method bool canDelete() Check if user can delete
 * @method bool canPurge() Check if user can purge
 */
abstract class Common extends CommonDBTM
{
    /**
     * Rights management
     * @var string
     */
    public static string $rightname = 'plugin_newbase';

    /**
     * Enable history tracking
     * @var bool
     */
    public bool $dohistory = true;

    /**
     * Get type name for display
     *
     * @param int $nb Number of items
     *
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return static::class;
    }

    /**
     * Get database table name for this class
     *
     * @param string|null $classname Class name (optional)
     *
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        $classname ??= static::class;
        $class = explode('\\', $classname);
        $class = end($class);
        return 'glpi_plugin_newbase_' . strtolower($class . 's');
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
     *
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
     *
     * @return string Form URL
     */
    public static function getFormURL($full = true): string
    {
        $dir = Plugin::getWebDir('newbase', $full);
        $item = strtolower(static::getType());
        return "{$dir}/front/{$item}.form.php";
    }

    /**
     * Get form URL with item ID
     *
     * @param int  $id   Item ID
     * @param bool $full Full path or relative
     *
     * @return string Form URL with ID
     */
    public static function getFormURLWithID(int $id = 0, bool $full = true): string
    {
        $link = static::getFormURL($full);
        $separator = strpos($link, '?') !== false ? '&' : '?';
        return "{$link}{$separator}id={$id}";
    }

    /**
     * Display form header (opening tag and table start)
     *
     * @param array $options Form options
     *
     * @return void
     */
    public function showFormHeader(array $options = []): void
    {
        $id = $this->fields['id'] ?? 0;
        $target = $this->getFormURL();

        echo "<form name='form' method='post' action='{$target}'>";

        if ($id > 0) {
            echo "<input type='hidden' name='id' value='{$id}'>";
        }

        echo "<div class='spaced'>";
        echo "<table class='tab_cadre_fixe'>";
    }

    /**
     * Display form buttons (save, delete, etc.)
     *
     * @param array $options Form options
     *
     * @return void
     */
    public function showFormButtons(array $options = []): void
    {
        $id = $this->fields['id'] ?? 0;
        $canedit = $this->canEdit($id);

        echo "</table>";
        echo "</div>";

        if ($canedit) {
            echo "<div class='center'>";

            if ($id > 0) {
                echo "<input type='submit' name='update' value='" . __('Save') . "' class='btn btn-primary'>";

                if ($this->canPurge()) {
                    echo "&nbsp;&nbsp;";
                    $confirm_msg = addslashes(__('Confirm the final deletion?'));
                    echo "<input type='submit' name='purge' value='" . __('Delete permanently')
                        . "' class='btn btn-danger' onclick='return confirm(\"{$confirm_msg}\");'>";
                }
            } else {
                echo "<input type='submit' name='add' value='" . __('Add') . "' class='btn btn-primary'>";
            }

            echo "</div>";
        }

        Html::closeForm();
    }

    /**
     * Display item details
     *
     * @param array $options Display options
     *
     * @return bool Success
     */
    public function display(array $options = []): bool
    {
        return $this->showForm($options['id'] ?? 0, $options);
    }

    /**
     * Show form (must be implemented by child classes)
     *
     * @param int   $ID      Item ID
     * @param array $options Form options
     *
     * @return bool Success
     */
    public function showForm($ID, array $options = []): bool
    {
        return false;
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

    /**
     * Validate CNPJ format and checksum
     *
     * @param string|null $cnpj CNPJ without formatting (14 digits)
     *
     * @return bool True if valid, false otherwise
     */
    public static function validateCNPJ(?string $cnpj): bool
    {
        if ($cnpj === null || $cnpj === '') {
            return false;
        }

        // Remove non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Check if it has exactly 14 digits
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Check if all digits are the same (invalid CNPJ)
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // Calculate first check digit
        $firstDigit = self::calculateCNPJCheckDigit(substr($cnpj, 0, 12), 5);
        if ((int)$cnpj[12] !== $firstDigit) {
            return false;
        }

        // Calculate second check digit
        $secondDigit = self::calculateCNPJCheckDigit(substr($cnpj, 0, 13), 6);
        if ((int)$cnpj[13] !== $secondDigit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate CNPJ check digit
     *
     * @param string $base             Base string (12 or 13 digits)
     * @param int    $multiplierStart  Starting multiplier (5 or 6)
     *
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

    /**
     * Search company data by CNPJ using external API
     *
     * @param string|null $cnpj CNPJ without formatting
     *
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

            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: GLPI-Newbase/2.1.0',
                    'timeout' => 10,
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return false;
            }

            $data = json_decode($response, true);

            if (isset($data['status']) && $data['status'] === 400) {
                return false; // CNPJ not found
            }

            // Return standardized data
            return [
                'legal_name'   => $data['nome'] ?? $data['legal_name'] ?? '',
                'fantasy_name' => $data['fantasia'] ?? $data['fantasy_name'] ?? '',
                'cnpj'         => $data['cnpj'] ?? $cnpj,
                'status'       => $data['situacao'] ?? '',
                'opening_date' => $data['data_abertura'] ?? '',
            ];
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error searching CNPJ {$cnpj}: " . $e->getMessage()
            );
            return false;
        }
    }

    /**
     * Search for additional company data via multiple APIs
     *
     * @param string|null $cnpj        CNPJ number
     * @param string      $companyName Company name for alternative search
     *
     * @return array Company data with email and phone
     */
    public static function searchCompanyAdditionalData(?string $cnpj, string $companyName = ''): array
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

        // Try ReceitaWS API
        $result = array_merge($result, self::searchReceitaWSAPI($cnpj));

        return $result;
    }

    /**
     * Search for company data via ReceitaWS API
     *
     * @param string $cnpj CNPJ number
     *
     * @return array Company data or empty array
     */
    private static function searchReceitaWSAPI(string $cnpj): array
    {
        $result = [
            'email'   => '',
            'phone'   => '',
            'website' => '',
        ];

        try {
            // ReceitaWS API - Brazilian public data
            $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: GLPI-Newbase/2.1.0',
                    'timeout' => 8,
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return $result;
            }

            $data = json_decode($response, true);

            // Check if request was successful
            if (isset($data['status']) && $data['status'] === 'OK') {
                $result['email'] = $data['email'] ?? '';
                $result['phone'] = $data['telefone'] ?? '';
                $result['website'] = $data['website'] ?? '';

                Toolbox::logInFile(
                    'newbase_plugin',
                    "ReceitaWS API found data for CNPJ {$cnpj}"
                );
            }
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ReceitaWS API Error for CNPJ {$cnpj}: " . $e->getMessage()
            );
        }

        return $result;
    }

    /**
     * Format phone number to Brazilian format
     *
     * @param string|null $phone Phone number
     *
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
     * @param string|null $cep CEP to format
     *
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
     * @param string|null $cnpj CNPJ to format
     *
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
     * Calculate distance between two GPS coordinates using Haversine formula
     *
     * @param float $lat1 Latitude of first point
     * @param float $lng1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lng2 Longitude of second point
     *
     * @return float Distance in kilometers
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
