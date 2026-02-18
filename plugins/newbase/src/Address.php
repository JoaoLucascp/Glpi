<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Newbase. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2024-2026 by João Lucas
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/JoaoLucascp/Glpi
 * -------------------------------------------------------------------------
 */

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use CommonGLPI;
use Entity;
use Html;
use Session;
use Toolbox;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Address - Gerencia endereços de empresas com integração de CEP
 * Manipula operações CRUD para endereços com busca automática de CEP,
 * geocodificação e relacionamento com CompanyData
 */
class Address extends CommonDBTM
{
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

    /**
     * Obter o nome do tipo
     * @param int $nb Número de itens
     * @return string Nome do tipo
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Address', 'Addresses', $nb, 'newbase');
    }

    /**
     * Obter o nome da tabela
     * @param string|null $classname Nome da classe (opcional)
     * @return string Nome da tabela
     */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_addresses';
    }

    /**
     * Obter ícone para menus (Tabler Icons - GLPI 10+)
     * @return string Classe de ícone
     */
    public static function getIcon(): string
    {
        return 'ti ti-map-pin';
    }

    /**
     * Definir opções de busca para o motor de busca do GLPI
     * @return array Opções de busca
     */
    public function rawSearchOptions(): array
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'            => '2',
            'table'         => $this->getTable(),
            'field'         => 'name',
            'name'          => __('Name'),
            'datatype'      => 'itemlink',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'       => '3',
            'table'    => CompanyData::getTable(),
            'field'    => 'name',
            'name'     => __('Company', 'newbase'),
            'datatype' => 'dropdown',
        ];

        $tab[] = [
            'id'       => '4',
            'table'    => $this->getTable(),
            'field'    => 'cep',
            'name'     => __('ZIP Code', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '5',
            'table'    => $this->getTable(),
            'field'    => 'street',
            'name'     => __('Street', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '6',
            'table'    => $this->getTable(),
            'field'    => 'number',
            'name'     => __('Number', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '7',
            'table'    => $this->getTable(),
            'field'    => 'neighborhood',
            'name'     => __('Neighborhood', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '8',
            'table'    => $this->getTable(),
            'field'    => 'city',
            'name'     => __('City', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '9',
            'table'    => $this->getTable(),
            'field'    => 'state',
            'name'     => __('State', 'newbase'),
            'datatype' => 'string',
        ];

        $tab[] = [
            'id'       => '10',
            'table'    => $this->getTable(),
            'field'    => 'latitude',
            'name'     => __('Latitude', 'newbase'),
            'datatype' => 'decimal',
        ];

        $tab[] = [
            'id'       => '11',
            'table'    => $this->getTable(),
            'field'    => 'longitude',
            'name'     => __('Longitude', 'newbase'),
            'datatype' => 'decimal',
        ];

        $tab[] = [
            'id'            => '19',
            'table'         => $this->getTable(),
            'field'         => 'date_mod',
            'name'          => __('Last update'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => '121',
            'table'         => $this->getTable(),
            'field'         => 'date_creation',
            'name'          => __('Creation date'),
            'datatype'      => 'datetime',
            'massiveaction' => false,
        ];

        return $tab;
    }

    /**
     * Exibir formulário para endereço
     * @param int   $ID      ID do item (0 para novo)
     * @param array $options Opções adicionais
     * @return bool Sucesso
     */
    public function showForm($ID, array $options = []): bool
    {
        $this->initForm($ID, $options);

        if (!$this->canView()) {
            return false;
        }

        // Obter companydata_id (prioritize GET)
        $companydata_id = $_GET['companydata_id'] ?? $options['companydata_id'] ?? $this->fields['companydata_id'] ?? 0;

        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('name', [
            'value' => $this->fields['name'] ?? '',
            'size'  => 50,
        ]);
        echo "</td>";
        echo "<td>" . __('Company', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        // Verificar se a classe existe
        if (class_exists('GlpiPlugin\\Newbase\\CompanyData')) {
            CompanyData::dropdown([
                'name'  => 'companydata_id',
                'value' => $companydata_id,
            ]);
        } else {
            echo Html::input('companydata_id', [
                'value' => $companydata_id,
                'type'  => 'number',
            ]);
        }
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('ZIP Code', 'newbase') . " <span class='red'>*</span></td>";
        echo "<td>";
        echo Html::input('cep', [
            'value' => $this->fields['cep'] ?? '',
            'size'  => 15,
            'id'    => 'cep_field',
        ]);
        echo " <button type='button' id='search_cep' class='btn btn-primary btn-sm'>";
        echo "<i class='ti ti-search'></i> " . __('Search CEP', 'newbase');
        echo "</button>";
        echo "</td>";
        echo "<td>" . __('Number', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('number', [
            'value' => $this->fields['number'] ?? '',
            'size'  => 10,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Street', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('street', [
            'value' => $this->fields['street'] ?? '',
            'size'  => 50,
            'id'    => 'street_field',
        ]);
        echo "</td>";
        echo "<td>" . __('Complement', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('complement', [
            'value' => $this->fields['complement'] ?? '',
            'size'  => 30,
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Neighborhood', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('neighborhood', [
            'value' => $this->fields['neighborhood'] ?? '',
            'size'  => 30,
            'id'    => 'neighborhood_field',
        ]);
        echo "</td>";
        echo "<td>" . __('City', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('city', [
            'value' => $this->fields['city'] ?? '',
            'size'  => 30,
            'id'    => 'city_field',
        ]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('State', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('state', [
            'value'     => $this->fields['state'] ?? '',
            'size'      => 2,
            'id'        => 'state_field',
            'maxlength' => 2,
        ]);
        echo "</td>";
        echo "<td>" . __('Entity') . "</td>";
        echo "<td>";
        Entity::dropdown([
            'name'  => 'entities_id',
            'value' => $this->fields['entities_id'] ?? $_SESSION['glpiactive_entity'],
        ]);
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }

    /**
     * Preparar dados de entrada antes de adicionar ao banco de dados
     *
     * @param array $input Dados de entrada do formulário
     * @return array|bool Entrada preparada ou false em caso de erro
     */
    public function prepareInputForAdd($input)
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
        // VALIDAÇÃO DE CEP
        if (!empty($input['cep'])) {
            $cep = preg_replace('/[^0-9]/', '', $input['cep']);

            if (strlen($cep) !== 8) {
                Session::addMessageAfterRedirect(
                    __('Invalid ZIP Code: must have 8 digits', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }

            if (preg_match('/^0+$/', $cep)) {
                Session::addMessageAfterRedirect(
                    __('Invalid ZIP Code: cannot be all zeros', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }

            $input['cep'] = $cep;

            // BUSCAR DADOS AUTOMÁTICOS DO CEP (ViaCEP)
            if (empty($input['street']) || empty($input['city'])) {
                $addressData = $this->fetchAddressFromCEP($cep);

                if ($addressData) {
                    $input['street']       = $input['street'] ?? $addressData['logradouro'];
                    $input['neighborhood'] = $input['neighborhood'] ?? $addressData['bairro'];
                    $input['city']         = $input['city'] ?? $addressData['localidade'];
                    $input['state']        = $input['state'] ?? $addressData['uf'];

                    Session::addMessageAfterRedirect(
                        __('Address data loaded from ZIP Code', 'newbase'),
                        false,
                        INFO
                    );
                }
            }
        }

        // VALIDAR COORDENADAS GPS
        if (!empty($input['latitude']) && !empty($input['longitude'])) {
            $lat = (float) $input['latitude'];
            $lng = (float) $input['longitude'];

            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                Session::addMessageAfterRedirect(
                    __('Invalid GPS coordinates', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }
        }

        return parent::prepareInputForAdd($input);
    }

    /**
     * Preparar dados antes de atualizar
     *
     * @param array $input Dados de entrada do formulário
     * @return array|bool Entrada preparada ou false em caso de erro
     */
    public function prepareInputForUpdate($input)
    {
        // Guard clause: validate input is array
        if (empty($input)) {
            return false;
        }
        if (isset($input['name']) && empty(trim($input['name']))) {
            Session::addMessageAfterRedirect(
                __('Name cannot be empty', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        if (isset($input['cep']) && !empty($input['cep'])) {
            $cep = preg_replace('/[^0-9]/', '', $input['cep']);

            if (strlen($cep) !== 8 || preg_match('/^0+$/', $cep)) {
                Session::addMessageAfterRedirect(
                    __('Invalid ZIP Code', 'newbase'),
                    false,
                    ERROR
                );
                return false;
            }

            $input['cep'] = $cep;
        }

        if (isset($input['state']) && !empty($input['state'])) {
            $input['state'] = strtoupper(substr($input['state'], 0, 2));
        }

        return parent::prepareInputForUpdate($input);
    }

    /**
     * Buscar dados de endereço pela API ViaCEP
     * @param string $cep CEP sem formatação (8 dígitos)
     * @return array|false Dados do endereço ou false se não encontrado
     */
    private function fetchAddressFromCEP(string $cep): array|false
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT      => 'GLPI Newbase Plugin/2.0',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$response) {
                Toolbox::logInFile('newbase_plugin', "ViaCEP API error for CEP {$cep}: HTTP {$httpCode}\n");
                return false;
            }

            $data = json_decode($response, true);

            if (isset($data['erro']) && $data['erro'] === true) {
                Toolbox::logInFile('newbase_plugin', "CEP not found: {$cep}\n");
                return false;
            }

            return $data;
        } catch (\Exception $e) {
            Toolbox::logInFile('newbase_plugin', "Error fetching CEP {$cep}: " . $e->getMessage() . "\n");
            return false;
        }
    }

    /**
     * Ações após adicionar item ao banco de dados
     */
    public function post_addItem(): void
    {
        Toolbox::logInFile(
            'newbase_plugin',
            sprintf(
                "Address added: ID=%d, CEP=%s, Company=%d\n",
                $this->fields['id'],
                $this->fields['cep'],
                $this->fields['companydata_id'] ?? 0
            )
        );
    }

    /**
     * Obter nome da aba para o item
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
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
     * Exibir conteúdo da aba para o item
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
     */
    public static function countForItem(CommonDBTM $item): int
    {
        return countElementsInTable(
            self::getTable(),
            [
                'companydata_id' => $item->getID(),
                'is_deleted'     => 0,
            ]
        );
    }

    /**
     * Mostrar endereços de uma empresa
     */
    public static function showForCompany(CompanyData $company): void
    {
        global $DB;

        $company_id = $company->getID();
        $canedit = $company->canUpdate();

        if ($canedit) {
            echo "<div class='center firstbloc'>";
            echo "<a class='btn btn-primary' href='" . self::getFormURL() . "?companydata_id=$company_id'>";
            echo "<i class='ti ti-plus'></i> " . __('Add an address', 'newbase');
            echo "</a>";
            echo "</div>";
        }

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'companydata_id' => $company_id,
                'is_deleted'     => 0,
            ],
            'ORDER' => ['name'],
        ]);

        if (count($iterator) === 0) {
            echo "<div class='center'><p>" . __('No address registered for this company', 'newbase') . "</p></div>";
            return;
        }

        echo "<div class='table-responsive'>";
        echo "<table class='tab_cadre_fixehov'>";
        echo "<thead><tr>";
        echo "<th>" . __('Name') . "</th>";
        echo "<th>" . __('ZIP Code', 'newbase') . "</th>";
        echo "<th>" . __('Street', 'newbase') . "</th>";
        echo "<th>" . __('Number', 'newbase') . "</th>";
        echo "<th>" . __('City', 'newbase') . "</th>";
        echo "<th>" . __('State', 'newbase') . "</th>";
        echo "<th>" . __('Coordinates', 'newbase') . "</th>";
        echo "</tr></thead><tbody>";

        foreach ($iterator as $data) {
            echo "<tr>";
            echo "<td><a href='" . self::getFormURLWithID($data['id']) . "'>{$data['name']}</a></td>";
            echo "<td>" . self::formatCEP($data['cep']) . "</td>";
            echo "<td>" . ($data['street'] ?: '-') . "</td>";
            echo "<td>" . ($data['number'] ?: 'S/N') . "</td>";
            echo "<td>" . ($data['city'] ?: '-') . "</td>";
            echo "<td>" . ($data['state'] ?: '-') . "</td>";
            echo "<td>";
            if ($data['latitude'] && $data['longitude']) {
                echo number_format((float) $data['latitude'], 6) . ", " . number_format((float) $data['longitude'], 6);
            } else {
                echo "-";
            }
            echo "</td></tr>";
        }

        echo "</tbody></table></div>";
    }

    /**
     * Formatar CEP para exibição (12345678 -> 12345-678)
     */
    private static function formatCEP(string $cep): string
    {
        return preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $cep);
    }
}
