# Newbase v2.1.0 - Guia de Actualización

## Visão Geral

A partir da versão **2.1.0**, o plugin Newbase foi completamente refatorado para remover a dependência de tabelas customizadas e utilizar **apenas as tabelas nativas do GLPI** para gestão de empresas.

### O que mudou?

#### Tabelas Removidas:
- ❌ `glpi_plugin_newbase_companydata` - Dados de empresas
- ❌ `glpi_plugin_newbase_addresses` - Endereços (deprecated, não mais utilizado)

#### Tabelas Mantidas:
- ✅ `glpi_plugin_newbase_company_extras` - Dados complementares (NOVO)
- ✅ `glpi_plugin_newbase_systems` - Documentação de sistemas
- ✅ `glpi_plugin_newbase_chatbot` - Configuração de Chatbot (NOVO)
- ✅ `glpi_plugin_newbase_tasks` - Tarefas com geolocalização
- ✅ `glpi_plugin_newbase_signatures` - Assinaturas digitais

#### Mudanças na Arquitetura:

**Antes (v2.0.0):**
```
Formulário de Empresa
    ↓
glpi_plugin_newbase_companydata (Tabela customizada)
    ↓
Campos: name, cnpj, corporate_name, fantasy_name, email, phone, cep...
```

**Depois (v2.1.0):**
```
Formulário de Empresa
    ↓
glpi_entities (Tabela nativa GLPI)
    + glpi_plugin_newbase_company_extras (Dados complementares)
    ↓
Campos nativos: name, email, phone, address1, address2, postcode, town, state, country
    + Campos extras: cnpj, corporate_name, fantasy_name, cep, website, contract_status, notes
```

## Benefícios

1. **Sincronização automática** - Dados de empresa sempre em sincronia com o core do GLPI
2. **Sem duplicação** - Uma única fonte de verdade (glpi_entities)
3. **Integração nativa** - Funciona perfeitamente com relatórios, permissões e buscas do GLPI
4. **Melhor performance** - Menos queries e menos overhead
5. **Compatibilidade futura** - Pronto para upgrades de GLPI

## Migração de Dados

### Pré-requisitos
- Backup completo do banco de dados
- Acesso administrativo ao GLPI
- Plugin Newbase v2.0.0 já instalado

### Passos de Migração

1. **Backup do banco de dados**
   ```bash
   mysqldump -u root -p glpi > backup_glpi_2.0.0.sql
   ```

2. **Desabilitar o plugin Newbase**
   - Ir em: Configuração > Plugins
   - Desabilitar "Newbase - Company Management"

3. **Atualizar o plugin**
   ```bash
   # Repositório
   git pull origin main
   # Ou extrair arquivo .zip da versão 2.1.0
   ```

4. **Reabilitar o plugin**
   - Ir em: Configuração > Plugins
   - Reabilitar "Newbase - Company Management"
   - O plugin vai executar as migrações automaticamente

5. **Migração de dados (automática)**
   - O sistema vai reconhecer a tabela antiga `glpi_plugin_newbase_companydata`
   - Dados serão migrados automaticamente para `glpi_entities` e `glpi_plugin_newbase_company_extras`
   - ⚠️ **IMPORTANTE**: Este processo é feito uma única vez na primeira ativação da v2.1.0

6. **Verificar integridade**
   - Ir em: Gestão > Empresas
   - Verificar se todas as empresas aparecem corretamente
   - Testar busca por CNPJ
   - Testar criação de nova empresa

### Possíveis Problemas

#### Problema: "Tabela glpi_plugin_newbase_companydata não encontrada"
- **Causa**: Migração não completou
- **Solução**: Reinstalar o plugin
  ```php
  // No console GLPI (em desenvolvimento)
  $plugin = new Plugin();
  $plugin->uninstall('newbase');
  $plugin->install('newbase');
  ```

#### Problema: Dados não aparecem na lista de empresas
- **Causa**: Cache do GLPI não foi limpo
- **Solução**: Limpar cache
  ```bash
  rm -rf /var/www/glpi/_cache/*
  # Ou acessar: Configuração > Limpeza > Limpar cache
  ```

#### Problema: Permissões quebradas
- **Causa**: Perfis de usuário não foram atualizados
- **Solução**: Redefining rights
  ```php
  // No console GLPI
  ProfileRight::addProfileRights(['plugin_newbase']);
  ```

## Mudanças na API

### Classes Afetadas

#### CompanyData (Nova Interface - Estática)

**Antes (v2.0.0):**
```php
// Classe extends Common extends CommonDBTM
$company = new CompanyData();
$company->getFromDB($id);
$companies = $company->find();
```

**Depois (v2.1.0):**
```php
// Classe estática - Utilitário
CompanyData::getAllCompanies();           // Obtém todas
CompanyData::getCompanyById(int $id);     // Por ID de entidade
CompanyData::getCompanyByCNPJ(string $cnpj); // Por CNPJ
CompanyData::saveCompanyExtras(int $id, array $data); // Salva dados extras
```

### Métodos Mantidos em Common

```php
Common::validateCNPJ($cnpj);              // Validação de CNPJ
Common::searchCompanyByCNPJ($cnpj);       // Busca via Brasil API
Common::searchCompanyAdditionalData($cnpj, $name); // Multi-API (email, phone)
Common::formatPhone($phone);              // Formatação de telefone
```

## Checklist de Validação

Após atualizar para v2.1.0:

- [ ] Plugin ativa sem erros
- [ ] Todas as empresas aparecem em "Gestão > Empresas"
- [ ] Busca por CNPJ funciona
- [ ] Criação de nova empresa funciona
- [ ] Edição de empresa existente funciona
- [ ] Formulário exibe todos os campos
- [ ] AJAX de busca de CNPJ responde corretamente
- [ ] Relatórios de empresas funcionam
- [ ] Integração com glpi_entities funciona (dados sincronizados)
- [ ] Sistemas documentados permanecem intactos
- [ ] Tarefas com geolocalização funcionam
- [ ] Assinaturas digitais funcionam

## Revertendo para v2.0.0

Se precisar reverter:

1. **Restaurar backup**
   ```bash
   mysql -u root -p glpi < backup_glpi_2.0.0.sql
   ```

2. **Reverter código do plugin**
   ```bash
   git checkout v2.0.0
   # Ou extrair arquivo v2.0.0 da versão anterior
   ```

3. **Reabilitar plugin**
   - Ir em: Configuração > Plugins
   - Reabilitar "Newbase - Company Management"

⚠️ **NOTA**: Dados criados em v2.1.0 podem não ser totalmente compatíveis com v2.0.0

## Suporte

Para problemas ou dúvidas sobre a migração:

1. Verificar logs em: `/files/_plugins/newbase/newbase.log`
2. Abrir issue no repositório
3. Contactar o administrador do GLPI

## Referências

- [GLPI Documentation - Entities](https://docs.glpi-project.org/current/pt_BR/tablas/index.html)
- [GLPI API - Database Layer](https://docs.glpi-project.org/current/pt_BR/desenvolvimento/index.html)
- [Newbase GitHub Repository](https://github.com/newtel/newbase)
