# Correção dos Erros - Plugin Newbase GLPI

## 🔴 Problema Identificado

**Erro Principal:**
```
Unknown column 'glpi_plugin_newbase_companydata.id' in 'field list'
Warning: Array to string conversion in D:\laragon\www\glpi\src\Search.php on line 752
```

**Causa Raiz:**
O método `getSearchOptionsNew()` no arquivo `CompanyData.php` está com configuração incorreta das opções de pesquisa, especialmente o campo 'id'.

---

## ✅ SOLUÇÃO COMPLETA

### Passo 1: Corrigir o arquivo `CompanyData.php`

Localize o arquivo: `D:\laragon\www\glpi\plugins\newbase\src\CompanyData.php`

**Substitua** a função `getSearchOptionsNew()` por esta versão corrigida:

```php
<?php

public function getSearchOptionsNew()
{
    $tab = [];

    // ID principal - CONFIGURAÇÃO CORRETA
    $tab[] = [
        'id'                 => 'common',
        'name'               => __('Characteristics')
    ];

    $tab[] = [
        'id'                 => '1',
        'table'              => $this->getTable(),
        'field'              => 'name',
        'name'               => __('Name'),
        'datatype'           => 'itemlink',
        'massiveaction'      => false,
        'forcegroupby'       => true,
        'autocomplete'       => true,
    ];

    $tab[] = [
        'id'                 => '2',
        'table'              => $this->getTable(),
        'field'              => 'id',
        'name'               => __('ID'),
        'massiveaction'      => false,
        'datatype'           => 'number',
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '3',
        'table'              => $this->getTable(),
        'field'              => 'cnpj',
        'name'               => __('CNPJ', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '4',
        'table'              => $this->getTable(),
        'field'              => 'corporate_name',
        'name'               => __('Razão Social', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '5',
        'table'              => $this->getTable(),
        'field'              => 'fantasy_name',
        'name'               => __('Nome Fantasia', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '6',
        'table'              => $this->getTable(),
        'field'              => 'branch',
        'name'               => __('Filial', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '7',
        'table'              => $this->getTable(),
        'field'              => 'federal_registration',
        'name'               => __('Inscrição Estadual', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '8',
        'table'              => $this->getTable(),
        'field'              => 'state_registration',
        'name'               => __('Inscrição Estadual', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '9',
        'table'              => $this->getTable(),
        'field'              => 'city_registration',
        'name'               => __('Inscrição Municipal', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '10',
        'table'              => $this->getTable(),
        'field'              => 'contract_status',
        'name'               => __('Status do Contrato', 'newbase'),
        'datatype'           => 'string',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '11',
        'table'              => $this->getTable(),
        'field'              => 'date_creation',
        'name'               => __('Data de criação', 'newbase'),
        'datatype'           => 'datetime',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    $tab[] = [
        'id'                 => '12',
        'table'              => $this->getTable(),
        'field'              => 'date_mod',
        'name'               => __('Data de modificação', 'newbase'),
        'datatype'           => 'datetime',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    // Entidade
    $tab[] = [
        'id'                 => '80',
        'table'              => 'glpi_entities',
        'field'              => 'completename',
        'name'               => __('Entity'),
        'datatype'           => 'dropdown',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    // Recursivo
    $tab[] = [
        'id'                 => '86',
        'table'              => $this->getTable(),
        'field'              => 'is_recursive',
        'name'               => __('Child entities'),
        'datatype'           => 'bool',
        'massiveaction'      => false,
        'forcegroupby'       => true
    ];

    return $tab;
}
```

---

### Passo 2: Verificar a Estrutura da Tabela no Banco de Dados

Execute este SQL no MySQL para confirmar que a tabela existe com os campos corretos:

```sql
-- Verificar estrutura da tabela
DESCRIBE glpi_plugin_newbase_companydata;

-- Verificar se há dados
SELECT COUNT(*) FROM glpi_plugin_newbase_companydata;

-- Ver primeiros registros
SELECT * FROM glpi_plugin_newbase_companydata LIMIT 5;
```

---

### Passo 3: Se a Tabela Estiver Incorreta, Recrie-a

**⚠️ ATENÇÃO: Faça backup antes de executar!**

```sql
-- Backup da tabela existente (se houver dados importantes)
CREATE TABLE glpi_plugin_newbase_companydata_backup AS 
SELECT * FROM glpi_plugin_newbase_companydata;

-- Remover tabela antiga
DROP TABLE IF EXISTS glpi_plugin_newbase_companydata;

-- Recriar tabela corretamente
CREATE TABLE `glpi_plugin_newbase_companydata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corporate_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fantasy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `federal_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `cnpj` (`cnpj`),
  KEY `name` (`name`),
  KEY `date_mod` (`date_mod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### Passo 4: Verificar o arquivo `hook.php`

