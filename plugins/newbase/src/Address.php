<?php
/**
* Classe Address - Gerenciamento de Endereços para o Plugin Newbase
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;

use GlpiPlugin\Newbase\Src\Common;
use GlpiPlugin\Newbase\Src\CompanyData;
use CommonGLPI;
use CommonDBTM;
use Html;
use Session;
use Entity;

/**
* Address - Gerencia endereços de empresas com integração de CEP
* Manipula operações CRUD para endereços com busca automática de CEP,
* geocodificação e relacionamento com CompanyData
*/
class Address extends Common
{
    // ===== CONFIGURAÇÕES GLPI =====
    /**
    * Gerenciamento de permissões
    * @var string
    */
    public static $rightname = 'plugin_newbase';

    /**
    * Habilitar rastreamento de histórico
    * @var bool
    */
    public $dohistory = true;

    // ===== RELACIONAMENTO =====
    /**
    * Nome do campo ID dos itens
    * @var string
    */
    public static $items_id = 'companydata_id';

    /**
    * Tipo de item ao qual esta classe pertence
    * @var string
    */
    public static $itemtype = 'GlpiPlugin\\Newbase\\Src\\CompanyData';

    // ===== MÉTODOS GLPI OBRIGATÓRIOS =====
    /**
    * Obter o nome do tipo
    * @param int $nb Número de itens
    * @return string Nome do tipo
    */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Addresses', 'newbase') : __('Address', 'newbase');
    }

    /**
    * Obter o nome da tabela
    * @param string $classname Nome da classe (opcional)
    * @return string Nome da tabela
    */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_addresses';
    }

    /**
    * Obter ícone para menus
    * @return string Classe de ícone Font Awesome
    */
    public static function getIcon(): string
    {
        return 'fas fa-map-marker-alt';
    }

    /**
    * Definir opções de busca para o motor de busca do GLPI
    * @return array Opções de busca
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

        // Empresa
        $tab[] = [
            'id'       => '3',
            'table'    => CompanyData::getTable(),
            'field'    => 'name',
            'name'     => __('Company', 'newbase'),
            'datatype' => 'dropdown',
        ];

        // CEP
        $tab[] = [
            'id'       => '4',
            'table'    => $this->getTable(),
            'field'    => 'cep',
            'name'     => __('ZIP Code', 'newbase'),
            'datatype' => 'string',
        ];

        // Rua
        $tab[] = [
            'id'       => '5',
            'table'    => $this->getTable(),
            'field'    => 'street',
            'name'     => __('Street', 'newbase'),
            'datatype' => 'string',
        ];

        // Número
        $tab[] = [
            'id'       => '6',
            'table'    => $this->getTable(),
            'field'    => 'number',
            'name'     => __('Number', 'newbase'),
            'datatype' => 'string',
        ];

        // Bairro
        $tab[] = [
            'id'       => '7',
            'table'    => $this->getTable(),
            'field'    => 'neighborhood',
            'name'     => __('Neighborhood', 'newbase'),
            'datatype' => 'string',
        ];

        // Cidade
        $tab[] = [
            'id'       => '8',
            'table'    => $this->getTable(),
            'field'    => 'city',
            'name'     => __('City', 'newbase'),
            'datatype' => 'string',
        ];

        // Estado
        $tab[] = [
            'id'       => '9',
            'table'    => $this->getTable(),
            'field'    => 'state',
            'name'     => __('State', 'newbase'),
            'datatype' => 'string',
        ];

        // Latitude
        $tab[] = [
            'id'       => '10',
            'table'    => $this->getTable(),
            'field'    => 'latitude',
            'name'     => __('Latitude', 'newbase'),
            'datatype' => 'decimal',
        ];

        // Longitude
        $tab[] = [
            'id'       => '11',
            'table'    => $this->getTable(),
            'field'    => 'longitude',
            'name'     => __('Longitude', 'newbase'),
            'datatype' => 'decimal',
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

    // ===== FORMULÁRIO =====
    /**
    * Exibir formulário para endereço
    * @param int   $ID      ID do item (0 para novo)
    * @param array $options Opções adicionais
    * @return bool Sucesso
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

        // Obter companydata_id da URL ou formulário
        $companydata_id = $options['companydata_id'] ?? $_GET['companydata_id'] ?? $this->fields['companydata_id'] ?? 0;

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

        // Dropdown de Empresa
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        CompanyData::dropdown([
            'name'   => 'companydata_id',
            'value'  => $companydata_id,
            'entity' => $_SESSION['glpiactive_entity'] ?? 0,
            'required' => true
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Campo CEP com busca automática
        echo "<td>" . __('ZIP Code', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('cep', [
            'value' => $this->fields['cep'] ?? '',
            'size'  => 15,
            'id'    => 'cep_field',
            'required' => true
        ]);
        echo " <button type='button' id='search_cep' class='btn btn-primary'>";
        echo "<i class='fas fa-search'></i> " . __('Search CEP', 'newbase');
        echo "</button>";
        echo "</td>";

        // Número
        echo "<td>" . __('Number', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('number', [
            'value' => $this->fields['number'] ?? '',
            'size'  => 10
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Rua
        echo "<td>" . __('Street', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('street', [
            'value' => $this->fields['street'] ?? '',
            'size'  => 50,
            'id'    => 'street_field'
        ]);
        echo "</td>";

        // Complemento
        echo "<td>" . __('Complement', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('complement', [
            'value' => $this->fields['complement'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Bairro
        echo "<td>" . __('Neighborhood', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('neighborhood', [
            'value' => $this->fields['neighborhood'] ?? '',
            'size'  => 30,
            'id'    => 'neighborhood_field'
        ]);
        echo "</td>";

        // Cidade
        echo "<td>" . __('City', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('city', [
            'value' => $this->fields['city'] ?? '',
            'size'  => 30,
            'id'    => 'city_field'
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Estado
        echo "<td>" . __('State', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('state', [
            'value' => $this->fields['state'] ?? '',
            'size'  => 2,
            'id'    => 'state_field',
            'maxlength' => 2
        ]);
        echo "</td>";

        // Entidade
        echo "<td>" . __('Entity') . "</td>";
        echo "<td>";
        Entity::dropdown([
            'name'   => 'entities_id',
            'value'  => $this->fields['entities_id'] ?? 0,
            'entity' => $_SESSION['glpiactive_entity'] ?? 0
        ]);
        echo "</td>";

        echo "</tr>";

        echo "<tr class='tab_bg_1'>";

        // Latitude (apenas leitura - preenchimento automático)
        echo "<td>" . __('Latitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('latitude', [
            'value' => $this->fields['latitude'] ?? '',
            'size'  => 15,
            'id'    => 'latitude_field',
            'readonly' => true
        ]);
        echo "</td>";

        // Longitude (apenas leitura - preenchimento automático)
        echo "<td>" . __('Longitude', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('longitude', [
            'value' => $this->fields['longitude'] ?? '',
            'size'  => 15,
            'id'    => 'longitude_field',
            'readonly' => true
        ]);
        echo "</td>";

        echo "</tr>";

        // JavaScript para busca automática de CEP
        $plugin_root = \Plugin::getWebDir('newbase');

        echo "<script type='text/javascript'>";
        echo "
        $(document).ready(function() {
            // Formatar CEP enquanto digita
            $('#cep_field').mask('00000-000');

            // Formatar Estado para maiúsculas
            $('#state_field').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            // Botão de busca de CEP
            $('#search_cep').click(function() {
                var cep = $('#cep_field').val().replace(/[^0-9]/g, '');

                if (cep.length !== 8) {
                    alert('" . __('Invalid ZIP Code', 'newbase') . "');
                    return;
                }

                // Mostrar carregando
                var btn = $(this);
                btn.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> " . __('Searching...', 'newbase') . "');

                // Chamar AJAX
                $.ajax({
                    url: '{$plugin_root}/ajax/searchAddress.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        cep: cep
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            var data = response.data;

                            // Preencher campos de endereço
                            $('#street_field').val(data.street || '');
                            $('#neighborhood_field').val(data.neighborhood || '');
                            $('#city_field').val(data.city || '');
                            $('#state_field').val(data.state || '');

                            // Preencher coordenadas se disponíveis
                            if (data.latitude && data.longitude) {
                                $('#latitude_field').val(data.latitude);
                                $('#longitude_field').val(data.longitude);
                            }

                            alert('" . __('Address found! Please verify the data.', 'newbase') . "');
                        } else {
                            alert(response.message || '" . __('Address not found', 'newbase') . "');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('" . __('Error searching ZIP Code', 'newbase') . "');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class=\"fas fa-search\"></i> " . __('Search CEP', 'newbase') . "');
                    }
                });
            });

            // Busca automática ao sair do campo CEP (opcional)
            $('#cep_field').blur(function() {
                var cep = $(this).val().replace(/[^0-9]/g, '');
                if (cep.length === 8 && !$('#street_field').val()) {
                    $('#search_cep').click();
                }
            });
        });
        ";
        echo "</script>";

        // Finalizar formulário
        $this->showFormButtons($options);

        return true;
    }

    // ===== VALIDAÇÕES =====
    /**
    * Preparar dados de entrada antes de adicionar ao banco de dados
    * @param array $input Dados de entrada
    * @return array|false Entrada preparada ou false em caso de erro
    */
    public function prepareInputForAdd($input)
    {
        // Validar campos obrigatórios
        if (empty($input['name'])) {
            Session::addMessageAfterRedirect(
                __('Name is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (empty($input['companydata_id'])) {
            Session::addMessageAfterRedirect(
                __('Company is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (empty($input['cep'])) {
            Session::addMessageAfterRedirect(
                __('ZIP Code is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Limpar e validar CEP
        $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);

        if (strlen($input['cep']) !== 8) {
            Session::addMessageAfterRedirect(
                __('Invalid ZIP Code', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Validar estado (2 letras)
        if (!empty($input['state'])) {
            $input['state'] = strtoupper(substr($input['state'], 0, 2));
        }

        // Verificar se a empresa existe
        $company = new CompanyData();
        if (!$company->getFromDB($input['companydata_id'])) {
            Session::addMessageAfterRedirect(
                __('Company not found', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }

    /**
    * Preparar dados de entrada antes de atualizar no banco de dados
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

        // Validar CEP se fornecido
        if (isset($input['cep'])) {
            $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);

            if (strlen($input['cep']) !== 8) {
                Session::addMessageAfterRedirect(
                    __('Invalid ZIP Code', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        // Validar estado se fornecido
        if (isset($input['state']) && !empty($input['state'])) {
            $input['state'] = strtoupper(substr($input['state'], 0, 2));
        }

        return $input;
    }

    // ===== AÇÕES PÓS CRUD =====
    /**
    * Ações após adicionar item ao banco de dados
    * @return void
    */
    public function post_addItem()
    {
        // Logar ação
        \Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Address added: ID=%d, CEP=%s, Company=%d\n",
                $this->fields['id'],
                $this->fields['cep'],
                $this->fields['companydata_id']
            )
        );
    }

    // ===== ABA EM COMPANYDATA =====
    /**
    * Obter nome da aba para o item
    * @param CommonGLPI $item Item
    * @param int $withtemplate Modo template
    * @return string Nome da aba
    */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item instanceof CompanyData) {
            if ($_SESSION['glpishow_count_on_tabs']) {
                $count = self::countForItem($item);
                return self::createTabEntry(
                    self::getTypeName(Session::getPluralNumber()),
                    $count
                );
            }
            return self::getTypeName(Session::getPluralNumber());
        }
        return '';
    }

    /**
    * Exibir conteúdo da aba para o item
    * @param CommonGLPI $item Item
    * @param int $tabnum Número da aba
    * @param int $withtemplate Modo template
    * @return bool Sucesso
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
    * Contar endereços para uma empresa
    * @param CommonDBTM $item Item da empresa
    * @return int Contagem
    */
    public static function countForItem(CommonDBTM $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM'  => self::getTable(),
            'WHERE' => [
                'companydata_id' => $item->getID(),
                'is_deleted'     => 0
            ]
        ]);

        $result = $iterator->current();
        return (int)($result['cpt'] ?? 0);
    }

    /**
    * Mostrar endereços de uma empresa
    * @param CompanyData $company Empresa
    * @return void
    */
    public static function showForCompany(CompanyData $company): void
    {
        global $DB;

        $company_id = $company->getID();
        $canedit = $company->canUpdate();

        // Botão adicionar
        if ($canedit) {
            echo "<div class='center firstbloc'>";
            echo "<a class='btn btn-primary' href='" . self::getFormURL() . "?companydata_id=$company_id'>";
            echo "<i class='fas fa-plus'></i> " . __('Add an address', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        // Obter endereços
        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'companydata_id' => $company_id,
                'is_deleted'     => 0
            ],
            'ORDER' => 'name'
        ]);

        if (count($iterator) === 0) {
            echo "<div class='center'>";
            echo "<p>" . __('No address registered for this company', 'newbase') . "</p>";
            echo "</div>";
            return;
        }

        // Exibir tabela
        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('Name') . "</th>";
        echo "<th>" . __('ZIP Code', 'newbase') . "</th>";
        echo "<th>" . __('Street', 'newbase') . "</th>";
        echo "<th>" . __('Number', 'newbase') . "</th>";
        echo "<th>" . __('City', 'newbase') . "</th>";
        echo "<th>" . __('State', 'newbase') . "</th>";
        echo "<th>" . __('Coordinates', 'newbase') . "</th>";
        if ($canedit) {
            echo "<th>" . __('Actions') . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($iterator as $data) {
            $address = new self();
            $address->getFromDB($data['id']);

            echo "<tr>";

            // Nome
            echo "<td>";
            echo "<a href='" . $address->getFormURLWithID($data['id']) . "'>";
            echo $data['name'];
            echo "</a>";
            echo "</td>";

            // CEP
            echo "<td>" . Common::formatCEP($data['cep']) . "</td>";

            // Rua
            echo "<td>" . ($data['street'] ?: '-') . "</td>";

            // Número
            echo "<td>" . ($data['number'] ?: 'S/N') . "</td>";

            // Cidade
            echo "<td>" . ($data['city'] ?: '-') . "</td>";

            // Estado
            echo "<td>" . ($data['state'] ?: '-') . "</td>";

            // Coordenadas
            echo "<td>";
            if ($data['latitude'] && $data['longitude']) {
                echo number_format((float)$data['latitude'], 6) . ", ";
                echo number_format((float)$data['longitude'], 6);
            } else {
                echo "-";
            }
            echo "</td>";

            // Ações
            if ($canedit) {
                echo "<td>";
                echo "<a href='" . $address->getFormURLWithID($data['id']) . "' class='btn btn-sm btn-primary'>";
                echo "<i class='fas fa-edit'></i>";
                echo "</a> ";
                echo Html::getSimpleForm(
                    $address->getFormURL(),
                    ['purge' => 'purge', 'id' => $data['id']],
                    __('Delete permanently'),
                    [],
                    'fa-trash-alt'
                );
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
}

