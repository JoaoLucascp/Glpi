# 🔧 CORREÇÕES REALIZADAS - Plugin Newbase

## Data: 02/01/2026

---

## RESUMO DAS ALTERAÇÕES

### 1. setup.php

*Arquivo:* `D:\laragon\www\glpi\plugins\newbase\setup.php`

*Alterações:*

- Comentado hook `post_init` que referenciava função inexistente

- Todos os nomes de tabelas corrigidos para padrão GLPI:
  - `newbase_companydata` → `glpi_plugin_newbase_companydata`
  - `newbase_address` → `glpi_plugin_newbase_address`
  - `newbase_system` → `glpi_plugin_newbase_system`
  - `newbase_task` → `glpi_plugin_newbase_task`
  - `newbase_tasksignature` → `glpi_plugin_newbase_tasksignature`
  - `newbase_config` → `glpi_plugin_newbase_config`

- Todas as foreign keys corrigidas:
  - `newbase_companydata_id` → `plugin_newbase_companydata_id`
  - `newbase_task_id` → `plugin_newbase_task_id`

- Todos os rightnames corrigidos:
  - `newbase_companydata` → `plugin_newbase_companydata`
  - `newbase_task` → `plugin_newbase_task`
  - `newbase_system` → `plugin_newbase_system`
  - `newbase_config` → `plugin_newbase_config`

---

### 2. hook.php

*Arquivo:* `D:\laragon\www\glpi\plugins\newbase\hook.php`

*Alterações:*

- Corrigida referência à tabela task em UPDATE
- Corrigidas referências nas search options
- Corrigidos rightnames na função `newbase_getRights()`

---

### 3. install/mysql/2.0.0.sql

*Arquivo:* `D:\laragon\www\glpi\plugins\newbase\install\mysql\2.0.0.sql`

*Alterações:*

- Todos os nomes de tabelas padronizados
- Todas as foreign keys corrigidas
- **CRÍTICO:** Linha corrompida na constraint da tabela `tasksignature` foi corrigida

---

### 4. Novos Arquivos Criados

#### 4.1. cleanup_db.php

*Arquivo:* `D:\laragon\www\glpi\plugins\newbase\tools\cleanup_db.php`
*Propósito:* Script para limpar completamente o banco de dados antes da reinstalação

*Uso:*

```bash
cd D:\laragon\www\glpi\plugins\newbase\tools
php cleanup_db.php
```

#### 4.2. INSTALLATION_GUIDE.md

*Arquivo:* `D:\laragon\www\glpi\plugins\newbase\INSTALLATION_GUIDE.md`
*Propósito:* Guia completo de instalação com passo a passo detalhado

---

## CLASSES PHP - STATUS

Todas as classes já estavam corretas e não precisaram de alterações:

- *CompanyData.php* - Tabela: `glpi_plugin_newbase_companydata`
- *Address.php* - Tabela: `glpi_plugin_newbase_address`
- *System.php* - Tabela: `glpi_plugin_newbase_system`
- *Task.php* - Tabela: `glpi_plugin_newbase_task`
- *TaskSignature.php* - Tabela: `glpi_plugin_newbase_tasksignature`
- *Config.php* - Tabela: `glpi_plugin_newbase_config`

---

## PROBLEMAS RESOLVIDOS

### Problema 1: Inconsistência de Nomes

*Status:* RESOLVIDO
Setup criava tabelas sem prefixo `glpi_plugin_`
Classes esperavam tabelas com prefixo `glpi_plugin_`
**Solução:** Padronizados todos os nomes no setup.php e SQL

### Problema 2: Hook Inexistente

*Status:* RESOLVIDO
Hook `newbase_postinit()` estava registrado mas não existia
**Solução:** Linha comentada no setup.php

### Problema 3: SQL Corrompido

*Status:* RESOLVIDO
Constraint da tabela `tasksignature` estava truncada/malformada
**Solução:** SQL completamente reconstruído

### Problema 4: Foreign Keys Incorretas

*Status:* RESOLVIDO
Foreign keys usavam nomes sem prefixo `plugin_`
**Solução:** Todas as FKs renomeadas com prefixo correto

### Problema 5: Rightnames Inconsistentes

*Status:* RESOLVIDO
Setup registrava direitos com um padrão
Classes esperavam direitos com outro padrão
**Solução:** Padronizados com prefixo `plugin_newbase_`

---

## PRÓXIMOS PASSOS