Certifique-se de que a função `plugin_newbase_install()` está correta:

```php
<?php

function plugin_newbase_install()
{
    global $DB;

    $migration = new Migration(100);
    
    // Tabela CompanyData
    if (!$DB->tableExists('glpi_plugin_newbase_companydata')) {
        $query = "CREATE TABLE `glpi_plugin_newbase_companydata` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `entities_id` int(11) NOT NULL DEFAULT '0',
            `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
            `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `corporate_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `fantasy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `federal_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `state_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `city_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `contract_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `date_creation` datetime DEFAULT NULL,
            `date_mod` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `entities_id` (`entities_id`),
            KEY `is_recursive` (`is_recursive`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $DB->queryOrDie($query, $DB->error());
    }

    return true;
}
```

---

### Passo 5: Limpar Cache do GLPI

Execute estes comandos no terminal (dentro da pasta do GLPI):

```bash
# Limpar cache do GLPI
cd D:\laragon\www\glpi

# Deletar arquivos de cache
rm -rf files/_cache/*
rm -rf files/_sessions/*
rm -rf files/_tmp/*

# Ou no Windows PowerShell:
Remove-Item -Path "files/_cache/*" -Recurse -Force
Remove-Item -Path "files/_sessions/*" -Recurse -Force
Remove-Item -Path "files/_tmp/*" -Recurse -Force
```

---

### Passo 6: Reinstalar o Plugin

1. Acesse o GLPI: `http://glpi.test/public`
2. Vá em **Configurar > Plugins**
3. Localize **Newbase**
4. Clique em **Desativar**
5. Clique em **Desinstalar**
6. Clique em **Instalar**
7. Clique em **Ativar**

---

## 🧪 TESTE

Após aplicar as correções:

```php
// Adicione este código em um arquivo de teste: test_search.php
<?php

define('GLPI_ROOT', 'D:/laragon/www/glpi');
include (GLPI_ROOT . "/inc/includes.php");

use GlpiPlugin\Newbase\CompanyData;

Session::checkLoginUser();

$company = new CompanyData();
$searchOptions = $company->getSearchOptionsNew();

echo "<pre>";
print_r($searchOptions);
echo "</pre>";

// Testar busca
$params = [
    'criteria' => [
        [
            'field' => 1,
            'searchtype' => 'contains',
            'value' => ''
        ]
    ]
];

$data = Search::getDatas('PluginNewbaseCompanyData', $params);
print_r($data);
```

---

## 📋 CHECKLIST DE VERIFICAÇÃO

- [ ] Arquivo `CompanyData.php` corrigido com `getSearchOptionsNew()`
- [ ] Tabela `glpi_plugin_newbase_companydata` possui campo `id`
- [ ] Cache do GLPI limpo
- [ ] Plugin desinstalado e reinstalado
- [ ] Teste de busca funcionando sem erros
- [ ] Variáveis não retornam mais "Undefined"

---

## 🔍 DEPURAÇÃO ADICIONAL

Se os erros persistirem, adicione este código no início do `CompanyData.php`:

```php
public function getSearchOptionsNew()
{
    // Log para debug
    error_log("CompanyData::getSearchOptionsNew() chamado");
    error_log("Tabela: " . $this->getTable());
    
    $tab = [];
    // ... resto do código
    
    error_log("SearchOptions gerados: " . count($tab));
    return $tab;
}
```

Verifique o log em: `D:\laragon\www\glpi\files\_log\php-errors.log`

---

## ❓ PROBLEMAS COMUNS

### Erro persiste após correção:

1. **Certifique-se de salvar os arquivos com encoding UTF-8 sem BOM**
2. **Reinicie o Apache:**
   ```bash
   # No Laragon, clique em "Stop All" e depois "Start All"
   ```

3. **Verifique permissões:**
   ```bash
   # No Windows, garanta que o usuário do Apache tem permissão de leitura
   icacls "D:\laragon\www\glpi\plugins\newbase" /grant Everyone:F /T
   ```

### Erros de Join ou LEFT JOIN:

Se aparecerem erros relacionados a JOIN, adicione em `CompanyData.php`:

```php
public function getSpecificValueToDisplay($field, $values, array $options = [])
{
    if (!is_array($values)) {
        $values = [$field => $values];
    }
    
    switch ($field) {
        case 'name':
            return $values[$field] ?? '';
    }
    
    return parent::getSpecificValueToDisplay($field, $values, $options);
}
```

---

## 📞 SUPORTE

Se o erro persistir, forneça:
- Versão exata do GLPI
- Conteúdo completo do arquivo `CompanyData.php`
- Resultado de `DESCRIBE glpi_plugin_newbase_companydata;`
- Log completo do erro (arquivo `php-errors.log`)

---

**Última atualização:** 07/01/2026
**Versão do documento:** 1.0
