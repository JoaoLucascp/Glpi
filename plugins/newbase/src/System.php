<?php
/**
* System Class - Gerenciamento de sistema para o plugin Newbase
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
use Session;
use Html;
use Entity;

/**
* System - Gerencia sistemas de empresas (PABX, IPBX, Chatbot, etc)
* Manipula operações CRUD para sistemas de telefonia com formulários dinâmicos
* baseados no tipo de sistema
*/
class System extends Common
{
    // ===== CONFIGURAÇÕES GLPI =====
    /**
    * Gerenciamento de direitos
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
    * Obter nome do tipo
    * @param int $nb Número de itens
    * @return string Nome do tipo
    */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Systems', 'newbase') : __('System', 'newbase');
    }

    /**
    * Obter nome da tabela
    * @param string $classname Nome da classe (opcional)
    * @return string Nome da tabela
    */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_systems';
    }

    /**
    * Obter ícone para menus
    * @return string Classe de ícone Font Awesome
    */
    public static function getIcon(): string
    {
        return 'fas fa-server';
    }

    /**
    * Obter tipos de sistema
    * @return array Tipos de sistema
    */
    public static function getSystemTypes(): array
    {
        return [
            'pabx'       => __('PABX', 'newbase'),
            'ipbx'       => __('IPBX', 'newbase'),
            'ipbx_cloud' => __('IPBX Cloud', 'newbase'),
            'chatbot'    => __('Chatbot', 'newbase'),
            'landline'   => __('Landline', 'newbase')
        ];
    }

    /**
    * Definir opções de pesquisa para o motor de busca do GLPI
    * @return array Opções de pesquisa
    */
    public function rawSearchOptions(): array
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
            'table'         => $this::getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'massiveaction' => false,
            'datatype'      => 'number'
        ];

        // Name
        $tab[] = [
            'id'            => '1',
            'table'         => $this::getTable(),
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

        // Tipo de Sistema
        $tab[] = [
            'id'       => '4',
            'table'    => $this::getTable(),
            'field'    => 'system_type',
            'name'     => __('Type', 'newbase'),
            'datatype' => 'string',
        ];

        // Descrição
        $tab[] = [
            'id'       => '5',
            'table'    => $this::getTable(),
            'field'    => 'description',
            'name'     => __('Description'),
            'datatype' => 'text',
        ];

        // Data de modificação
        $tab[] = [
            'id'            => '19',
            'table'         => $this::getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];

        // Data de criação
        $tab[] = [
            'id'            => '121',
            'table'         => $this::getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];

        return $tab;
    }

    // ===== FORMULÁRIO DINÂMICO =====
    /**
    * Exibir formulário para sistema com campos dinâmicos
    * @param int   $ID      ID do item (0 para novo)
    * @param array $options Opções adicionais
    * @return bool Sucesso
    */
    public function showForm($ID, array $options = []): bool
    {
        // Verificar permissões
        if (!$this::canView()) {
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

        // Campo de nome
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
            'required' => true
        ]);
        echo "</td>";

        // Dropdown de empresa
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

        // Dropdown de Tipo de Sistema
        echo "<td>" . __('System Type', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        $types = $this->getSystemTypes();
        $current_type = $this->fields['system_type'] ?? '';

        echo "<select name='system_type' id='system_type_field' required>";
        echo "<option value=''>-- " . __('Select a type', 'newbase') . " --</option>";
        foreach ($types as $key => $label) {
            $selected = ($current_type === $key) ? 'selected' : '';
            echo "<option value='$key' $selected>$label</option>";
        }
        echo "</select>";
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

        // Descrição (sempre visível)
        echo "<td colspan='1'>" . __('Description') . "</td>";
        echo "<td colspan='3'>";
        echo Html::textarea([
            'name'  => 'description',
            'value' => $this->fields['description'] ?? '',
            'cols'  => 80,
            'rows'  => 3
        ]);
        echo "</td>";

        echo "</tr>";

        // ===== CAMPOS ESPECÍFICOS POR TIPO =====

        // Campos de PABX
        echo "<tbody id='fields_pabx' class='system-fields' style='display:none;'>";
        echo "<tr class='tab_bg_1'><td colspan='4'><h3>" . __('PABX Configuration', 'newbase') . "</h3></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Brand', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('pabx_brand', [
            'value' => $this->fields['pabx_brand'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";

        echo "<td>" . __('Model', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('pabx_model', [
            'value' => $this->fields['pabx_model'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Extensions', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('pabx_extensions', [
            'value' => $this->fields['pabx_extensions'] ?? '',
            'size'  => 20,
            'type'  => 'number'
        ]);
        echo "</td>";
        echo "<td></td><td></td>";
        echo "</tr>";
        echo "</tbody>";

        // Campos de IPBX
        echo "<tbody id='fields_ipbx' class='system-fields' style='display:none;'>";
        echo "<tr class='tab_bg_1'><td colspan='4'><h3>" . __('IPBX Configuration', 'newbase') . "</h3></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('IP Address', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('ipbx_ip', [
            'value' => $this->fields['ipbx_ip'] ?? '',
            'size'  => 20
        ]);
        echo "</td>";

        echo "<td>" . __('Port', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('ipbx_port', [
            'value' => $this->fields['ipbx_port'] ?? '5060',
            'size'  => 10,
            'type'  => 'number'
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Asterisk Version', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('ipbx_version', [
            'value' => $this->fields['ipbx_version'] ?? '',
            'size'  => 20
        ]);
        echo "</td>";

        echo "<td>" . __('Users', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('ipbx_users', [
            'value' => $this->fields['ipbx_users'] ?? '',
            'size'  => 10,
            'type'  => 'number'
        ]);
        echo "</td>";
        echo "</tr>";
        echo "</tbody>";

        // Campos de IPBX Cloud
        echo "<tbody id='fields_ipbx_cloud' class='system-fields' style='display:none;'>";
        echo "<tr class='tab_bg_1'><td colspan='4'><h3>" . __('IPBX Cloud Configuration', 'newbase') . "</h3></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('URL', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('cloud_url', [
            'value' => $this->fields['cloud_url'] ?? '',
            'size'  => 50
        ]);
        echo "</td>";

        echo "<td>" . __('Username', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('cloud_username', [
            'value' => $this->fields['cloud_username'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('API Token', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::input('cloud_api_token', [
            'value' => $this->fields['cloud_api_token'] ?? '',
            'size'  => 80,
            'type'  => 'password'
        ]);
        echo "</td>";
        echo "</tr>";
        echo "</tbody>";

        // Campos de Chatbot
        echo "<tbody id='fields_chatbot' class='system-fields' style='display:none;'>";
        echo "<tr class='tab_bg_1'><td colspan='4'><h3>" . __('Chatbot Configuration', 'newbase') . "</h3></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Platform', 'newbase') . "</td>";
        echo "<td>";
        $platforms = [
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'messenger' => 'Facebook Messenger',
            'other' => __('Other', 'newbase')
        ];
        echo "<select name='chatbot_platform'>";
        echo "<option value=''>-- " . __('Select', 'newbase') . " --</option>";
        foreach ($platforms as $key => $label) {
            $selected = (($this->fields['chatbot_platform'] ?? '') === $key) ? 'selected' : '';
            echo "<option value='$key' $selected>$label</option>";
        }
        echo "</select>";
        echo "</td>";

        echo "<td>" . __('Phone Number', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('chatbot_phone', [
            'value' => $this->fields['chatbot_phone'] ?? '',
            'size'  => 20
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('API Key', 'newbase') . "</td>";
        echo "<td colspan='3'>";
        echo Html::input('chatbot_api_key', [
            'value' => $this->fields['chatbot_api_key'] ?? '',
            'size'  => 80,
            'type'  => 'password'
        ]);
        echo "</td>";
        echo "</tr>";
        echo "</tbody>";

        // Campos de Linha Fixa
        echo "<tbody id='fields_landline' class='system-fields' style='display:none;'>";
        echo "<tr class='tab_bg_1'><td colspan='4'><h3>" . __('Landline Configuration', 'newbase') . "</h3></td></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Phone Number', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('landline_number', [
            'value' => $this->fields['landline_number'] ?? '',
            'size'  => 20,
            'id'    => 'landline_number_field'
        ]);
        echo "</td>";

        echo "<td>" . __('Operator', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('landline_operator', [
            'value' => $this->fields['landline_operator'] ?? '',
            'size'  => 30
        ]);
        echo "</td>";
        echo "</tr>";
        echo "</tbody>";

        // JavaScript para formulário dinâmico
        echo "<script type='text/javascript'>";
        echo "
        $(document).ready(function() {
            // Mascara para telefone de linha fixa
            $('#landline_number_field').mask('(00) 0000-0000');

            // Funcao para mostrar/ocultar campos
            function showFieldsForType(type) {
                // Oculta todos os campos
                $('.system-fields').hide();

                // Mostra apenas os campos do tipo selecionado
                if (type) {
                    $('#fields_' + type).show();
                }
            }

            // Ao mudar o tipo
            $('#system_type_field').change(function() {
                showFieldsForType($(this).val());
            });

            // Mostra campos do tipo atual (se editando)
            var currentType = $('#system_type_field').val();
            if (currentType) {
                showFieldsForType(currentType);
            }
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
    public function prepareInputForAdd($input): mixed
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

        if (empty($input['system_type'])) {
            Session::addMessageAfterRedirect(
                __('System type is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Validar tipo de sistema
        $valid_types = array_keys($this::getSystemTypes());
        if (!in_array($input['system_type'], $valid_types)) {
            Session::addMessageAfterRedirect(
                __('Invalid system type', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Validar campos específicos do tipo
        switch ($input['system_type']) {
            case 'ipbx':
                if (empty($input['ipbx_ip'])) {
                    Session::addMessageAfterRedirect(
                        __('IP Address is required for IPBX', 'newbase'),
                        false,
                        ERROR
                    );
                    return false;
                }
                // Validar formato de IP
                if (!filter_var($input['ipbx_ip'], FILTER_VALIDATE_IP)) {
                    Session::addMessageAfterRedirect(
                        __('Invalid IP Address format', 'newbase'),
                        false,
                        ERROR
                    );
                    return false;
                }
                break;

            case 'ipbx_cloud':
                if (empty($input['cloud_url'])) {
                    Session::addMessageAfterRedirect(
                        __('URL is required for IPBX Cloud', 'newbase'),
                        false,
                        ERROR
                    );
                    return false;
                }
                break;

            case 'landline':
                if (empty($input['landline_number'])) {
                    Session::addMessageAfterRedirect(
                        __('Phone number is required for Landline', 'newbase'),
                        false,
                        ERROR
                    );
                    return false;
                }
                // Limpar número de telefone
                $input['landline_number'] = preg_replace('/[^0-9]/', '', $input['landline_number']);
                break;
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
    public function prepareInputForUpdate($input): mixed
    {
        // Usar as mesmas validações de adição
        return $this->prepareInputForAdd($input);
    }

    // ===== AÇÕES PÓS CRUD =====
    /**
    * Ações após adicionar item ao banco de dados
    * @return void
    */
    public function post_addItem(): void
    {
        // Logar ação
        \Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "System added: ID=%d, Type=%s, Company=%d\n",
                $this->fields['id'],
                $this->fields['system_type'],
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
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if ($item instanceof CompanyData) {
            if ($_SESSION['glpishow_count_on_tabs']) {
                $count = $this::countForItem($item);
                return $this::createTabEntry(
                    $this::getTypeName(Session::getPluralNumber()),
                    $count
                );
            }
            return $this::getTypeName(Session::getPluralNumber());
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
    * Contar sistemas de uma empresa
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
    * Mostrar sistemas de uma empresa
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
            echo "<i class='fas fa-plus'></i> " . __('Add a system', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        // Obter sistemas
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
            echo "<p>" . __('No system registered for this company', 'newbase') . "</p>";
            echo "</div>";
            return;
        }

        // Obter tipos
        $types = self::getSystemTypes();

        // Exibir tabela
        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>" . __('Name') . "</th>";
        echo "<th>" . __('Type', 'newbase') . "</th>";
        echo "<th>" . __('Description') . "</th>";
        if ($canedit) {
            echo "<th>" . __('Actions') . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($iterator as $data) {
            $system = new self();
            $system->getFromDB($data['id']);

            echo "<tr>";

            // Name
            echo "<td>";
            echo "<a href='" . $system->getFormURLWithID($data['id']) . "'>";
            echo "<i class='" . self::getIcon() . "'></i> ";
            echo $data['name'];
            echo "</a>";
            echo "</td>";

            // Type
            echo "<td>" . ($types[$data['system_type']] ?? $data['system_type']) . "</td>";

            // Description
            echo "<td>" . (substr($data['description'] ?? '', 0, 100) ?: '-') . "</td>";

            // Actions
            if ($canedit) {
                echo "<td>";
                echo "<a href='" . $system->getFormURLWithID($data['id']) . "' class='btn btn-sm btn-primary'>";
                echo "<i class='fas fa-edit'></i>";
                echo "</a> ";
                echo Html::getSimpleForm(
                    $system->getFormURL(),
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

    // ===== MÉTODOS DE PERMISSÃO =====
    /**
    * Verificar se o usuário pode visualizar o item
    * @return bool
    */
    public static function canView(): bool
    {
        return Session::haveRight(self::$rightname, READ);
    }

    /**
    * Verificar se o usuário pode criar o item
    * @return bool
    */
    public static function canCreate(): bool
    {
        return Session::haveRight(self::$rightname, CREATE);
    }

    /**
    * Verificar se o usuário pode atualizar o item
    * @return bool
    */
    public static function canUpdate(): bool
    {
        return Session::haveRight(self::$rightname, UPDATE);
    }

    /**
    * Verificar se o usuário pode excluir o item
    * @return bool
    */
    public static function canDelete(): bool
    {
        return Session::haveRight(self::$rightname, DELETE);
    }

    /**
    * Verificar se o usuário pode expurgar o item
    * @return bool
    */
    public static function canPurge(): bool
    {
        return Session::haveRight(self::$rightname, PURGE);
    }
}

