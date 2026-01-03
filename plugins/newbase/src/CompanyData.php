<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Toolbox;
use Session;
use Html;
use Dropdown;
use Entity;

/**
 * CompanyData class
 *
 * Gerencia apenas os dados principais da empresa.
 * Endereços são gerenciados na aba separada (tabela glpi_plugin_newbase_address)
 * Busca email usando proxy PHP que resolve CORS
 *
 * @package   PluginNewbase
 * @author    João Lucas
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

    public static function getTypeName($nb = 0): string
    {
        return ($nb > 1) ? 'Dados de Empresas' : 'Dados da Empresa';
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
     * Define tabs - ADICIONA ABA DE ENDEREÇOS
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
     * FORMULÁRIO APENAS COM DADOS BÁSICOS DA EMPRESA
     * (Endereços são gerenciados na aba separada)
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

        // ========== SEÇÃO 1: IDENTIFICAÇÃO ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-building'></i>&nbsp;IDENTIFICAÇÃO DA EMPRESA";
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

        // Linha 3: Razão Social
        echo "<tr class='tab_bg_1'>";
        echo "<td>Razão Social</td>";
        echo "<td colspan='3'>";
        echo Html::input('legal_name', [
            'value' => $this->fields['legal_name'] ?? '',
            'id' => 'legal_name_field',
            'style' => 'width: 95%;'
        ]);
        echo "</td>";
        echo "</tr>";

        // ========== SEÇÃO 2: CONTATO ==========
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

        // ========== NOTA SOBRE ENDEREÇOS ==========
        if ($ID > 0) {
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='4' style='text-align: center; padding: 15px; background-color: #f0f8ff;'>";
            echo "<i class='fas fa-info-circle' style='color: #0066cc;'></i> ";
            echo "<strong>Para gerenciar os endereços desta empresa, acesse a aba \"Endereços\" acima.</strong>";
            echo "</td>";
            echo "</tr>";
        }

        $this->showFormButtons($options);

        // ========== JAVASCRIPT COM PROXY PHP ==========
        echo Html::scriptBlock("
        $(document).ready(function() {
            // Máscaras
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
                    alert('❌ CNPJ inválido! Digite um CNPJ com 14 dígitos.');
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
                            var msg = '✅ Dados da empresa carregados com sucesso!';
                            if (data.email) {
                                msg += '\\n📧 Email encontrado!';
                            } else {
                                msg += '\\n⚠️ Email não encontrado nas APIs.';
                            }
                            msg += '\\n\\n💡 Dica: Após salvar, use a aba \"Endereços\".';

                            alert(msg);
                        } else {
                            alert('❌ Erro: ' + response.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 404) {
                            alert('❌ CNPJ não encontrado nas bases de dados.');
                        } else {
                            alert('❌ Erro ao buscar CNPJ. Tente novamente.');
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

        // Nome é obrigatório
        if (empty($input['name'])) {
            Session::addMessageAfterRedirect('Nome Fantasia é obrigatório', false, ERROR);
            return false;
        }

        // Valida CNPJ se fornecido
        if (!empty($input['cnpj'])) {
            $cnpj = preg_replace('/[^0-9]/', '', $input['cnpj']);
            if (!$this->validateCNPJ($cnpj)) {
                Session::addMessageAfterRedirect('CNPJ inválido', false, ERROR);
                return false;
            }
            $input['cnpj'] = $this->formatCNPJ($cnpj);
        }

        // Valida email se fornecido
        if (!empty($input['email'])) {
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                Session::addMessageAfterRedirect('Email inválido', false, ERROR);
                return false;
            }
        }

        // Valida telefone se fornecido
        if (!empty($input['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', $input['phone']);
            if (strlen($phone) < 10 || strlen($phone) > 11) {
                Session::addMessageAfterRedirect('Número de telefone inválido', false, ERROR);
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
