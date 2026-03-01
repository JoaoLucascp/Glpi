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
        // Normalize $values to array format if needed
        if (!is_array($values)) {
            $values = [$field => $values];
        }

        switch ($field) {
            case 'system_type':
                $types = self::getSystemTypes();
                return $types[$values[$field]] ?? $values[$field] ?? '';

            case 'status':
                $statuses = self::getSystemStatuses();
                return $statuses[$values[$field]] ?? $values[$field] ?? '';
        }

        return parent::getSpecificValueToDisplay($field, $values, $options);
    }

    /**
     * Display form for system (Bootstrap accordion + cards)
     *
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

        $this->showFormHeader($options);

        $v = $this->fields; // valores atuais

        // ── Linha 1 (fora de tabs): Nome / Entidade / Tipo / Status ───────────
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='required'>*</span></td>";
        echo "<td>";
        Html::autocompletionTextField($this, 'name', ['value' => $v['name'] ?? '', 'size' => 50]);
        echo "</td>";
        echo "<td>" . __('Type', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('system_type', self::getSystemTypes(), ['value' => $v['system_type'] ?? 'ipbx']);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Status') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getSystemStatuses(), ['value' => $v['status'] ?? 'active']);
        echo "</td>";
        echo "<td colspan='2'></td>";
        echo "</tr>";

        // ── Bloco de accordion dentro do formulário padrão ───────────────────
        echo "<tr><td colspan='4' class='p-0'>";
        echo '<div class="newbase-system-form-body px-2 py-3">';
        echo '<div class="accordion accordion-flush nb-accordion" id="sysFormAccordion">';

        // ── Accordion 1: Informações do Servidor ─────────────────────────────
        echo $this->_accordionItem(
            'sysInfoServer',
            'ti-server text-primary',
            'Informações do Servidor',
            true,
            function () use ($v) {
                $rows = [
                    ['Modelo',            'model',       false, 'Newcloud'],
                    ['Versão Servidor',   'version',     false, '3.19'],
                    ['IP Interno',        'internal_ip', false, '192.168.0.10'],
                    ['IP Externo',        'external_ip', false, '200.x.x.x'],
                    ['Porta Acesso Web',  'web_port',    false, '2080'],
                    ['Senha Acesso Web',  'web_password', true, 'senha123'],
                    ['Porta Acesso SSH',  'ssh_port',    false, '2022'],
                    ['Senha Acesso SSH',  'ssh_password', true, 'senha123'],
                ];
                echo '<div class="row g-3">';
                foreach ($rows as [$label, $field, $isPwd, $placeholder]) {
                    $pwdBadge = $isPwd ? '&nbsp;<span class="badge bg-warning-lt">visível</span>' : '';
                    echo '<div class="col-12 col-lg-6">';
                    echo "<label class='form-label nb-" . ($isPwd ? 'pwd-label' : 'label') . "'>{$label}{$pwdBadge}</label>";
                    echo Html::input($field, [
                        'value'       => $v[$field] ?? '',
                        'type'        => 'text',
                        'placeholder' => $placeholder,
                        'class'       => 'form-control nb-fc' . ($isPwd ? ' nb-pwd' : ''),
                    ]);
                    echo '</div>';
                }
                // Observações
                echo '<div class="col-12">';
                echo '<label class="form-label">Observações</label>';
                echo Html::textarea([
                    'name'  => 'observations',
                    'value' => $v['observations'] ?? '',
                    'class' => 'form-control nb-fc',
                    'rows'  => 4,
                ]);
                echo '</div>';
                echo '</div>';
            }
        );

        // ── Accordion 2: Descrição ────────────────────────────────────────────
        echo $this->_accordionItem(
            'sysDesc',
            'ti-file-description text-cyan',
            'Descrição',
            false,
            function () use ($v) {
                echo '<div class="row g-3">';
                echo '<div class="col-12">';
                echo '<label class="form-label">Descrição</label>';
                echo Html::textarea([
                    'name'  => 'description',
                    'value' => $v['description'] ?? '',
                    'class' => 'form-control nb-fc',
                    'rows'  => 5,
                ]);
                echo '</div>';
                echo '<div class="col-12">';
                echo '<label class="form-label">Configuração adicional (JSON)</label>';
                echo Html::textarea([
                    'name'  => 'configuration',
                    'value' => $v['configuration'] ?? '',
                    'class' => 'form-control nb-fc nb-monospace',
                    'rows'  => 5,
                    'placeholder' => '{"chave": "valor"}',
                ]);
                echo '</div>';
                echo '</div>';
            }
        );

        echo '</div>'; // /.accordion
        echo '</div>'; // /.newbase-system-form-body
        echo "</td></tr>";

        $this->showFormButtons($options);

        return true;
    }

    /**
     * Helper: gera HTML de um item de accordion (Bootstrap 5)
     *
     * @param string   $id       ID único
     * @param string   $icon     Classe do ícone Tabler
     * @param string   $title    Título
     * @param bool     $open     Se deve abrir por padrão
     * @param callable $content  Callback que imprime o conteúdo
     * @return string HTML completo do accordion-item
     */
    private function _accordionItem(string $id, string $icon, string $title, bool $open, callable $content): string
    {
        $expanded = $open ? 'true' : 'false';
        $show     = $open ? 'show' : '';
        ob_start();
        $content();
        $body = ob_get_clean();

        return "<div class='accordion-item nb-accordion-item'>"
            . "<h2 class='accordion-header' id='h{$id}'>"
            . "<button class='accordion-button nb-accordion-btn" . ($open ? '' : ' collapsed') . "' type='button'"
            . " data-bs-toggle='collapse' data-bs-target='#p{$id}'"
            . " aria-expanded='{$expanded}' aria-controls='p{$id}'>"
            . "<i class='ti {$icon} me-2'></i><strong>{$title}</strong>"
            . "</button></h2>"
            . "<div id='p{$id}' class='accordion-collapse collapse {$show}' aria-labelledby='h{$id}'>"
            . "<div class='accordion-body'>{$body}</div>"
            . "</div></div>";
    }

    /**
     * Prepare input for add
     *
     * @param array $input Input data from form
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForAdd($input)
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
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
     *
     * @param array $input Input data from form
     * @return array|bool Prepared input or false on error
     */
    public function prepareInputForUpdate($input)
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
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
     *
     * @param array $options Dropdown options (name, value, etc.)
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
