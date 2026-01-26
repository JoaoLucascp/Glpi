# BUGFIX SUMMARY - Plugin Newbase v2.1.0
**Data**: 23 de Janeiro de 2026  
**Status**: ✅ CORRIGIDO  
**Versão**: 2.1.0

---

## 📋 Resumo dos Erros Corrigidos

### 1. ❌ Erro: Função `plugin_newbase_getConfig()` Duplicada
**Arquivo**: `hook.php` linha 175  
**Problema**: Função redeclarada em dois arquivos  
**Solução**: 
- Remover função `plugin_newbase_getConfig()` do hook.php
- Manter definição única em setup.php
- Adicionar comentário em hook.php referenciando setup.php

**Status**: ✅ CORRIGIDO

---

### 2. ❌ Erro: Classe `GlpiPlugin\Newbase\Src\System` Não Encontrada
**Arquivo**: `setup.php` linha 48  
**Problema**: Classes no namespace `GlpiPlugin\Newbase\Src` não eram importadas  
**Solução**:
- Adicionar `use` statements para importar classes com namespace completo
- Atualizar class wrappers para herdar de classes já importadas
```php
use GlpiPlugin\Newbase\Src\System;
use GlpiPlugin\Newbase\Src\Task;
use GlpiPlugin\Newbase\Src\TaskSignature;
```

**Status**: ✅ CORRIGIDO

---

### 3. ❌ Erro: Namespace Incorreto em hook.php
**Arquivo**: `hook.php` linhas 17-19  
**Problema**: Namespace incompleto `GlpiPlugin\Newbase\*` em vez de `GlpiPlugin\Newbase\Src\*`  
**Solução**:
- Corrigir imports para usar namespace `Src` correto
```php
use GlpiPlugin\Newbase\Src\System;    // ✓ Correto
use GlpiPlugin\Newbase\Src\Task;      // ✓ Correto
use GlpiPlugin\Newbase\Src\TaskSignature; // ✓ Correto
```

**Status**: ✅ CORRIGIDO

---

### 4. ❌ Erro: Tabelas Não Encontradas
**Arquivo**: `front/index.php`  
**Problema**: Referências a tabelas que não existem em v2.1.0:
- `glpi_plugin_newbase_companydata` ❌ REMOVIDA
- `glpi_plugin_newbase_task` ❌ REMOVIDA (agora `glpi_plugin_newbase_tasks`)
- `glpi_plugin_newbase_system` ❌ REMOVIDA (agora `glpi_plugin_newbase_systems`)
- `glpi_plugin_newbase_address` ❌ REMOVIDA/DEPRECADA
- `glpi_plugin_newbase_config` ❌ NÃO FOI CRIADA

**Solução**:
- Atualizar todas as queries para usar novas tabelas v2.1.0
- Usar `glpi_entities` nativa em vez de `glpi_plugin_newbase_companydata`
- Usar `glpi_plugin_newbase_company_extras` para dados complementares

**Mudanças Específicas**:
1. **Contagem de Empresas**:
```php
// Antes (ERRADO):
countElementsInTable('glpi_plugin_newbase_companydata');
// Depois (CORRETO):
$DB->request(['COUNT' => 'cpt', 'FROM' => 'glpi_entities', ...])->current()['cpt']
```

2. **Contagem de Tarefas**:
```php
// Antes (ERRADO):
countElementsInTable('glpi_plugin_newbase_task', ...)
// Depois (CORRETO):
countElementsInTable('glpi_plugin_newbase_tasks', ...)
```

3. **Contagem de Sistemas**:
```php
// Antes (ERRADO):
countElementsInTable('glpi_plugin_newbase_system')
// Depois (CORRETO):
countElementsInTable('glpi_plugin_newbase_systems')
```

4. **Query de Tarefas Recentes**:
```php
// Antes (ERRADO):
'FROM' => 'glpi_plugin_newbase_task'
'glpi_plugin_newbase_companydata.name AS company_name'
'glpi_plugin_newbase_task.plugin_newbase_companydata_id'

// Depois (CORRETO):
'FROM' => 'glpi_plugin_newbase_tasks'
'glpi_entities.name AS company_name'
'glpi_plugin_newbase_tasks.entities_id'
```

**Status**: ✅ CORRIGIDO

---

### 5. ❌ Erro: Tabela `glpi_plugin_newbase_config` Não Criada
**Arquivo**: `hook.php` na função `plugin_newbase_install()`  
**Problema**: A tabela de configuração não era criada na instalação  
**Solução**:
- Adicionar criação da tabela `glpi_plugin_newbase_config` em `plugin_newbase_install()`
- Adicionar drop da tabela em `plugin_newbase_uninstall()`

