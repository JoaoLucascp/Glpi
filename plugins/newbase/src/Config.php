<?php

/**
* Config Class - Plugin configuration management
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;
use Toolbox;
use Html;
use Plugin;

/**
* Config - Manages plugin configuration options
* Stores key-value pairs in glpi_plugin_newbase_config table
*/
class Config extends CommonDBTM
{
/**
* Rights management
* @var string
*/
    public static $rightname = 'config';

/**
* Enable history tracking
* @var bool
*/
    public $dohistory = true;

/**
* Get type name
* @param int $nb Number of items
* @return string Type name
*/
    public static function getTypeName($nb = 0): string
    {
        return __('Configuration', 'newbase');
    }

/**
* Get table name
* @param string|null $classname Class name
* @return string Table name
*/
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_config';
    }

/**
* Get icon for menus
* @return string Icon class
*/
    public static function getIcon(): string
    {
        return 'ti ti-settings';
    }

/**
* Get a configuration value
* @param string $key Configuration key
* @param mixed $default Default value if key not found
* @return mixed Configuration value or default
*/
    public static function getConfigValue(string $key, $default = null)
    {
        global $DB;

        // Validate key
        if (empty($key)) {
            return $default;
        }

        try {
            $iterator = $DB->request([
                'FROM' => self::getTable(),
                'WHERE' => ['config_key' => $key],
                'LIMIT' => 1,
            ]);

            if (count($iterator)) {
                $row = $iterator->current();
                $value = $row['config_value'];

                // Try to decode JSON (for array values)
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    }
                }

                return $value;
            }

            return $default;

        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ERROR in getConfigValue('{$key}'): " . $e->getMessage() . "\n"
            );
            return $default;
        }
    }

/**
* Set a configuration value
* @param string $key Configuration key
* @param mixed $value Configuration value
* @return bool Success
*/
    public static function setConfigValue(string $key, $value): bool
    {
        global $DB;

        // VALIDAÇÕES

        // Validate key
        if (empty($key)) {
            return false;
        }

        // Validate value type
        if (is_object($value) || is_resource($value)) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Invalid value type for config key '{$key}': " . gettype($value) . "\n"
            );
            return false;
        }

        // PREPARAR DADOS

        // Sanitize key (GLPI 10.0.20 compatible)
        $key = $DB->escape($key);

        // Convert value to string (encode arrays as JSON)
        if (is_array($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        } else {
            $value = $DB->escape((string) $value);
        }

        try {

            // VERIFICAR SE JÁ EXISTE
            $iterator = $DB->request([
                'FROM' => self::getTable(),
                'WHERE' => ['config_key' => $key],
                'LIMIT' => 1,
            ]);

            $timestamp = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');

            if (count($iterator)) {

                // ATUALIZAR EXISTENTE
                $result = $DB->update(
                    self::getTable(),
                    [
                        'config_value' => $value,
                        'date_mod' => $timestamp,
                    ],
                    ['config_key' => $key]
                );

                if ($result === false) {
                    Toolbox::logInFile(
                        'newbase_plugin',
                        "Failed to update config key '{$key}'\n"
                    );
                    return false;
                }

                return true;

            } else {

                // INSERIR NOVO
                $result = $DB->insert(
                    self::getTable(),
                    [
                        'config_key' => $key,
                        'config_value' => $value,
                        'date_mod' => $timestamp,
                    ]
                );

                if ($result === false) {
                    Toolbox::logInFile(
                        'newbase_plugin',
                        "Failed to insert config key '{$key}'\n"
                    );
                    return false;
                }

                return true;
            }

        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ERROR in setConfigValue('{$key}'): " . $e->getMessage() . "\n"
            );
            return false;
        }
    }

/**
* Delete a configuration value
* @param string $key Configuration key
* @return bool Success
*/
    public static function deleteConfig(string $key): bool
    {
        global $DB;

        if (empty($key)) {
            return false;
        }

        try {
            $result = $DB->delete(
                self::getTable(),
                ['config_key' => $key]
            );

            if ($result === false) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Failed to delete config key '{$key}'\n"
                );
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ERROR in deleteConfig('{$key}'): " . $e->getMessage() . "\n"
            );
            return false;
        }
    }

