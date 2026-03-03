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
 * SectionIpbxCloud
 *
 * Responsável por carregar e exibir a aba "IPBX Cloud" dentro
 * do formulário de CompanyData.
 *
 * Tabela gerenciada: glpi_plugin_newbase_ipbx_cloud
 * Template         : @newbase/companydata/sections/ipbx_cloud.html.twig
 *
 * Estrutura idêntica ao SectionIpbxPabx — mesmos campos e tabelas dinâmicas
 * (ramais e operadoras/troncos), diferenciando-se apenas pela tabela de banco
 * e pela chave de seção AJAX.
 *
 * @package GlpiPlugin\Newbase\Sections
 */
class SectionIpbxCloud
{
    /** Nome da tabela gerenciada por esta seção */
    private const TABLE = 'glpi_plugin_newbase_ipbx_cloud';

    /** Chave de seção enviada ao endpoint AJAX */
    public const SECTION_KEY = 'ipbx_cloud';

    /** Template Twig relativo ao namespace @newbase */
    private const TEMPLATE = '@newbase/companydata/sections/ipbx_cloud.html.twig';

    // -----------------------------------------------------------------------
    // Exibição
    // -----------------------------------------------------------------------

    /**
     * Ponto de entrada chamado por CompanyData::displayTabContentForItem().
     *
     * Carrega os dados da tabela glpi_plugin_newbase_ipbx_cloud para a empresa
     * informada e renderiza o template Twig correspondente.
     *
     * @param CompanyData $item Registro de empresa atualmente aberto
     * @return void
     */
    public static function show(CompanyData $item): void
    {
        $companyId = (int) $item->getID();
        $data      = self::loadData($companyId);

        // Decodificar o campo JSON (ramais + operadoras)
        $systemsConfig = [];
        if (!empty($data['systems_config'])) {
            $decoded = json_decode($data['systems_config'], true);
            if (is_array($decoded)) {
                $systemsConfig = $decoded;
            }
        }

        TemplateRenderer::getInstance()->display(
            self::TEMPLATE,
            [
                'item_id'     => $companyId,
                'csrf_token'  => Session::getNewCSRFToken(),
                'section_key' => self::SECTION_KEY,
                // Campos escalares do registro
                'data'        => [
                    'id'          => $data['id']          ?? 0,
                    'modelo'      => $data['modelo']      ?? '',
                    'versao'      => $data['versao']      ?? '',
                    'ip_interno'  => $data['ip_interno']  ?? '',
                    'ip_externo'  => $data['ip_externo']  ?? '',
                    'porta_web'   => $data['porta_web']   ?? '',
                    'senha_web'   => $data['senha_web']   ?? '',
                    'porta_ssh'   => $data['porta_ssh']   ?? '',
                    'senha_ssh'   => $data['senha_ssh']   ?? '',
                    'observacoes' => $data['observacoes'] ?? '',
                ],
                // Sub-arrays do campo JSON
                'ramais'      => $systemsConfig['ramais']     ?? [],
                'operadoras'  => $systemsConfig['operadoras'] ?? [],
            ]
        );
    }

    // -----------------------------------------------------------------------
    // Persistência
    // -----------------------------------------------------------------------

    /**
     * Salva (INSERT ou UPDATE) o registro desta seção para uma empresa.
     *
     * Chamado por ajax/systemsConfig.php quando section_key === 'ipbx_cloud'.
     *
     * @param int   $companyId ID do registro em glpi_plugin_newbase_companydatas
     * @param array $input     Dados POST sanitizados
     * @return bool            true em caso de sucesso
     */
    public static function save(int $companyId, array $input): bool
    {
        global $DB;

        // Construir o campo JSON com ramais e operadoras
        $systemsConfig = json_encode([
            'ramais'     => self::sanitizeRows($input['ramais']     ?? []),
            'operadoras' => self::sanitizeRows($input['operadoras'] ?? []),
        ]);

        // Campos escalares permitidos
        $row = [
            'plugin_newbase_companydatas_id' => $companyId,
            'entities_id'                    => self::resolveEntitiesId($companyId),
            'modelo'                         => self::str($input['modelo']      ?? ''),
            'versao'                         => self::str($input['versao']      ?? ''),
            'ip_interno'                     => self::str($input['ip_interno']  ?? ''),
            'ip_externo'                     => self::str($input['ip_externo']  ?? ''),
            'porta_web'                      => self::str($input['porta_web']   ?? ''),
            'senha_web'                      => self::str($input['senha_web']   ?? ''),
            'porta_ssh'                      => self::str($input['porta_ssh']   ?? ''),
            'senha_ssh'                      => self::str($input['senha_ssh']   ?? ''),
            'observacoes'                    => self::str($input['observacoes'] ?? ''),
            'systems_config'                 => $systemsConfig,
            'date_mod'                       => date('Y-m-d H:i:s'),
        ];

        $existing = self::loadData($companyId);

        if (!empty($existing['id'])) {
            // UPDATE
            $result = $DB->update(
                self::TABLE,
                $row,
                ['id' => (int) $existing['id']]
            );
        } else {
            // INSERT
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
     * Sanitiza um array de linhas de tabela dinâmica.
     * Remove entradas completamente vazias e aplica strip_tags em cada valor.
     *
     * @param array<int, array<string, string>> $rows
     * @return array<int, array<string, string>>
     */
    private static function sanitizeRows(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            // Ignora linha onde todos os valores são vazios
            $values = array_filter(array_map('strval', $row), fn(string $v): bool => $v !== '');
            if (empty($values)) {
                continue;
            }

            $clean[] = array_map(
                fn($v): string => strip_tags(trim((string) $v)),
                $row
            );
        }

        return array_values($clean);
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
}
