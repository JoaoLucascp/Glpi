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
use Session;
use Html;
use User;
use Entity;
use Dropdown;
use Plugin;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Task - Manages field tasks with geolocation, signatures, and mileage tracking
 * 
 * Features:
 * - GPS tracking (start and end coordinates)
 * - Automatic mileage calculation
 * - Digital signatures
 * - Status workflow (new → assigned → in progress → completed)
 * - Assignment to users
 * - Links to addresses and systems
 *
 * @package GlpiPlugin\Newbase
 */
class Task extends CommonDBTM
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
        return _n('Task', 'Tasks', $nb, 'newbase');
    }

    /**
     * Get table name
     * @param string|null $classname Class name
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_tasks';
    }

    /**
     * Get icon for menus (Tabler Icons)
     * @return string Icon class
     */
    public static function getIcon(): string
    {
        return 'ti ti-checkbox';
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
     * Get task statuses
     * @return array Task statuses [key => label]
     */
    public static function getStatuses(): array
    {
        return [
            'new'         => __('New', 'newbase'),
            'assigned'    => __('Assigned', 'newbase'),
            'in_progress' => __('In Progress', 'newbase'),
            'pending'     => __('Pending', 'newbase'),
            'completed'   => __('Completed', 'newbase'),
            'cancelled'   => __('Cancelled', 'newbase'),
        ];
    }

    /**
     * Get foreign key field name
     * @return string Foreign key field name
     */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_tasks_id';
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
            'field'         => 'title',
            'name'          => __('Title', 'newbase'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'       => '3',
            'table'    => self::getTable(),
            'field'    => 'description',
            'name'     => __('Description'),
            'datatype' => 'text',
        ];

        $tab[] = [
            'id'       => '4',
            'table'    => self::getTable(),
            'field'    => 'date_start',
            'name'     => __('Start date', 'newbase'),
            'datatype' => 'datetime',
        ];

        $tab[] = [
            'id'       => '5',
            'table'    => self::getTable(),
            'field'    => 'date_end',
            'name'     => __('End date', 'newbase'),
            'datatype' => 'datetime',
        ];

        $tab[] = [
            'id'         => '6',
            'table'      => self::getTable(),
            'field'      => 'status',
            'name'       => __('Status'),
            'datatype'   => 'specific',
            'searchtype' => ['equals'],
        ];

        $tab[] = [
            'id'            => '7',
            'table'         => 'glpi_users',
            'field'         => 'name',
            'name'          => __('Assigned to', 'newbase'),
            'datatype'      => 'dropdown',
            'forcegroupby'  => true,
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'       => '8',
            'table'    => 'glpi_entities',
            'field'    => 'name',
            'name'     => __('Company', 'newbase'),
            'datatype' => 'dropdown',
        ];

        $tab[] = [
            'id'       => '10',
            'table'    => self::getTable(),
            'field'    => 'mileage',
            'name'     => __('Mileage (km)', 'newbase'),
            'datatype' => 'decimal',
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
            case 'status':
                $statuses = self::getStatuses();
                return $statuses[$values[$field]] ?? $values[$field];
        }

        return parent::getSpecificValueToDisplay($field, $values, $options);
    }

    /**
     * Display form for task
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
        echo "<td>" . __('Title', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('title', [
            'value' => $this->fields['title'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "<td>" . __('Status') . " <span class='red'>*</span></td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getStatuses(), [
            'value' => $this->fields['status'] ?? 'new',
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Entity::dropdown([
            'name'   => 'entities_id',
            'value'  => $entities_id,
            'entity' => $entities_id,
        ]);
        echo "</td>";
        echo "<td>" . __('Assigned to', 'newbase') . "</td>";
        echo "<td>";
        User::dropdown([
            'name'   => 'users_id',
            'value'  => $this->fields['users_id'] ?? Session::getLoginUserID(),
            'right'  => 'all',
            'entity' => $entities_id,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Address', 'newbase') . "</td>";
        echo "<td>";
        if (class_exists('GlpiPlugin\\Newbase\\Address')) {
            Address::dropdown([
                'name'   => 'plugin_newbase_addresses_id',
                'value'  => $this->fields['plugin_newbase_addresses_id'] ?? 0,
                'entity' => $entities_id,
            ]);
        } else {
            echo Html::input('plugin_newbase_addresses_id', [
                'value' => $this->fields['plugin_newbase_addresses_id'] ?? 0,
                'type'  => 'number',
            ]);
        }
        echo "</td>";
        echo "<td>" . __('System', 'newbase') . "</td>";
        echo "<td>";
        if (class_exists('GlpiPlugin\\Newbase\\System')) {
            System::dropdown([
                'name'   => 'plugin_newbase_systems_id',
                'value'  => $this->fields['plugin_newbase_systems_id'] ?? 0,
                'entity' => $entities_id,
            ]);
        } else {
            echo Html::input('plugin_newbase_systems_id', [
                'value' => $this->fields['plugin_newbase_systems_id'] ?? 0,
                'type'  => 'number',
            ]);
        }
        echo "</td>";
        echo "</tr>";

        // DATES
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Start date', 'newbase') . "</td>";
        echo "<td>";
        Html::showDateTimeField('date_start', [
            'value' => $this->fields['date_start'] ?? '',
        ]);
        echo "</td>";
        echo "<td>" . __('End date', 'newbase') . "</td>";
        echo "<td>";
        Html::showDateTimeField('date_end', [
            'value' => $this->fields['date_end'] ?? '',
        ]);
        echo "</td>";
        echo "</tr>";

        // DESCRIPTION
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

        // GPS & MILEAGE (only for existing tasks)
        if ($ID > 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Mileage (km)', 'newbase') . "</td>";
            echo "<td>";
            echo Html::input('mileage', [
                'value' => $this->fields['mileage'] ?? '',
                'type'  => 'number',
                'step'  => '0.01',
                'min'   => '0',
            ]);
            echo "</td>";
            echo "<td>" . __('GPS Start', 'newbase') . "</td>";
            echo "<td>";
            if (!empty($this->fields['gps_start_lat']) && !empty($this->fields['gps_start_lng'])) {
                echo sprintf(
                    '%.6f, %.6f',
                    $this->fields['gps_start_lat'],
                    $this->fields['gps_start_lng']
                );
            } else {
                echo '-';
            }
            echo "</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('GPS End', 'newbase') . "</td>";
            echo "<td>";
            if (!empty($this->fields['gps_end_lat']) && !empty($this->fields['gps_end_lng'])) {
                echo sprintf(
                    '%.6f, %.6f',
                    $this->fields['gps_end_lat'],
                    $this->fields['gps_end_lng']
                );
            } else {
                echo '-';
            }
            echo "</td>";
            echo "<td>" . __('Signature', 'newbase') . "</td>";
            echo "<td>";
            if (!empty($this->fields['signature_data'])) {
                echo "<span class='badge bg-success'>" . __('Signed', 'newbase') . "</span>";
            } else {
                echo "<span class='badge bg-secondary'>" . __('Not signed', 'newbase') . "</span>";
            }
            echo "</td>";
            echo "</tr>";
        }

        $this->showFormButtons($options);

        return true;
    }

    /**
     * Prepare input for add
     *
     * @param array $input Input data from form
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForAdd(array $input): array|bool
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
        // Validate status
        if (isset($input['status'])) {
            $validStatuses = array_keys(self::getStatuses());
            if (!in_array($input['status'], $validStatuses, true)) {
                Session::addMessageAfterRedirect(
                    __('Invalid task status', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validate GPS coordinates if provided
        if (!empty($input['gps_start_lat']) && !empty($input['gps_start_lng'])) {
            if (!$this->validateCoordinates($input['gps_start_lat'], $input['gps_start_lng'])) {
                Session::addMessageAfterRedirect(
                    __('Invalid GPS coordinates', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validate dates
        if (!empty($input['date_start']) && !empty($input['date_end'])) {
            if (strtotime($input['date_end']) < strtotime($input['date_start'])) {
                Session::addMessageAfterRedirect(
                    __('End date must be after start date', 'newbase'),
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
     *
     * @param array $input Input data from form
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForUpdate(array $input): array|bool
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
        // Validate status if provided
        if (isset($input['status'])) {
            $validStatuses = array_keys(self::getStatuses());
            if (!in_array($input['status'], $validStatuses, true)) {
                Session::addMessageAfterRedirect(
                    __('Invalid task status', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validate dates
        if (isset($input['date_start']) || isset($input['date_end'])) {
            $start = $input['date_start'] ?? $this->fields['date_start'];
            $end = $input['date_end'] ?? $this->fields['date_end'];

            if (!empty($start) && !empty($end)) {
                if (strtotime($end) < strtotime($start)) {
                    Session::addMessageAfterRedirect(
                        __('End date must be after start date', 'newbase'),
                        false,
                        ERROR
                    );
                    return false;
                }
            }
        }

        // Calculate mileage if GPS end is set
        if (!empty($input['gps_end_lat']) && !empty($input['gps_end_lng'])) {
            if ($this->validateCoordinates($input['gps_end_lat'], $input['gps_end_lng'])) {
                if (!empty($this->fields['gps_start_lat']) && !empty($this->fields['gps_start_lng'])) {
                    if (class_exists('GlpiPlugin\\Newbase\\Common')) {
                        $input['mileage'] = Common::calculateDistance(
                            (float) $this->fields['gps_start_lat'],
                            (float) $this->fields['gps_start_lng'],
                            (float) $input['gps_end_lat'],
                            (float) $input['gps_end_lng']
                        );
                    }
                }
            }
        }

        return parent::prepareInputForUpdate($input);
    }

    /**
     * Validate GPS coordinates
     * @param mixed $lat Latitude
     * @param mixed $lng Longitude
     * @return bool Valid coordinates
     */
    private function validateCoordinates(mixed $lat, mixed $lng): bool
    {
        if (!is_numeric($lat) || !is_numeric($lng)) {
            return false;
        }

        $lat = (float) $lat;
        $lng = (float) $lng;

        // Validate latitude range (-90 to 90)
        if ($lat < -90 || $lat > 90) {
            return false;
        }

        // Validate longitude range (-180 to 180)
        if ($lng < -180 || $lng > 180) {
            return false;
        }

        return true;
    }

    /**
     * Actions after adding item
     */
    public function post_addItem(): void
    {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Task added: ID=%d, Title=%s, Status=%s, Entity=%d, User=%d\n",
                $this->fields['id'],
                $this->fields['title'],
                $this->fields['status'],
                $this->fields['entities_id'],
                $this->fields['users_id'] ?? 0
            )
        );
    }

    /**
     * Get tab name for item
     *
     * @param CommonGLPI $item Item
     * @param int $withtemplate Template mode
     * @return string|array Tab name or empty if not applicable
     */
    public function getTabNameForItem(CommonGLPI $item, int $withtemplate = 0): string|array
    {
        if ($item instanceof Entity) {
            if ($_SESSION['glpishow_count_on_tabs'] ?? false) {
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
     * Count tasks for an entity
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
     * Show tasks for an entity
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
            echo "<i class='ti ti-plus'></i> " . __('Add a task', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        // Get tasks
        $iterator = $DB->request([
            'SELECT'    => [
                self::getTable() . '.*',
                'glpi_users.name AS username',
            ],
            'FROM'      => self::getTable(),
            'LEFT JOIN' => [
                'glpi_users' => [
                    'ON' => [
                        self::getTable() => 'users_id',
                        'glpi_users'     => 'id',
                    ],
                ],
            ],
            'WHERE'     => [
                self::getTable() . '.entities_id' => $entity_id,
                self::getTable() . '.is_deleted'  => 0,
            ],
            'ORDER'     => [self::getTable() . '.date_start DESC'],
        ]);

        if (count($iterator) === 0) {
            echo "<div class='center'>";
            echo "<p>" . __('No task found', 'newbase') . "</p>";
            echo "</div>";
            return;
        }

        // Display table
        $statuses = self::getStatuses();

        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead><tr>";
        echo "<th>" . __('ID') . "</th>";
        echo "<th>" . __('Title', 'newbase') . "</th>";
        echo "<th>" . __('Status') . "</th>";
        echo "<th>" . __('Assigned to', 'newbase') . "</th>";
        echo "<th>" . __('Start date', 'newbase') . "</th>";
        echo "<th>" . __('End date', 'newbase') . "</th>";
        echo "<th>" . __('Mileage (km)', 'newbase') . "</th>";
        echo "</tr></thead><tbody>";

        foreach ($iterator as $data) {
            echo "<tr>";
            echo "<td>{$data['id']}</td>";
            echo "<td><a href='" . self::getFormURLWithID($data['id']) . "'><i class='ti ti-checkbox'></i> " . htmlspecialchars($data['title']) . "</a></td>";
            echo "<td>" . ($statuses[$data['status']] ?? $data['status']) . "</td>";
            echo "<td>" . ($data['username'] ?? '-') . "</td>";
            echo "<td>" . Html::convDateTime($data['date_start']) . "</td>";
            echo "<td>" . ($data['date_end'] ? Html::convDateTime($data['date_end']) : '-') . "</td>";
            $mileage = $data['mileage'] ? number_format((float) $data['mileage'], 2, ',', '.') : '-';
            echo "<td>{$mileage}</td>";
            echo "</tr>";
        }

        echo "</tbody></table></div>";
    }

    /**
     * Dropdown for task selection
     *
     * @param array $options Dropdown options (name, value, etc.)
     * @return int|string Dropdown result
     */
    public static function dropdown(array $options = []): int|string
    {
        $defaults = [
            'name'   => 'plugin_newbase_tasks_id',
            'value'  => 0,
            'entity' => $_SESSION['glpiactive_entity'],
        ];

        $options = array_merge($defaults, $options);

        return Dropdown::show(self::class, $options);
    }
}
