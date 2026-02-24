# CORREÇÕES APLICADAS - PLUGIN NEWBASE v2.1.1
**Data:** 19/02/2026  
**Status:** ✅ Concluído

## 📋 RESUMO DAS CORREÇÕES

### 🔧 Arquivos Modificados

1. **src/CompanyData.php** - Reformulado completamente
   - ✅ Todos os campos de dados pessoais adicionados (email, phone, inscrições estadual/municipal)
   - ✅ Endereço completo implementado (rua, número, complemento, bairro, cidade, estado, país, coordenadas GPS)
   - ✅ Status do contrato (dropdown: ativo/inativo/cancelado)
   - ✅ Sistema de tabs implementado para seções IPBX/PABX, IPBX Cloud, Chatbot, Linha Telefônica
   - ✅ Token CSRF adicionado manualmente após showFormHeader
   - ✅ Métodos prepareInputForAdd/Update para processar JSON

2. **src/Menu.php**
   - ✅ Link de Relatórios adicionado ao menu principal

3. **js/forms.js**
   - ✅ Seletores atualizados para nomes corretos dos campos (street, number, neighborhood, etc.)
   - ✅ Busca CNPJ preenche todos os campos do endereço
   - ✅ Busca CEP preenche rua e bairro corretamente

4. **install/mysql/migrations/2.1.1-add_company_fields.sql** - NOVO
   - Migration SQL para adicionar campos faltantes na tabela company_extras

5. **front/tools/migrate.php** - NOVO
   - Interface web para executar a migration SQL

6. **docs/DOCUMENTACAO_TECNICA_V2.1.0_ATUALIZADA.md**
   - ✅ 4 novos erros documentados (ERRO 18-21)

---

## 🆕 CAMPOS E SEÇÕES ADICIONADOS

### Formulário Principal (Dados Pessoais):
- ✅ Email
- ✅ Telefone
- ✅ Inscrição Estadual
- ✅ Inscrição Municipal

### Seção de Endereço Completa:
- ✅ CEP (com botão buscar)
- ✅ Rua/Logradouro
- ✅ Número
- ✅ Complemento
- ✅ Bairro
- ✅ Cidade
- ✅ Estado
- ✅ País
- ✅ Latitude
- ✅ Longitude

### Status:
- ✅ Status do Contrato (dropdown)

### Nova Aba "Configurações de Sistemas":
- ✅ **IPBX/PABX:** Modelo, Versão, IPs, Portas, Senhas, Observações
- ✅ **IPBX Cloud:** Mesma estrutura do IPBX/PABX
- ✅ **Chatbot:** Plataforma, API Key, Configuração
- ✅ **Linha Telefônica:** Operadora, Contrato, Notas

### Menu:
- ✅ Link para Relatórios

---

## 📝 ERROS DOCUMENTADOS

| Erro    | Descrição                          | Impacto    | Status     |
| ------- | ---------------------------------- | ---------- | ---------- |
| ERRO 18 | Campos faltantes na tabela SQL     | 🔴 Crítico | ✅ Corrigido |
| ERRO 19 | Formulário CompanyData incompleto  | 🔴 Crítico | ✅ Corrigido |
| ERRO 20 | Tabs não implementadas             | 🔴 Crítico | ✅ Corrigido |
| ERRO 21 | Link de Relatórios ausente no menu | 🟡 Médio   | ✅ Corrigido |

---

## 🚀 COMO APLICAR AS MUDANÇAS

### Passo 1: Executar Migration SQL
Acesse no navegador:
```
http://glpi.test/plugins/newbase/front/tools/migrate.php
```

1. Faça login como administrador
2. Clique em "Executar Migration 2.1.1"
3. Aguarde confirmação de sucesso

### Passo 2: Verificar Funcionamento
1. Vá em **Plugins → Newbase → Empresas**
2. Clique em "Adicionar" ou edite uma empresa existente
3. Verifique se todos os campos aparecem:
   - Dados Pessoais completos
   - Endereço completo
   - Status do contrato
