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
* Config Class - Plugin configuration management
* @package   Plugin - Newbase
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

class Config
{
    /**
     * Get configuration table name
     * @return string
     */
    public static function getTable(): string
    {
        return 'glpi_plugin_newbase_configs';
    }

    /**
     * Initialize the configuration table
     * @return void
     */
    public static function initConfigTable(): void
    {
        global $DB;

        $table = self::getTable();

        // Check if table exists
        if ($DB->tableExists($table)) {
            return;
        }

        // Create table if it doesn't exist
        $query = "CREATE TABLE IF NOT EXISTS `$table` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `config_key` VARCHAR(255) NOT NULL UNIQUE,
            `config_value` LONGTEXT,
            `date_mod` DATETIME,
            `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `config_key` (`config_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $DB->query($query);
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
     * Display configuration form (GLPI 10.0+ compatible)
     * NÃO USA Html::openForm() - usa HTML e JavaScript nativos
     * @return void
     */
    public static function showConfigForm(): void
    {
        // VERIFICAR PERMISSÕES
        if (!Session::haveRight('config', UPDATE)) {
            echo "\n\n" . __('Access denied') . "";
            echo "\n\n|";
            return;
        }

        // Carrega valores da configuração
        $enable_signature = (int) self::getConfigValue('enable_signature', 0);
        $require_signature = (int) self::getConfigValue('require_signature', 0);
        $enable_gps = (int) self::getConfigValue('enable_gps', 0);
        $calculate_mileage = (int) self::getConfigValue('calculate_mileage', 0);
        $default_zoom = (int) self::getConfigValue('default_zoom', 10);

        // Processar POST se enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {
            if (Session::validateToken()) {
                self::setConfigValue('enable_signature', $_POST['enable_signature'] ?? 0);
                self::setConfigValue('require_signature', $_POST['require_signature'] ?? 0);
                self::setConfigValue('enable_gps', $_POST['enable_gps'] ?? 0);
                self::setConfigValue('calculate_mileage', $_POST['calculate_mileage'] ?? 0);
                self::setConfigValue('default_zoom', (int) ($_POST['default_zoom'] ?? 10));

                Session::addMessageAfterRedirect(
                    __('Settings saved successfully', 'newbase'),
                    true,
                    INFO
                );
            }
        }

        // usar a API do GLPI em vez de string crua
        echo "<form method='POST' action=''>";

        echo Html::hidden('_glpi_csrf_token', [
            'value' => Session::getNewCSRFToken(),
        ]);

        echo Html::hidden('update_config', [
            'value' => 1,
        ]);

        // Cria tabela com os campos
        echo "<table class='tab_cadre_fixe'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th colspan='2'>" . __('Plugin Configuration', 'newbase') . "</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Campo 1: Enable digital signature
        echo "<tr>";
        echo "<td>" . __('Enable digital signature', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'enable_signature',
            'value' => 1,
            'checked' => $enable_signature == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // Campo 2: Require signature to complete tasks
        echo "<tr>";
        echo "<td>" . __('Require signature to complete tasks', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'require_signature',
            'value' => 1,
            'checked' => $require_signature == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // Campo 3: Enable GPS tracking
        echo "<tr>";
        echo "<td>" . __('Enable GPS tracking', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'enable_gps',
            'value' => 1,
            'checked' => $enable_gps == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // Campo 4: Calculate mileage automatically
        echo "<tr>";
        echo "<td>" . __('Calculate mileage automatically', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name' => 'calculate_mileage',
            'value' => 1,
            'checked' => $calculate_mileage == 1,
        ]);
        echo "</td>";
        echo "</tr>";

        // Campo 5: Default map zoom level
        echo "<tr>";
        echo "<td>" . __('Default map zoom level', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='number' name='default_zoom' value='" . $default_zoom . "' min='1' max='20' step='1'>";
        echo " (1-20)";
        echo "</td>";
        echo "</tr>";

        // Botão de submissão
        echo "<tr>";
        echo "<td colspan='2' class='center'>";
        echo "<button type='submit' class='btn btn-primary'>" . __('Update') . "</button>";
        echo "</td>";
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";

        echo "</form>";
    }
}
