<?php

/**
* Task Class - Field task management with GPS, signature, and mileage
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use CommonGLPI;
use Session;
use Html;
use Toolbox;
use User;
use Entity;
use Dropdown;

/**
* Task - Manages field tasks with geolocation, signatures, and mileage tracking
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
        return $nb > 1 ? __('Tasks', 'newbase') : __('Task', 'newbase');
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
* Get icon for menus
* @return string Icon class
*/
    public static function getIcon(): string
    {
        return 'ti ti-checkbox';
    }

/**
* Get task statuses
* @return array Task statuses [key => label]
*/
    public static function getStatuses(): array
    {
        return [
            'new' => __('New', 'newbase'),
            'assigned' => __('Assigned', 'newbase'),
            'in_progress' => __('In Progress', 'newbase'),
            'pending' => __('Pending', 'newbase'),
            'completed' => __('Completed', 'newbase'),
            'cancelled' => __('Cancelled', 'newbase'),
        ];
    }

/**
* Get foreign key field name
* @return string
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
        $tab = [];

        // Main tab
        $tab[] = [
            'id' => 'common',
            'name' => __('Characteristics'),
        ];

        // ID
        $tab[] = [
            'id' => 1,
            'table' => self::getTable(),
            'field' => 'id',
            'name' => __('ID'),
            'datatype' => 'number',
            'massiveaction' => false,
        ];

        // Title
        $tab[] = [
            'id' => 2,
            'table' => self::getTable(),
            'field' => 'title',
            'name' => __('Title', 'newbase'),
            'datatype' => 'itemlink',
            'massiveaction' => false,
        ];

        // Description
        $tab[] = [
            'id' => 3,
            'table' => self::getTable(),
            'field' => 'description',
            'name' => __('Description'),
            'datatype' => 'text',
            'massiveaction' => false,
        ];

        // Start date
        $tab[] = [
            'id' => 4,
            'table' => self::getTable(),
            'field' => 'date_start',
            'name' => __('Start date', 'newbase'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        // End date
        $tab[] = [
            'id' => 5,
            'table' => self::getTable(),
            'field' => 'date_end',
            'name' => __('End date', 'newbase'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        // Status
        $tab[] = [
            'id' => 6,
            'table' => self::getTable(),
            'field' => 'status',
            'name' => __('Status'),
            'datatype' => 'specific',
            'searchtype' => ['equals'],
            'massiveaction' => false,
        ];

        // Assigned to
        $tab[] = [
            'id' => 7,
            'table' => 'glpi_users',
            'field' => 'name',
            'name' => __('Assigned to', 'newbase'),
            'datatype' => 'dropdown',
            'massiveaction' => false,
        ];

        // Company
        $tab[] = [
            'id' => 8,
            'table' => 'glpi_entities',
            'field' => 'name',
            'name' => __('Company', 'newbase'),
            'datatype' => 'dropdown',
            'massiveaction' => false,
        ];

        // Address
        $tab[] = [
            'id' => 9,
            'table' => Address::getTable(),
            'field' => 'name',
            'name' => __('Address', 'newbase'),
            'datatype' => 'dropdown',
            'massiveaction' => false,
        ];

        // Mileage
        $tab[] = [
            'id' => 10,
            'table' => self::getTable(),
            'field' => 'mileage',
            'name' => __('Mileage (km)', 'newbase'),
            'datatype' => 'decimal',
            'massiveaction' => false,
        ];

        // Creation date
        $tab[] = [
            'id' => 11,
            'table' => self::getTable(),
            'field' => 'date_creation',
            'name' => __('Creation date'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        // Modification date
        $tab[] = [
            'id' => 12,
            'table' => self::getTable(),
            'field' => 'date_mod',
            'name' => __('Last update'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        // Entity
        $tab[] = [
            'id' => 80,
            'table' => 'glpi_entities',
            'field' => 'completename',
            'name' => __('Entity'),
            'datatype' => 'dropdown',
            'massiveaction' => false,
        ];

        // Recursive
        $tab[] = [
            'id' => 86,
            'table' => self::getTable(),
            'field' => 'is_recursive',
            'name' => __('Child entities'),
            'datatype' => 'bool',
            'massiveaction' => false,
        ];

        return $tab;
    }

/**
* Get default columns to display
* @return array Column IDs
*/
    public function getDefaultToDisplay()
    {
        return ['id', 'title', 'status', 'date_start', 'date_end', 'mileage'];
    }