```php
CREATE TABLE `glpi_plugin_newbase_config` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `config_key` varchar(255) NOT NULL,
    `config_value` longtext,
    `is_deleted` tinyint(1) DEFAULT 0,
    `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `date_mod` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_config_key` (`config_key`),
    KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

**Status**: ✅ CORRIGIDO

---

### 6. ❌ Erro: Função `plugin_newbase_getDatabase()` com Namespace Incorreto
**Arquivo**: `hook.php` linha 187  
**Problema**: Tentava usar `System::class`, `Task::class` sem importação correta  
**Solução**:
- Retornar strings com nomes das classes wrapper
```php
// Antes (ERRADO):
return [System::class, Task::class, TaskSignature::class];

// Depois (CORRETO):
return ['PluginNewbaseSystem', 'PluginNewbaseTask', 'PluginNewbaseTaskSignature'];
```

**Status**: ✅ CORRIGIDO

---

## 📁 Arquivos Modificados

### setup.php
- ✅ Adicionado imports com `use` statements (linhas 47-49)
- ✅ Atualizadas class wrappers (linhas 51-69)
- ✅ Corrigido `plugin_newbase_getDatabase()` para retornar strings (linhas 107-113)

### hook.php
- ✅ Corrigido imports do namespace (linhas 17-19)
- ✅ Adicionada criação de tabela `glpi_plugin_newbase_config` (linhas 141-157)
- ✅ Adicionado drop de `glpi_plugin_newbase_config` em uninstall (linha 177)
- ✅ Removida função duplicada `plugin_newbase_getConfig()` (substituída por comentário)
- ✅ Corrigido `plugin_newbase_getDatabase()` (linhas 187-194)

### front/index.php
- ✅ Atualizado cálculo de contagem de empresas para usar `glpi_entities` (linhas 42-51)
- ✅ Atualizado cálculo de contagem de tarefas para usar `glpi_plugin_newbase_tasks` (linhas 53-56)
- ✅ Atualizado cálculo de contagem de sistemas para usar `glpi_plugin_newbase_systems` (linhas 58-59)
- ✅ Atualizado cálculo de complementos para usar `glpi_plugin_newbase_company_extras` (linhas 61-64)
- ✅ Atualizada query de tarefas recentes para usar `glpi_entities` e `glpi_plugin_newbase_tasks` (linhas 105-120)
- ✅ Atualizada verificação de tarefas com geolocalização (linhas 203-210)

---

## ✅ Validações Realizadas

- [x] Sintaxe PHP validada (sem erros)
- [x] composer.json válido
- [x] Namespaces corretos
- [x] Imports completados
- [x] Foreign keys corretas (todas apontam para `glpi_entities`)
- [x] Sem referências a tabelas deletadas
- [x] Tabela de config criada e removida corretamente

---

## 🚀 Próximas Ações

1. **Limpar cache do GLPI**
   ```bash
   rm -rf /var/www/glpi/_cache/*
   # Ou acessar: Configuração > Limpeza > Limpar cache
   ```

2. **Desabilitar e reabilitar plugin**
   - Configuração > Plugins
   - Desabilitar "Newbase"
   - Reabilitar "Newbase"
   - Plugin executará instalação/migração automaticamente

3. **Verificar logs**
   - Ver `files/_plugins/newbase/newbase.log`
   - Procurar por erros de instalação

4. **Validar no dashboard**
   - Gestão > Empresas (via glpi_entities)
   - Gestão > Tarefas
   - Gestão > Sistemas
   - Verificar se dados carregam corretamente

---

## 📝 Nota Importante

Esta correção resolve os erros de **compilação PHP** e **database schema** causados pela refatoração v2.1.0. Todos os erros reportados no log foram endereçados:

1. ✅ "Class not found: GlpiPlugin\Newbase\Src\System"
2. ✅ "Cannot redeclare plugin_newbase_getConfig()"
3. ✅ "Table 'glpi.glpi_plugin_newbase_companydata' doesn't exist"
4. ✅ "Table 'glpi.glpi_plugin_newbase_task' doesn't exist"
5. ✅ "Table 'glpi.glpi_plugin_newbase_system' doesn't exist"
6. ✅ "Table 'glpi.glpi_plugin_newbase_config' doesn't exist"

---

**Status Final**: ✅ PRONTO PARA TESTES

Testar a ativação do plugin no GLPI conforme procedimento na seção "Próximas Ações".
