<?php

/**
* CompanyData Class - Company data management for Newbase plugin
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Entity;
use Session;
use Toolbox;
use Plugin;

/**
* CompanyData - Manages company data with integration between
* glpi_entities (native) and glpi_plugin_newbase_company_extras (custom)
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
* Get type name
* @param int $nb Number of items
* @return string Type name
*/
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Companies', 'newbase') : __('Company', 'newbase');
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
* Get company search URL
* @param bool $full Full path
* @return string Search URL
*/
    public static function getSearchURL($full = true)
    {
        return Plugin::getWebDir('newbase', $full) . '/front/companydata.php';
    }

/**
* Get all active companies from glpi_entities
* @return array Associative array [id => name, ...]
*/
    public static function getAllCompanies(): array
    {
        global $DB;

        $companies = [];

        $result = $DB->request([
            'FROM' => 'glpi_entities',
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
* @param int $entity_id Entity ID (glpi_entities.id)
* @return array|null Array with company data or null
*/
    public static function getCompanyById(int $entity_id): ?array
    {
        global $DB;

        $entity = $DB->request([
            'FROM' => 'glpi_entities',
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

        // Remove CNPJ formatting
        $cnpj_clean = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj_clean) !== 14) {
            return null;
        }

        // Search in complementary data
        $extras = $DB->request([
            'FROM' => 'glpi_plugin_newbase_company_extras',
            'WHERE' => [
                'cnpj' => $cnpj_clean,
                'is_deleted' => 0,
            ],
        ])->current();

        if ($extras) {
            // Get complete entity data
            return self::getCompanyById($extras['entities_id']);
        }

        return null;
    }

/**
* Get company complementary data
* @param int $entity_id Entity ID
* @return array|null Array with complementary data or null
*/
    public static function getCompanyExtras(int $entity_id): ?array
    {
        global $DB;

        // Check if table exists before querying
        if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
            return null;
        }

        $result = $DB->request([
            'FROM' => 'glpi_plugin_newbase_company_extras',
            'WHERE' => [
                'entities_id' => $entity_id,
                'is_deleted' => 0,
            ],
        ])->current();

        return $result ?: null;
    }

/**
* Save or update company complementary data
* @param int $entity_id Entity ID
* @param array $data Data to save (cnpj, corporate_name, fantasy_name, etc)
* @return int|bool Record ID or false
*/
    public static function saveCompanyExtras(int $entity_id, array $data): int|bool
    {
        global $DB;

        // Validate data
        if (empty($data)) {
            return false;
        }

        // Validate if table exists
        if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Table glpi_plugin_newbase_company_extras does not exist\n"
            );
            return false;
        }

        // VALIDAR CNPJ SE FORNECIDO
        if (!empty($data['cnpj'])) {
            $data['cnpj'] = preg_replace('/[^0-9]/', '', $data['cnpj']);

            if (!Common::validateCNPJ($data['cnpj'])) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Invalid CNPJ provided: {$data['cnpj']}\n"
                );
                return false;
            }
        }

        // Prepare data
        $data['entities_id'] = $entity_id;

        // Check if record already exists
        $existing = $DB->request([
            'FROM' => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['entities_id' => $entity_id],
        ])->current();

        if ($existing) {
            // Update existing record
            $data['date_mod'] = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');

            $result = $DB->update(
                'glpi_plugin_newbase_company_extras',
                $data,
                ['id' => $existing['id']]
            );

            if ($result === false) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Failed to update company extras for entity {$entity_id}\n"
                );
                return false;
            }

            return $existing['id'];
        } else {
            // Insert new record
            $data['date_creation'] = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');
            $data['date_mod'] = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;

            $result = $DB->insert('glpi_plugin_newbase_company_extras', $data);

            if ($result === false) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Failed to insert company extras for entity {$entity_id}\n"
                );
                return false;
            }

            return $result;
        }
    }

/**
* Search companies by term
* @param string $search Search term
* @param int $limit Result limit
* @return array Array of found companies
*/
    public static function searchCompanies(string $search, int $limit = 20): array
    {
        global $DB;

        $companies = [];

        // CORRIGIDO: GLPI escapa automaticamente
        $result = $DB->request([
            'FROM' => 'glpi_entities',
            'WHERE' => [
                'is_deleted' => 0,
                'name' => ['LIKE', '%' . $search . '%'],
            ],
            'LIMIT' => $limit,
            'ORDER' => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[] = [
                'id' => $entity['id'],
                'name' => htmlspecialchars($entity['name'], ENT_QUOTES, 'UTF-8'),
            ];
        }

        return $companies;
    }

/**
* Display dropdown for company selection
* @param array $options Dropdown options
* @return int|string Dropdown result
*/
    public static function dropdown($options = [])
    {
        $defaults = [
            'name' => 'entities_id',
            'value' => 0,
            'display' => true,
            'entity' => -1,
        ];

        $options = array_merge($defaults, $options);

        // Use native Entity dropdown
        return Entity::dropdown($options);
    }

/**
* Get search options for GLPI search engine
* (Used if CompanyData becomes a full CommonDBTM in the future)
* @return array Search options
*/
    public function rawSearchOptions(): array
    {
        return [
            [
                'id' => 'common',
                'name' => __('Characteristics'),
            ],
            [
                'id' => '1',
                'table' => 'glpi_entities',
                'field' => 'name',
                'name' => __('Name'),
                'datatype' => 'itemlink',
                'massiveaction' => false,
            ],
            [
                'id' => '2',
                'table' => 'glpi_plugin_newbase_company_extras',
                'field' => 'cnpj',
                'name' => __('CNPJ', 'newbase'),
                'datatype' => 'string',
            ],
            [
                'id' => '3',
                'table' => 'glpi_plugin_newbase_company_extras',
                'field' => 'corporate_name',
                'name' => __('Corporate Name', 'newbase'),
                'datatype' => 'string',
            ],
            [
                'id' => '4',
                'table' => 'glpi_plugin_newbase_company_extras',
                'field' => 'fantasy_name',
                'name' => __('Fantasy Name', 'newbase'),
                'datatype' => 'string',
            ],
        ];
    }
}