/**
* Display form for task
* @param int $ID Item ID (0 for new)
* @param array $options Additional options
* @return bool Success
*/
    public function showForm($ID, array $options = []): bool
    {
        // Initialize form
        $this->initForm($ID, $options);

        // Check permissions
        if (!$this->canView()) {
            return false;
        }

        // Get entities_id
        $entities_id = $options['entities_id'] ?? $_GET['entities_id'] ?? $this->fields['entities_id'] ?? 0;

        // Start form
        $this->showFormHeader($options);

        // BASIC INFORMATION
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Title', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('title', [
            'value' => $this->fields['title'] ?? '',
            'size' => 50,
            'required' => true,
        ]);
        echo "</td>";
        echo "<td>" . __('Status') . " <span class='red'>*</span></td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getStatuses(), [
            'value' => $this->fields['status'] ?? 'new',
            'required' => true,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Entity::dropdown([
            'name' => 'entities_id',
            'value' => $entities_id,
            'required' => true,
        ]);
        echo "</td>";
        echo "<td>" . __('Assigned to', 'newbase') . "</td>";
        echo "<td>";
        User::dropdown([
            'name' => 'users_id',
            'value' => $this->fields['users_id'] ?? Session::getLoginUserID(),
            'right' => 'all',
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Address', 'newbase') . "</td>";
        echo "<td>";
        Address::dropdown([
            'name' => 'plugin_newbase_addresses_id',
            'value' => $this->fields['plugin_newbase_addresses_id'] ?? 0,
            'entity' => $entities_id,
        ]);
        echo "</td>";
        echo "<td>" . __('System', 'newbase') . "</td>";
        echo "<td>";
        System::dropdown([
            'name' => 'plugin_newbase_systems_id',
            'value' => $this->fields['plugin_newbase_systems_id'] ?? 0,
            'entity' => $entities_id,
        ]);
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
            'name' => 'description',
            'value' => $this->fields['description'] ?? '',
            'cols' => 80,
            'rows' => 4,
        ]);
        echo "</td>";
        echo "</tr>";

        // GPS & MILEAGE (readonly if already set)
        if ($ID > 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Mileage (km)', 'newbase') . "</td>";
            echo "<td>";
            echo Html::input('mileage', [
                'value' => $this->fields['mileage'] ?? '',
                'type' => 'number',
                'step' => '0.01',
            ]);
            echo "</td>";
            echo "<td>" . __('GPS Start', 'newbase') . "</td>";
            echo "<td>";
            if (!empty($this->fields['gps_start_lat']) && !empty($this->fields['gps_start_lng'])) {
                echo sprintf(
                    '%s, %s',
                    $this->fields['gps_start_lat'],
                    $this->fields['gps_start_lng']
                );
            } else {
                echo '-';
            }
            echo "</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Signature', 'newbase') . "</td>";
            echo "<td colspan='3'>";
            TaskSignature::showForTask($this);
            echo "</td>";
            echo "</tr>";
        }

        // Finalize form
        $this->showFormButtons($options);

        return true;
    }

/**
* Prepare input for add
* @param array $input Input data
* @return array|false Prepared input or false on error
*/
    public function prepareInputForAdd($input)
    {
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

        return parent::prepareInputForAdd($input);
    }

