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

namespace GlpiPlugin\Newbase;

use CommonGLPI;
use Session;
use Plugin;

/**
 * Menu Class - Handles plugin menu entries
 *
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @license   GPLv2+
 * @version   2.1.0
 */
class Menu extends CommonGLPI
{
    /**
     * Get type name
     *
     * @param int $nb Number of items
     *
     * @return string
     */
    public static function getTypeName($nb = 0): string
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Get icon for menu
     *
     * @return string
     */
    public static function getIcon(): string
    {
        return 'ti ti-building';
    }

    /**
     * Get menu content
     *
     * @return array{title: string, page: string, icon: string, links: array}|array
     */
    public static function getMenuContent(): array
    {
        $menu = [];

        // Check if user has rights
        if (!Session::haveRight('plugin_newbase', READ)) {
            return $menu;
        }

        // Get plugin base URL
        $plugin = new Plugin();
        $baseUrl = Plugin::getWebDir('newbase');

        // Main menu configuration
        $menu['title'] = self::getTypeName();
        $menu['page'] = $baseUrl . '/front/index.php';
        $menu['icon'] = self::getIcon();

        // Menu options
        $menu['options'] = [];

        // Company Data
        $menu['options']['companydata'] = [
            'title' => __('Company Data', 'newbase'),
            'page' => $baseUrl . '/front/companydata.php',
            'icon' => 'ti ti-building',
            'links' => [
                'search' => $baseUrl . '/front/companydata.php',
                'add' => $baseUrl . '/front/companydata.form.php',
            ]
        ];

        // Systems (PABX, etc)
        $menu['options']['system'] = [
            'title' => __('Systems', 'newbase'),
            'page' => $baseUrl . '/front/system.php',
            'icon' => 'ti ti-phone',
            'links' => [
                'search' => $baseUrl . '/front/system.php',
                'add' => $baseUrl . '/front/system.form.php',
            ]
        ];

        // Tasks
        $menu['options']['task'] = [
            'title' => __('Field Tasks', 'newbase'),
            'page' => $baseUrl . '/front/task.php',
            'icon' => 'ti ti-map-pin',
            'links' => [
                'search' => $baseUrl . '/front/task.php',
                'add' => $baseUrl . '/front/task.form.php',
            ]
        ];

        // Reports
        if (Session::haveRight('plugin_newbase', READ)) {
            $menu['options']['report'] = [
                'title' => __('Reports', 'newbase'),
                'page' => $baseUrl . '/front/report.php',
                'icon' => 'ti ti-chart-bar',
                'links' => [
                    'report' => $baseUrl . '/front/report.php',
                ]
            ];
        }

        // Configuration (only for users with config rights)
        if (Session::haveRight('config', UPDATE)) {
            $menu['options']['config'] = [
                'title' => __('Configuration', 'newbase'),
                'page' => $baseUrl . '/front/config.php',
                'icon' => 'ti ti-settings',
            ];
        }

        return $menu;
    }

    /**
     * Show menu
     *
     * @return void
     */
    public static function displayMenu(): void
    {
        $menu = self::getMenuContent();

        if (!empty($menu['options'])) {
            echo "<div class='newbase-menu'>";
            echo "<h2>" . $menu['title'] . "</h2>";
            echo "<ul>";

            foreach ($menu['options'] as $option) {
                echo "<li>";
                echo "<a href='" . $option['page'] . "'>";
                echo "<i class='" . $option['icon'] . "'></i> ";
                echo $option['title'];
                echo "</a>";
                echo "</li>";
            }

            echo "</ul>";
            echo "</div>";
        }
    }
}
