<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;


use CommonDBTM;
use Session;
use Html;
use Dropdown;
use Entity;
use Toolbox;
use CommonGLPI;
/**
 * CompanyData class
 *
 * Gerencia apenas os dados principais da empresa.
 * EndereÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§os sÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o gerenciados na aba separada (tabela glpi_plugin_newbase_address)
 * Busca email usando proxy PHP que resolve CORS
 *
 * @package   PluginNewbase
 * @author    JoÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
/**
 * Gerenciamento de dados de empresas (CNPJ, razao social, contatos)
 *
 * @package   PluginNewbase
 * @author    Joao Lucas
 * @copyright Copyright (c) 2025 Joao Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
/**
 * Gerenciamento de dados de empresas (CNPJ, razao social, contatos)
 *
 * @package   PluginNewbase
 * @author    Joao Lucas
 * @copyright Copyright (c) 2025 Joao Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class CompanyData extends CommonDBTM
{
    public static $rightname = 'plugin_newbase_companydata';
    public $dohistory = true;

    public static function getTable($classname = null)
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_companydata';
    }

    public static function getTypeName($nb = 0) {
        return _n('Dado Pessoal', 'Dados Pessoais', $nb, 'newbase');
    }

    /**
     * ✅ FUNÇÃO OBRIGATÓRIA: Define campos de busca/exibição
     */
    public function rawSearchOptions() {
        $tab = [];
        
        // Opção comum (cabeçalho)
        $tab[] = [
            'id'   => 'common',
            'name' => self::getTypeName(2)
        ];
        
        // ID
        $tab[] = [
            'id'            => '1',
            'table'         => $this->getTable(),
            'field'         => 'id',
            'name'          => __('ID'),
            'datatype'      => 'number',
            'massiveaction' => false
        ];
        
        // Nome/Razão Social
        $tab[] = [
            'id'            => '2',
            'table'         => $this->getTable(),
            'field'         => 'name',
            'name'          => __('Nome', 'newbase'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
            'autocomplete'  => true
        ];
        
        // CNPJ
        $tab[] = [
            'id'            => '3',
            'table'         => $this->getTable(),
            'field'         => 'cnpj',
            'name'          => __('CNPJ', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Email
        $tab[] = [
            'id'            => '4',
            'table'         => $this->getTable(),
            'field'         => 'email',
            'name'          => __('Email'),
            'datatype'      => 'email',
            'massiveaction' => false
        ];
        
        // Telefone
        $tab[] = [
            'id'            => '5',
            'table'         => $this->getTable(),
            'field'         => 'phone',
            'name'          => __('Telefone', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Nome Legal
        $tab[] = [
            'id'            => '6',
            'table'         => $this->getTable(),
            'field'         => 'legal_name',
            'name'          => __('Razão Social', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Nome Fantasia
        $tab[] = [
            'id'            => '7',
            'table'         => $this->getTable(),
            'field'         => 'fantasy_name',
            'name'          => __('Nome Fantasia', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Inscrição Estadual
        $tab[] = [
            'id'            => '8',
            'table'         => $this->getTable(),
            'field'         => 'state_registration',
            'name'          => __('Inscrição Estadual', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Inscrição Municipal
        $tab[] = [
            'id'            => '9',
            'table'         => $this->getTable(),
            'field'         => 'city_registration',
            'name'          => __('Inscrição Municipal', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Status do Contrato
        $tab[] = [
            'id'            => '10',
            'table'         => $this->getTable(),
            'field'         => 'contract_status',
            'name'          => __('Status do Contrato', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false
        ];
        
        // Data de Criação
        $tab[] = [
            'id'            => '11',
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Data de criação'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];
        
        // Data de Modificação
        $tab[] = [
            'id'            => '12',
            'table'         => $this->getTable(),
            'field'         => 'date_mod',
            'name'          => __('Data de modificação'),
            'datatype'      => 'datetime',
            'massiveaction' => false
        ];
        
        // Entidade (relação com tabela de entidades)
        $tab[] = [
            'id'            => '80',
            'table'         => 'glpi_entities',
            'field'         => 'completename',
            'name'          => __('Entidade'),
            'datatype'      => 'dropdown',
            'massiveaction' => false
        ];
        
        // Recursivo
        $tab[] = [
            'id'            => '86',
            'table'         => $this->getTable(),
            'field'         => 'is_recursive',
            'name'          => __('Entidades filhas'),
            'datatype'      => 'bool',
            'massiveaction' => false
        ];
        
        return $tab;
    }
    
    /**
     * ✅ Define campos padrão a serem exibidos
     */
    public function getDefaultToDisplay() {
        return ['id', 'name', 'cnpj', 'email', 'phone', 'contract_status'];
    }
    
    /**
     * ✅ Título da página de formulário
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
        return self::getTypeName(2);
    }
    
    /**
     * ✅ Exibe conteúdo das abas
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
        return true;
    }

    public static function getForeignKeyField()
    {
        return 'plugin_newbase_companydata_id';
    }

    public static function getFormURL($full = true): string
    {
        return Toolbox::getItemTypeFormURL(self::class, $full);
    }

    public static function canView(): bool
    {
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    public static function canCreate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    public static function canUpdate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    public static function canDelete(): bool
    {
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    public static function canPurge(): bool
    {
        return (bool) Session::haveRight(self::$rightname, PURGE);
    }

    /**
     * Get menu content
     */
    public static function getMenuContent(): array
    {
        $menu = [];

        if (Session::haveRight(self::$rightname, READ)) {
            $menu['title'] = self::getTypeName(Session::getPluralNumber());
            $menu['page'] = '/plugins/newbase/front/companydata.php';
            $menu['icon'] = 'ti ti-building';

            $menu['options']['companydata'] = [
                'title' => self::getTypeName(Session::getPluralNumber()),
                'page'  => '/plugins/newbase/front/companydata.php',
                'icon'  => 'ti ti-building'
            ];
        }

        if (count($menu)) {
            return $menu;
        }

        return [];
    }

    /**
     * Define tabs - ADICIONA ABA DE ENDEREÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡OS
     */
    public function defineTabs($options = []): array
    {
        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab(Address::class, $ong, $options);
        $this->addStandardTab(System::class, $ong, $options);
        $this->addStandardTab(Task::class, $ong, $options);
        $this->addStandardTab('Log', $ong, $options);
        return $ong;
    }

    /**
     * FORMULÃƒÆ’Ã†â€™Ãƒâ€šÃ‚ÂRIO APENAS COM DADOS BÃƒÆ’Ã†â€™Ãƒâ€šÃ‚ÂSICOS DA EMPRESA
     * (EndereÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§os sÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o gerenciados na aba separada)
     */
    public function showForm($ID, array $options = []): bool
    {
        global $CFG_GLPI;

        if (!$this->canView()) {
            return false;
        }

        if (!isset($options['target'])) {
            $options['target'] = $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php';
        }

        if (!isset($options['canedit'])) {
            $options['canedit'] = $this->canEdit($ID);
        }

        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        // ========== SEÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢O 1: IDENTIFICAÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢O ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-building'></i>&nbsp;IDENTIFICAÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢O DA EMPRESA";
        echo "</th></tr>";

        // Linha 1: CNPJ e Status
        echo "<tr class='tab_bg_1'>";
        echo "<td width='15%'><label for='cnpj'>CNPJ <span class='red'>*</span></label></td>";
        echo "<td width='35%'>";
        echo Html::input('cnpj', [
            'value' => $this->fields['cnpj'] ?? '',
            'id' => 'cnpj_field',
            'placeholder' => '00.000.000/0000-00'
        ]);
        echo "&nbsp;<button type='button' id='btn_buscar_cnpj' class='btn btn-sm btn-primary' title='Buscar dados na Receita Federal'>";
        echo "<i class='fas fa-search'></i> Buscar CNPJ";
        echo "</button>";
        echo "</td>";

        echo "<td width='15%'>Status do Contrato</td>";
        echo "<td width='35%'>";
        Dropdown::showFromArray('contract_status', [
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'cancelled' => 'Cancelado'
        ], [
            'value' => $this->fields['contract_status'] ?? 'active'
        ]);
        echo "</td>";
        echo "</tr>";

        // Linha 2: Nome Fantasia e Entidade
        echo "<tr class='tab_bg_1'>";
        echo "<td>Nome Fantasia <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'required' => true,
            'id' => 'fantasy_name_field'
        ]);
        echo "</td>";

        echo "<td>" . __('Entity') . "</td>";
        echo "<td>";
        Entity::dropdown(['value' => $this->fields['entities_id'] ?? $_SESSION['glpiactive_entity']]);
        echo "</td>";
        echo "</tr>";

        // Linha 3: RazÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o Social
        echo "<tr class='tab_bg_1'>";
        echo "<td>RazÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o Social</td>";
        echo "<td colspan='3'>";
        echo Html::input('legal_name', [
            'value' => $this->fields['legal_name'] ?? '',
            'id' => 'legal_name_field',
            'style' => 'width: 95%;'
        ]);
        echo "</td>";
        echo "</tr>";

        // ========== SEÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡ÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢O 2: CONTATO ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-phone'></i>&nbsp;CONTATO";
        echo "</th></tr>";

        // Linha 1: Telefone e Email
        echo "<tr class='tab_bg_1'>";
        echo "<td>Telefone</td>";
        echo "<td>";
        echo Html::input('phone', [
            'value' => $this->fields['phone'] ?? '',
            'placeholder' => '(11) 3333-4444',
            'id' => 'phone_field'
        ]);
        echo "</td>";

        echo "<td>Email</td>";
        echo "<td>";
        echo Html::input('email', [
            'value' => $this->fields['email'] ?? '',
            'type' => 'email',
            'placeholder' => 'contato@empresa.com.br',
            'id' => 'email_field'
        ]);
        echo "</td>";
        echo "</tr>";

        // ========== NOTA SOBRE ENDEREÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¡OS ==========
        if ($ID > 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='4' style='text-align: center; padding: 15px; background-color: #f0f8ff;'>";
            echo "<i class='fas fa-info-circle' style='color: #0066cc;'></i> ";
            echo "<strong>Para gerenciar os endereÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§os desta empresa, acesse a aba \"EndereÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§os\" acima.</strong>";
            echo "</td>";
            echo "</tr>";
        }

        $this->showFormButtons($options);

        // ========== JAVASCRIPT COM PROXY PHP ==========
        echo Html::scriptBlock("
        $(document).ready(function() {
            // MÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡scaras
            $('#cnpj_field').mask('00.000.000/0000-00');
            $('#phone_field').mask('(00) 0000-00009');
            $('#phone_field').on('blur', function() {
                var phone = $(this).val().replace(/\D/g, '');
                if (phone.length > 10) {
                    $(this).mask('(00) 00000-0009');
                } else {
                    $(this).mask('(00) 0000-00009');
                }
            });

            // Buscar CNPJ via proxy PHP (resolve CORS)
            $('#btn_buscar_cnpj').on('click', function() {
                var cnpj = $('#cnpj_field').val().replace(/\D/g, '');
                if (cnpj.length !== 14) {
                    alert('ÃƒÆ’Ã‚Â¢Ãƒâ€šÃ‚ÂÃƒâ€¦Ã¢â‚¬â„¢ CNPJ invÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡lido! Digite um CNPJ com 14 dÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­gitos.');
                    return;
                }

                var button = $(this);
                button.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> Buscando...');

                // Busca via proxy PHP (evita CORS)
                $.ajax({
                    url: '" . $CFG_GLPI['root_doc'] . "/plugins/newbase/ajax/cnpj_proxy.php',
                    method: 'POST',
                    data: {
                        cnpj: cnpj,
                        _glpi_csrf_token: $('input[name=_glpi_csrf_token]').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;

                            // Preencher campos
                            $('#legal_name_field').val(data.razao_social || '');
                            $('#fantasy_name_field').val(data.nome_fantasia || data.razao_social || '');
                            $('#phone_field').val(data.telefone || '');
                            $('#email_field').val(data.email || '');

                            // Mensagem de sucesso
                            var msg = 'ÃƒÆ’Ã‚Â¢Ãƒâ€¦Ã¢â‚¬Å“ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â¦ Dados da empresa carregados com sucesso!';
                            if (data.email) {
                                msg += '\\nÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œÃƒâ€šÃ‚Â§ Email encontrado!';
                            } else {
                                msg += '\\nÃƒÆ’Ã‚Â¢Ãƒâ€¦Ã‚Â¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¸Ãƒâ€šÃ‚Â Email nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o encontrado nas APIs.';
                            }
                            msg += '\\n\\nÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã¢â€žÂ¢Ãƒâ€šÃ‚Â¡ Dica: ApÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³s salvar, use a aba \"EndereÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â§os\".';

                            alert(msg);
                        } else {
                            alert('ÃƒÆ’Ã‚Â¢Ãƒâ€šÃ‚ÂÃƒâ€¦Ã¢â‚¬â„¢ Erro: ' + response.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 404) {
                            alert('ÃƒÆ’Ã‚Â¢Ãƒâ€šÃ‚ÂÃƒâ€¦Ã¢â‚¬â„¢ CNPJ nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o encontrado nas bases de dados.');
                        } else {
                            alert('ÃƒÆ’Ã‚Â¢Ãƒâ€šÃ‚ÂÃƒâ€¦Ã¢â‚¬â„¢ Erro ao buscar CNPJ. Tente novamente.');
                        }
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class=\"fas fa-search\"></i> Buscar CNPJ');
                    }
                });
            });
        });
        ");

        return true;
    }

    public function prepareInputForAdd($input)
    {
        return $this->validateInput($input);
    }

    public function prepareInputForUpdate($input)
    {
        return $this->validateInput($input);
    }

    private function validateInput(array $input)
    {
        $input = Toolbox::addslashes_deep($input);

        // Nome ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© obrigatÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³rio
        if (empty($input['name'])) {
            Session::addMessageAfterRedirect('Nome Fantasia ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© obrigatÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â³rio', false, ERROR);
            return false;
        }

        // Valida CNPJ se fornecido
        if (!empty($input['cnpj'])) {
            $cnpj = preg_replace('/[^0-9]/', '', $input['cnpj']);
            if (!$this->validateCNPJ($cnpj)) {
                Session::addMessageAfterRedirect('CNPJ invÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡lido', false, ERROR);
                return false;
            }
            $input['cnpj'] = $this->formatCNPJ($cnpj);
        }

        // Valida email se fornecido
        if (!empty($input['email'])) {
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                Session::addMessageAfterRedirect('Email invÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡lido', false, ERROR);
                return false;
            }
        }

        // Valida telefone se fornecido
        if (!empty($input['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', $input['phone']);
            if (strlen($phone) < 10 || strlen($phone) > 11) {
                Session::addMessageAfterRedirect('NÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Âºmero de telefone invÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¡lido', false, ERROR);
                return false;
            }
            $input['phone'] = $this->formatPhone($phone);
        }

        return $input;
    }

    private function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if (intval($cnpj[12]) !== $digit1) {
            return false;
        }

        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (intval($cnpj[13]) === $digit2);
    }

    private function formatCNPJ(string $cnpj): string
    {
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

    private function formatPhone(string $phone): string
    {
        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        }
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
    }
}