/**
* Prepare input for update
* @param array $input Input data
* @return array|false Prepared input or false on error
*/
    public function prepareInputForUpdate($input)
    {
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

        // Calculate mileage if GPS end is set
        if (!empty($input['gps_end_lat']) && !empty($input['gps_end_lng'])) {
            if ($this->validateCoordinates($input['gps_end_lat'], $input['gps_end_lng'])) {
                if (!empty($this->fields['gps_start_lat']) && !empty($this->fields['gps_start_lng'])) {
                    $input['mileage'] = Common::calculateDistance(
                        (float) $this->fields['gps_start_lat'],
                        (float) $this->fields['gps_start_lng'],
                        (float) $input['gps_end_lat'],
                        (float) $input['gps_end_lng']
                    );
                }
            }
        }

        return $input;
    }

/**
* Validate GPS coordinates
* @param mixed $lat Latitude
* @param mixed $lng Longitude
* @return bool Valid coordinates
*/
    private function validateCoordinates($lat, $lng): bool
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
* Get tab name for item
* @param CommonGLPI $item Item
* @param int $withtemplate Template mode
* @return string Tab name
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
* Count tasks for an entity
* @param CommonDBTM $item Entity item
* @return int Count
*/
    public static function countForItem(CommonDBTM $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM' => self::getTable(),
            'WHERE' => [
                'entities_id' => $item->getID(),
                'is_deleted' => 0,
            ],
        ]);

        $result = $iterator->current();
        return (int) ($result['cpt'] ?? 0);
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
            'SELECT' => [
                self::getTable() . '.*',
                'glpi_users.name AS username',
            ],
            'FROM' => self::getTable(),
            'LEFT JOIN' => [
                'glpi_users' => [
                    'FKEY' => [
                        self::getTable() => 'users_id',
                        'glpi_users' => 'id',
                    ],
                ],
            ],
            'WHERE' => [
                self::getTable() . '.entities_id' => $entity_id,
                self::getTable() . '.is_deleted' => 0,
            ],
            'ORDER' => self::getTable() . '.date_start DESC',
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
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('ID') . "</th>";
        echo "<th>" . __('Title', 'newbase') . "</th>";
        echo "<th>" . __('Status') . "</th>";
        echo "<th>" . __('Assigned to', 'newbase') . "</th>";
        echo "<th>" . __('Start date', 'newbase') . "</th>";
        echo "<th>" . __('End date', 'newbase') . "</th>";
        echo "<th>" . __('Mileage (km)', 'newbase') . "</th>";
        if ($canedit) {
            echo "<th>" . __('Actions') . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($iterator as $data) {
            $task = new self();
            $task->getFromDB($data['id']);

            echo "<tr>";

            // ID
            echo "<td>" . $data['id'] . "</td>";

            // Title
            echo "<td>";
            echo "<a href='" . $task->getFormURLWithID($data['id']) . "'>";
            echo "<i class='ti ti-checkbox'></i> ";
            echo htmlspecialchars($data['title']);
            echo "</a>";
            echo "</td>";

            // Status
            echo "<td>" . ($statuses[$data['status']] ?? $data['status']) . "</td>";

            // Assigned to
            echo "<td>" . ($data['username'] ?? '-') . "</td>";

            // Dates
            echo "<td>" . Html::convDateTime($data['date_start']) . "</td>";
            echo "<td>" . ($data['date_end'] ? Html::convDateTime($data['date_end']) : '-') . "</td>";

            // Mileage
            $mileage = $data['mileage'] ? number_format((float) $data['mileage'], 2, ',', '.') : '-';
            echo "<td>" . $mileage . "</td>";

            // Actions
            if ($canedit) {
                echo "<td>";
                echo "<a href='" . $task->getFormURLWithID($data['id']) . "' class='btn btn-sm btn-primary'>";
                echo "<i class='ti ti-edit'></i>";
                echo "</a> ";
                echo Html::getSimpleForm(
                    $task->getFormURL(),
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
}