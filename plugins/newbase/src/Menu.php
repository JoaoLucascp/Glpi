<?php

/**
* Newbase Plugin - Menu Class (SIMPLIFICADO)
*
* @version 2.0.0
* @license GPLv2+
*/

namespace GlpiPlugin\Newbase;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Menu Class - Gerenciamento do menu do plugin
 */
class Menu
{
    /**
     * Retorna o nome do menu
     *
     * @return string
     */
    public static function getMenuName()
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Retorna o conteúdo do menu
     *
     * @return array
     */
    public static function getMenuContent()
    {
        global $CFG_GLPI;

        $menu = [];

        $menu['title'] = self::getMenuName();
        $menu['page'] = $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php';
        $menu['icon'] = 'ti ti-building';

        $menu['options'] = [
            'companydata' => [
                'title' => __('Company Data', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php',
                'icon' => 'ti ti-building',
                'links' => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php',
                    'add' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php',
                ]
            ],
            'system' => [
                'title' => __('Systems', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php',
                'icon' => 'ti ti-phone',
                'links' => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php',
                    'add' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.form.php',
                ]
            ],
            'address' => [
                'title' => __('Addresses', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/address.php',
                'icon' => 'ti ti-map-pin',
                'links' => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/address.php',
                    'add' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/address.form.php',
                ]
            ],
            'task' => [
                'title' => __('Tasks', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php',
                'icon' => 'ti ti-checkbox',
                'links' => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php',
                    'add' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.form.php',
                ]
            ],
            'config' => [
                'title' => __('Configuration', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/config.php',
                'icon' => 'ti ti-settings',
            ],
        ];

        return $menu;
    }
}
