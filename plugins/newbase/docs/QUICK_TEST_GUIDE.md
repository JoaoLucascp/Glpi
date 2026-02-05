# ✅ GUIA DE TESTE RÁPIDO - Plugin Newbase

**Data:** 04/02/2026  
**Versão:** 2.1.0

---

## 🎯 OBJETIVO

Este guia vai te ajudar a verificar se todas as correções foram aplicadas corretamente e se o plugin está funcionando perfeitamente.

---

## 📋 PRÉ-REQUISITOS

Antes de começar, certifique-se de que:

- [x] GLPI 10.0.20+ está instalado
- [x] PHP 8.1+ está rodando
- [x] MySQL 8.0+ está ativo
- [x] Laragon está rodando

---

## 🔧 PASSO 1: LIMPEZA INICIAL (5 minutos)

### 1.1. Limpar Cache do GLPI

```powershell
# No PowerShell
cd D:\laragon\www\glpi
Remove-Item "files\_cache\*" -Force -Recurse
Remove-Item "files\_sessions\*" -Force -Recurse
Remove-Item "files\_tmp\*" -Force -Recurse
```

### 1.2. Verificar Permissões de Arquivos

```powershell
# Verificar se os arquivos estão acessíveis
Test-Path "D:\laragon\www\glpi\plugins\newbase\setup.php"
Test-Path "D:\laragon\www\glpi\plugins\newbase\hook.php"
Test-Path "D:\laragon\www\glpi\plugins\newbase\src\Menu.php"
```

**Resultado esperado:** Todos devem retornar `True`

---

## 🚀 PASSO 2: INSTALAÇÃO DO PLUGIN (5 minutos)

### 2.1. Acessar GLPI

1. Abra seu navegador
2. Acesse: `http://glpi.test/`
3. Faça login com usuário Admin

### 2.2. Desinstalar Plugin (se já estava instalado)

1. Vá em: **Configurar > Plugins**
2. Localize o plugin **Newbase**
3. Se estiver ativo, clique em **Desativar**
4. Depois clique em **Desinstalar**

### 2.3. Instalar Plugin Novamente

1. Na lista de plugins, localize **Newbase**
2. Clique em **Instalar**
3. Aguarde a mensagem de sucesso
4. Clique em **Ativar**

**Resultado esperado:** Plugin instalado e ativado sem erros

---

## ✅ PASSO 3: VERIFICAÇÕES FUNCIONAIS (10 minutos)

### 3.1. Verificar Menu Principal

1. No menu superior do GLPI, clique em **Ferramentas**
2. Procure por **Newbase** no submenu

**Resultado esperado:** Menu "Newbase" aparece com ícone de prédio

### 3.2. Verificar Submenus

Clique em **Newbase** e verifique se aparecem as seguintes opções:

- [ ] **Company Data** (Dados de Empresas) - Ícone de prédio
- [ ] **Systems** (Sistemas) - Ícone de telefone
- [ ] **Field Tasks** (Tarefas de Campo) - Ícone de mapa
- [ ] **Reports** (Relatórios) - Ícone de gráfico
- [ ] **Configuration** (Configuração) - Ícone de engrenagem

**Resultado esperado:** Todos os submenus aparecem com seus ícones

### 3.3. Testar Cada Submenu

#### 3.3.1. Company Data
1. Clique em **Company Data**
2. Verifique se a página carrega
3. Tente adicionar uma nova empresa clicando no botão "+"

**Resultado esperado:** Página carrega, formulário de adição funciona

#### 3.3.2. Systems
1. Clique em **Systems**
2. Verifique se a página carrega
3. Tente adicionar um novo sistema

**Resultado esperado:** Página carrega, formulário funciona

#### 3.3.3. Field Tasks
1. Clique em **Field Tasks**
2. Verifique se a página carrega
3. Tente criar uma nova tarefa

**Resultado esperado:** Página carrega com opções de GPS

#### 3.3.4. Reports
1. Clique em **Reports**
2. Verifique se os relatórios são exibidos

**Resultado esperado:** Relatórios carregam

#### 3.3.5. Configuration
1. Clique em **Configuration**
2. Verifique as opções de configuração
3. Tente alterar uma configuração

**Resultado esperado:** Configurações aparecem e podem ser salvas

---

## 🔍 PASSO 4: VERIFICAÇÕES TÉCNICAS (5 minutos)

### 4.1. Verificar Logs de Erro

```powershell
# Ver últimos erros PHP
Get-Content "D:\laragon\www\glpi\files\_log\php-errors.log" -Tail 20

# Ver log do plugin
Get-Content "D:\laragon\www\glpi\files\_log\newbase.log" -Tail 20
```

**Resultado esperado:** Nenhum erro relacionado ao Newbase

### 4.2. Verificar Tabelas no Banco de Dados

1. Abra o HeidiSQL (Laragon)
2. Conecte ao banco de dados do GLPI
3. Verifique se existem as tabelas:

