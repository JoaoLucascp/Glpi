<?php

/**
* CompanyData
* Classe utilitária para gerenciar dados de empresas
* Lê dados nativos de glpi_entities, não cria tabela própria
* Mantém dados complementares em glpi_plugin_newbase_company_extras
* @package   GlpiPlugin\Newbase
* @author    João Lucas
* @license   GPLv2+
* @since     2.1.0
*/

namespace GlpiPlugin\Newbase\Src;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
* Class CompanyData
* Gerencia dados de empresas (entidades) do GLPI
* com complementos específicos do plugin Newbase
*/
class CompanyData
{
    /**
     * Retorna o nome do tipo (para compatibilidade com GLPI)
     *
     * @param int $nb Número de itens
     * @return string
     */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Companies', 'newbase') : __('Company', 'newbase');
    }

    /**
     * Retorna o ícone para menus (Tabler Icons)
     * @return string Classe de ícone
     */
    public static function getIcon(): string
    {
        return 'ti ti-building';
    }

    /**
     * Retorna a URL de busca de empresas
     * @return string
     */
    public static function getSearchURL(): string
    {
        global $CFG_GLPI;
        return $CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php';
    }

    /**
     * Obtém todas as empresas ativas de glpi_entities
     *
     * @return array Array associativo [id => name, ...]
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
     * Obtém dados completos de uma empresa pelo ID
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

        // Mesclar dados (glpi_entities + complementos)
        return array_merge($entity, $extras ?? []);
    }

    /**
     * Obtém uma empresa pelo CNPJ
     *
     * @param string $cnpj CNPJ com ou sem formatação
     * @return array|null Array com dados da empresa ou null
     */
    public static function getCompanyByCNPJ(string $cnpj): ?array
    {
        global $DB;

        // Remover formatação do CNPJ
        $cnpj_clean = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj_clean) !== 14) {
            return null;
        }

        // Buscar nos dados complementares
        $extras = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['cnpj' => $cnpj, 'is_deleted' => 0],
        ])->current();

        if ($extras) {
            // Obter dados completos da entidade
            return self::getCompanyById($extras['entities_id']);
        }

        return null;
    }

    /**
     * Obtém dados complementares de uma empresa
     * @param int $entity_id ID da entidade
     * @return array|null Array com dados complementares ou null
     */
    public static function getCompanyExtras(int $entity_id): ?array
    {
        global $DB;

        // Verifica se a tabela existe antes de consultar
        if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
            return null;
        }

        $result = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['entities_id' => $entity_id, 'is_deleted' => 0],
        ])->current();

        return $result ?: null;
    }

    /**
     * Salvar ou atualizar dados complementares de uma empresa
     * @param int   $entity_id ID da entidade
     * @param array $data      Dados a salvar (cnpj, corporate_name, fantasy_name, etc)
     * @return int|bool ID do registro ou false
     */
    public static function saveCompanyExtras(int $entity_id, array $data)
    {
        global $DB;

        // Validar dados
        if (empty($data)) {
            return false;
        }

        // Validar se tabela existe
        if (!$DB->tableExists('glpi_plugin_newbase_company_extras')) {
            return false;
        }

        // Preparar dados
        $data['entities_id'] = $entity_id;

        // Verificar se já existe registro
        $existing = $DB->request([
            'FROM'  => 'glpi_plugin_newbase_company_extras',
            'WHERE' => ['entities_id' => $entity_id],
        ])->current();

        if ($existing) {
            // Atualizar registro existente
            $data['date_mod'] = date('Y-m-d H:i:s');
            return $DB->update(
                'glpi_plugin_newbase_company_extras',
                $data,
                ['id' => $existing['id']]
            );
        } else {
            // Inserir novo registro
            $data['date_creation'] = date('Y-m-d H:i:s');
            $data['date_mod'] = date('Y-m-d H:i:s');
            $data['is_deleted'] = 0;
            return $DB->insert('glpi_plugin_newbase_company_extras', $data);
        }
    }

    /**
     * Pesquisar empresas por termo
     * @param string $search Termo de busca
     * @param int    $limit  Limite de resultados
     * @return array Array de empresas encontradas
     */
    public static function searchCompanies(string $search, int $limit = 20): array
    {
        global $DB;

        $companies = [];
        $search_term = '%' . addslashes($search) . '%';

        $result = $DB->request([
            'FROM'   => 'glpi_entities',
            'WHERE'  => [
                'is_deleted' => 0,
                'name'       => ['LIKE', $search_term],
            ],
            'LIMIT'  => $limit,
            'ORDER'  => ['name' => 'ASC'],
        ]);

        foreach ($result as $entity) {
            $companies[] = [
                'id'   => $entity['id'],
                'name' => htmlspecialchars($entity['name']),
            ];
        }

        return $companies;
    }
    /**
     * Obtém opções de busca para o motor de pesquisa do GLPI
     * (Usado se CompanyData for uma CommonDBTM no futuro)
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
