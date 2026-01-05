<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonGLPI;

/**
 * Task class for Newbase Plugin
 *
 * Manages tasks with geolocation, status tracking, mileage calculation
 * and assignment to users
 *
 * @package   PluginNewbase
 * @author    JoÃƒÂ£o Lucas
 * @copyright Copyright (c) 2025 JoÃƒÂ£o Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
/**
 * Gerenciamento de tarefas com geolocalizacao e assinatura digital
 *
 * @package   PluginNewbase
 * @author    Joao Lucas
 * @copyright Copyright (c) 2025 Joao Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class Task extends CommonDBTM
{
    // Right name for permissions
    public static $rightname = 'plugin_newbase_task';

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
        return 'glpi_plugin_newbase_task';
    }

    /**
     * Get type name
     *
     * @param int $nb Number of items
     * @return string
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Task', 'Tasks', $nb, 'newbase');
    }

    /**
     * Get foreign key field name
     *
     * @return string
     */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_task_id';
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
     * Get menu content
     *
     * @return array
     */
    public static function getMenuContent(): array
    {
        $menu = [];

        if (Session::haveRight(self::$rightname, READ)) {
            $menu['title'] = self::getTypeName(Session::getPluralNumber());
            $menu['page'] = '/plugins/newbase/front/task.php';
            $menu['icon'] = 'ti ti-checklist';

            $menu['options']['task'] = [
                'title' => self::getTypeName(Session::getPluralNumber()),
                'page'  => '/plugins/newbase/front/task.php',
                'icon'  => 'ti ti-checklist'
            ];
        }

        if (count($menu)) {
            return $menu;
        }

        return [];
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
     * Count tasks for a company
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
     * Show tasks for a company
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
            echo "<a class='btn btn-primary' href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.form.php?plugin_newbase_companydata_id=" . $company_id . "'>";
            echo "<i class='fas fa-plus'></i>&nbsp;" . __('Add task', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        $iterator = $DB->request([
            'SELECT' => [self::getTable() . '.*', 'glpi_users.name AS username'],
            'FROM'  => self::getTable(),
            'LEFT JOIN' => [
                'glpi_users' => [
                    'ON' => [
                        self::getTable() => 'assigned_to',
                        'glpi_users' => 'id'
                    ]
                ]
            ],
            'WHERE' => ['plugin_newbase_companydata_id' => $company_id],
            'ORDER' => 'date_start DESC'
        ]);

        if (count($iterator)) {
            echo "<table class='tab_cadre_fixehov'>";
            echo "<tr class='noHover'>";
            echo "<th colspan='8'>" . self::getTypeName(count($iterator)) . "</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>" . __('ID') . "</th>";
            echo "<th>" . __('Title', 'newbase') . "</th>";
            echo "<th>" . __('Status', 'newbase') . "</th>";
            echo "<th>" . __('Assigned to', 'newbase') . "</th>";
            echo "<th>" . __('Start Date', 'newbase') . "</th>";
            echo "<th>" . __('End Date', 'newbase') . "</th>";
            echo "<th>" . __('Mileage (km)', 'newbase') . "</th>";
            echo "<th>" . __('Actions') . "</th>";
            echo "</tr>";

            $statuses = self::getTaskStatuses();

            foreach ($iterator as $data) {
                $status_class = 'tab_bg_1';
                if ($data['status'] === 'completed') {
                    $status_class = 'tab_bg_3';
                } elseif ($data['status'] === 'in_progress') {
                    $status_class = 'tab_bg_2';
                }

                echo "<tr class='$status_class'>";
                echo "<td>" . $data['id'] . "</td>";
                echo "<td><b>" . $data['title'] . "</b></td>";
                echo "<td>" . ($statuses[$data['status']] ?? $data['status']) . "</td>";
                echo "<td>" . ($data['username'] ?? '-') . "</td>";
                echo "<td>" . Html::convDateTime($data['date_start']) . "</td>";
                echo "<td>" . ($data['date_end'] ? Html::convDateTime($data['date_end']) : '-') . "</td>";
                echo "<td>" . ($data['mileage'] ? number_format((float)$data['mileage'], 2, ',', '.') : '-') . "</td>";
                echo "<td>";
                if ($canedit) {
                    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.form.php?id=" . $data['id'] . "'>";
                    echo "<i class='fas fa-edit'></i></a>&nbsp;";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_2'><th>" . __('No task found', 'newbase') . "</th></tr>";
            echo "</table>";
        }

        echo "</div>";
    }

    /**
     * Get task statuses array
     *
     * @return array
     */
    public static function getTaskStatuses(): array
    {
        return [
            'open' => __('Open', 'newbase'),
            'in_progress' => __('In Progress', 'newbase'),
            'paused' => __('Paused', 'newbase'),
            'completed' => __('Completed', 'newbase')
        ];
    }

    /**
     * Display form for task
     *
     * @param int   $ID      ID of the item
     * @param array $options Options
     * @return bool
     */
    public function showForm($ID, array $options = []): bool
    {
        global $CFG_GLPI;

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

        echo "<td>" . __('Title', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        echo Html::input('title', [
            'value' => $this->fields['title'] ?? '',
            'required' => true
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Status', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getTaskStatuses(), [
            'value' => $this->fields['status'] ?? 'open'
        ]);
        echo "</td>";

        echo "<td>" . __('Assigned to', 'newbase') . "</td>";
        echo "<td>";
        User::dropdown([
            'name' => 'assigned_to',
            'value' => $this->fields['assigned_to'] ?? 0,
            'right' => 'all'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Start Date', 'newbase') . "</td>";
        echo "<td>";
        Html::showDateTimeField('date_start', [
            'value' => $this->fields['date_start'] ?? date('Y-m-d H:i:s')
        ]);
        echo "</td>";

        echo "<td>" . __('End Date', 'newbase') . "</td>";
        echo "<td>";
        Html::showDateTimeField('date_end', [
            'value' => $this->fields['date_end'] ?? ''
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

        // Geolocation section
        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Geolocation', 'newbase') . "</th>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Start Latitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('lat_start', [
            'value' => $this->fields['lat_start'] ?? '',
            'type' => 'number',
            'step' => '0.00000001'
        ]);
        echo "</td>";
        echo "<td>" . __('Start Longitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('lng_start', [
            'value' => $this->fields['lng_start'] ?? '',
            'type' => 'number',
            'step' => '0.00000001'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('End Latitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('lat_end', [
            'value' => $this->fields['lat_end'] ?? '',
            'type' => 'number',
            'step' => '0.00000001'
        ]);
        echo "</td>";
        echo "<td>" . __('End Longitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('lng_end', [
            'value' => $this->fields['lng_end'] ?? '',
            'type' => 'number',
            'step' => '0.00000001'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Mileage (km)', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::input('mileage', [
            'value' => $this->fields['mileage'] ?? '',
            'type' => 'number',
            'step' => '0.01'
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
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = Toolbox::sanitizeString($value);
            }
        }

        if (empty($input['plugin_newbase_companydata_id'])) {
            Session::addMessageAfterRedirect(
                __('Company is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (empty($input['title'])) {
            Session::addMessageAfterRedirect(
                __('Title is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }
}

