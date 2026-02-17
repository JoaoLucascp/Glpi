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

use CommonGLPI;
use Config as CoreConfig;
use Session;
use Html;
use Glpi\Application\View\TemplateRenderer;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Config Class - Plugin configuration management
 *
 * Manages plugin settings stored in glpi_configs table with context 'plugin:newbase'.
 * Displays configuration tab in Setup > General > Newbase.
 *
 * @package GlpiPlugin\Newbase
 */
class Config extends \Config
{
    /**
     * Rights management (config right)
     * @var string
     */
    public static $rightname = 'config';

    /**
     * Get type name for display
     *
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Get plugin configuration from database
     *
     * Retrieves all settings stored in glpi_configs with context 'plugin:newbase'
     *
     * @return array Configuration array with keys and values
     */
    public static function getConfig(): array
    {
        return CoreConfig::getConfigurationValues('plugin:newbase');
    }

    /**
     * Get default configuration values
     *
     * @return array Default configuration
     */
    public static function getDefaultConfig(): array
    {
        return [
            'enable_signature'  => 0,
            'require_signature' => 0,
            'enable_gps'        => 0,
            'calculate_mileage' => 0,
            'default_zoom'      => 10,
            'enable_cnpj_search' => 1,
            'enable_cep_search'  => 1,
        ];
    }

    /**
     * Get tab name for item
     *
     * Adds "Newbase" tab to Setup > General configuration
     *
     * @param CommonGLPI $item Item to display tab for
     * @param int $withtemplate Template mode
     * @return string|bool Tab name or false
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        // Only show tab in main GLPI Config
        if ($item instanceof CoreConfig) {
            return self::createTabEntry(self::getTypeName());
        }
        return '';
    }

    /**
     * Display tab content for item
     *
     * @param CommonGLPI $item Item
     * @param int $tabnum Tab number
     * @param int $withtemplate Template mode
     * @return bool Success
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item instanceof CoreConfig) {
            self::showForConfig($item, $withtemplate);
            return true;
        }
        return false;
    }

    /**
     * Display and process configuration form
     *
     * Handles both display and POST processing of plugin settings
     *
     * @param CoreConfig $config GLPI Config object
     * @param int $withtemplate Template mode
     * @return bool Success
     */
    public static function showForConfig(CoreConfig $config, $withtemplate = 0): bool
    {
        // Check view permission
        if (!self::canView()) {
            return false;
        }

        $canedit = Session::haveRight(self::$rightname, UPDATE);

        // --- PROCESS FORM SUBMISSION ---
        if ($canedit && isset($_POST['update_config'])) {
            // Validate CSRF token
            Session::checkCSRF($_POST);

            try {
                // Prepare and validate data
                $new_configs = [
                    'enable_signature'   => self::validateBoolean($_POST['enable_signature'] ?? 0),
                    'require_signature'  => self::validateBoolean($_POST['require_signature'] ?? 0),
                    'enable_gps'         => self::validateBoolean($_POST['enable_gps'] ?? 0),
                    'calculate_mileage'  => self::validateBoolean($_POST['calculate_mileage'] ?? 0),
                    'default_zoom'       => self::validateZoom($_POST['default_zoom'] ?? 10),
                    'enable_cnpj_search' => self::validateBoolean($_POST['enable_cnpj_search'] ?? 1),
                    'enable_cep_search'  => self::validateBoolean($_POST['enable_cep_search'] ?? 1),
                ];

                // Save to glpi_configs table with context 'plugin:newbase'
                CoreConfig::setConfigurationValues('plugin:newbase', $new_configs);

                // Log the change
                Toolbox::logInFile(
                    'newbase_plugin',
                    sprintf(
                        "Configuration updated by user %s (ID: %d)\n",
                        Session::getLoginUserName(),
                        Session::getLoginUserID()
                    )
                );

                // Success message
                Session::addMessageAfterRedirect(
                    __('Settings saved successfully', 'newbase'),
                    false,
                    INFO
                );

                // Redirect to avoid form resubmission
                Html::back();
            } catch (\Exception $e) {
                // Error handling
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Error saving configuration: " . $e->getMessage() . "\n"
                );

                Session::addMessageAfterRedirect(
                    __('Error saving settings', 'newbase'),
                    false,
                    ERROR
                );
            }
        }

        // --- DISPLAY FORM ---

        // Load current configuration with defaults
        $defaults = self::getDefaultConfig();
        $stored_config = self::getConfig();
        $current_config = array_merge($defaults, $stored_config);

        // Render template using Twig
        try {
            TemplateRenderer::getInstance()->display('@newbase/config.html.twig', [
                'item'     => $config,
                'config'   => $current_config,
                'can_edit' => $canedit,
                'params'   => [
                    'candel'       => false,
                    'formfooter'   => null,
                    'withtemplate' => $withtemplate,
                ],
            ]);
        } catch (\Exception $e) {
            // Fallback if template doesn't exist
            Toolbox::logInFile(
                'newbase_plugin',
                "Template rendering error: " . $e->getMessage() . "\n"
            );

            // Display basic form
            self::showBasicForm($current_config, $canedit);
        }

        return true;
    }

    /**
     * Validate boolean value
     *
     * @param mixed $value Value to validate
     * @return int 0 or 1
     */
    private static function validateBoolean($value): int
    {
        return (int) (bool) $value;
    }

    /**
     * Validate zoom level
     *
     * @param mixed $value Zoom level to validate
     * @return int Zoom level between 1 and 20
     */
    private static function validateZoom($value): int
    {
        $zoom = (int) $value;
        return max(1, min(20, $zoom));
    }

    /**
     * Display basic HTML form (fallback if Twig template fails)
     *
     * @param array $config Current configuration
     * @param bool $canedit Can edit configuration
     * @return void
     */
    private static function showBasicForm(array $config, bool $canedit): void
    {
        echo "<div class='center'>";
        echo "<form name='form' method='post' action='" . CoreConfig::getFormURL() . "'>";
        echo Html::hidden('_glpi_csrf_token', ['value' => Session::getNewCSRFToken()]);

        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_1'>";
        echo "<th colspan='2'>" . __('Newbase Plugin Configuration', 'newbase') . "</th>";
        echo "</tr>";

        // Enable Signature
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable signature', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'enable_signature',
            'checked' => $config['enable_signature'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Require Signature
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Require signature', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'require_signature',
            'checked' => $config['require_signature'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Enable GPS
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable GPS tracking', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'enable_gps',
            'checked' => $config['enable_gps'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Calculate Mileage
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Calculate mileage automatically', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'calculate_mileage',
            'checked' => $config['calculate_mileage'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Enable CNPJ Search
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable CNPJ search', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'enable_cnpj_search',
            'checked' => $config['enable_cnpj_search'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Enable CEP Search
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Enable CEP search', 'newbase') . "</td>";
        echo "<td>";
        Html::showCheckbox([
            'name'    => 'enable_cep_search',
            'checked' => $config['enable_cep_search'],
        ]);
        echo "</td>";
        echo "</tr>";

        // Default Zoom
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Default map zoom', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('default_zoom', [
            'value' => $config['default_zoom'],
            'type'  => 'number',
            'min'   => 1,
            'max'   => 20,
        ]);
        echo "</td>";
        echo "</tr>";

        if ($canedit) {
            echo "<tr class='tab_bg_2'>";
            echo "<td class='center' colspan='2'>";
            echo "<input type='submit' name='update_config' value='" . _sx('button', 'Save') . "' class='btn btn-primary'>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</form>";
        echo "</div>";
    }
}
