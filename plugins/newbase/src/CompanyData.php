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
* Get table name - Override to use correct table
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
* Display company form
* @param int $entity_id Entity ID (0 for new company)
* @param array $options Form options
* @return bool|void
*/
    public function showForm($entity_id = 0, $options = [])
    {
        global $CFG_GLPI;

        $is_new = ($entity_id == 0);
        $entity = new Entity();
        $company_data = [];

        // Load existing data
        if (!$is_new) {
            if (!$entity->getFromDB($entity_id)) {
                return false;
            }
            $company_data = self::getCompanyById($entity_id) ?? [];
        }

        // Check permissions
        if ($is_new) {
            if (!Entity::canCreate()) {
                return false;
            }
        } else {
            if (!$entity->canView()) {
                return false;
            }
        }

        // Start form
        $form_action = Plugin::getWebDir('newbase') . '/front/companydata.form.php';

        echo "<div class='center'>";
        echo "<form name='form_company' id='form_company' method='post' action='" . $form_action . "' enctype='multipart/form-data'>";

        // CSRF Token - CRÍTICO PARA SEGURANÇA
        echo "<input type='hidden' name='_glpi_csrf_token' value='" . Session::getNewCSRFToken() . "' />";

        if (!$is_new) {
            echo "<input type='hidden' name='entities_id' value='" . $entity_id . "' />";
        }

        echo "<div class='spaced'>";
        echo "<table class='tab_cadre_fixe'>";

        // Header
        echo "<tr class='tab_bg_1'>";
        echo "<th colspan='4'>";
        echo $is_new ? __("New Company", 'newbase') : __("Edit Company", 'newbase');
        echo "</th>";
        echo "</tr>";

        // Company Name *
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo "<input type='text' name='name' id='name' value='" .
                htmlspecialchars($company_data['name'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' required='required' />";
        echo "</td>";

        // CNPJ
        echo "<td>" . __('CNPJ', 'newbase') . "</td>";
        echo "<td>";
        echo "<div style='display:flex; align-items:center; gap:5px;'>";
        echo "<input type='text' name='cnpj' id='cnpj' value='" .
                htmlspecialchars($company_data['cnpj'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='20' maxlength='18' />";
        echo "<button type='button' data-action='search-cnpj' class='btn btn-sm btn-icon btn-ghost-secondary' title='" . __('Search CNPJ', 'newbase') . "'><i class='ti ti-search'></i></button>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";

        // Corporate Name / Fantasy Name
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Corporate Name', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='text' name='corporate_name' id='corporate_name' value='" .
                htmlspecialchars($company_data['corporate_name'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' />";
        echo "</td>";

        echo "<td>" . __('Fantasy Name', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='text' name='fantasy_name' id='fantasy_name' value='" .
                htmlspecialchars($company_data['fantasy_name'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' />";
        echo "</td>";
        echo "</tr>";

        // Email / Phone
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Email') . "</td>";
        echo "<td>";
        echo "<input type='email' name='email' id='email' value='" .
                htmlspecialchars($company_data['email'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' />";
        echo "</td>";

        echo "<td>" . __('Phone') . "</td>";
        echo "<td>";
        echo "<input type='text' name='phone' id='phone' value='" .
                htmlspecialchars($company_data['phone'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='20' />";
        echo "</td>";
        echo "</tr>";

        // Contact Person / Website
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Contact Person', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='text' name='contact_person' id='contact_person' value='" .
                htmlspecialchars($company_data['contact_person'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' />";
        echo "</td>";

        echo "<td>" . __('Website', 'newbase') . "</td>";
        echo "<td>";
        echo "<input type='url' name='website' id='website' value='" .
                htmlspecialchars($company_data['website'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='50' />";
        echo "</td>";
        echo "</tr>";

        // Address
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Address') . "</td>";
        echo "<td colspan='3'>";
        echo "<input type='text' name='address' id='address' value='" .
                htmlspecialchars($company_data['address'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='100' style='width:95%' />";
        echo "</td>";
        echo "</tr>";

        // CEP / City / State
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Postal code') . "</td>";
        echo "<td>";
        echo "<div style='display:flex; align-items:center; gap:5px;'>";
        echo "<input type='text' name='zip_code' id='zip_code' value='" .
                htmlspecialchars($company_data['postcode'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='10' />";
        echo "<button type='button' data-action='search-cep' class='btn btn-sm btn-icon btn-ghost-secondary' title='" . __('Search CEP', 'newbase') . "'><i class='ti ti-search'></i></button>";
        echo "</div>";
        echo "</td>";

        echo "<td>" . __('City') . "</td>";
        echo "<td>";
        echo "<input type='text' name='city' id='city' value='" .
                htmlspecialchars($company_data['town'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='30' />";
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('State') . "</td>";
        echo "<td>";
        echo "<input type='text' name='state' id='state' value='" .
                htmlspecialchars($company_data['state'] ?? '', ENT_QUOTES, 'UTF-8') .
                "' size='20' maxlength='2' />";
        echo "</td>";

        echo "<td>" . __('Country') . "</td>";
        echo "<td>";
        echo "<input type='text' name='country' id='country' value='" .
                htmlspecialchars($company_data['country'] ?? 'BR', ENT_QUOTES, 'UTF-8') .
                "' size='2' maxlength='2' />";
        echo "</td>";
        echo "</tr>";

        // Notes
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Comments') . "</td>";
        echo "<td colspan='3'>";
        echo "<textarea name='notes' id='notes' rows='4' style='width:95%'>";
        echo htmlspecialchars($company_data['notes'] ?? $company_data['comment'] ?? '', ENT_QUOTES, 'UTF-8');
        echo "</textarea>";
        echo "</td>";
        echo "</tr>";

        // Action buttons
        echo "<tr class='tab_bg_2'>";
        echo "<td colspan='4' class='center'>";

        if ($is_new) {
            echo "<input type='submit' name='add' value='" . _sx('button', 'Add') . "' class='btn btn-primary' />";
        } else {
            echo "<input type='submit' name='update' value='" . _sx('button', 'Save') . "' class='btn btn-primary' />";

            if ($entity->canDelete()) {
                echo "&nbsp;&nbsp;";
                echo "<input type='submit' name='delete' value='" . _sx('button', 'Delete') . "'
                        class='btn btn-danger'
                        onclick='return confirm(\"" . __('Confirm deletion?') . "\");' />";
            }

            if ($entity->canPurge()) {
                echo "&nbsp;&nbsp;";
                echo "<input type='submit' name='purge' value='" . _sx('button', 'Delete permanently') . "'
                        class='btn btn-danger'
                        onclick='return confirm(\"" . __('Confirm permanent deletion? This action cannot be undone!') . "\");' />";
            }
        }

        echo "</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";

        echo "</form>";
        echo "</div>";

        echo "<script src='" . Plugin::getWebDir('newbase') . "/js/jquery.mask.min.js'></script>\n";
        echo "<script src='" . Plugin::getWebDir('newbase') . "/js/forms.js'></script>\n";

        // Include JavaScript for masks and validations
        echo "<script type='text/javascript'>\n";
        echo "$(document).ready(function() {\n";
        echo "  /* CNPJ Mask */\n";
        echo "  if (typeof $.fn.mask !== 'undefined') {\n";
        echo "    $('#cnpj').mask('00.000.000/0000-00');\n";
        echo "    $('#zip_code').mask('00000-000');\n";
        echo "    $('#phone').mask('(00) 00000-0000');\n";
        echo "  }\n";
        echo "});\n";
        echo "</script>\n";

        return true;
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