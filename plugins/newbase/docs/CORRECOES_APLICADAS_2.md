# Correções Aplicadas ao Plugin Newbase

## Problemas Corrigidos

### 1. Coluna `is_completed` ausente na tabela tasks
**Erro:** `Unknown column 'is_completed' in 'where clause'`

**Solução:** Adicionada coluna `is_completed` TINYINT NOT NULL DEFAULT 0 na tabela `glpi_plugin_newbase_tasks`

### 2. Nome de tabela incorreto para CompanyData
**Erro:** `Table 'glpi.glpi_plugin_newbase_companydatas' doesn't exist`

**Solução:** Adicionado método `getTable()` em `src/CompanyData.php` para usar a tabela correta `glpi_plugin_newbase_company_extras`

---

## Como Aplicar as Correções

As correções nos arquivos PHP já foram aplicadas:
- ✅ `src/CompanyData.php` - método getTable() adicionado
- ✅ `hook.php` - definição da coluna is_completed adicionada

Agora você precisa adicionar a coluna no banco de dados. Escolha UMA das 3 opções abaixo:

### OPÇÃO 1: Via PhpMyAdmin (Mais Fácil)
1. Abra o PhpMyAdmin: http://localhost/phpmyadmin
2. Selecione o banco de dados `glpi`
3. Clique na aba "SQL"
4. Copie e cole o conteúdo do arquivo `migration_add_is_completed.sql`
5. Clique em "Executar"

### OPÇÃO 2: Via Script PHP (Navegador)
1. Abra no navegador: http://localhost/glpi/plugins/newbase/migration_add_is_completed.php
2. Aguarde a mensagem de sucesso
3. Após confirmar o sucesso, delete o arquivo migration_add_is_completed.php

### OPÇÃO 3: Via MySQL Command Line
1. Abra o terminal do Laragon
2. Execute:
```bash
cd D:\laragon\www\glpi\plugins\newbase
mysql -u root glpi < migration_add_is_completed.sql
```

---

## Verificação

Após aplicar a migração, verifique se tudo está funcionando:

1. **Acesse o GLPI**
2. **Vá em:** Plugins > Newbase > Dashboard
3. **Verifique:** Se não há mais erros SQL nos logs

### Verificar no Banco de Dados
Execute no PhpMyAdmin ou MySQL:
```sql
SHOW COLUMNS FROM glpi_plugin_newbase_tasks LIKE 'is_completed';
```

Deve retornar:
```
is_completed | tinyint | NO | | 0
```

---

## Arquivos Modificados

1. **src/CompanyData.php**
   - Adicionado método `getTable()` na linha 38
   
2. **hook.php**
   - Linha 116: Adicionada coluna `is_completed` na criação da tabela
   - Linha 134: Adicionado índice para `is_completed`
   - Linhas 155-168: Adicionada lógica de migração automática

---

## Limpeza Pós-Migração

Após confirmar que tudo está funcionando, você pode deletar:
- `migration_add_is_completed.php`
- `migration_add_is_completed.sql`
- `CORRECOES_APLICADAS.md` (este arquivo)

---

## Suporte

Se ainda houver erros:
1. Verifique o log: `D:\laragon\www\glpi\files\_log\sql-errors.log`
2. Verifique o log do plugin: `D:\laragon\www\glpi\files\_log\newbase.log`
3. Execute novamente a migração

---

**Data:** 04/02/2026  
**Versão do Plugin:** 2.1.0  
**GLPI:** 10.0.20
