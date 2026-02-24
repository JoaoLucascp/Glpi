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
use Toolbox;
use Plugin;
use Session;
use Html;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * CompanyData Class - Company data management for Newbase plugin
 *
 * Extends Entity data with additional fields stored in glpi_plugin_newbase_company_extras.
 * Manages Brazilian company-specific data like CNPJ, corporate name, and fantasy name.
 *
 * @package GlpiPlugin\Newbase
 */
class CompanyData extends CommonDBTM
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
     * Get table name - Override to use correct table
     * @param string|null $classname Class name
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_company_extras';
    }

    /**
     * Get type name
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Company', 'Companies', $nb, 'newbase');
    }

    /**
     * Get icon for menus (Tabler Icons)
     * @return string Icon class
     */
    public static function getIcon(): string
    {
        return 'ti ti-building';
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
     * Get company search URL
     * @param bool $full Full path
     * @return string Search URL
     */
    public static function getSearchURL($full = true): string
    {
        return Plugin::getWebDir('newbase', $full) . '/front/companydata.php';
    }

    /**
     * Get company form URL
     * @param bool $full Full path
     * @return string Form URL
     */
    public static function getFormURL($full = true): string
    {
        return Plugin::getWebDir('newbase', $full) . '/front/companydata.form.php';
    }

    // ========== TAB SYSTEM ==========

    /**
     * Define tabs for CompanyData
     * 
     * @param array $options Options
     * @return array Tab names
     */
    public function defineTabs($options = []) // ✅ ERRO 26: Era defineTab (sem 's')
    {
        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab(__CLASS__, $ong, $options);
        return $ong;
    }

    /**
     * Get tab name for item
     * 
     * @param CommonGLPI $item Item
     * @param int        $withtemplate Template flag
     * @return string Tab name
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if ($item->getType() === self::getType()) {
            return self::createTabEntry(__('Systems Configuration', 'newbase'));
        }
        return '';
    }

    /**
     * Display tab content
     * 
     * @param CommonGLPI $item         Item
     * @param int        $tabnum       Tab number
     * @param int        $withtemplate Template flag
     * @return bool Success
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item->getType() === self::getType()) {
            self::showSystemsConfigTab($item);
            return true;
        }
        return false;
    }

    /**
     * Show systems configuration tab (IPBX/PABX, IPBX Cloud, Chatbot, Linha)
     * 
     * @param CompanyData $item Company item
     * @return void
     */
    private static function showSystemsConfigTab(CompanyData $item): void
    {
        $ID = $item->getID();
        $systems_config = json_decode($item->fields['systems_config'] ?? '{}', true) ?: [];

        echo "<div class='center'>";
        echo "<form method='post' action='" . $item->getFormURL() . "'>";
        echo Html::hidden('id', ['value' => $ID]);
        echo Html::hidden('_glpi_csrf_token');

        // IPBX/PABX Section
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h3>" . __('IPBX/PABX', 'newbase') . "</h3></div>";
        echo "<div class='card-body'>";
        
        $ipbx = $systems_config['ipbx'] ?? [];
        
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Model', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][model]', ['value' => $ipbx['model'] ?? '']) . "</td>";
        echo "<td>" . __('Version', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][version]', ['value' => $ipbx['version'] ?? '']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Internal IP', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][ip_interno]', ['value' => $ipbx['ip_interno'] ?? '']) . "</td>";
        echo "<td>" . __('External IP', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][ip_externo]', ['value' => $ipbx['ip_externo'] ?? '']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Web Port', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][porta_web]', ['value' => $ipbx['porta_web'] ?? '']) . "</td>";
        echo "<td>" . __('Web Password', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][senha_web]', ['value' => $ipbx['senha_web'] ?? '', 'type' => 'text']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('SSH Port', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][porta_ssh]', ['value' => $ipbx['porta_ssh'] ?? '']) . "</td>";
        echo "<td>" . __('SSH Password', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[ipbx][senha_ssh]', ['value' => $ipbx['senha_ssh'] ?? '', 'type' => 'text']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td colspan='4'>" . __('Observations', 'newbase') . "<br>";
        echo Html::textarea(['name' => 'systems_config[ipbx][observacoes]', 'value' => $ipbx['observacoes'] ?? '', 'cols' => 80, 'rows' => 3]);
        echo "</td></tr>";
        echo "</table>";
        echo "</div></div>";

        // IPBX Cloud Section
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h3>" . __('IPBX Cloud', 'newbase') . "</h3></div>";
        echo "<div class='card-body'>";
        
        $cloud = $systems_config['cloud'] ?? [];
        
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Model', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][model]', ['value' => $cloud['model'] ?? '']) . "</td>";
        echo "<td>" . __('Version', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][version]', ['value' => $cloud['version'] ?? '']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Internal IP', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][ip_interno]', ['value' => $cloud['ip_interno'] ?? '']) . "</td>";
        echo "<td>" . __('External IP', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][ip_externo]', ['value' => $cloud['ip_externo'] ?? '']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Web Port', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][porta_web]', ['value' => $cloud['porta_web'] ?? '']) . "</td>";
        echo "<td>" . __('Web Password', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][senha_web]', ['value' => $cloud['senha_web'] ?? '', 'type' => 'text']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('SSH Port', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][porta_ssh]', ['value' => $cloud['porta_ssh'] ?? '']) . "</td>";
        echo "<td>" . __('SSH Password', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[cloud][senha_ssh]', ['value' => $cloud['senha_ssh'] ?? '', 'type' => 'text']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td colspan='4'>" . __('Observations', 'newbase') . "<br>";
        echo Html::textarea(['name' => 'systems_config[cloud][observacoes]', 'value' => $cloud['observacoes'] ?? '', 'cols' => 80, 'rows' => 3]);
        echo "</td></tr>";
        echo "</table>";
        echo "</div></div>";

        // Chatbot Section
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h3>" . __('Chatbot', 'newbase') . "</h3></div>";
        echo "<div class='card-body'>";
        
        $chatbot = $systems_config['chatbot'] ?? [];
        
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Platform', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[chatbot][platform]', ['value' => $chatbot['platform'] ?? '']) . "</td>";
        echo "<td>" . __('API Key', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[chatbot][api_key]', ['value' => $chatbot['api_key'] ?? '', 'type' => 'text']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td colspan='4'>" . __('Configuration', 'newbase') . "<br>";
        echo Html::textarea(['name' => 'systems_config[chatbot][config]', 'value' => $chatbot['config'] ?? '', 'cols' => 80, 'rows' => 3]);
        echo "</td></tr>";
        echo "</table>";
        echo "</div></div>";

        // Linha Telefônica Section
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h3>" . __('Phone Line', 'newbase') . "</h3></div>";
        echo "<div class='card-body'>";
        
        $linha = $systems_config['linha'] ?? [];
        
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Provider', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[linha][provider]', ['value' => $linha['provider'] ?? '']) . "</td>";
        echo "<td>" . __('Contract Number', 'newbase') . "</td>";
        echo "<td>" . Html::input('systems_config[linha][contract]', ['value' => $linha['contract'] ?? '']) . "</td>";
        echo "</tr>";
        
        echo "<tr class='tab_bg_1'>";
        echo "<td colspan='4'>" . __('Notes', 'newbase') . "<br>";
        echo Html::textarea(['name' => 'systems_config[linha][notes]', 'value' => $linha['notes'] ?? '', 'cols' => 80, 'rows' => 3]);
        echo "</td></tr>";
        echo "</table>";
        echo "</div></div>";

        echo "<div class='center'>";
        echo "<button type='submit' name='update' class='btn btn-primary'>";
        echo "<i class='ti ti-device-floppy'></i> " . __('Save');
        echo "</button></div>";
        
        Html::closeForm();
        echo "</div>";
    }

    // ========== DATA RETRIEVAL METHODS ==========

    /**
     * Get all active companies from glpi_entities
     * @return array Associative array [id => name, ...]
     */
    public static function getAllCompanies(): array
    {
        global $DB;

        $companies = [];

        $result = $DB->request([
            'FROM'  => 'glpi_entities',
            'WHERE' => ['is_deleted' => 0],
            'ORDER' => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[$entity['id']] = $entity['name'];
        }

        return $companies;
    }

    /**
     * Get complete company data by ID
     * 
     * Combines data from glpi_entities and glpi_plugin_newbase_company_extras
     * 
     * @param int $entity_id Entity ID (glpi_entities.id)
     * @return array|null Array with company data or null
     */
    public static function getCompanyById(int $entity_id): ?array
    {
        global $DB;

        $entity = $DB->request([
            'FROM'  => 'glpi_entities',
            'WHERE' => ['id' => $entity_id, 'is_deleted' => 0],
        ])->current();

        if (!$entity) {
            return null;
        }

        // Get complementary data
        $extras = self::getCompanyExtras($entity_id);

        // Merge data (glpi_entities + extras)
        return array_merge($entity, $extras ?? []);
    }

    /**
     * Get company by CNPJ
     * @param string $cnpj CNPJ with or without formatting
     * @return array|null Array with company data or null
     */
    public static function getCompanyByCNPJ(string $cnpj): ?array
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'cnpj'       => $cnpj,
                'is_deleted' => 0,
            ],
            'LIMIT' => 1,
        ]);

        if (count($iterator) === 0) {
            return null;
        }

        return $iterator->current();
    }

    /**
     * Get company complementary data
     * @param int $entity_id Entity ID
     * @return array|null Array with complementary data or null
     */
    public static function getCompanyExtras(int $entity_id): ?array
    {
        global $DB;

        $result = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => [
                'entities_id' => $entity_id,
                'is_deleted'  => 0,
            ],
        ])->current();

        return $result ?: null;
    }

    /**
     * Save or update company complementary data
     * @param int   $entity_id Entity ID
     * @param array $data      Data to save (cnpj, corporate_name, fantasy_name, etc)
     * @return int|bool Record ID or false
     */
    public static function saveCompanyExtras(int $entity_id, array $data): int|bool
    {
        // Validate data
        if (empty($data)) {
            return false;
        }

        // VALIDAR CNPJ SE FORNECIDO
        if (!empty($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);

            if (class_exists('GlpiPlugin\\Newbase\\Common')
                && method_exists('GlpiPlugin\\Newbase\\Common', 'validateCNPJ')) {

                if (!Common::validateCNPJ($data['cnpj'])) {
                    Toolbox::logInFile(
                        'newbase_plugin',
                        "Invalid CNPJ provided: {$data['cnpj']}\n"
                    );
                    return false;
                }
            }
        }

        // Normalizar telefone (se informado)
        if (!empty($data['phone'])) {
            $rawPhone = preg_replace('/[^0-9]/', '', $data['phone']);

            if (!Common::validatePhone($rawPhone)) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Invalid phone provided: {$data['phone']}\n"
                );
                return false;
            }

            $data['phone'] = Common::formatPhone($rawPhone);
        }

        // Normalizar CEP (se informado)
        if (!empty($data['cep'])) {
            $rawCep = preg_replace('/[^0-9]/', '', $data['cep']);

            if (!Common::validateCEP($rawCep)) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Invalid CEP provided: {$data['cep']}\n"
                );
                return false;
            }

            $data['cep'] = Common::formatCEP($rawCep);
        }

        // Prepare data
        $companyExtras = new self();
        $data['entities_id'] = $entity_id;

        // Check if record already exists
        if ($companyExtras->getFromDBByCrit(['entities_id' => $entity_id])) {
            $data['id'] = $companyExtras->getID();
            if ($companyExtras->update($data)) {
                return $companyExtras->getID();
            }
        } else {
            $data['is_deleted'] = 0;
            if ($newID = $companyExtras->add($data)) {
                return $newID;
            }
        }

        Toolbox::logInFile(
            'newbase_plugin',
            "Failed to save company extras for entity {$entity_id}\n"
        );
        return false;
    }

    /**
     * Prepare input for add
     * 
     * @param array $input Input data
     * @return array|false Modified input or false
     */
    public function prepareInputForAdd($input)
    {
        // Process systems_config array into JSON
        if (isset($input['systems_config']) && is_array($input['systems_config'])) {
            $input['systems_config'] = json_encode($input['systems_config']);
        }
        
        // ✅ ERRO 24: Converter strings vazias para NULL em campos decimais
        $decimal_fields = ['latitude', 'longitude'];
        foreach ($decimal_fields as $field) {
            if (isset($input[$field]) && $input[$field] === '') {
                $input[$field] = null;
            }
        }

        return parent::prepareInputForAdd($input);
    }

    /**
     * Prepare input for update
     * 
     * @param array $input Input data
     * @return array|false Modified input or false
     */
    public function prepareInputForUpdate($input)
    {
        // Process systems_config array into JSON
        if (isset($input['systems_config']) && is_array($input['systems_config'])) {
            $input['systems_config'] = json_encode($input['systems_config']);
        }
        
        // ✅ ERRO 24: Converter strings vazias para NULL em campos decimais
        $decimal_fields = ['latitude', 'longitude'];
        foreach ($decimal_fields as $field) {
            if (isset($input[$field]) && $input[$field] === '') {
                $input[$field] = null;
            }
        }

        return parent::prepareInputForUpdate($input);
    }

    /**
     * Search companies by term
     * @param string $search Search term
     * @param int    $limit  Result limit
     * @return array Array of found companies
     */
    public static function searchCompanies(string $search, int $limit = 20): array
    {
        global $DB;

        $companies = [];

        $result = $DB->request([
            'FROM'  => 'glpi_entities',
            'WHERE' => [
                'is_deleted' => 0,
                'name'       => ['LIKE', '%' . $search . '%'],
            ],
            'LIMIT' => $limit,
            'ORDER' => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[] = [
                'id'   => $entity['id'],
                'name' => htmlspecialchars($entity['name'], ENT_QUOTES, 'UTF-8'),
            ];
        }

        return $companies;
    }

    /**
     * Display dropdown for company selection
     * 
     * Wrapper around Entity::dropdown() for consistency
     *
     * @param array $options Dropdown options (name, value, etc.)
     * @return int|string Dropdown result
     */
    public static function dropdown($options = [])
    {
        $defaults = [
            'name'    => 'entities_id',
            'value'   => 0,
            'display' => true,
            'entity'  => -1,
        ];

        $options = array_merge($defaults, $options);

        // Use native Entity dropdown
        return Entity::dropdown($options);
    }

    /**
     * Get search options for GLPI search engine
     * @return array Search options
     */
    public function rawSearchOptions(): array
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'            => '2',
            'table'         => 'glpi_entities',
            'field'         => 'name',
            'name'          => __('Name'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'       => '3',
            'table'    => $this->getTable(),
            'field'    => 'cnpj',
            'name'     => __('CNPJ', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '4',
            'table'    => $this->getTable(),
            'field'    => 'corporate_name',
            'name'     => __('Corporate Name', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '5',
            'table'    => $this->getTable(),
            'field'    => 'fantasy_name',
            'name'     => __('Fantasy Name', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '6',
            'table'    => $this->getTable(),
            'field'    => 'contact_person',
            'name'     => __('Contact Person', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '7',
            'table'    => $this->getTable(),
            'field'    => 'website',
            'name'     => __('Website', 'newbase'),
            'datatype' => 'weblink',
        ];

        $tab[] = [
            'id'            => '19',
            'table'         => $this->getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => '121',
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        return $tab;
    }

    /**
     * Display form for company
     *
     * @param int   $ID      Item ID (0 for new)
     * @param array $options Form options
     * @return bool Success
     */
    public function showForm($ID, array $options = []): bool
    {
        $this->initForm($ID, $options);

        if (!$this->canView()) {
            return false;
        }

        $this->showFormHeader($options);
        
        // ✅ CORREÇÃO ERRO 18/19: Adicionar token CSRF manualmente após showFormHeader
        if (!isset($_SESSION['_glpi_csrf_token'])) {
            Session::getNewCSRFToken();
        }
        echo Html::hidden('_glpi_csrf_token');

        // === SEÇÃO: DADOS PESSOAIS ===
        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Personal Data', 'newbase') . "</th>";
        echo "</tr>";

        // Nome / CNPJ
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";

        echo "<td>" . __('CNPJ', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('cnpj', [
            'value'     => $this->fields['cnpj'] ?? '',
            'size'      => 20,
            'maxlength' => 18,
            'name'      => 'cnpj',
        ]);
        echo "&nbsp;<button type='button' class='btn btn-secondary' data-action='search-cnpj'>";
        echo "<i class='ti ti-search'></i></button>";
        echo "</td>";
        echo "</tr>";

        // Email / Telefone
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Email') . "</td>";
        echo "<td>";
        echo Html::input('email', [
            'value' => $this->fields['email'] ?? '',
            'type'  => 'email',
            'size'  => 50,
        ]);
        echo "</td>";

        echo "<td>" . __('Phone') . "</td>";
        echo "<td>";
        echo Html::input('phone', [
            'value' => $this->fields['phone'] ?? '',
            'size'  => 20,
        ]);
        echo "</td>";
        echo "</tr>";

        // Razão Social / Nome Fantasia
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Corporate Name', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('corporate_name', [
            'value' => $this->fields['corporate_name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";

        echo "<td>" . __('Fantasy Name', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('fantasy_name', [
            'value' => $this->fields['fantasy_name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "</tr>";

        // Inscrições
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('State Registration', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('inscricao_estadual', [
            'value' => $this->fields['inscricao_estadual'] ?? '',
            'size'  => 30,
        ]);
        echo "</td>";

        echo "<td>" . __('Municipal Registration', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('inscricao_municipal', [
            'value' => $this->fields['inscricao_municipal'] ?? '',
            'size'  => 30,
        ]);
        echo "</td>";
        echo "</tr>";

        // Pessoa de Contato / Website
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Contact Person', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('contact_person', [
            'value' => $this->fields['contact_person'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";

        echo "<td>" . __('Website', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('website', [
            'value' => $this->fields['website'] ?? '',
            'type'  => 'url',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "</tr>";

        // === SEÇÃO: ENDEREÇO ===
        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Address', 'newbase') . "</th>";
        echo "</tr>";

        // CEP / Rua
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Postal Code', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('cep', [
            'value' => $this->fields['cep'] ?? '',
            'size'  => 10,
            'name'  => 'cep',
        ]);
        echo "&nbsp;<button type='button' class='btn btn-secondary' data-action='search-cep'>";
        echo "<i class='ti ti-search'></i></button>";
        echo "</td>";

        echo "<td>" . __('Street', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('street', [
            'value' => $this->fields['street'] ?? '',
            'size'  => 50,
            'name'  => 'street',
        ]);
        echo "</td>";
        echo "</tr>";

        // Número / Complemento
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Number', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('number', [
            'value' => $this->fields['number'] ?? '',
            'size'  => 10,
        ]);
        echo "</td>";

        echo "<td>" . __('Complement', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('complement', [
            'value' => $this->fields['complement'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "</tr>";

        // Bairro / Cidade
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Neighborhood', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('neighborhood', [
            'value' => $this->fields['neighborhood'] ?? '',
            'size'  => 30,
            'name'  => 'neighborhood',
        ]);
        echo "</td>";

        echo "<td>" . __('City') . "</td>";
        echo "<td>";
        echo Html::input('city', [
            'value' => $this->fields['city'] ?? '',
            'size'  => 30,
            'name'  => 'city',
        ]);
        echo "</td>";
        echo "</tr>";

        // Estado / País
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('State') . "</td>";
        echo "<td>";
        echo Html::input('state', [
            'value' => $this->fields['state'] ?? '',
            'size'  => 2,
            'maxlength' => 2,
            'name'  => 'state',
        ]);
        echo "</td>";

        echo "<td>" . __('Country') . "</td>";
        echo "<td>";
        echo Html::input('country', [
            'value' => $this->fields['country'] ?? 'Brasil',
            'size'  => 30,
        ]);
        echo "</td>";
        echo "</tr>";

        // Coordenadas GPS
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Latitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('latitude', [
            'value' => $this->fields['latitude'] ?? '',
            'size'  => 20,
            'name'  => 'latitude',
        ]);
        echo "</td>";

        echo "<td>" . __('Longitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('longitude', [
            'value' => $this->fields['longitude'] ?? '',
            'size'  => 20,
            'name'  => 'longitude',
        ]);
        echo "</td>";
        echo "</tr>";

        // === SEÇÃO: STATUS ===
        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='4'>" . __('Status', 'newbase') . "</th>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Contract Status', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        $contract_options = [
            'active'    => __('Active Contract', 'newbase'),
            'inactive'  => __('No Contract', 'newbase'),
            'cancelled' => __('Cancelled Contract', 'newbase'),
        ];
        echo Html::select('contract_status', $contract_options, [
            'value' => $this->fields['contract_status'] ?? 'active',
        ]);
        echo "</td>";
        echo "</tr>";

        // Observações
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Comments') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name'  => 'notes',
            'value' => $this->fields['notes'] ?? '',
            'cols'  => 80,
            'rows'  => 4,
        ]);
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }
}
