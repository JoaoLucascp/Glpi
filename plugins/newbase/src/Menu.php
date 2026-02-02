<?php

/**
* Menu Configuration - Plugin Newbase
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use Session;
use Plugin;

// Menu class - Defines plugin menu structure for GLPI
class Menu
{

    // Get menu content for GLPI navigation
    public static function getMenuContent(): array
    {
        global $CFG_GLPI;

        $menu = [];

        // VERIFICAR PERMISSÕES
        if (!Session::haveRight('plugin_newbase', READ)) {
            return $menu;
        }

        // OBTER URL BASE DO PLUGIN
        $baseUrl = Plugin::getWebDir('newbase');

        // MENU PRINCIPAL
        $menu['title'] = __('Newbase', 'newbase');
        $menu['page'] = $baseUrl . '/front/index.php';
        $menu['icon'] = 'ti ti-database';

        // OPÇÕES DO MENU
        $menu['options'] = [];

        // DASHBOARD
        $menu['options']['dashboard'] = [
            'title' => __('Dashboard', 'newbase'),
            'page' => $baseUrl . '/front/index.php',
            'icon' => 'ti ti-dashboard',
        ];

        // DADOS DE EMPRESA
        $menu['options']['companydata'] = [
            'title' => __('Company Data', 'newbase'),
            'page' => $baseUrl . '/front/companydata.php',
            'icon' => 'ti ti-building',
            'links' => [
                'search' => $baseUrl . '/front/companydata.php',
                'add' => $baseUrl . '/front/companydata.form.php',
            ],
        ];

        // SISTEMAS
        $menu['options']['system'] = [
            'title' => __('Systems', 'newbase'),
            'page' => $baseUrl . '/front/system.php',
            'icon' => 'ti ti-server',
            'links' => [
                'search' => $baseUrl . '/front/system.php',
                'add' => $baseUrl . '/front/system.form.php',
            ],
        ];

        // ENDEREÇOS
        $menu['options']['address'] = [
            'title' => __('Addresses', 'newbase'),
            'page' => $baseUrl . '/front/address.php',
            'icon' => 'ti ti-map-pin',
            'links' => [
                'search' => $baseUrl . '/front/address.php',
                'add' => $baseUrl . '/front/address.form.php',
            ],
        ];

        // TAREFAS
        $menu['options']['task'] = [
            'title' => __('Tasks', 'newbase'),
            'page' => $baseUrl . '/front/task.php',
            'icon' => 'ti ti-checkbox',
            'links' => [
                'search' => $baseUrl . '/front/task.php',
                'add' => $baseUrl . '/front/task.form.php',
            ],
        ];

        // RELATÓRIOS (apenas se tiver permissão UPDATE)
        if (Session::haveRight('plugin_newbase', UPDATE)) {
            $menu['options']['report'] = [
                'title' => __('Reports', 'newbase'),
                'page' => $baseUrl . '/front/report.php',
                'icon' => 'ti ti-chart-line',
            ];
        }

        // CONFIGURAÇÃO (apenas para admins)
        if (Session::haveRight('config', UPDATE)) {
            $menu['options']['config'] = [
                'title' => __('Configuration', 'newbase'),
                'page' => $baseUrl . '/front/config.php',
                'icon' => 'ti ti-settings',
            ];
        }

        return $menu;
    }

    // Display menu item
    public static function displayMenu(): void
    {
        $menu = self::getMenuContent();

        if (empty($menu)) {
            return;
        }

        echo "<div class='newbase-menu'>";
        echo "<h2>" . $menu['title'] . "</h2>";
        echo "<ul class='menu-list'>";

        foreach ($menu['options'] as $key => $option) {
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