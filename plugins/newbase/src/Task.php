<?php

/**
* Classe de tarefas para o plugin Newbase
* Gerencia tarefas com geolocalização, rastreamento de status, cálculo de quilometragem e atribuição a usuários
* Gerenciamento de tarefas com geolocalização e assinatura digital
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;

use Session;
use Html;
use Dropdown;
use User;
use CommonGLPI;
use CommonDBTM;

/**
* Classe Task - Gerenciamento de tarefas com geolocalização e rastreamento de status
*/
class Task
{
    /**
    * Nome do direito no perfil
    */
    public static $rightname = 'plugin_newbase_task';

    /**
    * Obter nome de tipo
    * @param int $nb Número
    * @return string
    */
    public static function getTypeName($nb = 0): string
    {
        return _n('Tarefa', 'Tarefas', $nb, 'newbase');
    }

    // Opções de busca
    public function rawSearchOptions()
    {
        $tab = [];

        // Grupo principal
        $tab['common'] = self::getTypeName(2);

        $tab[1] = [
            'id'            => 1,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'id',
            'name'          => __('ID'),
            'datatype'      => 'number',
            'massiveaction' => false,
        ];

        $tab[2] = [
            'id'            => 2,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'name',
            'name'          => __('Nome', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false,
            'autocomplete'  => true,
        ];

        $tab[3] = [
            'id'            => 3,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'description',
            'name'          => __('Descrição'),
            'datatype'      => 'text',
            'massiveaction' => false,
        ];

        $tab[4] = [
            'id'            => 4,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'date_start',
            'name'          => __('Data de início', 'newbase'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[5] = [
            'id'            => 5,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'date_end',
            'name'          => __('Data de término', 'newbase'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[6] = [
            'id'            => 6,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'status',
            'name'          => __('Status'),
            'datatype'      => 'string',
            'massiveaction' => false,
        ];

        // Data de Criação
        $tab[11] = [
            'id'            => 11,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'date_creation',
            'name'          => __('Data de criação'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        // Data de Modificação
        $tab[12] = [
            'id'            => 12,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'date_mod',
            'name'          => __('Data de modificação'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        // Entidade
        $tab[80] = [
            'id'            => 80,
            'table'         => 'glpi_entities',
            'field'         => 'completename',
            'name'          => __('Entidade'),
            'datatype'      => 'dropdown',
            'massiveaction' => false,
        ];

        // Recursivo
        $tab[86] = [
            'id'            => 86,
            'table'         => 'glpi_plugin_newbase_tasks',
            'field'         => 'is_recursive',
            'name'          => __('Entidades filhas'),
            'datatype'      => 'bool',
            'massiveaction' => false,
        ];

        return $tab;
    }

    public function getDefaultToDisplay()
    {
        return ['id', 'name', 'description', 'date_start', 'date_end', 'status'];
    }

    /**
    * Obter o nome do campo de chave estrangeira
    * @return string
    */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_task_id';
    }

    /**
    * Verificar se o usuário pode visualizar o item
    * @return bool
    */
    public static function canView(): bool
    {
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    /**
    * Verificar se o usuário pode criar um item
    * @return bool
    */
    public static function canCreate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    /**
    * Verificar se o usuário pode atualizar o item
    * @return bool
    */
    public static function canUpdate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    /**
    * Verificar se o usuário pode excluir o item
    * @return bool
    */
    public static function canDelete(): bool
    {
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    /**
    * Verificar se o usuário pode purgar o item
    * @return bool
    */
    public static function canPurge(): bool
    {
        return (bool) Session::haveRight(self::$rightname, PURGE);
    }

    /**
    * Obtenha o conteúdo do menu
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
                'icon'  => 'ti ti-checklist',
            ];
        }

        if (count($menu)) {
            return $menu;
        }

        return [];
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
    * @param int        $tabnum Número da guia
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
    * Contabilizar tarefas para uma empresa
    * @param CommonDBTM $item Item da empresa
    * @return int
    */
    public static function countForItem(CommonDBTM $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM'  => self::getTable(),
            'WHERE' => [
                'plugin_newbase_companydata_id' => $item->getID(),
            ],
        ]);

        $result = $iterator->current();
        return (int) ($result['cpt'] ?? 0);
    }

    /**
    * Exibir tarefas para uma empresa
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
                        'glpi_users' => 'id',
                    ],
                ],
            ],
            'WHERE' => ['plugin_newbase_companydata_id' => $company_id],
            'ORDER' => 'date_start DESC',
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
                echo "<td>" . ($data['mileage'] ? number_format((float) $data['mileage'], 2, ',', '.') : '-') . "</td>";
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
    * Obter matriz de status de tarefas
    *
    * @return array
    */
    public static function getTaskStatuses(): array
    {
        return [
            'open' => __('Open', 'newbase'),
            'in_progress' => __('In Progress', 'newbase'),
            'paused' => __('Paused', 'newbase'),
            'completed' => __('Completed', 'newbase'),
        ];
    }

    /**
    * Exibir formulário para tarefa
    * @param int   $ID ID do item
    * @param array $options Opções
    * @return bool
    */
    public function showForm($ID, array $options = []): bool
    {
        // Inicializar formulário com permissões e dados
        $this->initForm($ID, $options);

        // Verificar permissões
        if (!$this->canView()) {
            return false;
        }

        // Iniciar renderização do formulário
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Company', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        CompanyData::dropdown([
            'name' => 'plugin_newbase_companydata_id',
            'value' => $this->fields['plugin_newbase_companydata_id'] ?? $options['plugin_newbase_companydata_id'] ?? 0,
            'required' => true,
        ]);
        echo "</td>";
        echo "<td>" . __('Title', 'newbase') . " <span class='required'>*</span></td>";
        echo "<td>";
        echo Html::input('title', [
            'value' => $this->fields['title'] ?? '',
            'required' => true,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Status', 'newbase') . "</td>";
        echo "<td>";
        Dropdown::showFromArray('status', self::getTaskStatuses(), [
            'value' => $this->fields['status'] ?? 'open',
        ]);
        echo "</td>";
        echo "<td>" . __('Assigned to', 'newbase') . "</td>";
        echo "<td>";
        User::dropdown([
            'name' => 'assigned_to',
            'value' => $this->fields['assigned_to'] ?? 0,
            'right' => 'all',
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
            'cols' => 80,
        ]);
        echo "</td>";
        echo "</tr>";

        // Finalizar formulário
        $this->showFormButtons($options);

        return true;
    }

    /**
    * Prepare a entrada para adição
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
    * Validar dados de entrada
    * @param array $input Dados de entrada
    * @return array|false
    */
    private function validateInput(array $input)
    {
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Use GLPI's built-in sanitization
                $input[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
