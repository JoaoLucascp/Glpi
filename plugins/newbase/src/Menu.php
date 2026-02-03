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

class Menu
{
    public static function getMenuContent(): array
    {
        $menu = [];

        // Verificar permissões
        if (!Session::haveRight('plugin_newbase', READ)) {
            return $menu;
        }

        $baseUrl = Plugin::getWebDir('newbase');

        // Menu principal
        $menu['title'] = __('Newbase', 'newbase');
        $menu['page'] = $baseUrl . '/front/index.php';
        $menu['icon'] = 'ti ti-building';
        $menu['links'] = [
            'search' => $baseUrl . '/front/companydata.php',
            'add' => $baseUrl . '/front/companydata.form.php',
        ];

        return $menu;
    }
}