### 1. LIMPEZA (OBRIGATÓRIO)

Execute o script de limpeza antes de tentar reinstalar:

```bash
cd D:\laragon\www\glpi\plugins\newbase\tools
php cleanup_db.php
```

**OU** execute manualmente no MySQL:

```sql
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `glpi_plugin_newbase_tasksignature`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_task`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_system`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_address`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_companydata`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_config`;
DROP TABLE IF EXISTS `newbase_tasksignature`;
DROP TABLE IF EXISTS `newbase_task`;
DROP TABLE IF EXISTS `newbase_system`;
DROP TABLE IF EXISTS `newbase_address`;
DROP TABLE IF EXISTS `newbase_companydata`;
DROP TABLE IF EXISTS `newbase_config`;
SET FOREIGN_KEY_CHECKS = 1;
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` LIKE 'GlpiPlugin\\Newbase\\%';
DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'plugin_newbase_%';
DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'newbase_%';
```

### 2. INSTALAÇÃO

1. Acesse: **Setup > Plugins**
2. Localize: **Newbase**
3. Clique em: **Instalar**
4. Aguarde a conclusão
5. Clique em: **Ativar**

### 3. VERIFICAÇÃO

Execute no MySQL para verificar:

```sql
-- Verificar tabelas criadas
SHOW TABLES LIKE 'glpi_plugin_newbase_%';

-- Verificar permissões
SELECT * FROM glpi_profilerights WHERE name LIKE 'plugin_newbase_%';

-- Verificar configuração
SELECT * FROM glpi_plugin_newbase_config;
```

---

## ARQUIVOS ALTERADOS

1. *setup.php* - Completamente corrigido
2. *hook.php* - Referências de tabelas e direitos corrigidas
3. *install/mysql/2.0.0.sql* - SQL corrompido corrigido
4. *tools/cleanup_db.php* - NOVO - Script de limpeza
5. *INSTALLATION_GUIDE.md* - NOVO - Guia de instalação
6. *CORRECTIONS_SUMMARY.md* - ESTE ARQUIVO

---

## ESTRUTURA FINAL DO BANCO

```sql
glpi_plugin_newbase_companydata (PRINCIPAL)
  ├── id (PK)
  ├── entities_id
  ├── cnpj
  ├── name
  └── ... (outros campos)

glpi_plugin_newbase_address
  ├── id (PK)
  ├── plugin_newbase_companydata_id (FK → companydata)
  └── ... (outros campos)

glpi_plugin_newbase_system
  ├── id (PK)
  ├── plugin_newbase_companydata_id (FK → companydata)
  └── ... (outros campos)

glpi_plugin_newbase_task
  ├── id (PK)
  ├── plugin_newbase_companydata_id (FK → companydata)
  ├── assigned_to (FK → glpi_users)
  └── ... (outros campos)

glpi_plugin_newbase_tasksignature
  ├── id (PK)
  ├── plugin_newbase_task_id (FK → task)
  └── ... (outros campos)

glpi_plugin_newbase_config
  ├── id (PK)
  ├── config_key (UNIQUE)
  └── config_value
```

---

## CHECKLIST FINAL

Antes de instalar, certifique-se:

- [x] Todos os arquivos foram corrigidos
- [x] Script de limpeza foi criado
- [x] Guia de instalação foi criado
- [ ] Banco de dados foi limpo
- [ ] Plugin foi desinstalado (se já estava instalado)
- [ ] Plugin foi reinstalado via interface GLPI
- [ ] Tabelas foram criadas corretamente
- [ ] Permissões foram criadas corretamente
- [ ] Plugin está funcionando

---

## PRÓXIMOS PASSOS RECOMENDADOS

1. *Execute o cleanup* usando o script criado
2. *Siga o guia* INSTALLATION_GUIDE.md passo a passo
3. *Verifique* se as tabelas foram criadas corretamente
4. *Teste* o cadastro de uma empresa
5. *Configure* as permissões por perfil se necessário

---

## CONCLUSÃO

Todas as inconsistências foram corrigidas. O plugin agora está seguindo as convenções do GLPI e deve instalar sem erros.

**Importante:**
SEMPRE execute o cleanup antes de reinstalar
Siga o guia de instalação rigorosamente
Em caso de erro, verifique os logs em `files/_log/`

---

**Desenvolvido por:** João Lucas
**Corrigido em:** 02/01/2026
**Versão:** 2.0.0
