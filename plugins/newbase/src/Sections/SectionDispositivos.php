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
 * SectionDispositivos
 *
 * Responsável por carregar e exibir a aba "Dispositivos" dentro
 * do formulário de CompanyData.
 *
 * Tabela gerenciada: glpi_plugin_newbase_dispositivos
 * Template         : @newbase/companydata/sections/dispositivos.html.twig
 *
 * Diferente das seções IPBX, esta seção gerencia múltiplos registros
 * (N linhas por empresa), cada um representando um dispositivo de rede
 * (switch, roteador, AP, etc.). Não há campo JSON — cada linha é uma
 * linha na tabela.
 *
 * @package GlpiPlugin\Newbase\Sections
 */
class SectionDispositivos
{
    /** Nome da tabela gerenciada por esta seção */
    private const TABLE = 'glpi_plugin_newbase_dispositivos';

    /** Chave de seção enviada ao endpoint AJAX */
    public const SECTION_KEY = 'dispositivos';

    /** Template Twig relativo ao namespace @newbase */
    private const TEMPLATE = '@newbase/companydata/sections/dispositivos.html.twig';

    /**
     * Colunas que compõem uma linha da tabela dinâmica.
     * Usadas tanto no sanitize do save() quanto como estrutura
     * de linha vazia no show().
     */
    private const ROW_FIELDS = [
        'tipo_dispositivo',
        'ip_dispositivo',
        'senha_dispositivo',
        'observacoes',
    ];

    // -----------------------------------------------------------------------
    // Exibição
    // -----------------------------------------------------------------------

    /**
     * Ponto de entrada chamado por CompanyData::displayTabContentForItem().
     *
     * Carrega todos os registros de glpi_plugin_newbase_dispositivos para
     * a empresa informada e renderiza o template Twig correspondente.
     *
     * @param CompanyData $item Registro de empresa atualmente aberto
     * @return void
     */
    public static function show(CompanyData $item): void
    {
        $companyId = (int) $item->getID();

        TemplateRenderer::getInstance()->display(
            self::TEMPLATE,
            [
                'item_id'     => $companyId,
                'csrf_token'  => Session::getNewCSRFToken(),
                'section_key' => self::SECTION_KEY,
                // Array de linhas já formatado para o template
                'rows'        => self::loadRows($companyId),
            ]
        );
    }

    // -----------------------------------------------------------------------
    // Persistência
    // -----------------------------------------------------------------------

    /**
     * Salva a lista completa de dispositivos para uma empresa.
     *
     * Estratégia: soft-delete em todos os registros existentes da empresa
     * seguido de INSERT das linhas recebidas (não-vazias). Isso garante
     * que linhas removidas pelo usuário sumam da listagem sem perder
     * o histórico (is_deleted = 1).
     *
     * Chamado por ajax/systemsConfig.php quando section_key === 'dispositivos'.
     *
     * @param int   $companyId ID do registro em glpi_plugin_newbase_companydatas
     * @param array $input     Dados POST sanitizados — espera $input['rows']
     * @return bool            true em caso de sucesso
     */
    public static function save(int $companyId, array $input): bool
    {
        global $DB;

        $entitiesId = self::resolveEntitiesId($companyId);
        $rows       = self::sanitizeRows($input['rows'] ?? []);

        // 1. Soft-delete em todos os registros atuais da empresa
        $DB->update(
            self::TABLE,
            ['is_deleted' => 1, 'date_mod' => date('Y-m-d H:i:s')],
            ['plugin_newbase_companydatas_id' => $companyId, 'is_deleted' => 0]
        );

        // 2. Re-inserir as linhas recebidas
        $now = date('Y-m-d H:i:s');

        foreach ($rows as $row) {
            $DB->insert(self::TABLE, [
                'plugin_newbase_companydatas_id' => $companyId,
                'entities_id'                    => $entitiesId,
                'tipo_dispositivo'               => $row['tipo_dispositivo']  ?? '',
                'ip_dispositivo'                 => $row['ip_dispositivo']    ?? '',
                'senha_dispositivo'              => $row['senha_dispositivo'] ?? '',
                'observacoes'                    => $row['observacoes']       ?? '',
                'is_deleted'                     => 0,
                'date_creation'                  => $now,
                'date_mod'                       => $now,
            ]);
        }

        return true;
    }

    // -----------------------------------------------------------------------
    // Helpers privados
    // -----------------------------------------------------------------------

    /**
     * Carrega todos os dispositivos ativos de uma empresa.
     * Garante que cada linha tenha exatamente as chaves de ROW_FIELDS.
     *
     * @param int $companyId
     * @return array<int, array<string, string>>
     */
    private static function loadRows(int $companyId): array
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'  => self::TABLE,
            'WHERE' => [
                'plugin_newbase_companydatas_id' => $companyId,
                'is_deleted'                     => 0,
            ],
            'ORDER' => ['id' => 'ASC'],
        ]);

        $rows = [];

        foreach ($iterator as $record) {
            $rows[] = [
                'tipo_dispositivo'  => $record['tipo_dispositivo']  ?? '',
                'ip_dispositivo'    => $record['ip_dispositivo']    ?? '',
                'senha_dispositivo' => $record['senha_dispositivo'] ?? '',
                'observacoes'       => $record['observacoes']       ?? '',
            ];
        }

        return $rows;
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
     * Sanitiza o array de linhas recebido via POST.
     * Remove linhas onde todos os campos são vazios.
     * Aplica strip_tags + trim em cada valor.
     *
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, string>>
     */
    private static function sanitizeRows(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            // Normaliza: garante que só existam as chaves esperadas
            $normalized = [];
            foreach (self::ROW_FIELDS as $field) {
                $normalized[$field] = strip_tags(trim((string) ($row[$field] ?? '')));
            }

            // Descarta linha inteiramente vazia
            $filled = array_filter($normalized, fn(string $v): bool => $v !== '');
            if (empty($filled)) {
                continue;
            }

            $clean[] = $normalized;
        }

        return array_values($clean);
    }
}