- [ ] `glpi_plugin_newbase_addresses`
- [ ] `glpi_plugin_newbase_systems`
- [ ] `glpi_plugin_newbase_tasks`
- [ ] `glpi_plugin_newbase_task_signatures`
- [ ] `glpi_plugin_newbase_company_extras`
- [ ] `glpi_plugin_newbase_config`

**Resultado esperado:** Todas as 6 tabelas existem

### 4.3. Verificar Permissões

1. Vá em **Administração > Perfis**
2. Clique em **Super-Admin**
3. Procure pela aba **Plugin Newbase**
4. Verifique se há opções de permissão

**Resultado esperado:** Aba de permissões existe

---

## 🎨 PASSO 5: VERIFICAÇÕES VISUAIS (5 minutos)

### 5.1. Verificar Ícones

Todos os menus devem ter ícones corretos:

- [ ] Dashboard - Ícone de casa
- [ ] Company Data - Ícone de prédio
- [ ] Systems - Ícone de telefone
- [ ] Field Tasks - Ícone de localização/mapa
- [ ] Reports - Ícone de gráfico
- [ ] Configuration - Ícone de engrenagem

### 5.2. Verificar CSS

1. Abra qualquer página do plugin
2. Pressione F12 (DevTools)
3. Vá na aba Console
4. Verifique se há erros CSS

**Resultado esperado:** Nenhum erro 404 de arquivos CSS

### 5.3. Verificar JavaScript

1. Na mesma aba Console
2. Verifique se há erros JavaScript

**Resultado esperado:** Nenhum erro JS relacionado ao plugin

---

## 📊 PASSO 6: TESTES DE FUNCIONALIDADES (10 minutos)

### 6.1. Teste de CNPJ

1. Vá em **Company Data**
2. Clique em adicionar nova empresa
3. Digite um CNPJ válido: `00.000.000/0001-00`
4. Clique no botão de buscar

**Resultado esperado:** Dados são preenchidos automaticamente via API

### 6.2. Teste de CEP

1. Em qualquer formulário de endereço
2. Digite um CEP válido: `29900-000`
3. Clique no botão de buscar

**Resultado esperado:** Endereço é preenchido via ViaCEP

### 6.3. Teste de GPS

1. Crie uma nova **Field Task**
2. Verifique se há opções de GPS
3. Tente capturar localização (se tiver GPS)

**Resultado esperado:** Funcionalidade GPS está presente

### 6.4. Teste de Assinatura Digital

1. Em uma tarefa criada
2. Verifique se há opção de assinatura
3. Tente adicionar uma assinatura

**Resultado esperado:** Assinatura pode ser capturada

---

## ✅ CHECKLIST FINAL

Marque apenas se TODOS os itens passaram:

### Instalação
- [ ] Plugin instalou sem erros
- [ ] Plugin ativou corretamente
- [ ] Sem mensagens de erro no log

### Menu
- [ ] Menu "Newbase" aparece em Ferramentas
- [ ] Todos os 5 submenus aparecem
- [ ] Ícones estão corretos

### Funcionalidades
- [ ] Company Data funciona
- [ ] Systems funciona
- [ ] Tasks funciona
- [ ] Reports funciona
- [ ] Configuration funciona

### Integrações
- [ ] Busca por CNPJ funciona
- [ ] Busca por CEP funciona
- [ ] GPS está disponível
- [ ] Assinatura digital funciona

### Técnico
- [ ] Tabelas criadas no banco
- [ ] Sem erros nos logs
- [ ] CSS carrega corretamente
- [ ] JavaScript funciona

---

## 🚨 PROBLEMAS COMUNS E SOLUÇÕES

### Problema 1: Plugin não aparece na lista

**Solução:**
```powershell
# Verificar se arquivos existem
Test-Path "D:\laragon\www\glpi\plugins\newbase\setup.php"
Test-Path "D:\laragon\www\glpi\plugins\newbase\hook.php"

# Verificar permissões
icacls "D:\laragon\www\glpi\plugins\newbase"
```

### Problema 2: Erro ao instalar

**Solução:**
1. Verificar logs: `D:\laragon\www\glpi\files\_log\php-errors.log`
2. Limpar cache do GLPI
3. Verificar se MySQL está rodando
4. Reinstalar plugin

### Problema 3: Menu não aparece

**Solução:**
1. Limpar cache: `Remove-Item "files\_cache\*" -Force -Recurse`
2. Fazer logout e login novamente
3. Verificar permissões do perfil

### Problema 4: Erro CSRF

**Solução:**
```php
// Verificar se no setup.php tem:
$PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;
```

---

## 📞 SUPORTE

Se todos os testes passaram: **🎉 PARABÉNS! Seu plugin está 100% funcional!**

Se algum teste falhou:
1. Anote qual teste falhou
2. Verifique o log correspondente
3. Verifique a documentação: `docs/CORRECTIONS_APPLIED.md`
4. Contate o suporte se necessário

---

**Tempo total estimado:** 40 minutos  
**Última atualização:** 04/02/2026
