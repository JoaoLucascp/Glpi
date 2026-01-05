# 🔧 Plugin Newbase - Correções Aplicadas

## 📋 Resumo das Correções

Foram aplicadas correções críticas no plugin Newbase para garantir que ele funcione corretamente no GLPI 10.0.20.

### ✅ Problemas Corrigidos

1. **Permissões não criadas corretamente** ✓
2. **Plugin não aparece no menu** ✓
3. **Namespaces incorretos** ✓
4. **Links quebrados** ✓
5. **Erro de acesso à configuração** ✓

---

## 🚀 Como Aplicar as Correções

### **Opção 1: Reinstalação Limpa (RECOMENDADO)**

1. **Desinstale o plugin:**
   - Acesse: http://glpi.test/public
   - Vá em: **Configurar > Plug-ins**
   - Clique em **Desativar** (botão ao lado do Newbase)
   - Clique em **Desinstalar**

2. **Reinstale o plugin:**
   - Na mesma página, clique em **Instalar**
   - Aguarde concluir
   - Clique em **Ativar**

3. **Verifique:**
   - Vá em: **Gerência** (menu principal)
   - Deve aparecer **"Dados da Empresa"**
   - Clique para acessar o dashboard

### **Opção 2: Correção de Permissões (se Opção 1 não funcionar)**

1. **Execute o script de correção:**
   ```
   http://glpi.test/plugins/newbase/front/tools/fix_permissions.php
   ```

2. **Faça logout e login novamente**

3. **Verifique o menu:**
   - Vá em: **Gerência**
   - Deve aparecer **"Dados da Empresa"**

---

## 🔍 Ferramentas de Diagnóstico

### 1. Diagnóstico Completo
```
http://glpi.test/plugins/newbase/front/tools/diagnostic.php
```

**O que faz:**
- ✓ Verifica versão do GLPI e PHP
- ✓ Verifica instalação do plugin
- ✓ Verifica tabelas do banco de dados
- ✓ Verifica permissões do usuário
- ✓ Verifica arquivos do plugin
- ✓ Mostra configurações atuais

### 2. Correção de Permissões
```
http://glpi.test/plugins/newbase/front/tools/fix_permissions.php
```

**O que faz:**
- ✓ Cria permissões faltantes
- ✓ Atualiza permissões incorretas
- ✓ Mostra relatório detalhado

---

## 📁 Arquivos Corrigidos

| Arquivo | Status | Descrição |
|---------|--------|-----------|
| `setup.php` | ✅ Corrigido | Instalação e permissões |
| `front/index.php` | ✅ Corrigido | Dashboard principal |
| `front/tools/fix_permissions.php` | ✅ Novo | Correção de permissões |
| `front/tools/diagnostic.php` | ✅ Novo | Diagnóstico completo |
| `docs/CORREÇÃO_PLUGIN.md` | ✅ Novo | Documentação detalhada |

---

## ✔️ Checklist de Verificação

Execute este checklist para confirmar que tudo está funcionando:

### Passo 1: Verificar Instalação
- [ ] Plugin aparece em **Configurar > Plug-ins**
- [ ] Status mostra **"Instalado"** e **"Ativado"**

### Passo 2: Verificar Menu
- [ ] Menu **"Gerência"** mostra **"Dados da Empresa"**
- [ ] Clicar no menu abre o dashboard
- [ ] Dashboard mostra estatísticas (Empresas, Tarefas, etc)

### Passo 3: Verificar Permissões
- [ ] Pode acessar **Configuração** sem erro
- [ ] Pode criar nova **Empresa**
- [ ] Pode criar nova **Tarefa**
- [ ] Pode criar novo **Sistema**

### Passo 4: Testar Funcionalidades
- [ ] Busca de CNPJ funciona
- [ ] Busca de CEP funciona
- [ ] Formulários salvam dados
- [ ] Listas exibem dados corretamente

---

## 🆘 Solução de Problemas

### Problema: "Plugin não aparece no menu"

**Solução:**
1. Execute: http://glpi.test/plugins/newbase/front/tools/diagnostic.php
2. Verifique seção **"5. User Permissions"**
3. Se houver erro, execute: http://glpi.test/plugins/newbase/front/tools/fix_permissions.php
4. Faça logout e login novamente

### Problema: "Erro de permissão ao acessar config.php"

**Solução:**
```
1. Execute: http://glpi.test/plugins/newbase/front/tools/fix_permissions.php
2. Faça logout e login
3. Tente acessar novamente
```

### Problema: "Tabelas não existem"

**Solução:**
```
1. Desinstale o plugin (Configurar > Plug-ins > Desinstalar)
2. Reinstale o plugin
3. Ative o plugin
```

### Problema: "Namespace não encontrado"

**Solução:**
```
1. Verifique se o arquivo setup.php foi atualizado
2. Execute: composer dump-autoload (na pasta do plugin)
3. Limpe cache do GLPI
```

---

## 📊 Permissões Criadas

O plugin cria estas permissões para cada perfil:

| Permissão | Super-Admin | Central | Helpdesk |
|-----------|-------------|---------|----------|
| plugin_newbase_companydata | 127 (Todos) | 15 (R/C/U/D) | 1 (Read) |
| plugin_newbase_task | 127 (Todos) | 15 (R/C/U/D) | 1 (Read) |
| plugin_newbase_system | 127 (Todos) | 15 (R/C/U/D) | 1 (Read) |
| plugin_newbase_config | 3 (R/U) | 3 (R/U) | 1 (Read) |

**Valores:**
- 1 = READ
- 2 = CREATE
- 4 = UPDATE
- 8 = DELETE
- 16 = PURGE
- 127 = ALLSTANDARDRIGHT (todos os acima)

---

## 📝 Logs Importantes

### Verificar Logs do GLPI:

1. **Erros PHP:**
   ```
   D:\laragon\www\glpi\files\_log\php-errors.log
   ```

2. **Eventos:**
   ```
   D:\laragon\www\glpi\files\_log\events.log
   ```

3. **SQL:**
   ```
   D:\laragon\www\glpi\files\_log\sql-errors.log
   ```

---

## 🔗 Links Úteis

- **Dashboard:** http://glpi.test/plugins/newbase/front/index.php
- **Configuração:** http://glpi.test/plugins/newbase/front/config.php
- **Empresas:** http://glpi.test/plugins/newbase/front/companydata.php
- **Tarefas:** http://glpi.test/plugins/newbase/front/task.php
- **Sistemas:** http://glpi.test/plugins/newbase/front/system.php

---

## 📞 Suporte

Se após seguir todas as instruções o plugin ainda não funcionar:

1. Execute o diagnóstico completo
2. Capture os logs de erro
3. Verifique a versão do GLPI (deve ser 10.0.20)
4. Verifique a versão do PHP (deve ser >= 8.1)

---

## ✨ Próximos Passos

Após confirmar que o plugin está funcionando:

1. **Configure as APIs:**
   - Acesse: Configuração
   - Configure URL da API de CNPJ
   - Configure URL da API de CEP

2. **Cadastre uma empresa:**
   - Acesse: Gerência > Dados da Empresa
   - Clique em "Adicionar"
   - Preencha os dados
   - Use busca de CNPJ

3. **Crie uma tarefa:**
   - Acesse: Tarefas
   - Clique em "Nova Tarefa"
   - Associe a uma empresa
   - Atribua a um usuário

4. **Explore o Dashboard:**
   - Veja estatísticas
   - Visualize mapa de tarefas
   - Acesse relatórios

---

**Versão:** 2.0.0  
**Data:** 03/01/2026  
**GLPI:** 10.0.20+  
**PHP:** 8.1+
