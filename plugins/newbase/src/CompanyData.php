<?php

/**
 * Newbase - Gerenciador de Gestão de Empresas para GLPI
 *
 * @author João Lucas <joao@example.com>
 * @license GPLv2+
 * @link https://github.com/newbase/glpi
 */

declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;

/**
 * Classe CompanyData - Utilitário estático para gestão de empresas
 *
 * A partir da v2.1.0, esta classe é um utilitário estático que lê diretamente de glpi_entities,
 * eliminando duplicação de dados e garantindo sincronização automática com o core do GLPI.
 *
 * Dados complementares de empresas são armazenados em glpi_plugin_newbase_company_extras.
 *
 * @package GlpiPlugin\Newbase\Src
 */
class CompanyData
{
    /**
     * Obtenha o nome do tipo
     * @param int $nb Número de itens
     * @return string Nome do tipo
     */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Companies', 'newbase') : __('Company', 'newbase');
    }

    /**
     * Obtenha o ícone para menus
     * @return string Classe de ícones do Font Awesome
     */
    public static function getIcon(): string
    {
        return 'fas fa-building';
    }

    /**
     * Obtenha todas as empresas ativas de glpi_entities
     *
     * @return array Array de empresas [id => name, ...]
     */
    public static function getAllCompanies(): array
    {
        global $DB;

        $companies = [];

        $result = $DB->request([
            'FROM'   => 'glpi_entities',
            'WHERE'  => ['is_deleted' => 0],
            'ORDER'  => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[$entity['id']] = $entity['name'];
        }

        return $companies;
    }

    /**
     * Obtenha dados completos de uma empresa pelo ID (Entity ID do GLPI)
     *
     * @param int $entity_id ID da entidade (glpi_entities.id)
     * @return array|null Array com dados da empresa ou null
     */
    public static function getCompanyById(int $entity_id): ?array
    {
        global $DB;

        $entity = $DB->request([
            'FROM'  => 'glpi_entities',
            'WHERE' => ['id' => $entity_id, 'is_deleted' => 0],
        ])->current();

        if (!$entity) {
            return null;
        }

        // Obter dados complementares
        $extras = self::getCompanyExtras($entity_id);

        // Mesclar dados
        return array_merge($entity, $extras ?? []);
    }

    /**
     * Obtenha uma empresa pelo CNPJ
     *
     * @param string $cnpj CNPJ sem formatação (14 dígitos)
     * @return array|null Array com dados da empresa ou null
     */
    public static function getCompanyByCNPJ(string $cnpj): ?array
    {
        global $DB;

        // Remover formatação do CNPJ
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Buscar nos dados complementares
        $extras = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['cnpj' => $cnpj, 'is_deleted' => 0],
        ])->current();

        if ($extras) {
            // Obter dados completos da entidade
            $entity = self::getCompanyById($extras['entities_id']);
            return $entity;
        }

        return null;
    }

    /**
     * Obtenha dados complementares de uma empresa
     *
     * @param int $entity_id ID da entidade (glpi_entities.id)
     * @return array|null Array com dados complementares ou null
     */
    public static function getCompanyExtras(int $entity_id): ?array
    {
        global $DB;

        $result = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['entities_id' => $entity_id, 'is_deleted' => 0],
        ])->current();

        return $result ?: null;
    }

    /**
     * Salvar ou atualizar dados complementares de uma empresa
     *
     * @param int    $entity_id       ID da entidade
     * @param array  $data            Dados a salvar (cnpj, corporate_name, fantasy_name, etc)
     * @return int|false ID do registro ou false
     */
    public static function saveCompanyExtras(int $entity_id, array $data)
    {
        global $DB;

        // Validar dados
        if (empty($data)) {
            return false;
        }

        // Preparar dados
        $data['entities_id'] = $entity_id;

        // Verificar se já existe
        $existing = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['entities_id' => $entity_id],
        ])->current();

        if ($existing) {
            // Atualizar
            $data['date_mod'] = date('Y-m-d H:i:s');
            return $DB->update('glpi_plugin_newbase_company_extras', $data, ['id' => $existing['id']]);
        } else {
            // Inserir novo
            $data['date_creation'] = date('Y-m-d H:i:s');
            $data['date_mod'] = date('Y-m-d H:i:s');
            return $DB->insert('glpi_plugin_newbase_company_extras', $data);
        }
    }

    /**
     * Pesquisar empresas por termo (busca nos nomes de glpi_entities)
     *
     * @param string $search Termo de busca
     * @param int    $limit  Limite de resultados
     * @return array Array de empresas encontradas
     */
    public static function searchCompanies(string $search, int $limit = 20): array
    {
        global $DB;

        $companies = [];
        $search_term = '%' . $search . '%';

        $result = $DB->request([
            'FROM'   => 'glpi_entities',
            'WHERE'  => [
                'is_deleted' => 0,
                'OR' => [
                    ['name' => ['LIKE', $search_term]],
                ],
            ],
            'LIMIT'  => $limit,
            'ORDER'  => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[] = [
                'id'   => $entity['id'],
                'name' => $entity['name'],
            ];
        }

        return $companies;
    }
    /**
     * Obtenha opções de busca para o motor de pesquisa do GLPI
     *
     * @return array Opções de pesquisa
     */
    public static function rawSearchOptions(): array
    {
        return [
            [
                'id'   => 'common',
                'name' => __('Characteristics'),
            ],
            [
                'id'            => '1',
                'table'         => 'glpi_entities',
                'field'         => 'name',
                'name'          => __('Name'),
                'datatype'      => 'itemlink',
                'massiveaction' => false,
            ],
            [
                'id'       => '2',
                'table'    => 'glpi_plugin_newbase_company_extras',
                'field'    => 'cnpj',
                'name'     => __('CNPJ', 'newbase'),
                'datatype' => 'string',
            ],
            [
                'id'       => '3',
                'table'    => 'glpi_plugin_newbase_company_extras',
                'field'    => 'corporate_name',
                'name'     => __('Corporate Name', 'newbase'),
                'datatype' => 'string',
            ],
            [
                'id'       => '4',
                'table'    => 'glpi_plugin_newbase_company_extras',
                'field'    => 'fantasy_name',
                'name'     => __('Fantasy Name', 'newbase'),
                'datatype' => 'string',
            ],
        ];
    }
}
