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
use Session;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Menu class for Newbase plugin
 */
class Menu extends CommonGLPI
{
    /**
     * Rights management
     * @var string
     */
    public static $rightname = 'plugin_newbase';

    /**
     * Get type name
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Get icon for menu
     * @return string Icon class
     */
    public static function getIcon(): string
    {
        return 'ti ti-plugin';
    }

    /**
     * Get menu name
     * @return string Menu name
     */
    public static function getMenuName(): string
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Check if current user can view item
     * @return bool Can view
     */
    public static function canView(): bool
    {
        // CORREÇÃO: Converter para bool explicitamente
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    /**
     * Check if current user can create items
     * @return bool Can create
     */
    public static function canCreate(): bool
    {
        // CORREÇÃO: Converter para bool explicitamente
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    /**
     * Check if current user can update items
     * @return bool Can update
     */
    public static function canUpdate(): bool
    {
        // CORREÇÃO: Converter para bool explicitamente
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    /**
     * Check if current user can delete items
     * @return bool Can delete
     */
    public static function canDelete(): bool
    {
        // CORREÇÃO: Converter para bool explicitamente
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    /**
     * Get menu content for this item type
     * @return array Menu content
     */
    public static function getMenuContent(): array
    {
        $menu = [];

        // Check user rights
        if (!self::canView()) {
            return $menu;
        }

        // Main menu configuration
        $menu['title'] = self::getMenuName();
        $menu['page']  = '/plugins/newbase/front/index.php';
        $menu['icon']  = self::getIcon();

        // Links
        $menu['links'] = [];

        // Dashboard link
        $menu['links']['dashboard'] = '/plugins/newbase/front/index.php';

        // Tasks submenu
        if (class_exists('GlpiPlugin\\Newbase\\Task')) {
            $menu['links']['task'] = Task::getSearchURL(false);

            if (Task::canCreate()) {
                $menu['links']['add_task'] = Task::getFormURL(false);
            }
        }

        // Addresses submenu
        if (class_exists('GlpiPlugin\\Newbase\\Address')) {
            $menu['links']['address'] = Address::getSearchURL(false);

            if (Address::canCreate()) {
                $menu['links']['add_address'] = Address::getFormURL(false);
            }
        }

        // Systems submenu
        if (class_exists('GlpiPlugin\\Newbase\\System')) {
            $menu['links']['system'] = System::getSearchURL(false);

            if (System::canCreate()) {
                $menu['links']['add_system'] = System::getFormURL(false);
            }
        }

        // Company data submenu (link for cadastro/edição/exclusão)
        // 🔧 ERRO FIX: antendia relatórios onde o menu de empresas não aparecia.
        // Foi esquecido ao montar o menu principal. Agora incluído com mesmo
        // padrão de verificação de permissões usado nas outras entidades.
        if (class_exists('GlpiPlugin\\Newbase\\CompanyData')) {
            $menu['links']['company'] = CompanyData::getSearchURL(false);

            if (CompanyData::canCreate()) {
                $menu['links']['add_company'] = CompanyData::getFormURL(false);
            }
        }

        // Configuration link (only for admins)
        if (Session::haveRight('config', UPDATE)) {
            $menu['links']['config'] = '/plugins/newbase/front/config.form.php';
        }

        // Options submenu
        $menu['options'] = [];

        // Tasks option
        if (class_exists('GlpiPlugin\\Newbase\\Task')) {
            $menu['options']['task'] = [
                'title' => Task::getTypeName(Session::getPluralNumber()),
                'page'  => Task::getSearchURL(false),
                'icon'  => Task::getIcon(),
                'links' => [
                    'search' => Task::getSearchURL(false),
                ],
            ];

            if (Task::canCreate()) {
                $menu['options']['task']['links']['add'] = Task::getFormURL(false);
            }
        }

        // Company data option
        if (class_exists('GlpiPlugin\\Newbase\\CompanyData')) {
            $menu['options']['company'] = [
                'title' => CompanyData::getTypeName(Session::getPluralNumber()),
                'page'  => CompanyData::getSearchURL(false),
                'icon'  => CompanyData::getIcon(),
                'links' => [
                    'search' => CompanyData::getSearchURL(false),
                ],
            ];

            if (CompanyData::canCreate()) {
                $menu['options']['company']['links']['add'] = CompanyData::getFormURL(false);
            }
        }

        // Addresses option
        if (class_exists('GlpiPlugin\\Newbase\\Address')) {
            $menu['options']['address'] = [
                'title' => Address::getTypeName(Session::getPluralNumber()),
                'page'  => Address::getSearchURL(false),
                'icon'  => Address::getIcon(),
                'links' => [
                    'search' => Address::getSearchURL(false),
                ],
            ];

            if (Address::canCreate()) {
                $menu['options']['address']['links']['add'] = Address::getFormURL(false);
            }
        }

        // Systems option
        if (class_exists('GlpiPlugin\\Newbase\\System')) {
            $menu['options']['system'] = [
                'title' => System::getTypeName(Session::getPluralNumber()),
                'page'  => System::getSearchURL(false),
                'icon'  => System::getIcon(),
                'links' => [
                    'search' => System::getSearchURL(false),
                ],
            ];

            if (System::canCreate()) {
                $menu['options']['system']['links']['add'] = System::getFormURL(false);
            }
        }

        return $menu;
    }
}
