# 🧪 Guia de Validação - Newbase v2.1.0

## Objetivo
Validar que a refatoração foi implementada corretamente antes de fazer deploy.

---

## ✅ Checklist de Validação

### 1. Validação de Arquivos

#### SQL

- [ ] Arquivo `/install/mysql/2.1.0.sql` existe
- [ ] Não contém referências a `glpi_plugin_newbase_companydata`
- [ ] Tabela `glpi_plugin_newbase_company_extras` definida
- [ ] Todas as foreign keys apontam para `glpi_entities`
- [ ] Sem erros de sintaxe SQL

#### PHP

- [ ] `VERSION` contém `2.1.0`
- [ ] `setup.php` usa `2.1.0.sql`
- [ ] `CompanyData.php` é classe estática (sem extends Common)
- [ ] `searchCompany.php` importa `CompanyData`
- [ ] `companydata.form.php` usa `CompanyData::showForm()`

#### Documentação

- [ ] `CHANGELOG.md` tem seção [2.1.0]
- [ ] `UPGRADE.md` existe
- [ ] `REFACTORING_SUMMARY.md` existe
- [ ] `COMPLETION_REPORT.md` existe

### 2. Validação de Código

#### Namespaces

```bash
grep -r "namespace GlpiPlugin" plugins/newbase/src/*.php
# Deve retornar: GlpiPlugin\Newbase\Src (consistente)
```

#### Imports

```bash
grep -r "use.*CompanyData" plugins/newbase/
# Deve usar: use GlpiPlugin\Newbase\Src\CompanyData;
```

#### Métodos Estáticos

```php
// Testar em console PHP/GLPI
$companies = CompanyData::getAllCompanies();
$company = CompanyData::getCompanyById(1);
$company_cnpj = CompanyData::getCompanyByCNPJ('12345678901234');
```

### 3. Validação de Banco de Dados

#### Tabelas

```sql
-- Verificar que tabelas esperadas existem
SHOW TABLES LIKE 'glpi_plugin_newbase_%';

-- Resultado esperado:
-- glpi_plugin_newbase_company_extras ✅
-- glpi_plugin_newbase_systems ✅
-- glpi_plugin_newbase_tasks ✅
-- glpi_plugin_newbase_signatures ✅
-- glpi_plugin_newbase_chatbot ✅
-- (NOT glpi_plugin_newbase_companydata) ❌
-- (NOT glpi_plugin_newbase_addresses) ❌
```

#### Colunas

```sql
-- Verificar que coluna entities_id existe em systems
DESC glpi_plugin_newbase_systems;
# Procurar por: entities_id (must exist) ✅
# Procurar por: companydata_id (must NOT exist) ❌

-- Verificar glpi_plugin_newbase_company_extras
DESC glpi_plugin_newbase_company_extras;
# Deve ter: id, entities_id, cnpj, corporate_name, fantasy_name, email, phone, cep, website
```

#### Foreign Keys

```sql
-- Verificar todas as FKs apontam para glpi_entities
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME LIKE 'glpi_plugin_newbase_%'
AND COLUMN_NAME = 'entities_id';

-- Resultado esperado: Todos devem referenciar glpi_entities
```

### 4. Testes Funcionais

#### Setup de Teste

1. Criar ambiente de staging (clone de produção)
2. Fazer backup do banco
3. Executar plugin_newbase_install()
4. Verificar se tabelas foram criadas

#### Teste de Formulário

1. Acessar: Gestão > Empresas
2. Clicar "Adicionar empresa"
3. Campos visíveis:
   - [ ] Nome *
   - [ ] CNPJ
   - [ ] Razão Social
   - [ ] Nome Fantasia
   - [ ] Email
   - [ ] Telefone
   - [ ] CEP
   - [ ] Website
   - [ ] Notas

#### Teste de CNPJ Search

1. Preencher CNPJ: `11222333000181` (Empresa teste)
2. Clicar "Buscar"
3. Verificar se:
   - [ ] Dados são preenchidos (corporate_name, email, phone)
   - [ ] Não há erro JavaScript no console
   - [ ] Requisição AJAX é bem-sucedida

#### Teste de Salvar

1. Preencher formulário com dados válidos
2. Clicar "Salvar"
3. Verificar:
   - [ ] Mensagem de sucesso aparece
   - [ ] Dados salvos em glpi_entities
   - [ ] Dados complementares salvos em glpi_plugin_newbase_company_extras

#### Teste de Edição

1. Abrir empresa criada
2. Modificar dados
3. Salvar
4. Verificar:
   - [ ] Dados atualizados em glpi_entities
   - [ ] Dados complementares atualizados

