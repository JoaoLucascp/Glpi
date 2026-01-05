# Como Corrigir o Plugin Newbase no GLPI 10.0.20

## ⚠️ Problema Identificado

O plugin Newbase foi instalado e ativado, mas **não aparece no menu** do GLPI porque:

1. **Permissões não foram criadas corretamente** durante a instalação
2. O usuário Super-Admin não tem os direitos necessários para acessar o plugin

## ✅ Solução Rápida

Siga estes passos **nesta ordem exata**:

### Passo 1: Desinstalar o Plugin

1. Acesse o GLPI: `http://glpi.test/public`
2. Faça login como **Super-Admin**
3. Vá em: **Configurar > Plug-ins**
4. Localize **Newbase - Gestão de Dados Pessoais**
5. Clique em **Desativar**
6. Clique em **Desinstalar**

### Passo 2: Reinstalar o Plugin

1. Na mesma página de Plug-ins
2. Clique em **Instalar** (ao lado do Newbase)
3. Aguarde a instalação concluir
4. Clique em **Ativar**

### Passo 3: Corrigir Permissões (SE NECESSÁRIO)

Se após reinstalar o plugin ainda não aparecer no menu, execute o script de correção:

1. Acesse diretamente no navegador:
   ```
   http://glpi.test/plugins/newbase/front/tools/fix_permissions.php
   ```

2. O script irá:
   - Verificar todas as permissões
   - Criar permissões faltantes
   - Corrigir permissões incorretas
   - Mostrar um relatório detalhado

3. Após executar o script, **faça logout e login novamente** no GLPI

### Passo 4: Verificar se o Plugin Apareceu

1. No menu principal do GLPI
2. Vá em: **Gerência** (Management)
3. Você deve ver **"Dados da Empresa"** (ou "Company Data") no menu
4. Clique para acessar o dashboard do Newbase

## 📋 Arquivos Corrigidos

Os seguintes arquivos foram atualizados para corrigir os problemas:

### 1. `setup.php`
**Correções aplicadas:**
- ✅ Função `plugin_newbase_install()` corrigida para criar permissões corretamente
- ✅ Uso de `ALLSTANDARDRIGHT` para definir permissões completas
- ✅ Super-Admin (perfil ID 4) recebe todos os direitos automaticamente
- ✅ Perfis Central recebem READ, CREATE, UPDATE, DELETE
- ✅ Perfis Helpdesk recebem apenas READ

### 2. `front/index.php`
**Correções aplicadas:**
- ✅ Namespaces corrigidos para usar `GlpiPlugin\Newbase\Task` e `GlpiPlugin\Newbase\Config`
- ✅ Link corrigido de `company_data.php` para `companydata.php`
- ✅ Chamadas de método corrigidas para usar classes corretas

### 3. `front/tools/fix_permissions.php` (NOVO)
**Script criado para:**
- ✅ Diagnosticar permissões existentes
- ✅ Criar permissões faltantes
- ✅ Atualizar permissões incorretas
- ✅ Exibir relatório detalhado

## 🔍 Como Verificar se Está Funcionando

### Teste 1: Verificar Menu
```
1. Login no GLPI como Super-Admin
2. Menu > Gerência (Management)
3. Deve aparecer "Dados da Empresa" ou "Company Data"
```

### Teste 2: Acessar Configuração
```
1. Acesse diretamente: http://glpi.test/plugins/newbase/front/config.php
2. Você deve ver a página de configuração SEM erros
3. Se aparecer erro de permissão, execute o script fix_permissions.php
```

### Teste 3: Acessar Dashboard
```
1. Acesse: http://glpi.test/plugins/newbase/front/index.php
2. Deve mostrar o dashboard com estatísticas
3. Deve ver: Empresas, Tarefas, Sistemas, Endereços
```

## ⚠️ Se Ainda Não Funcionar

### Verifique os Logs

1. **Log de Erros PHP**: `D:\laragon\www\glpi\files\_log\php-errors.log`
2. **Log de Eventos**: `D:\laragon\www\glpi\files\_log\events.log`

### Verifique as Permissões no Banco de Dados

Execute este SQL no banco de dados:

```sql
-- Verificar permissões do Super-Admin (ID 4)
SELECT 
    p.name as profile_name,
    pr.name as right_name,
    pr.rights as right_value
FROM glpi_profilerights pr
INNER JOIN glpi_profiles p ON p.id = pr.profiles_id
WHERE p.id = 4
  AND pr.name LIKE 'plugin_newbase_%'
ORDER BY pr.name;
```

**Valores esperados:**
- `plugin_newbase_companydata`: 127 (ALLSTANDARDRIGHT)
- `plugin_newbase_task`: 127 (ALLSTANDARDRIGHT)
- `plugin_newbase_system`: 127 (ALLSTANDARDRIGHT)
- `plugin_newbase_config`: 3 (READ + UPDATE)

### Limpar Cache do GLPI

```
1. Acesse: Configurar > Geral > Sistema
2. Clique em "Limpar cache"
3. Ou delete manualmente: D:\laragon\www\glpi\files\_cache\*
```

## 📝 Checklist Final

Antes de considerar que o problema está resolvido, verifique:

- [ ] Plugin desinstalado e reinstalado
- [ ] Script fix_permissions.php executado (se necessário)
- [ ] Logout e login novamente no GLPI
- [ ] Menu "Gerência" mostra "Dados da Empresa"
- [ ] Página de configuração acessível sem erros
- [ ] Dashboard mostra estatísticas corretamente
- [ ] Pode criar nova empresa
- [ ] Pode criar nova tarefa

## 🆘 Suporte

Se após todos esses passos o plugin ainda não funcionar:

1. **Verifique a versão do GLPI**: Deve ser exatamente **10.0.20**
2. **Verifique a versão do PHP**: Deve ser **8.1 ou superior**
3. **Verifique os logs** conforme instruções acima
4. **Envie os logs** para análise

---

## 📌 Informações Técnicas

### Permissões Criadas

O plugin cria estas permissões na tabela `glpi_profilerights`:

| Nome | Descrição | Valor (Super-Admin) |
|------|-----------|---------------------|
| `plugin_newbase_companydata` | Dados de Empresas | 127 (ALLSTANDARDRIGHT) |
| `plugin_newbase_task` | Tarefas | 127 (ALLSTANDARDRIGHT) |
| `plugin_newbase_system` | Sistemas | 127 (ALLSTANDARDRIGHT) |
| `plugin_newbase_config` | Configuração | 3 (READ + UPDATE) |

### Valores de Permissões

```php
READ    = 1   // Visualizar
CREATE  = 2   // Criar
UPDATE  = 4   // Editar
DELETE  = 8   // Deletar
PURGE   = 16  // Remover permanentemente
UNLOCK  = 32  // Desbloquear
READNOTE = 64 // Ler notas

ALLSTANDARDRIGHT = 127 // Todas as permissões padrão (1+2+4+8+16+32+64)
```

### Estrutura do Menu

O plugin é adicionado ao menu **Gerência (Management)** através do hook:

```php
$PLUGIN_HOOKS['menu_toadd']['newbase'] = [
    'management' => CompanyData::class
];
```

A classe `CompanyData` define o conteúdo do menu através do método `getMenuContent()`.

---

**Criado em:** 03/01/2026  
**Versão do Plugin:** 2.0.0  
**GLPI Compatível:** 10.0.20+
