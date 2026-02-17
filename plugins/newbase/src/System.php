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

use CommonDBTM;
use CommonGLPI;
use Entity;
use Session;
use Html;
use Plugin;
use Dropdown;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * System - Manages telecommunication systems
 * 
 * Supports multiple system types:
 * - PABX (Private Automatic Branch Exchange)
 * - IPBX (IP-based PBX)
 * - IPBX Cloud (Cloud-based IP PBX)
 * - Chatbot (Automated chat systems)
 * - Landline (Traditional phone lines)
 *
 * @package GlpiPlugin\Newbase
 */
class System extends CommonDBTM
{
    /**
     * Rights management
     * @var string
     */
    public static $rightname = 'plugin_newbase';

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
        return _n('System', 'Systems', $nb, 'newbase');
    }

    /**
     * Get table name
     * @param string|null $classname Class name
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_systems';
    }

    /**
     * Get icon for menus (Tabler Icons)
     * @return string Icon class
     */
    public static function getIcon(): string
    {
        return 'ti ti-server';
    }

    /**
     * Get menu content for this item type
     * @return array Menu content
     */
    public static function getMenuContent(): array
    {
        $menu = [];

        // Check user rights
        if (!Session::haveRight(self::$rightname, READ)) {
            return $menu;
        }

        // Menu configuration
        $menu['title'] = self::getTypeName(2);
        $menu['page']  = self::getSearchURL(false);
        $menu['icon']  = self::getIcon();

        // Add links
        $menu['links'] = [
            'search' => self::getSearchURL(false),
        ];

        // Add form link if user can create
        if (self::canCreate()) {
            $menu['links']['add'] = self::getFormURL(false);
        }

        return $menu;
    }

    /**
     * Get system types
     * @return array System types [key => label]
     */
    public static function getSystemTypes(): array
    {
        return [
            'pabx'       => __('PABX', 'newbase'),
            'ipbx'       => __('IPBX', 'newbase'),
            'ipbx_cloud' => __('IPBX Cloud', 'newbase'),
            'chatbot'    => __('Chatbot', 'newbase'),
            'landline'   => __('Landline', 'newbase'),
        ];
    }

    /**
     * Get system statuses
     * @return array System statuses [key => label]
     */
    public static function getSystemStatuses(): array
    {
        return [
            'active'      => __('Active'),
            'inactive'    => __('Inactive'),
            'maintenance' => __('Maintenance'),
        ];
    }

    /**
     * Define search options for GLPI search engine
     * @return array Search options
     */
    public function rawSearchOptions(): array
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'            => '2',
            'table'         => self::getTable(),
            'field'         => 'name',
            'name'          => __('Name'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'       => '3',
            'table'    => 'glpi_entities',
            'field'    => 'name',
            'name'     => __('Company', 'newbase'),
            'datatype' => 'dropdown',
        ];

        $tab[] = [
            'id'         => '4',
            'table'      => self::getTable(),
            'field'      => 'system_type',
            'name'       => __('Type', 'newbase'),
            'datatype'   => 'specific',
            'searchtype' => ['equals'],
        ];

        $tab[] = [
            'id'       => '5',
            'table'    => self::getTable(),
            'field'    => 'status',
            'name'     => __('Status'),
            'datatype' => 'specific',
        ];

        $tab[] = [
            'id'       => '6',
            'table'    => self::getTable(),
            'field'    => 'description',
            'name'     => __('Description'),
            'datatype' => 'text',
        ];

        $tab[] = [
            'id'            => '7',
            'table'         => self::getTable(),
            'field'         => 'configuration',
            'name'          => __('Configuration', 'newbase'),
            'datatype'      => 'text',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => '19',
            'table'         => self::getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => '121',
            'table'         => self::getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        return $tab;
    }

    /**
     * Display specific value for search result
     * 
     * @param string $field Field name
     * @param array  $values Values
     * @param array  $options Options
     * @return string Formatted value
     */
    public static function getSpecificValueToDisplay($field, $values, array $options = []): string
    {
        if (!is_array($values)) {
            $values = [$field => $values];
        }

        switch ($field) {
            case 'system_type':
                $types = self::getSystemTypes();
                return $types[$values[$field]] ?? $values[$field];

            case 'status':
                $statuses = self::getSystemStatuses();
                return $statuses[$values[$field]] ?? $values[$field];
        }

        return parent::getSpecificValueToDisplay($field, $values, $options);
    }

    /**
     * Display form for system
     * @param int   $ID      Item ID (0 for new)
     * @param array $options Additional options
     * @return bool Success
     */
    public function showForm($ID, array $options = []): bool
    {
        $this->initForm($ID, $options);

        if (!$this->canView()) {
            return false;
        }

        // Get entities_id (prioritize GET parameter)
        $entities_id = $_GET['entities_id'] ?? $options['entities_id'] ?? $this->fields['entities_id'] ?? $_SESSION['glpiactive_entity'];

        $this->showFormHeader($options);

        // BASIC INFORMATION
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Entity::dropdown([
            'name'   => 'entities_id',
            'value'  => $entities_id,
            'entity' => $entities_id,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Type', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Dropdown::showFromArray('system_type', self::getSystemTypes(), [
            'value' => $this->fields['system_type'] ?? 'pabx',
        ]);
        echo "</td>";
        echo "<td>" . __('Status') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getSystemStatuses(), [
            'value' => $this->fields['status'] ?? 'active',
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Description') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name'  => 'description',
            'value' => $this->fields['description'] ?? '',
            'cols'  => 80,
            'rows'  => 4,
        ]);
        echo "</td>";
        echo "</tr>";

        // CONFIGURATION (JSON)
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Configuration', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name'  => 'configuration',
            'value' => $this->fields['configuration'] ?? '',
            'cols'  => 80,
            'rows'  => 8,
        ]);
        echo "<br><small class='text-muted'>" . __('JSON format for technical configuration', 'newbase') . "</small>";
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }

    /**
     * Prepare input for add
     * @param array $input Input data
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForAdd($input)
    {
        // Validate system type
        if (isset($input['system_type'])) {
            $validTypes = array_keys(self::getSystemTypes());
            if (!in_array($input['system_type'], $validTypes, true)) {
                Session::addMessageAfterRedirect(
                    __('Invalid system type', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validate configuration JSON
        if (!empty($input['configuration'])) {
            $decoded = json_decode($input['configuration'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Session::addMessageAfterRedirect(
                    __('Invalid JSON in configuration field', 'newbase') . ': ' . json_last_error_msg(),
                    false,
                    ERROR
                );
                return false;
            }
        }

        return parent::prepareInputForAdd($input);
    }

    /**
     * Prepare input for update
     * @param array $input Input data
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForUpdate($input)
    {
        // Validate system type if provided
        if (isset($input['system_type'])) {
            $validTypes = array_keys(self::getSystemTypes());
            if (!in_array($input['system_type'], $validTypes, true)) {
                Session::addMessageAfterRedirect(
                    __('Invalid system type', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validate configuration JSON if provided
        if (isset($input['configuration']) && !empty($input['configuration'])) {
            $decoded = json_decode($input['configuration'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Session::addMessageAfterRedirect(
                    __('Invalid JSON in configuration field', 'newbase') . ': ' . json_last_error_msg(),
                    false,
                    ERROR
                );
                return false;
            }
        }

        return parent::prepareInputForUpdate($input);
    }

    /**
     * Actions after adding item
     */
    public function post_addItem(): void
    {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "System added: ID=%d, Name=%s, Type=%s, Entity=%d\n",
                $this->fields['id'],
                $this->fields['name'],
                $this->fields['system_type'],
                $this->fields['entities_id']
            )
        );
    }

    /**
     * Get tab name for item
     * @param CommonGLPI $item Item
     * @param int $withtemplate Template mode
     * @return string|bool Tab name
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item instanceof Entity) {
            if ($_SESSION['glpishow_count_on_tabs']) {
                return self::createTabEntry(
                    self::getTypeName(Session::getPluralNumber()),
                    self::countForItem($item)
                );
            }
            return self::getTypeName(Session::getPluralNumber());
        }
        return '';
    }

    /**
     * Display tab content for item
     * @param CommonGLPI $item Item
     * @param int $tabnum Tab number
     * @param int $withtemplate Template mode
     * @return bool Success
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item instanceof Entity) {
            self::showForEntity($item);
            return true;
        }
        return false;
    }

    /**
     * Count systems for an entity
     * @param CommonDBTM $item Entity item
     * @return int Count
     */
    public static function countForItem(CommonDBTM $item): int
    {
        return countElementsInTable(
            self::getTable(),
            [
                'entities_id' => $item->getID(),
                'is_deleted'  => 0,
            ]
        );
    }

    /**
     * Show systems for an entity
     * @param Entity $entity Entity
     * @return void
     */
    public static function showForEntity(Entity $entity): void
    {
        global $DB;

        $entity_id = $entity->getID();
        $canedit = $entity->canUpdate();

        // Add button
        if ($canedit) {
            echo "<div class='center firstbloc'>";
            echo "<a class='btn btn-primary' href='" . self::getFormURL() . "?entities_id=$entity_id'>";
            echo "<i class='ti ti-plus'></i> " . __('Add a system', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        // Get systems
        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'entities_id' => $entity_id,
                'is_deleted'  => 0,
            ],
            'ORDER' => ['name'],
        ]);

        if (count($iterator) === 0) {
            echo "<div class='center'>";
            echo "<p>" . __('No system registered for this company', 'newbase') . "</p>";
            echo "</div>";
            return;
        }

        // Display table
        $types = self::getSystemTypes();
        $statuses = self::getSystemStatuses();

        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('Name') . "</th>";
        echo "<th>" . __('Type', 'newbase') . "</th>";
        echo "<th>" . __('Status') . "</th>";
        echo "<th>" . __('Description') . "</th>";
        if ($canedit) {
            echo "<th>" . __('Actions') . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($iterator as $data) {
            echo "<tr>";

            // Name
            echo "<td>";
            echo "<a href='" . self::getFormURLWithID($data['id']) . "'>";
            echo "<i class='ti ti-server'></i> ";
            echo htmlspecialchars($data['name']);
            echo "</a>";
            echo "</td>";

            // Type
            echo "<td>" . ($types[$data['system_type']] ?? $data['system_type']) . "</td>";

            // Status
            echo "<td>" . ($statuses[$data['status']] ?? $data['status']) . "</td>";

            // Description (truncated)
            $desc = $data['description'] ?? '';
            echo "<td>" . (mb_strlen($desc) > 100 ? mb_substr($desc, 0, 100) . '...' : ($desc ?: '-')) . "</td>";

            // Actions
            if ($canedit) {
                echo "<td>";
                echo "<a href='" . self::getFormURLWithID($data['id']) . "' class='btn btn-sm btn-primary'>";
                echo "<i class='ti ti-edit'></i>";
                echo "</a> ";
                echo Html::getSimpleForm(
                    self::getFormURL(),
                    ['purge' => 'purge', 'id' => $data['id']],
                    __('Delete permanently'),
                    ['class' => 'btn btn-sm btn-danger'],
                    'ti-trash'
                );
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }

    /**
     * Dropdown for system selection
     * @param array $options Dropdown options
     * @return int|string Dropdown result
     */
    public static function dropdown($options = [])
    {
        $defaults = [
            'name'   => 'plugin_newbase_systems_id',
            'value'  => 0,
            'entity' => $_SESSION['glpiactive_entity'],
        ];

        $options = array_merge($defaults, $options);

        return Dropdown::show(self::class, $options);
    }
}