### 5. Testes de Integração

#### Sistemas

1. Criar Nova Empresa
2. Ir para: Gestão > Sistemas
3. Adicionar sistema:
   - [ ] Seletor de empresa mostra a empresa criada
   - [ ] CNPJ/dados são exibidos corretamente
   - [ ] Sistema é salvo com referência correta

#### Tarefas

1. Criar Nova Tarefa:
   - [ ] Seletor de empresa funciona
   - [ ] Campos de geolocalização aparecem
   - [ ] Assinaturas podem ser capturadas

### 6. Testes de Segurança

#### Permissões

1. Usuário sem permissão tenta acessar:
   - [ ] Retorna erro de permissão
   - [ ] Não expõe dados

#### CSRF

1. Submeter formulário sem token CSRF:
   - [ ] Rejeita requisição
   - [ ] Não salva dados

#### SQL Injection

1. Tentar injetar SQL no CNPJ:
   - [ ] Rejeita entrada
   - [ ] Não executa código malicioso

### 7. Testes de Performance

#### Query Performance

```sql
-- Antes (esperado com v2.0.0 - 2 queries)
-- SELECT * FROM glpi_plugin_newbase_companydata
-- SELECT * FROM glpi_entities

-- Depois (esperado com v2.1.0 - 1 query)
-- SELECT * FROM glpi_entities + join glpi_plugin_newbase_company_extras
```

#### Load Testing

1. Criar 1000+ empresas
2. Medir tempo de:
   - [ ] Listagem < 2s
   - [ ] Busca < 1s
   - [ ] Formulário < 1s

### 8. Testes de Rollback

#### Simular Problema

1. Atualizar para v2.1.0
2. Testes falham
3. Reverter para v2.0.0:
   - [ ] Restaurar backup automático
   - [ ] Plugin volta a funcionar
   - [ ] Dados preservados

---

## 🔍 Validation Commands

### PHP Lint Check

```bash
find plugins/newbase -name "*.php" -exec php -l {} \;
```

### SQL Syntax Check

```bash
cat install/mysql/2.1.0.sql | mysql --syntax-check
```

### Composer Autoload

```bash
cd plugins/newbase
php -r "require 'vendor/autoload.php'; echo 'Autoload OK';"
```

### Check Version

```php
// Em admin/index.php
echo PLUGIN_NEWBASE_VERSION; // Deve ser "2.1.0"
```

---

## 📊 Expected Results

### After Installation

**Table Status:**
✅ glpi_plugin_newbase_company_extras
✅ glpi_plugin_newbase_systems
✅ glpi_plugin_newbase_tasks
✅ glpi_plugin_newbase_signatures
✅ glpi_plugin_newbase_chatbot

**Removed Tables:**
❌ glpi_plugin_newbase_companydata (OK - removed as expected)
❌ glpi_plugin_newbase_addresses (OK - removed as expected)

**Version:**
✅ PLUGIN_NEWBASE_VERSION = "2.1.0"

**Classes:**
✅ CompanyData (static utility)
✅ System (extends Common, uses entities_id)
✅ Task (updated)
✅ TaskSignature (updated)

---

## 🚨 Common Issues & Fixes

### Issue: "Table not found: glpi_plugin_newbase_companydata"

**Cause**: Old references still in code
**Fix**: Search and replace all occurrences of `glpi_plugin_newbase_companydata` with `glpi_entities`

### Issue: "CompanyData cannot be instantiated"

**Cause**: Trying to do `new CompanyData()` 
**Fix**: Use static methods: `CompanyData::getCompanyById()`

### Issue: CNPJ Search doesn't work

**Cause**: Ajax endpoint still references old API
**Fix**: Verify `searchCompany.php` imports `CompanyData` and uses `CompanyData::getCompanyByCNPJ()`

### Issue: Systems dropdown shows nothing

**Cause**: System.php still uses `companydata_id` instead of `entities_id`
**Fix**: Update System.php to use Entity::dropdown() with `entities_id`

---

## ✅ Sign-Off

When all checks pass:

1. [ ] Developer: Code review complete
2. [ ] QA: Functional tests passed
3. [ ] DBA: Database validation passed
4. [ ] Security: Security tests passed
5. [ ] Performance: Performance acceptable
6. [ ] Ready for Production Deployment

**Date**: _______________
**Approved By**: _______________
**Ticket**: _______________

---

## 📞 Escalation

If validation fails:

1. Document the failure
2. Create GitHub issue with details
3. Tag: `bug`, `v2.1.0`, `validation-failed`
4. Do NOT merge to main branch
5. Fix issue and re-run validation
