<?php

/**
* CompanyData Class - Company Management
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;

use GlpiPlugin\Newbase\Src\Common;
use GlpiPlugin\Newbase\Src\Address;
use GlpiPlugin\Newbase\Src\System;
use Session;
use Html;
use Entity;

/**
* CompanyData - Manage company information
*/
class CompanyData extends Common
{
    // ============= CONFIGURAÇÕES GLPI ==========
    /**
    * Gestão de direitos
    * @var string
    */
    public static $rightname = 'plugin_newbase';

    /**
    * Habilitar rastreamento de histórico
    * @var bool
    */
    public $dohistory = true;

    // ========== MÉTODOS GLPI OBRIGATÓRIOS ===========
    /**
    * Obtenha o nome do tipo
    * @param int $nb Número de itens
    * @return string Nome do tipo
    */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Companies', 'newbase') : __('Company', 'newbase');
    }

    /**
    * Obtenha o nome da tabela
    * @param string $classname Nome da Classe (opcional)
    * @return string Nome da tabela
    */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_companydata';
    }

    /**
    * Obtenha o ícone para menus
    * @return string Classe de ícones do Font Awesome
    */
    public static function getIcon(): string
    {
        return 'fas fa-building';
    }

    /**
    * Defina as opções de busca para o motor de busca do GLPI
    * @return array Opções de pesquisa
    */
    public function rawSearchOptions()
    {
        $tab = [];

        // Aba principal
        $tab[] = [
            'id'   => 'common',
            'name' => __('Characteristics'),
        ];

        // ID
        $tab[] = [
            'id'            => '2',
            'table'         => $this->getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'massiveaction' => false,
            'datatype'      => 'number',
        ];

        // Nome
        $tab[] = [
            'id'            => '1',
            'table'         => $this->getTable(),
            'field'         => 'name',
            'name'          => __('Name'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
        ];

        // CNPJ
        $tab[] = [
            'id'       => '3',
            'table'    => $this->getTable(),
            'field'    => 'cnpj',
            'name'     => __('CNPJ', 'newbase'),
            'datatype' => 'string',
        ];

        // Razão Social
        $tab[] = [
            'id'       => '4',
            'table'    => $this->getTable(),
            'field'    => 'corporate_name',
            'name'     => __('Corporate Name', 'newbase'),
            'datatype' => 'string',
        ];

        // Nome Fantasia
        $tab[] = [
            'id'       => '5',
            'table'    => $this->getTable(),
            'field'    => 'fantasy_name',
            'name'     => __('Fantasy Name', 'newbase'),
            'datatype' => 'string',
        ];

        // Filial
        $tab[] = [
            'id'       => '6',
            'table'    => $this->getTable(),
            'field'    => 'branch',
            'name'     => __('Branch', 'newbase'),
            'datatype' => 'string',
        ];

        // Data de modificação
        $tab[] = [
            'id'            => '19',
            'table'         => $this->getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        // Data de criação
        $tab[] = [
            'id'            => '121',
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        // Website
        $tab[] = [
            'id' => '7',
            'table' => $this->getTable(),
            'field' => 'website',
            'name' => __('Website', 'newbase'),
            'datatype' => 'string',
        ];

        // Email
        $tab[] = [
            'id' => '8',
            'table' => $this->getTable(),
            'field' => 'email',
            'name' => __('Email'),
            'datatype' => 'email',
        ];

        // Phone
        $tab[] = [
            'id' => '9',
            'table' => $this->getTable(),
            'field' => 'phone',
            'name' => __('Phone'),
            'datatype' => 'string',
        ];

        // CEP
        $tab[] = [
            'id' => '10',
            'table' => $this->getTable(),
            'field' => 'cep',
            'name' => __('ZIP Code', 'newbase'),
            'datatype' => 'string',
        ];

        // Endereço
        $tab[] = [
            'id' => '11',
            'table' => $this->getTable(),
            'field' => 'address',
            'name' => __('Address'),
            'datatype' => 'string',
        ];

        // City
        $tab[] = [
            'id' => '12',
            'table' => $this->getTable(),
            'field' => 'city',
            'name' => __('City'),
            'datatype' => 'string',
        ];

        // State
        $tab[] = [
            'id' => '13',
            'table' => $this->getTable(),
            'field' => 'state',
            'name' => __('State'),
            'datatype' => 'string',
        ];

        return $tab;
    }

    // =========== FORMULÁRIO ===============
    /**
    * Exibir formulário para empresa
    * @param int   $ID ID do item (0 para novo)
    * @param array $options Opções adicionais
    * @return bool Succeso
    */
    public function showForm($ID, array $options = []): bool
    {
        // Verificar permissões
        if (!$this->canView()) {
            return false;
        }

        // Verificar acesso ao item
        if ($ID > 0) {
            $this->check($ID, READ);
        } else {
            $this->check(-1, CREATE);
            $this->getEmpty();
        }

        // Iniciar formulário
        $this->showFormHeader($options);
        echo "<tr class='tab_bg_1'>";
        // Campo Nome
        echo "<td>" . __('Name') . "</td>";
        echo "<td>";
        Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "</tr>";

        // Campo CNPJ com integração com API
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('CNPJ', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        Html::input('cnpj', [
            'value' => $this->fields['cnpj'] ?? '',
            'size'  => 20,
            'required' => true,
        ]);
        echo "</td>";
        echo "<tr>";

        // Razão Social
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Corporate Name', 'newbase') . "</td>";
        echo "<td>";
        Html::input('corporate_name', [
            'value' => $this->fields['corporate_name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "<tr>";

        // Nome Fantasia
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Fantasy Name', 'newbase') . "</td>";
        echo "<td>";
        Html::input('fantasy_name', [
            'value' => $this->fields['fantasy_name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "</tr>";

        // Filial
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Filial', 'newbase') . "</td>";
        echo "<td>";
        Html::input('filial', [
            'value' => $this->fields['filial'] ?? '',
            'size'  => 20,
        ]);
        echo "</td>";
        echo "</tr>";

        // CEP
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('ZIP Code', 'newbase') . "</td>";
        echo "<td>";
        Html::input('cep', [
            'value' => $this->fields['cep'] ?? '',
            'size'  => 15,
            'placeholder' => '00000-000',
        ]);
        echo "<button type='button' onclick='searchByCEP(this) '>buscar</button>";
        echo "</td>";
        echo "</tr>";

        // Entidade
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Entity') . "</td>";
        echo "<td>";
        Entity::dropdown([
            'entity' => $this->fields['entities_id'] ?? 0,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<script>";
        echo "function searchByCNPJ(input) {";
        echo "    const cnpj = input.value.replace(/\D/g, '');";
        echo "    if (cnpj.length === 14) {";
        echo "        // Chamar AJAX para buscar dados";
        echo "        fetch('../../../front/companydata.php?action=search_cnpj&cnpj=' + cnpj)";
        echo "            .then(r => r.json())";
        echo "            .then(data => {";
        echo "                document.querySelector('[name=corporate_name]').value = data.corporate_name;";
        echo "                document.querySelector('[name=fantasy_name]').value = data.fantasy_name;";
        echo "                document.querySelector('[name=email]').value = data.email;";
        echo "                document.querySelector('[name=phone]').value = data.phone;";
        echo "            });";
        echo "    }";
        echo "}";
        echo "</script>";
        // Fim do Formulário
        $this->showFormButtons($options);
        return true;
    }

    // ======================== VALIDAÇÕES ===========================
    /**
    * Prepare os dados de entrada antes de adicionar ao banco de dados
    * @param array $input Input data
    * @return array|false Prepared input or false on error
    */
    public function prepareInputForAdd($input)
    {
        //  Validar campos obrigatórios
        if (empty($input['name'])) {
            Session::addMessageAfterRedirect(
                __('Name is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (empty($input['cnpj'])) {
            Session::addMessageAfterRedirect(
                __('CNPJ is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        //  Limpe e valide o CNPJ
        $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);

        if (!Common::validateCNPJ($input['cnpj'])) {
            Session::addMessageAfterRedirect(
                __('Invalid CNPJ', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Verifique se o CNPJ já existe
        $existing = $this->find(['cnpj' => $input['cnpj']]);
        if (count($existing) > 0) {
            Session::addMessageAfterRedirect(
                __('CNPJ already registered', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }

    /**
    * Prepare os dados de entrada antes de atualizar no banco de dados
    * @param array $input Dados de entrada
    * @return array|false Entrada preparada ou false em caso de erro
    */
    public function prepareInputForUpdate($input)
    {
        // Validar nome se fornecido
        if (isset($input['name']) && empty($input['name'])) {
            Session::addMessageAfterRedirect(
                __('Name cannot be empty', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Validar CNPJ se fornecido
        if (isset($input['cnpj'])) {
            $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);

            if (!Common::validateCNPJ($input['cnpj'])) {
                Session::addMessageAfterRedirect(
                    __('Invalid CNPJ', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }

            // Verifique se o CNPJ já existe (excluindo o item atual)
            $existing = $this->find([
                'cnpj' => $input['cnpj'],
                'id'   => ['!=', $input['id']],
            ]);

            if (count($existing) > 0) {
                Session::addMessageAfterRedirect(
                    __('CNPJ already registered', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        return $input;
    }

    // ================== AÇÕES PÓS CRUD ==================
    /**
     * Ações após adicionar item ao banco de dados
     * @return void
     */
    public function post_addItem()
    {
        // Ação de registro de log
        \Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Company added: ID=%d, Name=%s, CNPJ=%s\n",
                $this->fields['id'],
                $this->fields['name'],
                $this->fields['cnpj']
            )
        );

        // Adicionar entrada no histórico
        if ($this->dohistory) {
            $changes = [
                0,
                '',
                sprintf(__('Company created: %s', 'newbase'), $this->fields['name']),
            ];
            \Log::history(
                $this->fields['id'],
                $this->getType(),
                $changes,
                0,
                \Log::HISTORY_CREATE_ITEM
            );
        }
    }

    /**
     * Ações após atualizar item no banco de dados
     * @param int $history Ativar histórico (1 ou 0)
     * @return void
     */
    public function post_updateItem($history = 1)
    {
        // Ação de registro de log
        \Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Company updated: ID=%d, Name=%s\n",
                $this->fields['id'],
                $this->fields['name']
            )
        );
    }

    /**
     * Actions before deleting item from database
     * @return bool Can delete?
     */
    public function pre_deleteItem()
    {
        // Verifique se a empresa tem endereços relacionados
        $address = new Address();
        $addresses = $address->find(['companydata_id' => $this->fields['id']]);

        if (count($addresses) > 0) {
            Session::addMessageAfterRedirect(
                __('Cannot delete company with registered addresses', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Verifique se a empresa tem sistemas relacionados
        $system = new System();
        $systems = $system->find(['companydata_id' => $this->fields['id']]);

        if (count($systems) > 0) {
            Session::addMessageAfterRedirect(
                __('Cannot delete company with registered systems', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return true;
    }

    //================== MÉTODOS AJAX ====================
    /**
     * Lidar com solicitações AJAX
     * @param array $params Request parameters
     * @return void
     */
    public static function handleAjax($params): void
    {
        header('Content-Type: application/json');

        if (!isset($params['action'])) {
            echo json_encode(['success' => false, 'message' => 'No action specified']);
            return;
        }

        switch ($params['action']) {
            case 'search_cnpj':
                self::ajaxSearchCNPJ($params);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }

    /**
     * AJAX: Pesquisar empresa por CNPJ
     * @param array $params Parâmetros
     * @return void
     */
    private static function ajaxSearchCNPJ($params)
    {
        if (empty($params['cnpj'])) {
            echo json_encode(['success' => false, 'message' => 'CNPJ required']);
            return;
        }

        // Usa a função Common::searchCompanyByCNPJ()
        $data = Common::searchCompanyByCNPJ($params['cnpj']);

        if ($data) {
            echo json_encode([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Company not found',
            ]);
        }
    }

    /**
    * Obtenha campos pesquisáveis para integrações externas
    * @return array Campos pesquisáveis
    */
    public static function getSearchableFields()
    {
        return [
            'name'           => __('Name'),
            'cnpj'           => __('CNPJ', 'newbase'),
            'corporate_name' => __('Corporate Name', 'newbase'),
            'fantasy_name'   => __('Fantasy Name', 'newbase'),
            'email'          => __('Email'),
            'phone'          => __('Phone'),
        ];
    }

    // Buscar dados automaticamente pelo CNPJ
    public function fetchFromReceitaFederal(string $cnpj)
    {
        $url = "https://www.receitafederal.gov.br/api/cnpj/{$cnpj}";
        // Implementar chamada API e preenchimento automático
    }
}
