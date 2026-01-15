<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;
use Glpi\Toolbox\Sanitizer;
use CommonDBTM;
use Session;
use Html;
use Dropdown;
use CommonGLPI;

/**
* System class
* Gerenciamento de sistemas (IPBX, PABX, Chatbot)
* @package   PluginNewbase
* @author    Joao Lucas
* @copyright Copyright (c) 2026 Joao Lucas
* @license   GPLv2+
* @since     2.0.0
*/
class System extends CommonDBTM
{
    // Nome correto para permissões
    public static $rightname = 'plugin_newbase_system';

    // Ativar histórico
    public $dohistory = true;

    /**
    * Obter nome da tabela
    * @param string|null $classname Nome da classe
    * @return string
    */
    public static function getTable($classname = null): string
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_systems';
    }

    /**
    * Obter nome do tipo
    * @param int $nb Numero do item
    * @return string
    */
    public static function getTypeName($nb = 0) {
        return _n('Sistema', 'Sistemas', $nb, 'newbase');
    }

    // Opções de busca
    public function rawSearchOptions() {
        $tab = [];

        // Grupo principal
        $tab['common'] = self::getTypeName(2);

        $tab[1] = [
            'id'            => 1,
            'table'         => $this->getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'datatype'      => 'number',
            'massiveaction' => false
        ];

        $tab[2] = [
            'id'            => 2,
            'table'         => $this->getTable(),
            'field'         => 'name',
            'name'          => __('Nome', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false,
            'autocomplete'  => true
        ];

        $tab[3] = [
            'id'            => 3,
            'table'         => $this->getTable(),
            'field'         => 'ip_address',
            'name'          => __('Endereço IP', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];

        $tab[4] = [
            'id'            => 4,
            'table'         => $this->getTable(),
            'field'         => 'version',
            'name'          => __('Versão', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];

        $tab[5] = [
            'id'            => 5,
            'table'         => $this->getTable(),
            'field'         => 'status',
            'name'          => __('Status'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];

        // Data de Criação
        $tab[11] = [
            'id'            => 11,
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Data de criação'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];

        // Data de Modificação
        $tab[12] = [
            'id'            => 12,
            'table'         => $this->getTable(),
            'field'         => 'date_mod',
            'name'          => __('Data de modificação'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];

        // Entidade
        $tab[80] = [
            'id'            => 80,
            'table'         => 'glpi_entities',
            'field'         => 'completename',
            'name'          => __('Entidade'),
            'datatype'      => 'dropdown',
            'massiveaction' => false
        ];

        // Recursivo
        $tab[86] = [
            'id'            => 86,
            'table'         => $this->getTable(),
            'field'         => 'is_recursive',
            'name'          => __('Entidades filhas'),
            'datatype'      => 'bool',
            'massiveaction' => false
        ];

        return $tab;
    }

    public function getDefaultToDisplay() {
        return ['id', 'name', 'ip_address', 'version', 'status'];
    }

    /**
    * Obter o nome do campo de chave estrangeira
    * @return string
    */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_system_id';
    }

    /**
    * Verificar se o usuario pode visualizar o item
    * @return bool
    */
    public static function canView(): bool
    {
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    /**
    * Verificar se o usuario pode criar um item
    * @return bool
    */
    public static function canCreate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    /**
    * Verificar se o usuario pode atualizar o item
    * @return bool
    */
    public static function canUpdate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    /**
    * Verificar se o usuario pode excluir o item
    * @return bool
    */
    public static function canDelete(): bool
    {
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    /**
    * Verificar se o usuario pode excluir o item
    * @return bool
    */
    public static function canPurge(): bool
    {
        return (bool) Session::haveRight(self::$rightname, PURGE);
    }

    /**
    * Obter o nome da aba do item
    * @param CommonGLPI $item Item
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
    * Exibir conteúdo da guia
    * @param CommonGLPI $item Item
    * @param int        $tabnum Numero da guia
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
    * Sistemas de contagem para uma empresa
    * @param CommonGLPI $item Item da empresa
    * @return int
    */
    public static function countForItem(CommonDBTM $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM'  => self::getTable(),
            'WHERE' => [
                'companydata_id' => $item->getId()
            ]
        ]);

        $result = $iterator->current();
        return (int)($result['cpt'] ?? 0);
    }

    /**
    * Apresentar sistemas para uma empresa
    * @param CompanyData $company Item da empresa
    * @return void
    */
    public static function showForCompany(CompanyData $company): void
    {
        global $DB, $CFG_GLPI;

        $company_id = $company->getId();
        $canedit = $company->canUpdate();

        echo "<div class='spaced'>";

        if ($canedit) {
            echo "<div class='center firstbloc'>";
            echo "<a class='btn btn-primary' href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php?companydata_id=" . $company_id . "'>";
            echo "<i class='fas fa-plus'></i>&nbsp;" . __('Add system', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['companydata_id' => $company_id],
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
    * Obter matriz de tipos de sistema
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
    * Obter matriz de status do sistema
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
    * Exibir formulario para o sistema
    * @param int   $ID ID do item
    * @param array $options Opções
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
    * Prepare a entrada para adição.
    * @param array $input Dados de entrada
    * @return array|false
    */
    public function prepareInputForAdd($input)
    {
        return $this->validateInput($input);
    }

    /**
    * Prepare os dados para a atualização
    * @param array $input Dados de entrada
    * @return array|false
    */
    public function prepareInputForUpdate($input)
    {
        return $this->validateInput($input);
    }

    /**
    * Validate Dados de entrada
    * @param array $input Dados de entrada
    * @return array|false
    */
    private function validateInput(array $input)
    {
        // Higienize todas as entradas de string usando o GLPI Sanitizer
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = Sanitizer::unsanitize($value); // Remove escapes + encode para output seguro
                // OU use: Sanitizer::encodeHtmlSpecialChars($value); para apenas HTML escape
            }
        }

        // Validar campos obrigatórios
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

