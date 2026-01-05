# GUIA DE INSTALAÇÃO - Plugin Newbase
## GLPI 10.0.20+

**Data:** 02/01/2026  
**Versão do Plugin:** 2.0.0  
**Status:** ✅ Pronto para instalação

---

## 📋 Problemas Corrigidos

### ❌ Problemas Identificados:
1. **Nomes de tabelas inconsistentes** - Resolvido ✅
2. **Hook `newbase_postinit()` não definido** - Desabilitado ✅
3. **SQL corrompido** - Corrigido ✅
4. **Rightnames incorretos** - Padronizados ✅

### ✅ Alterações Realizadas:

#### 1. Padronização dos Nomes de Tabelas
**ANTES:**
- `newbase_companydata`
- `newbase_address`
- etc.

**DEPOIS:**
- `glpi_plugin_newbase_companydata` ✅
- `glpi_plugin_newbase_address` ✅
- `glpi_plugin_newbase_system` ✅
- `glpi_plugin_newbase_task` ✅
- `glpi_plugin_newbase_tasksignature` ✅
- `glpi_plugin_newbase_config` ✅

#### 2. Padronização dos Foreign Keys
**ANTES:**
- `newbase_companydata_id`
- `newbase_task_id`

**DEPOIS:**
- `plugin_newbase_companydata_id` ✅
- `plugin_newbase_task_id` ✅

#### 3. Padronização dos Rightnames
**ANTES:**
- `newbase_companydata`
- `newbase_task`

**DEPOIS:**
- `plugin_newbase_companydata` ✅
- `plugin_newbase_task` ✅
- `plugin_newbase_system` ✅
- `plugin_newbase_config` ✅

---

## 🚀 Passo a Passo para Instalação

### **PASSO 1: Limpar Banco de Dados (OBRIGATÓRIO)**

Se você já tentou instalar o plugin antes, é **ESSENCIAL** limpar o banco de dados primeiro.

#### Opção A: Via Script PHP (Recomendado)

```bash
cd D:\laragon\www\glpi\plugins\newbase\tools
php cleanup_db.php
```

#### Opção B: Manualmente via SQL

Execute no phpMyAdmin ou MySQL:

```sql
SET FOREIGN_KEY_CHECKS = 0;

-- Remover tabelas antigas (ordem importante!)
DROP TABLE IF EXISTS `glpi_plugin_newbase_tasksignature`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_task`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_system`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_address`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_companydata`;
DROP TABLE IF EXISTS `glpi_plugin_newbase_config`;

-- Remover possíveis tabelas com nomes antigos
DROP TABLE IF EXISTS `newbase_tasksignature`;
DROP TABLE IF EXISTS `newbase_task`;
DROP TABLE IF EXISTS `newbase_system`;
DROP TABLE IF EXISTS `newbase_address`;
DROP TABLE IF EXISTS `newbase_companydata`;
DROP TABLE IF EXISTS `newbase_config`;

SET FOREIGN_KEY_CHECKS = 1;

-- Limpar preferências de exibição
DELETE FROM `glpi_displaypreferences` WHERE `itemtype` LIKE 'GlpiPlugin\\Newbase\\%';

-- Limpar direitos de perfil
DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'plugin_newbase_%';
DELETE FROM `glpi_profilerights` WHERE `name` LIKE 'newbase_%';
```

---

### **PASSO 2: Desabilitar Plugin (se já estiver instalado)**

1. Acesse: **Configurar > Plugins**
2. Localize o plugin **Newbase**
3. Clique em **Desativar** (se estiver ativo)
4. Clique em **Desinstalar** (se estiver instalado)

---

### **PASSO 3: Verificar Arquivos do Plugin**

Certifique-se de que todos os arquivos estão corretos:

```bash
cd D:\laragon\www\glpi\plugins\newbase

# Verificar estrutura
dir /B
```

Deve conter:
- ✅ setup.php
- ✅ hook.php
- ✅ src/ (com as classes)
- ✅ front/ (com as páginas)
- ✅ install/mysql/2.0.0.sql
- ✅ composer.json
- ✅ vendor/ (com autoload)

---

### **PASSO 4: Executar Composer (se necessário)**

