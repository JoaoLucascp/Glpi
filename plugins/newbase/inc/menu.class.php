<?php

/**
* Newbase Plugin - Menu Class (Legacy Style)
* Esta classe usa o estilo legado (sem namespace) porque o GLPI 10.0.20
* ainda espera encontrar classes de menu no formato antigo.
* @package   PluginNewbase
* @author    João Lucas
* @license   GPLv2+
* @since     2.0.0
*/

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
* PluginNewbaseMenu
*
* Classe responsável por gerenciar o menu do plugin Newbase
* IMPORTANTE: Esta classe NÃO usa namespace porque o GLPI 10.0.20
* procura por classes de menu no formato global (PluginNomeMenu)
*/
class PluginNewbaseMenu
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
    public static function getMenuContent(): array
    {
        global $CFG_GLPI;

        $menu = [];

        $menu['title'] = self::getMenuName();
        $menu['page'] = $CFG_GLPI['root_doc'] . '/plugins/newbase/front/index.php';
        $menu['icon'] = 'ti ti-building';

        $menu['options'] = [
            'companydata' => [
                'title' => __('Company Data', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php',
                'icon' => 'ti ti-building',
                'links' => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php',
                    'add' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php',
                ],
            ],
            'system' => [
                'title' => __('Systems', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php',
                'icon' => 'ti ti-phone',
                'links'   => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php',
                    'add'    => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.form.php',
                ],
            ],
            'task' => [
                'title' => __('Tasks', 'newbase'),
                'page' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php',
                'icon' => 'ti ti-checkbox',
                'links'   => [
                    'search' => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php',
                    'add'    => $CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.form.php',
                ],
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
