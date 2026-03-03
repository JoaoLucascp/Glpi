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
 * SectionChatbot
 *
 * Responsável por carregar e exibir a aba "Chatbot" dentro
 * do formulário de CompanyData.
 *
 * Tabela gerenciada: glpi_plugin_newbase_chatbot
 * Template         : @newbase/companydata/sections/chatbot.html.twig
 *
 * Assim como as seções IPBX, é um registro único por empresa.
 * O campo systems_config armazena JSON com três sub-arrays:
 *   - comunicacao_massa[]
 *   - restricoes[]
 *   - usuarios[]
 *
 * @package GlpiPlugin\Newbase\Sections
 */
class SectionChatbot
{
    /** Nome da tabela gerenciada por esta seção */
    private const TABLE = 'glpi_plugin_newbase_chatbot';

    /** Chave de seção enviada ao endpoint AJAX */
    public const SECTION_KEY = 'chatbot';

    /** Template Twig relativo ao namespace @newbase */
    private const TEMPLATE = '@newbase/companydata/sections/chatbot.html.twig';

    /**
     * Campos escalares do registro (excluem os sub-arrays JSON).
     * Usados para montar o array 'data' enviado ao template e para
     * sanitizar o input no save().
     */
    private const SCALAR_FIELDS = [
        'modelo',
        'chatbot_id',
        'data_ativacao',
        'numero_telefone',
        'link_acesso',
        'plano',
        'qtd_usuarios',
        'qtd_supervisores',
        'qtd_administradores',
        'login_admin',
        'senha_admin',
        'login_superadmin',
        'senha_superadmin',
        'nome_responsavel',
        'numero_responsavel',
        'email_responsavel',
        'redes_sociais',
        'observacoes',
    ];

    /**
     * Sub-arrays dentro de systems_config (tabelas dinâmicas).
     */
    private const JSON_SECTIONS = [
        'comunicacao_massa',
        'restricoes',
        'usuarios',
    ];

    /**
     * Colunas permitidas por sub-array do JSON.
     * Usadas no sanitize para aceitar apenas chaves conhecidas.
     */
    private const JSON_ROW_FIELDS = [
        'comunicacao_massa' => [
            'nome_sistema',
            'data_ativacao',
            'numero_autenticado',
            'tipo_homologacao',
            'link_acesso',
            'login',
            'senha',
            'responsavel',
        ],
        'restricoes' => [
            'data',
            'duracao',
            'numero_restrito',
        ],
        'usuarios' => [
            'nome',
            'login',
            'senha',
            'email',
            'tipo',
            'observacoes',
        ],
    ];

    // -----------------------------------------------------------------------
    // Exibição
    // -----------------------------------------------------------------------

    /**
     * Ponto de entrada chamado por CompanyData::displayTabContentForItem().
     *
     * Carrega o registro de glpi_plugin_newbase_chatbot para a empresa
     * informada, decodifica o campo JSON e renderiza o template Twig.
     *
     * @param CompanyData $item Registro de empresa atualmente aberto
     * @return void
     */
    public static function show(CompanyData $item): void
    {
        $companyId = (int) $item->getID();
        $record    = self::loadData($companyId);

        // Decodificar systems_config
        $systemsConfig = [];
        if (!empty($record['systems_config'])) {
            $decoded = json_decode($record['systems_config'], true);
            if (is_array($decoded)) {
                $systemsConfig = $decoded;
            }
        }

        // Construir array de campos escalares para o template
        $data = ['id' => $record['id'] ?? 0];
        foreach (self::SCALAR_FIELDS as $field) {
            $data[$field] = $record[$field] ?? '';
        }

        TemplateRenderer::getInstance()->display(
            self::TEMPLATE,
            [
                'item_id'           => $companyId,
                'csrf_token'        => Session::getNewCSRFToken(),
                'section_key'       => self::SECTION_KEY,
                'data'              => $data,
                'comunicacao_massa' => $systemsConfig['comunicacao_massa'] ?? [],
                'restricoes'        => $systemsConfig['restricoes']        ?? [],
                'usuarios'          => $systemsConfig['usuarios']          ?? [],
            ]
        );
    }

    // -----------------------------------------------------------------------
    // Persistência
    // -----------------------------------------------------------------------

    /**
     * Salva (INSERT ou UPDATE) o registro desta seção para uma empresa.
     *
     * Chamado por ajax/systemsConfig.php quando section_key === 'chatbot'.
     *
     * @param int   $companyId ID do registro em glpi_plugin_newbase_companydatas
     * @param array $input     Dados POST sanitizados
     * @return bool            true em caso de sucesso
     */
    public static function save(int $companyId, array $input): bool
    {
        global $DB;

        // Montar campo JSON com os três sub-arrays
        $systemsConfig = json_encode([
            'comunicacao_massa' => self::sanitizeRows(
                $input['comunicacao_massa'] ?? [],
                'comunicacao_massa'
            ),
            'restricoes' => self::sanitizeRows(
                $input['restricoes'] ?? [],
                'restricoes'
            ),
            'usuarios' => self::sanitizeRows(
                $input['usuarios'] ?? [],
                'usuarios'
            ),
        ]);

        // Montar linha com campos escalares
        $row = [
            'plugin_newbase_companydatas_id' => $companyId,
            'entities_id'                    => self::resolveEntitiesId($companyId),
            'systems_config'                 => $systemsConfig,
            'date_mod'                       => date('Y-m-d H:i:s'),
        ];

        foreach (self::SCALAR_FIELDS as $field) {
            // qtd_* são inteiros; os demais são strings
            if (str_starts_with($field, 'qtd_')) {
                $row[$field] = (int) ($input[$field] ?? 0);
            } else {
                $row[$field] = self::str($input[$field] ?? '');
            }
        }

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
     * Sanitiza um array de linhas de tabela dinâmica.
     *
     * Aceita apenas as chaves declaradas em JSON_ROW_FIELDS para a seção
     * informada. Remove linhas inteiramente vazias. Aplica strip_tags + trim.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param string                           $section Chave em JSON_ROW_FIELDS
     * @return array<int, array<string, string>>
     */
    private static function sanitizeRows(array $rows, string $section): array
    {
        $allowedFields = self::JSON_ROW_FIELDS[$section] ?? [];
        $clean         = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $normalized = [];
            foreach ($allowedFields as $field) {
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
