# 🧭 GUIA DE NAVEGAÇÃO - PLUGIN NEWBASE

## 📍 ONDE ENCONTRAR AS EMPRESAS

### Opção 1: Via Menu Lateral do GLPI
1. No menu lateral esquerdo, clique em **Plugins**
2. Selecione **Newbase**
3. No submenu, você verá **Companies** (Empresas)
4. Clique para ver a listagem de empresas

**Caminho:** `Plugins → Newbase → Companies`

### Opção 2: Via Dashboard do Plugin
1. Acesse: `Plugins → Newbase` (vai para o dashboard)
2. Você verá 4 botões no topo:
   - 🏢 **Empresas** (azul) - Ver lista de empresas
   - ➕ **Nova Empresa** (cinza) - Criar nova empresa
   - ✅ **Tarefas** (azul) - Ver lista de tarefas
   - ➕ **Nova Tarefa** (cinza) - Criar nova tarefa

3. Clique no botão 🏢 **Empresas**

### Opção 3: URL Direta
Acesse diretamente: `http://glpi.test/plugins/newbase/front/companydata.php`

---

## ⚠️ NÃO CONFUNDIR COM:

**❌ ERRADO:** `Administração → Entidades`
- Isso é do GLPI core, não do plugin Newbase
- Gerencia entidades hierárquicas do GLPI

**✅ CORRETO:** `Plugins → Newbase → Companies`
- Isso é do plugin Newbase
- Gerencia empresas com CNPJ, endereço, IPBX/PABX, etc.

---

## 📝 COMO ADICIONAR/EDITAR EMPRESAS

### Adicionar Nova Empresa:
1. `Plugins → Newbase → Companies`
2. Clique no botão **+ Adicionar** (canto superior)
3. Preencha os dados
4. Clique em **Salvar**

### Editar Empresa Existente:
1. `Plugins → Newbase → Companies`
2. Clique no nome da empresa na lista
3. Edite os campos desejados
4. **CLIQUE NA ABA "Configurações de Sistemas"** (topo da página)
5. Preencha IPBX/PABX, Cloud, Chatbot, Linha Telefônica
6. Clique em **Salvar**

---

## 🎯 ABAS DISPONÍVEIS NO FORMULÁRIO DE EMPRESA

Quando você edita uma empresa, verá **2 abas** no topo:

### Aba 1: Formulário Principal (padrão)
Contém:
- **Dados Pessoais:** Nome, CNPJ, Email, Telefone, Razão Social, Nome Fantasia, Inscrições, Pessoa de Contato, Website
- **Endereço:** CEP, Rua, Número, Complemento, Bairro, Cidade, Estado, País, Coordenadas GPS
- **Status:** Status do Contrato (ativo/inativo/cancelado)
- **Observações**

### Aba 2: Configurações de Sistemas ⭐ (NOVA!)
Contém 4 seções em cards:

1. **IPBX/PABX**
   - Modelo, Versão
   - IP Interno, IP Externo
   - Porta Web, Senha Web
   - Porta SSH, Senha SSH
   - Observações

2. **IPBX Cloud**
   - Mesma estrutura do IPBX/PABX

3. **Chatbot**
   - Plataforma
   - API Key
   - Configuração

4. **Linha Telefônica**
   - Operadora
   - Número do Contrato
   - Notas

---

## 🔧 SE AS ABAS NÃO APARECEM

Execute no navegador:
- Limpe o cache: **Ctrl + Shift + Del** → Limpar dados de navegação
- Recarregue a página: **Ctrl + F5** (força reload sem cache)

Ou acesse o formulário de edição novamente:
1. Vá em `Plugins → Newbase → Companies`
2. Clique em uma empresa existente
3. Procure as abas no topo da página (abaixo do título)

---

## ✅ TESTE RÁPIDO

1. Acesse: `http://glpi.test/plugins/newbase/front/companydata.php`
2. Você deve ver uma **lista vazia** ou com empresas cadastradas
3. Clique em **+ Adicionar**
4. Preencha ao menos o **Nome** e **CNPJ**
5. Clique em **Salvar**
6. Você será redirecionado para a **edição** da empresa
7. **Procure a aba "Configurações de Sistemas"** no topo
8. Clique nela e preencha os dados de IPBX/PABX

---

## 📞 PROBLEMAS COMUNS

### "Não vejo o menu Newbase"
- Verifique se o plugin está **instalado E ativado**: `Configurar → Plugins`
- Verifique se você tem permissão: usuário deve ter direito `plugin_newbase`

### "Não vejo a aba Configurações de Sistemas"
- A aba só aparece ao **editar** uma empresa existente (não ao criar nova)
- Após salvar uma empresa nova, clique nela na lista para editá-la
- Aí sim a aba aparecerá

### "Vou para Entidades do GLPI ao invés de Empresas do Newbase"
- Você está clicando no lugar errado
- Vá em: **Plugins → Newbase → Companies** (não em Administração → Entidades)

---

**Status:** ✅ Correções aplicadas - versão 2.1.1 + ERRO 26 corrigido
