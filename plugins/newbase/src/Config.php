<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;
use Html;
use Dropdown;
use Toolbox;
use Exception;

/**
 * Config class for Newbase Plugin
 *
 * Manages plugin configuration settings
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class Config extends CommonDBTM
{
    // Right name for permissions
    public static $rightname = 'plugin_newbase_config';

    /**
     * Get table name
     *
     * @param string|null $classname Class name
     * @return string
     */
    public static function getTable($classname = null): string
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_config';
    }

    /**
     * Get type name
     *
     * @param int $nb Number of items
     * @return string
     */
    public static function getTypeName($nb = 0): string
    {
        return __('Newbase Configuration', 'newbase');
    }

    /**
     * Get a configuration value
     *
     * @param string $key Configuration key
     * @param mixed  $default Default value if key not found
     * @return mixed
     */
    public static function getConfigValue(string $key, $default = null)
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['config_key' => $key],
            'LIMIT' => 1
        ]);

        if (count($iterator)) {
            $row = $iterator->current();
            return $row['config_value'];
        }

        return $default;
    }

    /**
     * Set a configuration value
     *
     * @param string $key Configuration key
     * @param mixed  $value Configuration value
     * @return bool
     */
    public static function setConfigValue(string $key, $value): bool
    {
        global $DB;

        $key = \Glpi\Toolbox\Sanitizer::cleanHtml($key);
        $value = is_array($value) ? json_encode($value) : \Glpi\Toolbox\Sanitizer::cleanHtml((string)$value);

        try {
            // Check if key exists
            $iterator = $DB->request([
                'FROM'  => self::getTable(),
                'WHERE' => ['config_key' => $key],
                'LIMIT' => 1
            ]);

            if (count($iterator)) {
                // Update existing
                return $DB->update(
                    self::getTable(),
                    [
                        'config_value' => $value,
                        'date_mod' => date('Y-m-d H:i:s')
                    ],
                    ['config_key' => $key]
                );
            } else {
                // Insert new
                return $DB->insert(
                    self::getTable(),
                    [
                        'config_key' => $key,
                        'config_value' => $value,
                        'date_mod' => date('Y-m-d H:i:s')
                    ]
                );
            }
        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "ERROR in setConfigValue(): " . $e->getMessage() . "\n");
            return false;
        }
    }

    /**
     * Display configuration form
     *
     * @return void
     */
    public function showConfigForm(): void
    {
        if (!Session::haveRight(self::$rightname, UPDATE)) {
            return;
        }

        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<div class='center' id='tabsbody'>";
        echo "<table class='tab_cadre_fixe'>";

        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('General Configuration', 'newbase') . "</th>";
        echo "</tr>";

        // Enable CNPJ API
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable CNPJ API', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('enable_cnpj_api', self::getConfigValue('enable_cnpj_api', '1'));
        echo "</td>";

        // Enable CEP API
        echo "<td>" . __('Enable CEP API (ViaCEP)', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('enable_cep_api', self::getConfigValue('enable_cep_api', '1'));
        echo "</td>";
        echo "</tr>";

        // Enable Geolocation
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable Geolocation', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('enable_geolocation', self::getConfigValue('enable_geolocation', '1'));
        echo "</td>";

        // Enable Signature
        echo "<td>" . __('Enable Digital Signature', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('enable_signature', self::getConfigValue('enable_signature', '1'));
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('API Configuration', 'newbase') . "</th>";
        echo "</tr>";

        // CNPJ API URL
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('CNPJ API URL', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::input('cnpj_api_url', [
            'value' => self::getConfigValue('cnpj_api_url', 'https://brasilapi.com.br/api/cnpj/v1/'),
            'size' => 80
        ]);
        echo "</td>";
        echo "</tr>";

        // CEP API URL
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('CEP API URL (ViaCEP)', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::input('cep_api_url', [
            'value' => self::getConfigValue('cep_api_url', 'https://viacep.com.br/ws/'),
            'size' => 80
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Map Configuration', 'newbase') . "</th>";
        echo "</tr>";

        // Map Provider
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Map Provider', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('map_provider', [
            'leaflet' => 'Leaflet (OpenStreetMap)',
            'google' => 'Google Maps'
        ], [
            'value' => self::getConfigValue('map_provider', 'leaflet')
        ]);
        echo "</td>";

        // Map Default Zoom
        echo "<td>" . __('Default Map Zoom', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('map_default_zoom', array_combine(range(1, 20), range(1, 20)), [
            'value' => self::getConfigValue('map_default_zoom', '13')
        ]);
        echo "</td>";
        echo "</tr>";

        // Map Default Center
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Default Map Center (Latitude)', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('map_default_lat', [
            'value' => self::getConfigValue('map_default_lat', '-23.5505'),
            'type' => 'number',
            'step' => 'any'
        ]);
        echo "</td>";

        echo "<td>" . __('Default Map Center (Longitude)', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('map_default_lng', [
            'value' => self::getConfigValue('map_default_lng', '-46.6333'),
            'type' => 'number',
            'step' => 'any'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Task Configuration', 'newbase') . "</th>";
        echo "</tr>";

        // Auto-calculate mileage
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Auto-calculate mileage on task save', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('auto_calculate_mileage', self::getConfigValue('auto_calculate_mileage', '1'));
        echo "</td>";

        // Require signature for completion
        echo "<td>" . __('Require signature for task completion', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showYesNo('require_signature', self::getConfigValue('require_signature', '0'));
        echo "</td>";
        echo "</tr>";

        // Save button
        echo "<tr class='tab_bg_2'>";
        echo "<td colspan='4' class='center'>";
        echo Html::submit(_sx('button', 'Save'), ['name' => 'update_config', 'class' => 'btn btn-primary']);
        echo "</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";
        Html::closeForm();
    }

    /**
     * Handle configuration form submission
     *
     * @param array $input Form input
     * @return bool
     */
    public static function handleConfigUpdate(array $input): bool
    {
        if (!Session::haveRight(self::$rightname, UPDATE)) {
            return false;
        }

        $config_keys = [
            'enable_cnpj_api',
            'enable_cep_api',
            'enable_geolocation',
            'enable_signature',
            'cnpj_api_url',
            'cep_api_url',
            'map_provider',
            'map_default_zoom',
            'map_default_lat',
            'map_default_lng',
            'auto_calculate_mileage',
            'require_signature'
        ];

        $success = true;

        foreach ($config_keys as $key) {
            if (isset($input[$key])) {
                if (!self::setConfigValue($key, $input[$key])) {
                    $success = false;
                }
            }
        }

        if ($success) {
            Session::addMessageAfterRedirect(
                __('Configuration saved successfully', 'newbase'),
                true,
                INFO
            );
            Toolbox::logInFile('newbase_plugin', "Configuration updated successfully\n");
        } else {
            Session::addMessageAfterRedirect(
                __('Error saving configuration', 'newbase'),
                false,
                ERROR
            );
            Toolbox::logInFile('newbase_plugin', "ERROR updating configuration\n");
        }

        return $success;
    }

    /**
     * Check if CNPJ API is enabled
     *
     * @return bool
     */
    public static function isCNPJApiEnabled(): bool
    {
        return self::getConfigValue('enable_cnpj_api', '1') === '1';
    }

    /**
     * Check if CEP API is enabled
     *
     * @return bool
     */
    public static function isCEPApiEnabled(): bool
    {
        return self::getConfigValue('enable_cep_api', '1') === '1';
    }

    /**
     * Check if geolocation is enabled
     *
     * @return bool
     */
    public static function isGeolocationEnabled(): bool
    {
        return self::getConfigValue('enable_geolocation', '1') === '1';
    }

    /**
     * Check if digital signature is enabled
     *
     * @return bool
     */
    public static function isSignatureEnabled(): bool
    {
        return self::getConfigValue('enable_signature', '1') === '1';
    }

    /**
     * Get CNPJ API URL
     *
     * @return string
     */
    public static function getCNPJApiUrl(): string
    {
        return self::getConfigValue('cnpj_api_url', 'https://brasilapi.com.br/api/cnpj/v1/');
    }

    /**
     * Get CEP API URL
     *
     * @return string
     */
    public static function getCEPApiUrl(): string
    {
        return self::getConfigValue('cep_api_url', 'https://viacep.com.br/ws/');
    }
}