/**
* Get all configuration values
* @return array Associative array [key => value]
*/
    public static function getAllConfig(): array
    {
        global $DB;

        $configs = [];

        try {
            $iterator = $DB->request([
                'FROM' => self::getTable(),
                'ORDER' => 'config_key ASC',
            ]);

            foreach ($iterator as $row) {
                $value = $row['config_value'];

                // Try to decode JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $configs[$row['config_key']] = $decoded;
                } else {
                    $configs[$row['config_key']] = $value;
                }
            }

        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ERROR in getAllConfig(): " . $e->getMessage() . "\n"
            );
        }

        return $configs;
    }

/**
* Display configuration form
* @return void
*/
    public static function showConfigForm(): void
    {

        // VERIFICAR PERMISSÕES
        if (!Session::haveRight('config', UPDATE)) {
            echo "<div class='center'>";
            echo "<p class='red'>" . __('Access denied') . "</p>";
            echo "</div>";
            return;
        }

        // OBTER CONFIGURAÇÕES ATUAIS
        $enable_signature = self::getConfigValue('enable_signature', 1);
        $require_signature = self::getConfigValue('require_signature', 0);
        $enable_gps = self::getConfigValue('enable_gps', 1);
        $calculate_mileage = self::getConfigValue('calculate_mileage', 1);
        $default_map_zoom = self::getConfigValue('default_map_zoom', 13);

        // RENDERIZAR FORMULÁRIO
        Html::openForm(['action' => self::getFormURL()]);

        echo "<div class='spaced'>";
        echo "<table class='tab_cadre_fixe'>";

        echo "<tr class='tab_bg_1'>";
        echo "<th colspan='2'>";
        echo "<h2>" . __('Plugin Configuration', 'newbase') . "</h2>";
        echo "</th>";
        echo "</tr>";

        // ASSINATURA DIGITAL
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable digital signature', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'enable_signature',
            'checked' => $enable_signature == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Require signature to complete tasks', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'require_signature',
            'checked' => $require_signature == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // GEOLOCALIZAÇÃO
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable GPS tracking', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'enable_gps',
            'checked' => $enable_gps == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Calculate mileage automatically', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'calculate_mileage',
            'checked' => $calculate_mileage == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // MAPA
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Default map zoom level', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='number' name='default_map_zoom' value='" . htmlspecialchars($default_map_zoom, ENT_QUOTES, 'UTF-8') . "' min='1' max='20' size='5' class='form-control'>";
        echo " <span class='text-muted'>(1-20)</span>";
        echo "</td>";
        echo "</tr>";

        // BOTÃO SALVAR
        echo "<tr class='tab_bg_1'>";
        echo "<td colspan='2' class='center'>";
        echo "<input type='submit' name='update_config' value='" . __('Save') . "' class='btn btn-primary'>";
        echo "</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";

        Html::closeForm();
    }

/**
* Process configuration form submission
* @param array $input Form input data
* @return bool Success
*/
    public static function saveConfigForm(array $input): bool
    {
        if (!Session::haveRight('config', UPDATE)) {
            Session::addMessageAfterRedirect(
                __('Access denied'),
                false,
                ERROR
            );
            return false;
        }

        $success = true;

        // Save each configuration value
        $configs = [
            'enable_signature' => isset($input['enable_signature']) ? 1 : 0,
            'require_signature' => isset($input['require_signature']) ? 1 : 0,
            'enable_gps' => isset($input['enable_gps']) ? 1 : 0,
            'calculate_mileage' => isset($input['calculate_mileage']) ? 1 : 0,
            'default_map_zoom' => (int) ($input['default_map_zoom'] ?? 13),
        ];

        foreach ($configs as $key => $value) {
            if (!self::setConfigValue($key, $value)) {
                $success = false;
            }
        }

        if ($success) {
            Session::addMessageAfterRedirect(
                __('Configuration saved successfully', 'newbase'),
                false,
                INFO
            );
        } else {
            Session::addMessageAfterRedirect(
                __('Error saving configuration', 'newbase'),
                false,
                ERROR
            );
        }

        return $success;
    }

/**
* Get form URL
* @param bool $full Full path
* @return string Form URL
*/
    public static function getFormURL($full = true): string
    {
        return Plugin::getWebDir('newbase', $full) . '/front/config.php';
    }
}