```bash
cd D:\laragon\www\glpi\plugins\newbase
composer install --no-dev --optimize-autoloader
```

---

### **PASSO 5: Instalar o Plugin via Interface GLPI**

1. **Acesse:** Setup > Plugins
2. **Localize:** Plugin Newbase
3. **Clique em:** Instalar
4. **Aguarde** a conclusão (pode levar alguns segundos)
5. **Clique em:** Ativar

---

### **PASSO 6: Verificar Instalação**

#### Verificar Tabelas Criadas:

```sql
SHOW TABLES LIKE 'glpi_plugin_newbase_%';
```

**Resultado esperado:**
- ✅ glpi_plugin_newbase_address
- ✅ glpi_plugin_newbase_companydata
- ✅ glpi_plugin_newbase_config
- ✅ glpi_plugin_newbase_system
- ✅ glpi_plugin_newbase_task
- ✅ glpi_plugin_newbase_tasksignature

#### Verificar Permissões:

```sql
SELECT * FROM glpi_profilerights WHERE name LIKE 'plugin_newbase_%';
```

**Resultado esperado:**
- ✅ plugin_newbase_companydata
- ✅ plugin_newbase_task
- ✅ plugin_newbase_system
- ✅ plugin_newbase_config

---

### **PASSO 7: Acessar o Plugin**

1. **Menu:** Management (Gestão)
2. **Item:** Company Data (Dados de Empresas)
3. **Ou:** Plugins > Newbase

---

## 🔧 Resolução de Problemas

### Problema: "Error creating table..."

**Solução:** Execute o PASSO 1 novamente para limpar completamente o banco.

### Problema: "CSRF token invalid"

**Solução:** 
1. Limpe o cache do navegador
2. Faça logout e login novamente
3. Tente reinstalar

### Problema: "Table already exists"

**Solução:** Execute o script de cleanup:

```bash
php D:\laragon\www\glpi\plugins\newbase\tools\cleanup_db.php
```

### Problema: "Foreign key constraint fails"

**Solução:** As foreign keys agora estão corretas. Execute o cleanup e reinstale.

---

## 📊 Estrutura do Banco de Dados

```
glpi_plugin_newbase_companydata (PAI)
  ├── glpi_plugin_newbase_address (FK: plugin_newbase_companydata_id)
  ├── glpi_plugin_newbase_system (FK: plugin_newbase_companydata_id)
  └── glpi_plugin_newbase_task (FK: plugin_newbase_companydata_id)
      └── glpi_plugin_newbase_tasksignature (FK: plugin_newbase_task_id)

glpi_plugin_newbase_config (INDEPENDENTE)
```

---

## 📝 Requisitos do Sistema

- **GLPI:** 10.0.20 a 10.0.99
- **PHP:** >= 8.1
- **MySQL:** >= 8.0
- **Extensões PHP:**
  - mysqli
  - curl
  - json
  - mbstring

---

## ✅ Checklist de Instalação

- [ ] Executei o cleanup do banco de dados
- [ ] Desativei e desinstalei versões antigas do plugin
- [ ] Verifiquei que todos os arquivos estão presentes
- [ ] Executei o composer install
- [ ] Instalei o plugin via interface do GLPI
- [ ] Ativei o plugin
- [ ] Verifiquei que as tabelas foram criadas corretamente
- [ ] Verifiquei que os direitos foram criados corretamente
- [ ] Consigo acessar o menu do plugin

---

## 🆘 Suporte

Se ainda tiver problemas após seguir este guia:

1. Verifique os logs do GLPI em: `files/_log/`
2. Verifique os logs do PHP
3. Verifique o erro específico no navegador (F12 > Console)
4. Execute o cleanup novamente e tente reinstalar

---

## 🎉 Sucesso!

Se chegou até aqui e tudo funcionou, parabéns! O plugin Newbase está pronto para uso.

**Próximos passos:**
1. Configure as permissões por perfil
2. Configure as opções do plugin em: Setup > Plugins > Newbase > Configuração
3. Comece a cadastrar suas empresas!

---

**Desenvolvido por:** João Lucas  
**Versão:** 2.0.0  
**Data:** 02/01/2026  
**Licença:** GPLv2+
