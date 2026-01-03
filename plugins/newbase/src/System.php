<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use CommonGLPI;
use Session;
use Html;
use Dropdown;
use Toolbox;

/**
 * System class for Newbase Plugin
 *
 * Manages communication systems (IPBX, PABX, Chatbot, IPBX Cloud, Telephone Lines)
 * associated with companies
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class System extends CommonDBTM
{
    // Right name for permissions
    public static $rightname = 'plugin_newbase_system';

    // Enable history
    public $dohistory = true;

    /**
     * Get table name
     *
     * @param string|null $classname Class name
     * @return string
     */
    public static function getTable($classname = null): string
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_system';
    }

    /**
     * Get type name
     *
     * @param int $nb Number of items
     * @return string
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Communication System', 'Communication Systems', $nb, 'newbase');
    }

    /**
     * Get foreign key field name
     *
     * @return string
     */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_system_id';
    }

    /**
     * Check if user can view item
     *
     * @return bool
     */
    public static function canView(): bool
    {
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    /**
     * Check if user can create item
     *
     * @return bool
     */
    public static function canCreate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    /**
     * Check if user can update item
     *
     * @return bool
     */
    public static function canUpdate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    /**
     * Check if user can delete item
     *
     * @return bool
     */
    public static function canDelete(): bool
    {
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    /**
     * Check if user can purge item
     *
     * @return bool
     */
    public static function canPurge(): bool
    {
        return (bool) Session::haveRight(self::$rightname, PURGE);
    }

    /**
     * Get tab name for item
     *
     * @param CommonGLPI $item         Item
     * @param int        $withtemplate Template
     * @return string
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if ($item instanceof CompanyData) {
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
     * Display tab content
     *
     * @param CommonGLPI $item         Item
     * @param int        $tabnum       Tab number
     * @param int        $withtemplate Template
     * @return bool
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item instanceof CompanyData) {
            self::showForCompany($item);
            return true;
        }
        return false;
    }

    /**
     * Count systems for a company
     *
     * @param CommonGLPI $item Company item
     * @return int
     */
    public static function countForItem(CommonGLPI $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM'  => self::getTable(),
            'WHERE' => [
                'plugin_newbase_companydata_id' => $item->getID()
            ]
        ]);

        $result = $iterator->current();
        return (int)($result['cpt'] ?? 0);
    }

    /**
     * Show systems for a company
     *
     * @param CompanyData $company Company item
     * @return void
     */
    public static function showForCompany(CompanyData $company): void
    {
        global $DB, $CFG_GLPI;

        $company_id = $company->getID();
        $canedit = $company->canUpdate();

        echo "<div class='spaced'>";

        if ($canedit) {
            echo "<div class='center firstbloc'>";
            echo "<a class='btn btn-primary' href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php?plugin_newbase_companydata_id=" . $company_id . "'>";
            echo "<i class='fas fa-plus'></i>&nbsp;" . __('Add system', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['plugin_newbase_companydata_id' => $company_id],
            'ORDER' => 'name ASC'
        ]);

        if (count($iterator)) {
            echo "<table class='tab_cadre_fixehov'>";
            echo "<tr class='noHover'>";
            echo "<th colspan='6'>" . self::getTypeName(count($iterator)) . "</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>" . __('ID') . "</th>";
            echo "<th>" . __('Name', 'newbase') . "</th>";
            echo "<th>" . __('Type', 'newbase') . "</th>";
            echo "<th>" . __('Status', 'newbase') . "</th>";
            echo "<th>" . __('Description', 'newbase') . "</th>";
            echo "<th>" . __('Actions') . "</th>";
            echo "</tr>";

            $types = self::getSystemTypes();
            $statuses = self::getSystemStatuses();

            foreach ($iterator as $data) {
                echo "<tr class='tab_bg_1'>";
                echo "<td>" . $data['id'] . "</td>";
                echo "<td><b>" . $data['name'] . "</b></td>";
                echo "<td>" . ($types[$data['type']] ?? $data['type']) . "</td>";
                echo "<td>" . ($statuses[$data['status']] ?? $data['status']) . "</td>";
                echo "<td>" . substr((string)($data['description'] ?? ''), 0, 100) . "</td>";
                echo "<td>";
                if ($canedit) {
                    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php?id=" . $data['id'] . "'>";
                    echo "<i class='fas fa-edit'></i></a>&nbsp;";

                    echo Html::getSimpleForm(
                        $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php",
                        ['delete' => 'delete'],
                        '',
                        ['id' => $data['id']],
                        "<i class='fas fa-trash'></i>"
                    );
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_2'><th>" . __('No system found', 'newbase') . "</th></tr>";
            echo "</table>";
        }

        echo "</div>";
    }

    /**
     * Get system types array
     *
     * @return array
     */
    public static function getSystemTypes(): array
    {
        return [
            'ipbx' => __('IPBX', 'newbase'),
            'pabx' => __('PABX', 'newbase'),
            'chatbot' => __('Chatbot', 'newbase'),
            'ipbx_cloud' => __('IPBX Cloud', 'newbase'),
            'telephone_line' => __('Telephone Line', 'newbase')
        ];
    }

    /**
     * Get system statuses array
     *
     * @return array
     */
    public static function getSystemStatuses(): array
    {
        return [
            'active' => __('Active', 'newbase'),
            'inactive' => __('Inactive', 'newbase')
        ];
    }

    /**
     * Display form for system
     *
     * @param int   $ID      ID of the item
     * @param array $options Options
     * @return bool
     */
    public function showForm($ID, array $options = []): bool
    {
        if (!$this->canView()) {
            return false;
        }

        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Company', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        CompanyData::dropdown([
            'name' => 'plugin_newbase_companydata_id',
            'value' => $this->fields['plugin_newbase_companydata_id'] ?? $options['plugin_newbase_companydata_id'] ?? 0,
            'required' => true
        ]);
        echo "</td>";

        echo "<td>" . __('Name', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'required' => true
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Type', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        Dropdown::showFromArray('type', self::getSystemTypes(), [
            'value' => $this->fields['type'] ?? 'ipbx'
        ]);
        echo "</td>";

        echo "<td>" . __('Status', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getSystemStatuses(), [
            'value' => $this->fields['status'] ?? 'active'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Description', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name' => 'description',
            'value' => $this->fields['description'] ?? '',
            'rows' => 5,
            'cols' => 80
        ]);
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }

    /**
     * Prepare input for add
     *
     * @param array $input Input data
     * @return array|false
     */
    public function prepareInputForAdd($input)
    {
        return $this->validateInput($input);
    }

    /**
     * Prepare input for update
     *
     * @param array $input Input data
     * @return array|false
     */
    public function prepareInputForUpdate($input)
    {
        return $this->validateInput($input);
    }

    /**
     * Validate input data
     *
     * @param array $input Input data
     * @return array|false
     */
    private function validateInput(array $input)
    {
        // Sanitize all string inputs
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = Toolbox::sanitizeString($value);
            }
        }

        // Validate required fields
        if (empty($input['plugin_newbase_companydata_id'])) {
            Session::addMessageAfterRedirect(
                __('Company is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (empty($input['name'])) {
            Session::addMessageAfterRedirect(
                __('Name is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }
}
