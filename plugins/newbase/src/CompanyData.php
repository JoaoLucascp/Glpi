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
            'name' => __('Characteristics')
        ];

        // ID
        $tab[] = [
            'id'            => '2',
            'table'         => $this->getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'massiveaction' => false,
            'datatype'      => 'number'
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

        // Corporate Name (Razão Social)
        $tab[] = [
            'id'       => '4',
            'table'    => $this->getTable(),
            'field'    => 'corporate_name',
            'name'     => __('Corporate Name', 'newbase'),
            'datatype' => 'string',
        ];

        // Fantasy Name (Nome Fantasia)
        $tab[] = [
            'id'       => '5',
            'table'    => $this->getTable(),
            'field'    => 'fantasy_name',
            'name'     => __('Fantasy Name', 'newbase'),
            'datatype' => 'string',
        ];

        // Branch (Filial)
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
            'massiveaction' => false
        ];

        // Data de criação
        $tab[] = [
            'id'            => '121',
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false
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
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
            'required' => true
        ]);
        echo "</td>";

        // Campo CNPJ com integração com API
        echo "<td>" . __('CNPJ', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('cnpj', [
            'value' => $this->fields['cnpj'] ?? '',
            'size'  => 20,
            'id'    => 'cnpj_field',
            'required' => true
        ]);
        echo " <button type='button' id='search_cnpj' class='btn btn-primary'>";
        echo "<i class='fas fa-search'></i> " . __('Search', 'newbase');
        echo "</button>";
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Corporate Name (Razão Social)
        echo "<td>" . __('Corporate Name', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('corporate_name', [
            'value' => $this->fields['corporate_name'] ?? '',
            'size'  => 50,
            'id'    => 'corporate_name_field'
        ]);
        echo "</td>";

        // Fantasy Name (Nome Fantasia)
        echo "<td>" . __('Fantasy Name', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('fantasy_name', [
            'value' => $this->fields['fantasy_name'] ?? '',
            'size'  => 50,
            'id'    => 'fantasy_name_field'
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Branch (Filial)
        echo "<td>" . __('Branch', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('branch', [
            'value' => $this->fields['branch'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";

        // Entidade (se houver múltiplas entidades)
        echo "<td>" . __('Entity') . "</td>";
        echo "<td>";
        Entity::dropdown([
            'entity' => $this->fields['entities_id'] ?? 0]);
        echo "</td>";

        echo "</tr>";

        // JavaScript para integração com API do CNPJ
        echo "<script type='text/javascript'>";
        echo "
        $(document).ready(function() {
            // Format CNPJ while typing
            $('#cnpj_field').mask('00.000.000/0000-00');

            // Search CNPJ button
            $('#search_cnpj').click(function() {
                var cnpj = $('#cnpj_field').val().replace(/[^0-9]/g, '');

                if (cnpj.length !== 14) {
                    alert('" . __('Invalid CNPJ', 'newbase') . "');
                    return;
                }

                // Show loading
                $(this).prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> " . __('Searching...', 'newbase') . "');

                // Call AJAX
                $.ajax({
                    url: CFG_GLPI['root_doc'] + '/plugins/newbase/ajax/searchCompany.php',
                    type: 'POST',
                    data: {
                        cnpj: cnpj,
                        '_token': '" . Session::getNewCSRFToken() . "'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#corporate_name_field').val(response.data.legal_name);
                            $('#fantasy_name_field').val(response.data.fantasy_name);
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('" . __('Error searching CNPJ', 'newbase') . "');
                        console.error('AJAX Error:', error);
                    },
                    complete: function() {
                        $('#search_cnpj').prop('disabled', false).html('<i class=\"fas fa-search\"></i> " . __('Search', 'newbase') . "');
                    }
                });
            });
        });
        ";
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
                'id'   => ['!=', $input['id']]
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
                sprintf(__('Company created: %s', 'newbase'), $this->fields['name'])
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
                'data'    => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Company not found'
            ]);
        }
    }
}

