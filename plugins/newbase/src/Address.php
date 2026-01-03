<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use CommonGLPI;
use Session;
use Html;
use Toolbox;

/**
 * Address class
 *
 * Gerencia endereços com busca automática de CEP via ViaCEP
 * e suporte a geolocalização
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */
class Address extends CommonDBTM
{
    public static $rightname = 'plugin_newbase_companydata';
    public $dohistory = true;

    public static function getTable($classname = null)
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_address';
    }

    public static function getTypeName($nb = 0): string
    {
        return ($nb > 1) ? 'Endereços' : 'Endereço';
    }

    /**
     * Get tab name for item
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
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
     * Display tab content
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
     * Count addresses for a company
     */
    public static function countForItem(CommonDBTM $item): int
    {
        global $DB;

        $iterator = $DB->request([
            'COUNT' => 'cpt',
            'FROM'  => self::getTable(),
            'WHERE' => [
                'plugin_newbase_companydata_id' => $item->getId()
            ]
        ]);

        $result = $iterator->current();
        return (int)($result['cpt'] ?? 0);
    }

    /**
     * Show addresses for a company - INTERFACE MELHORADA
     */
    public static function showForCompany(CompanyData $company): void
    {
        global $DB, $CFG_GLPI;

        $company_id = $company->getID();
        $canedit = $company->canUpdate();

        echo "<div class='spaced'>";

        // Botão de adicionar
        if ($canedit) {
            echo "<div class='center firstbloc' style='margin-bottom: 15px;'>";
            echo "<a class='btn btn-primary' href='" . $CFG_GLPI['root_doc'] .
                "/front/form.php?itemtype=" . __CLASS__ .
                "&plugin_newbase_companydata_id=" . $company_id . "'>";
            echo "<i class='fas fa-plus'></i>&nbsp;Adicionar Endereço";
            echo "</a>";
            echo "</div>";
        }

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['plugin_newbase_companydata_id' => $company_id],
            'ORDER' => 'id DESC'
        ]);

        if (count($iterator)) {
            echo "<table class='tab_cadre_fixehov'>";
            echo "<tr class='noHover'>";
            echo "<th colspan='9' style='text-align: left; padding: 10px;'>";
            echo "<i class='fas fa-map-marker-alt'></i>&nbsp;";
            echo self::getTypeName(count($iterator)) . " (" . count($iterator) . ")";
            echo "</th>";
            echo "</tr>";

            echo "<tr>";
            echo "<th width='5%'>ID</th>";
            echo "<th width='10%'>CEP</th>";
            echo "<th width='25%'>Logradouro</th>";
            echo "<th width='8%'>Número</th>";
            echo "<th width='15%'>Bairro</th>";
            echo "<th width='15%'>Cidade</th>";
            echo "<th width='5%'>UF</th>";
            echo "<th width='12%'>Coordenadas</th>";
            echo "<th width='5%'>Ações</th>";
            echo "</tr>";

            foreach ($iterator as $data) {
                echo "<tr class='tab_bg_1'>";

                // ID
                echo "<td>" . $data['id'] . "</td>";

                // CEP
                echo "<td>" . ($data['cep'] ?: '-') . "</td>";

                // Logradouro
                echo "<td>" . ($data['street'] ?: '-') . "</td>";

                // Número
                echo "<td>" . ($data['number'] ?: 'S/N') . "</td>";

                // Bairro
                echo "<td>" . ($data['neighborhood'] ?: '-') . "</td>";

                // Cidade
                echo "<td>" . ($data['city'] ?: '-') . "</td>";

                // Estado
                echo "<td><strong>" . ($data['state'] ?: '-') . "</strong></td>";

                // Coordenadas
                echo "<td style='font-size: 0.85em;'>";
                if ($data['latitude'] && $data['longitude']) {
                    echo "<i class='fas fa-map-pin' style='color: #dc3545;'></i> ";
                    echo number_format((float)$data['latitude'], 6) . ", " . number_format((float)$data['longitude'], 6);
                } else {
                    echo "<span style='color: #999;'>-</span>";
                }
                echo "</td>";

                // Ações
                echo "<td class='center'>";
                if ($canedit) {
                    // Botão Editar
                    echo "<a href='" . $CFG_GLPI['root_doc'] .
                        "/front/form.php?itemtype=" . __CLASS__ .
                        "&id=" . $data['id'] . "' title='Editar'>";
                    echo "<i class='fas fa-edit' style='color: #0066cc;'></i></a>&nbsp;";

                    // Botão Excluir
                    echo "<a href='#' onclick='return confirm(\"Confirma a exclusão deste endereço?\")'
                            style='color: #dc3545;' title='Excluir'>";
                    echo "<i class='fas fa-trash'></i></a>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            // Nenhum endereço encontrado
            echo "<div style='text-align: center; padding: 40px; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px;'>";
            echo "<i class='fas fa-map-marker-alt' style='font-size: 48px; color: #ccc;'></i>";
            echo "<h3 style='color: #666; margin-top: 15px;'>Nenhum endereço cadastrado</h3>";
            echo "<p style='color: #999;'>Clique no botão acima para adicionar o primeiro endereço desta empresa.</p>";
            echo "</div>";
        }

        echo "</div>";
    }

    /**
     * FORMULÁRIO MELHORADO COM BUSCA DE CEP
     */
    public function showForm($ID, array $options = []): bool
    {
        global $CFG_GLPI;

        if (!$this->canView()) {
            return false;
        }

        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        // ========== SEÇÃO 1: EMPRESA ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-building'></i>&nbsp;EMPRESA";
        echo "</th></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td width='15%'>Empresa <span class='red'>*</span></td>";
        echo "<td colspan='3'>";
        CompanyData::dropdown([
            'name' => 'plugin_newbase_companydata_id',
            'value' => $this->fields['plugin_newbase_companydata_id'] ?? $options['plugin_newbase_companydata_id'] ?? 0,
            'required' => true,
            'display_emptychoice' => false
        ]);
        echo "</td>";
        echo "</tr>";

        // ========== SEÇÃO 2: ENDEREÇO ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-map-marker-alt'></i>&nbsp;ENDEREÇO";
        echo "</th></tr>";

        // Linha 1: CEP
        echo "<tr class='tab_bg_1'>";
        echo "<td>CEP <span class='red'>*</span></td>";
        echo "<td colspan='3'>";
        echo Html::input('cep', [
            'value' => $this->fields['cep'] ?? '',
            'required' => true,
            'id' => 'cep_field',
            'placeholder' => '00000-000'
        ]);
        echo "&nbsp;<button type='button' id='btn_buscar_cep' class='btn btn-sm btn-primary' title='Buscar endereço pelo CEP'>";
        echo "<i class='fas fa-search'></i> Buscar CEP";
        echo "</button>";
        echo "</td>";
        echo "</tr>";

        // Linha 2: Logradouro e Número
        echo "<tr class='tab_bg_1'>";
        echo "<td>Logradouro <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('street', [
            'value' => $this->fields['street'] ?? '',
            'required' => true,
            'id' => 'street_field'
        ]);
        echo "</td>";

        echo "<td width='15%'>Número</td>";
        echo "<td width='35%'>";
        echo Html::input('number', [
            'value' => $this->fields['number'] ?? '',
            'placeholder' => 'Ex: 123 ou S/N',
            'style' => 'width: 100px;'
        ]);
        echo "</td>";
        echo "</tr>";

        // Linha 3: Complemento e Bairro
        echo "<tr class='tab_bg_1'>";
        echo "<td>Complemento</td>";
        echo "<td>";
        echo Html::input('complement', [
            'value' => $this->fields['complement'] ?? '',
            'placeholder' => 'Apto, Sala, Bloco, etc.'
        ]);
        echo "</td>";

        echo "<td>Bairro <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('neighborhood', [
            'value' => $this->fields['neighborhood'] ?? '',
            'required' => true,
            'id' => 'neighborhood_field'
        ]);
        echo "</td>";
        echo "</tr>";

        // Linha 4: Cidade, Estado e País
        echo "<tr class='tab_bg_1'>";
        echo "<td>Cidade <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('city', [
            'value' => $this->fields['city'] ?? '',
            'required' => true,
            'id' => 'city_field'
        ]);
        echo "</td>";

        echo "<td>Estado (UF) <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('state', [
            'value' => $this->fields['state'] ?? '',
            'required' => true,
            'maxlength' => 2,
            'placeholder' => 'SP',
            'style' => 'width: 60px; text-transform: uppercase;',
            'id' => 'state_field'
        ]);
        echo "&nbsp;&nbsp;País: ";
        echo Html::input('country', [
            'value' => $this->fields['country'] ?? 'Brasil',
            'style' => 'width: 150px;'
        ]);
        echo "</td>";
        echo "</tr>";

        // ========== SEÇÃO 3: GEOLOCALIZAÇÃO ==========
        echo "<tr class='tab_bg_1'><th colspan='4'>";
        echo "<i class='fas fa-globe'></i>&nbsp;GEOLOCALIZAÇÃO (Opcional)";
        echo "</th></tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>Latitude</td>";
        echo "<td>";
        echo Html::input('latitude', [
            'value' => $this->fields['latitude'] ?? '',
            'type' => 'number',
            'step' => '0.00000001',
            'placeholder' => '-23.5505199',
            'id' => 'latitude_field'
        ]);
        echo "</td>";

        echo "<td>Longitude</td>";
        echo "<td>";
        echo Html::input('longitude', [
            'value' => $this->fields['longitude'] ?? '',
            'type' => 'number',
            'step' => '0.00000001',
            'placeholder' => '-46.6333094',
            'id' => 'longitude_field'
        ]);
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        // ========== JAVASCRIPT ==========
        echo Html::scriptBlock("
        $(document).ready(function() {
            // Máscara no CEP
            $('#cep_field').mask('00000-000');

            // Força maiúscula no estado
            $('#state_field').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            // Buscar CEP via ViaCEP
            $('#btn_buscar_cep').on('click', function() {
                var cep = $('#cep_field').val().replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert('❌ CEP inválido! Digite um CEP com 8 dígitos.');
                    return;
                }

                $(this).prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> Buscando...');

                $.ajax({
                    url: 'https://viacep.com.br/ws/' + cep + '/json/',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.erro) {
                            alert('❌ CEP não encontrado!');
                            return;
                        }

                        // Preenche os campos
                        $('#street_field').val(data.logradouro || '');
                        $('#neighborhood_field').val(data.bairro || '');
                        $('#city_field').val(data.localidade || '');
                        $('#state_field').val(data.uf || '');

                        alert('✅ Endereço carregado com sucesso!');

                        // Foca no campo número
                        $('input[name=\"number\"]').focus();
                    },
                    error: function() {
                        alert('❌ Erro ao buscar CEP. Verifique sua conexão e tente novamente.');
                    },
                    complete: function() {
                        $('#btn_buscar_cep').prop('disabled', false)
                            .html('<i class=\"fas fa-search\"></i> Buscar CEP');
                    }
                });
            });
        });
        ");

        return true;
    }
}