4. Clique na aba **"Configurações de Sistemas"**
5. Verifique se aparecem as 4 seções:
   - IPBX/PABX
   - IPBX Cloud
   - Chatbot
   - Linha Telefônica

### Passo 3: Testar Botões
1. **Buscar CNPJ:**
   - Digite um CNPJ válido
   - Clique no botão 🔍 ao lado
   - Deve preencher automaticamente: nome, razão social, email, telefone, endereço

2. **Buscar CEP:**
   - Digite um CEP válido
   - Clique no botão 🔍 ao lado
   - Deve preencher: rua, bairro, cidade, estado

---

## ⚠️ NOTAS IMPORTANTES

1. **Backup:** Os dados existentes NÃO serão afetados pela migration
2. **Campos novos:** Empresas antigas terão campos vazios até serem editadas
3. **JSON:** Configurações de sistemas são armazenadas como JSON no campo `systems_config`
4. **Compatibilidade:** Mantém 100% de compatibilidade com GLPI 10.0.20+

---

## 📞 SUPORTE

Se algo não funcionar:
1. Verifique o console do navegador (F12) para erros JavaScript
2. Verifique os logs do GLPI em `files/_log/`
3. Verifique se a migration foi executada com sucesso
4. Limpe o cache do navegador (Ctrl+Shift+Del)

---

**Status Final:** ✅ Todas as correções aplicadas com sucesso!

---

## 🟡 ERRO 22: Botão para listar empresas ausente no dashboard (20/02/2026)

**Causa Raiz:**
O dashboard (`front/index.php`) tinha apenas o botão "Nova empresa" mas não tinha um botão para ver a **lista de empresas cadastradas**, tornando impossível acessar a página `companydata.php`.

**Manifestação:**
Usuários podiam criar empresas mas não conseguiam ver a lista de empresas já cadastradas sem digitar a URL manualmente.

**Localização Exata:** `front/index.php` linhas 50-68

**Código ANTES:**
```php
echo "<div class='mb-3'>";

if (\GlpiPlugin\Newbase\CompanyData::canCreate()) {
    echo "<a class='btn btn-secondary me-2' href='...'>Nova empresa</a>";
}

if (\GlpiPlugin\Newbase\Task::canCreate()) {
    echo "<a class='btn btn-primary me-2' href='...'>Nova Tarefa</a>";
}
```

**Código DEPOIS:**
```php
echo "<div class='mb-3'>";

// Link para lista de empresas
if (\GlpiPlugin\Newbase\CompanyData::canView()) {
    echo "<a class='btn btn-primary me-2' href='.../companydata.php'>
           <i class='ti ti-building'></i> Empresas</a>";
}

if (\GlpiPlugin\Newbase\CompanyData::canCreate()) {
    echo "<a class='btn btn-secondary me-2' href='.../companydata.form.php'>
           <i class='ti ti-plus'></i> Nova Empresa</a>";
}

// Link para lista de tarefas
if (\GlpiPlugin\Newbase\Task::canView()) {
    echo "<a class='btn btn-primary me-2' href='...Task::getSearchURL()'>
           <i class='ti ti-list-check'></i> Tarefas</a>";
}

if (\GlpiPlugin\Newbase\Task::canCreate()) {
    echo "<a class='btn btn-secondary me-2' href='...Task::getFormURL()'>
           <i class='ti ti-plus'></i> Nova Tarefa</a>";
}
```

**Impacto:** 🟡 MÉDIO - Funcionalidade de listagem inacessível via interface
**Status:** ✅ APLICADO

---

### Botões Finais no Dashboard:
1. 🏢 **Empresas** (azul) - Ver todas as empresas
2. ➕ **Nova Empresa** (cinza) - Criar nova empresa
3. ✅ **Tarefas** (azul) - Ver todas as tarefas
4. ➕ **Nova Tarefa** (cinza) - Criar nova tarefa
