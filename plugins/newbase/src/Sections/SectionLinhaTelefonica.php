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

namespace GlpiPlugin\Newbase\Sections;

use GlpiPlugin\Newbase\CompanyData;
use Glpi\Application\View\TemplateRenderer;
use Session;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * SectionLinhaTelefonica
 *
 * Responsável por carregar e exibir a aba "Linha Telefônica" dentro
 * do formulário de CompanyData.
 *
 * Tabela gerenciada: glpi_plugin_newbase_linha_telefonica
 * Template         : @newbase/companydata/sections/linha_telefonica.html.twig
 *
 * Registro ÚNICO por empresa — sem campo JSON.
 * Todos os dados são colunas escalares diretas na tabela.
 *
 * @package GlpiPlugin\Newbase\Sections
 */
class SectionLinhaTelefonica
{
    /** Nome da tabela gerenciada por esta seção */
    private const TABLE = 'glpi_plugin_newbase_linha_telefonica';

    /** Chave de seção enviada ao endpoint AJAX */
    public const SECTION_KEY = 'linha_telefonica';

    /** Template Twig relativo ao namespace @newbase */
    private const TEMPLATE = '@newbase/companydata/sections/linha_telefonica.html.twig';

    /**
     * Campos escalares string do registro.
     * Campos de data e inteiros são tratados separadamente no save().
     */
    private const STRING_FIELDS = [
        'numero_piloto',
        'tipo_linha',
        'operadora',
        'operadora_anterior',
        'status_linha',
        'ip_proxy',
        'porta_proxy',
        'ip_audio',
        'observacoes',
    ];

    /** Campos do tipo DATE (validados e convertidos no save) */
    private const DATE_FIELDS = [
        'data_portabilidade',
        'data_ativacao',
        'data_vencimento',
    ];

    /** Campos do tipo inteiro */
    private const INT_FIELDS = [
        'qtd_canais',
        'qtd_ddr',
    ];

    /** Valores aceitos para status_linha */
    private const STATUS_OPTIONS = [
        'ativo',
        'portando',
        'cancelado',
        'pausado',
    ];

    // -----------------------------------------------------------------------
    // Exibição
    // -----------------------------------------------------------------------

    /**
     * Ponto de entrada chamado por CompanyData::displayTabContentForItem().
     *
     * Carrega o registro de glpi_plugin_newbase_linha_telefonica para a
     * empresa informada e renderiza o template Twig correspondente.
     *
     * @param CompanyData $item Registro de empresa atualmente aberto
     * @return void
     */
    public static function show(CompanyData $item): void
    {
        $companyId = (int) $item->getID();
        $record    = self::loadData($companyId);

        // Montar array de dados para o template
        $data = ['id' => $record['id'] ?? 0];

        foreach (self::STRING_FIELDS as $field) {
            $data[$field] = $record[$field] ?? '';
        }

        foreach (self::DATE_FIELDS as $field) {
            // Normalizar para string vazia se NULL
            $data[$field] = $record[$field] ?? '';
        }

        foreach (self::INT_FIELDS as $field) {
            $data[$field] = (int) ($record[$field] ?? 0);
        }

        // Portabilidade é TINYINT — converte para bool para o template
        $data['portabilidade'] = (bool) ($record['portabilidade'] ?? 0);

        TemplateRenderer::getInstance()->display(
            self::TEMPLATE,
            [
                'item_id'        => $companyId,
                'csrf_token'     => Session::getNewCSRFToken(),
                'section_key'    => self::SECTION_KEY,
                'data'           => $data,
                'status_options' => self::STATUS_OPTIONS,
            ]
        );
    }

    // -----------------------------------------------------------------------
    // Persistência
    // -----------------------------------------------------------------------

    /**
     * Salva (INSERT ou UPDATE) o registro desta seção para uma empresa.
     *
     * Chamado por ajax/systemsConfig.php quando section_key === 'linha_telefonica'.
     *
     * @param int   $companyId ID do registro em glpi_plugin_newbase_companydatas
     * @param array $input     Dados POST sanitizados
     * @return bool            true em caso de sucesso
     */
    public static function save(int $companyId, array $input): bool
    {
        global $DB;

        $row = [
            'plugin_newbase_companydatas_id' => $companyId,
            'entities_id'                    => self::resolveEntitiesId($companyId),
            'date_mod'                       => date('Y-m-d H:i:s'),
        ];

        // Campos string
        foreach (self::STRING_FIELDS as $field) {
            $row[$field] = self::str($input[$field] ?? '');
        }

        // Validar status_linha contra lista branca
        if (!in_array($row['status_linha'], self::STATUS_OPTIONS, true)) {
            $row['status_linha'] = 'ativo';
        }

        // Campos de data — aceita 'YYYY-MM-DD' ou vazio → NULL
        foreach (self::DATE_FIELDS as $field) {
            $row[$field] = self::sanitizeDate($input[$field] ?? '');
        }

        // Campos inteiros
        foreach (self::INT_FIELDS as $field) {
            $row[$field] = (int) ($input[$field] ?? 0);
        }

        // Portabilidade — booleano (0 ou 1)
        $row['portabilidade'] = empty($input['portabilidade']) ? 0 : 1;

        $existing = self::loadData($companyId);

        if (!empty($existing['id'])) {
            $result = $DB->update(
                self::TABLE,
                $row,
                ['id' => (int) $existing['id']]
            );
        } else {
            $row['date_creation'] = date('Y-m-d H:i:s');
            $row['is_deleted']    = 0;
            $result = $DB->insert(self::TABLE, $row);
        }

        return (bool) $result;
    }

    // -----------------------------------------------------------------------
    // Helpers privados
    // -----------------------------------------------------------------------

    /**
     * Carrega o registro desta seção para uma empresa.
     * Retorna array vazio se ainda não existir.
     *
     * @param int $companyId
     * @return array<string, mixed>
     */
    private static function loadData(int $companyId): array
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'  => self::TABLE,
            'WHERE' => [
                'plugin_newbase_companydatas_id' => $companyId,
                'is_deleted'                     => 0,
            ],
            'LIMIT' => 1,
        ]);

        return $iterator->current() ?: [];
    }

    /**
     * Resolve o entities_id a partir do registro de empresa.
     *
     * @param int $companyId
     * @return int
     */
    private static function resolveEntitiesId(int $companyId): int
    {
        global $DB;

        $row = $DB->request([
            'SELECT' => ['entities_id'],
            'FROM'   => 'glpi_plugin_newbase_companydatas',
            'WHERE'  => ['id' => $companyId],
            'LIMIT'  => 1,
        ])->current();

        return (int) ($row['entities_id'] ?? 0);
    }

    /**
     * Sanitiza um valor escalar string.
     *
     * @param mixed $value
     * @return string
     */
    private static function str(mixed $value): string
    {
        return strip_tags(trim((string) $value));
    }

    /**
     * Valida e normaliza um campo de data.
     *
     * Aceita apenas o formato 'YYYY-MM-DD'. Retorna NULL para qualquer
     * valor inválido ou vazio, evitando erros de tipo no MySQL.
     *
     * @param mixed $value
     * @return string|null 'YYYY-MM-DD' ou null
     */
    private static function sanitizeDate(mixed $value): ?string
    {
        $str = trim((string) $value);

        if ($str === '' || $str === '0000-00-00') {
            return null;
        }

        // Valida formato YYYY-MM-DD e existência real da data
        $parts = explode('-', $str);
        if (count($parts) === 3 && checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
            return $str;
        }

        return null;
    }
}
