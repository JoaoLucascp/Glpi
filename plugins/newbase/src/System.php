<?php

/**
* System Class - Telecommunication systems management
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use CommonGLPI;
use Entity;
use Session;
use Html;
use Toolbox;

/**
* System - Manages telecommunication systems
* Supports multiple system types: PABX, IPBX, IPBX Cloud, Chatbot, Landline
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
* Items ID field name
* @var string
*/
    public static $items_id = 'entities_id';

/**
* Item type
* @var string
*/
    public static $itemtype = 'Entity';

/**
* Get type name
* @param int $nb Number of items
* @return string Type name
*/
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Systems', 'newbase') : __('System', 'newbase');
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
* Get system types
* @return array System types [key => label]
*/
    public static function getSystemTypes(): array
    {
        return [
            'pabx' => __('PABX', 'newbase'),
            'ipbx' => __('IPBX', 'newbase'),
            'ipbx_cloud' => __('IPBX Cloud', 'newbase'),
            'chatbot' => __('Chatbot', 'newbase'),
            'landline' => __('Landline', 'newbase'),
        ];
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
            'id' => '2',
            'table' => self::getTable(),
            'field' => 'id',
            'name' => __('ID'),
            'massiveaction' => false,
            'datatype' => 'number',
        ];

        // Name
        $tab[] = [
            'id' => '1',
            'table' => self::getTable(),
            'field' => 'name',
            'name' => __('Name'),
            'datatype' => 'itemlink',
            'massiveaction' => false,
        ];

        // Company
        $tab[] = [
            'id' => '3',
            'table' => 'glpi_entities',
            'field' => 'name',
            'name' => __('Company', 'newbase'),
            'datatype' => 'dropdown',
        ];

        // System Type
        $tab[] = [
            'id' => '4',
            'table' => self::getTable(),
            'field' => 'system_type',
            'name' => __('Type', 'newbase'),
            'datatype' => 'specific',
            'searchtype' => ['equals'],
        ];

        // Description
        $tab[] = [
            'id' => '5',
            'table' => self::getTable(),
            'field' => 'description',
            'name' => __('Description'),
            'datatype' => 'text',
        ];

        // Configuration
        $tab[] = [
            'id' => '6',
            'table' => self::getTable(),
            'field' => 'configuration',
            'name' => __('Configuration', 'newbase'),
            'datatype' => 'text',
            'massiveaction' => false,
        ];

        // Last update
        $tab[] = [
            'id' => '19',
            'table' => self::getTable(),
            'field' => 'date_mod',
            'name' => __('Last update'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        // Creation date
        $tab[] = [
            'id' => '121',
            'table' => self::getTable(),
            'field' => 'date_creation',
            'name' => __('Creation date'),
            'datatype' => 'datetime',
            'massiveaction' => false,
        ];

        return $tab;
    }

/**
* Display form for system with dynamic fields
* @param int $ID Item ID (0 for new)
* @param array $options Additional options
* @return bool Success
*/
    public function showForm($ID, array $options = []): bool
    {
        // Initialize form with permissions and data
        $this->initForm($ID, $options);

        // Check permissions
        if (!$this->canView()) {
            return false;
        }

        // Get entities_id from URL or form (FK to glpi_entities)
        $entities_id = $options['entities_id'] ?? $_GET['entities_id'] ?? $this->fields['entities_id'] ?? 0;

        // Start form rendering
        $this->showFormHeader($options);

        // BASIC INFORMATION
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size' => 50,
            'required' => true,
        ]);
        echo "</td>";
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Entity::dropdown([
            'name' => 'entities_id',
            'value' => $entities_id,
            'required' => true,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Type', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        \Dropdown::showFromArray('system_type', self::getSystemTypes(), [
            'value' => $this->fields['system_type'] ?? 'pabx',
            'required' => true,
        ]);
        echo "</td>";
        echo "<td>" . __('Status') . "</td>";
        echo "<td>";
        \Dropdown::showFromArray('status', [
            'active' => __('Active'),
            'inactive' => __('Inactive'),
            'maintenance' => __('Maintenance'),
        ], [
            'value' => $this->fields['status'] ?? 'active',
        ]);
        echo "</td>";
        echo "</tr>";

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

        // CONFIGURATION (JSON)
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Configuration', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name' => 'configuration',
            'value' => $this->fields['configuration'] ?? '',
            'cols' => 80,
            'rows' => 8,
        ]);
        echo "<br><small class='text-muted'>" . __('JSON format for technical configuration', 'newbase') . "</small>";
        echo "</td>";
        echo "</tr>";

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

        // VALIDATE SYSTEM TYPE
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

        // VALIDATE CONFIGURATION JSON
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
* @return array|false Prepared input or false on error
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

        return $input;
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
                $count = self::countForItem($item);
                return self::createTabEntry(
                    self::getTypeName(Session::getPluralNumber()),
                    $count
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
            'FROM' => self::getTable(),
            'WHERE' => [
                'entities_id' => $entity_id,
                'is_deleted' => 0,
            ],
            'ORDER' => 'name',
        ]);

        if (count($iterator) === 0) {
            echo "<div class='center'>";
            echo "<p>" . __('No system registered for this company', 'newbase') . "</p>";
            echo "</div>";
            return;
        }

        // Display table
        $types = self::getSystemTypes();

        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('Name') . "</th>";
        echo "<th>" . __('Type', 'newbase') . "</th>";
        echo "<th>" . __('Description') . "</th>";
        if ($canedit) {
            echo "<th>" . __('Actions') . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($iterator as $data) {
            $system = new self();
            $system->getFromDB($data['id']);

            echo "<tr>";

            // Name
            echo "<td>";
            echo "<a href='" . $system->getFormURLWithID($data['id']) . "'>";
            echo "<i class='ti ti-server'></i> ";
            echo htmlspecialchars($data['name']);
            echo "</a>";
            echo "</td>";

            // Type
            echo "<td>" . ($types[$data['system_type']] ?? $data['system_type']) . "</td>";

            // Description (truncated)
            echo "<td>" . (substr($data['description'] ?? '', 0, 100) ?: '-') . "</td>";

            // Actions
            if ($canedit) {
                echo "<td>";
                echo "<a href='" . $system->getFormURLWithID($data['id']) . "' class='btn btn-sm btn-primary'>";
                echo "<i class='ti ti-edit'></i>";
                echo "</a> ";
                echo Html::getSimpleForm(
                    $system->getFormURL(),
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
