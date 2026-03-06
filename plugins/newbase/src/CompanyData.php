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
use GlpiPlugin\Newbase\Sections\SectionIpbxPabx;
use GlpiPlugin\Newbase\Sections\SectionIpbxCloud;
use GlpiPlugin\Newbase\Sections\SectionDispositivos;
use GlpiPlugin\Newbase\Sections\SectionRede;
use GlpiPlugin\Newbase\Sections\SectionChatbot;
use GlpiPlugin\Newbase\Sections\SectionLinhaTelefonica;
use GlpiPlugin\Newbase\Sections\SectionDocumentos;

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
     * Enable recycle bin (soft delete) support
     * @var bool
     */
    public $use_deleted = true; // Informa ao GLPI que a tabela tem coluna is_deleted

    /**
     * Habilita lixeira para esta classe
     * @return bool
     */
    public function maybeDeleted(): bool
    {
        return true;
    }

    /**
     * Permite exclusão permanente (purge) da lixeira
     * Necessário para o GLPI processar o toggle de lixeira corretamente
     * @return bool
     */
    public static function canPurge(): bool
    {
        return (bool) Session::haveRight(self::$rightname, PURGE);
    }

    /**
     * Get table name - Override to use correct table
     * @param string|null $classname Class name
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        // Tabela renomeada na migration (rename_table.php) para seguir
        // a convenção do GLPI: CompanyData → glpi_plugin_newbase_companydatas
        return 'glpi_plugin_newbase_companydatas';
    }

    /**
     * Get type name
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Empresa', 'Companies', $nb, 'newbase');
    }

    /**
     * Override name field: a tabela usa corporate_name como campo principal de exibição.
     * Sem isso o CommonDBTM tenta gravar/ler uma coluna `name` que não existe.
     *
     * @return string
     */
    public static function getNameField(): string
    {
        return 'fantasy_name';
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
        global $CFG_GLPI;

        $dir = ($full ? $CFG_GLPI['root_doc'] : '');
        return $dir . '/plugins/newbase/front/companydata.php';
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
    public function defineTabs($options = []) // ERRO 26: Era defineTab (sem 's')
    {
        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab(__CLASS__, $ong, $options);
        return $ong;
    }

    /**
     * Get tab name for item
     *
     * @param CommonGLPI $item         Item
     * @param int        $withtemplate Template flag
     * @return string|array Tab name(s)
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string|array
    {
        if ($item->getType() === self::getType()) {
            return [
                1 => self::createTabEntry(__('IPBX / PABX', 'newbase')),
                2 => self::createTabEntry(__('IPBX Cloud', 'newbase')),
                3 => self::createTabEntry(__('Dispositivos', 'newbase')),
                4 => self::createTabEntry(__('Rede', 'newbase')),
                5 => self::createTabEntry(__('Chatbot', 'newbase')),
                6 => self::createTabEntry(__('Linha Telefônica', 'newbase')),
                7 => self::createTabEntry(__('Documentos', 'newbase')),
            ];
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
            /** @var CompanyData $item */
            match ((int) $tabnum) {
                1 => SectionIpbxPabx::show($item),
                2 => SectionIpbxCloud::show($item),
                3 => SectionDispositivos::show($item),
                4 => SectionRede::show($item),
                5 => SectionChatbot::show($item),
                6 => SectionLinhaTelefonica::show($item),
                7 => SectionDocumentos::show($item),
                default => false,
            };
            return true;
        }
        return false;
    }

    // Os métodos showSection* foram migrados para src/Sections/*.php
    // Cada classe Section é responsável por carregar seus próprios dados e renderizar o template.


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
        // CORREÇÃO: remover campo 'name' residual do POST (a tabela não tem essa coluna;
        // getNameField() retorna 'corporate_name' como campo principal).
        unset($input['name']);

        // Garantir que corporate_name não esteja vazio
        if (empty($input['corporate_name'])) {
            \Session::addMessageAfterRedirect(
                __('Corporate Name is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Process systems_config array into JSON
        if (isset($input['systems_config']) && is_array($input['systems_config'])) {
            $input['systems_config'] = json_encode($input['systems_config']);
        }

        // ERRO 24: Converter strings vazias para NULL em campos decimais
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

        // CORREÇÃO: remover campo 'name' residual do POST
        unset($input['name']);

        // Process systems_config array into JSON
        if (isset($input['systems_config']) && is_array($input['systems_config'])) {
            $input['systems_config'] = json_encode($input['systems_config']);
        }

        // ERRO 24: Converter strings vazias para NULL em campos decimais
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
    $tab = [];

    $tab[] = [
        'id'       => '1',
        'table'    => $this->getTable(),
        'field'    => 'corporate_name',
        'name'     => __('Corporate Name', 'newbase'),
        'datatype' => 'itemlink',
        'itemtype' => self::class,
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
        'id'       => '8',
        'table'    => $this->getTable(),
        'field'    => 'phone',
        'name'     => __('Phone'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '9',
        'table'    => $this->getTable(),
        'field'    => 'email',
        'name'     => __('Email'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '10',
        'table'    => $this->getTable(),
        'field'    => 'cep',
        'name'     => __('Postal Code', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '11',
        'table'    => $this->getTable(),
        'field'    => 'street',
        'name'     => __('Street', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '12',
        'table'    => $this->getTable(),
        'field'    => 'number',
        'name'     => __('Number', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '13',
        'table'    => $this->getTable(),
        'field'    => 'complement',
        'name'     => __('Complement', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '14',
        'table'    => $this->getTable(),
        'field'    => 'neighborhood',
        'name'     => __('Neighborhood', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '15',
        'table'    => $this->getTable(),
        'field'    => 'city',
        'name'     => __('City'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '16',
        'table'    => $this->getTable(),
        'field'    => 'state',
        'name'     => __('State'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '17',
        'table'    => $this->getTable(),
        'field'    => 'country',
        'name'     => __('Country'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '18',
        'table'    => $this->getTable(),
        'field'    => 'latitude',
        'name'     => __('Latitude', 'newbase'),
        'datatype' => 'decimal',
    ];

    $tab[] = [
        'id'       => '20',
        'table'    => $this->getTable(),
        'field'    => 'longitude',
        'name'     => __('Longitude', 'newbase'),
        'datatype' => 'decimal',
    ];

    $tab[] = [
        'id'       => '21',
        'table'    => $this->getTable(),
        'field'    => 'contract_status',
        'name'     => __('Contract Status', 'newbase'),
        'datatype' => 'string',
    ];

    $tab[] = [
        'id'       => '22',
        'table'    => $this->getTable(),
        'field'    => 'notes',
        'name'     => __('Comments'),
        'datatype' => 'text',
    ];

    $tab[] = [
        'id'       => '23',
        'table'    => $this->getTable(),
        'field'    => 'date_end',
        'name'     => __('Data de Encerramento', 'newbase'),
        'datatype' => 'date',
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

    $tab[] = [
        'id'            => '30',
        'table'         => $this->getTable(),
        'field'         => 'is_deleted',
        'name'          => __('Deleted'),
        'datatype'      => 'bool',
        'massiveaction' => false,
        'nosearch'      => true,
        'nodisplay'     => true,
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
        // O ID real pode vir em $options['id'] quando chamado via aba AJAX
        $real_id = (int) ($options['id'] ?? $ID);

        $this->initForm($real_id, $options);

        if (!$this->canView()) {
            return false;
        }

        // Após initForm com ID correto, $this->fields['id'] deve ter o valor real
        $final_id = (int) ($this->fields['id'] ?? $real_id);

        \Glpi\Application\View\TemplateRenderer::getInstance()->display(
            '@newbase/companydata/sections/empresa.html.twig',
            [
                'item_id'    => $final_id,
                'form_url'   => $this->getFormURL(),
                'csrf_token' => \Session::getNewCSRFToken(),
                'data'       => $this->fields,
            ]
        );

        return true;
    }

}