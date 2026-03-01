# MANUAL OPERACIONAL E MANUTENÇÃO - PLUGIN NEWBASE

Este manual descreve como navegar, usar e realizar a manutenção do plugin Newbase no GLPI.

---

## 1. GUIA DE NAVEGAÇÃO

### 1.1. Onde Encontrar o Plugin

Para acessar as funcionalidades do plugin, siga um dos caminhos abaixo.

#### Opção 1: Via Menu Lateral do GLPI

1. No menu lateral esquerdo, clique em **Plugins**.
2. Selecione **Newbase**.
3. No submenu que aparece, clique em **Companies** (Empresas) ou outras funcionalidades disponíveis.
   - **Caminho:** `Plugins → Newbase → Companies`

#### Opção 2: Via Dashboard do Plugin

1. Acesse o dashboard principal do plugin em `Plugins → Newbase`.
2. No topo da página, você encontrará botões de acesso rápido:
   - 🏢 **Empresas** (azul): Abre a lista de empresas cadastradas.
   - ➕ **Nova Empresa** (cinza): Abre o formulário para criar uma nova empresa.
   - ✅ **Tarefas** (azul): Abre a lista de tarefas.
   - ➕ **Nova Tarefa** (cinza): Abre o formulário para criar uma nova tarefa.

#### Opção 3: URL Direta

Para acessar a lista de empresas diretamente, use a URL:
`http://<seu-glpi>/plugins/newbase/front/companydata.php`

> **⚠️ Importante:** Não confunda a gestão de empresas do plugin (`Plugins → Newbase`) com a gestão de entidades nativa do GLPI (`Administração → Entidades`). São funcionalidades distintas.

### 1.2. Como Adicionar e Editar Empresas

#### Para Adicionar uma Nova Empresa

1. Navegue até a lista de empresas (`Plugins → Newbase → Companies`).
2. Clique no botão **+ Adicionar**.
3. Preencha os dados no formulário principal.
4. Clique em **Salvar**.

#### Para Editar uma Empresa Existente

1. Navegue até a lista de empresas.
2. Clique no nome da empresa que deseja editar.
3. Modifique os campos no **Formulário Principal**.
4. Para adicionar detalhes de sistemas, clique na aba **Configurações de Sistemas**.
5. Preencha as informações e clique em **Salvar**.

---

## 2. FLUXO DE USO DAS ABAS DE SISTEMAS

Ao editar uma empresa, o formulário é dividido em duas abas principais para melhor organização.

### Aba 1: Formulário Principal (Padrão)

Esta aba contém as informações de identificação e localização da empresa:

- **Dados Pessoais:** Nome, CNPJ, Email, Telefone, Razão Social, etc.
- **Endereço:** CEP, Rua, Número, Bairro, Cidade, Coordenadas GPS, etc.
- **Status:** Status do Contrato (ativo, inativo, cancelado).
- **Observações**.

### Aba 2: Configurações de Sistemas (Disponível na Edição)

Esta aba só aparece ao **editar** uma empresa já existente. Ela contém 4 seções para documentar a infraestrutura técnica do cliente:

1. **IPBX/PABX:**
    - Modelo, Versão, IP Interno/Externo, Portas, Senhas e Observações.
2. **IPBX Cloud:**
    - Mesma estrutura do IPBX/PABX.
3. **Chatbot:**
    - Plataforma, API Key e Configuração.
4. **Linha Telefônica:**
    - Operadora, Número do Contrato e Notas.

> **Nota:** Os dados inseridos nesta aba são salvos em um único campo JSON no banco de dados, o que facilita a exportação e a manutenção.

---

## 3. FAQ E RESOLUÇÃO DE PROBLEMAS (TROUBLESHOOTING)

### Problema: "Não vejo o menu `Newbase` em `Plugins`."

- **Causa 1:** O plugin não está ativado.
  - **Solução:** Vá em `Configurar → Plugins`, localize o "Newbase" e clique em **Instalar** e depois em **Ativar**.
- **Causa 2:** Seu perfil de usuário não tem permissão.
  - **Solução:** Peça a um administrador para ir em `Configurar → Perfis`, editar seu perfil, e na aba "Plugins", conceder a permissão `plugin_newbase`.

### Problema: "Não vejo a aba `Configurações de Sistemas`."

- **Causa:** A aba só é exibida ao **editar** uma empresa. Ela não aparece no formulário de criação.
  - **Solução:** Salve a nova empresa primeiro. Depois, volte para a lista, clique no nome da empresa recém-criada para abri-la no modo de edição. A aba estará visível.

### Problema: "A interface parece quebrada ou as abas não aparecem mesmo na edição."

- **Causa:** Cache desatualizado do navegador.
  - **Solução 1 (Navegador):** Force a recarga da página sem usar o cache pressionando `Ctrl + F5` (ou `Cmd + Shift + R` no Mac).
  - **Solução 2 (Navegador):** Limpe o cache do seu navegador (`Ctrl + Shift + Del`).
  - **Solução 3 (GLPI):** Acesse `https://<seu-glpi>/front/central.php?purge=cache` para limpar o cache do lado do servidor.

---

## 4. GUIA DE EXECUÇÃO DE MIGRATIONS MANUAIS

Caso a atualização automática do banco de dados falhe ou precise ser executada manualmente, siga um dos métodos abaixo para aplicar as migrations SQL.

### Método 1: Via phpMyAdmin (Recomendado)

1. Acesse seu phpMyAdmin (ex: `http://localhost/phpmyadmin`).
2. Selecione o banco de dados do GLPI (geralmente `glpi`).
3. Abra a aba **SQL**.
4. Cole o conteúdo do arquivo de migration (`.sql`) na área de texto.
5. Clique em **Executar**.

**Exemplo de SQL (Migration 2.1.1):**

```sql
-- Adiciona campos de endereço e configurações na tabela de empresas
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN `inscricao_estadual` VARCHAR(50) DEFAULT NULL,
ADD COLUMN `inscricao_municipal` VARCHAR(50) DEFAULT NULL,
ADD COLUMN `cep` VARCHAR(10) DEFAULT NULL,
ADD COLUMN `street` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `number` VARCHAR(20) DEFAULT NULL,
ADD COLUMN `complement` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `neighborhood` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `city` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `state` VARCHAR(2) DEFAULT NULL,
ADD COLUMN `country` VARCHAR(100) DEFAULT 'Brasil',
ADD COLUMN `latitude` DECIMAL(10, 8) DEFAULT NULL,
ADD COLUMN `longitude` DECIMAL(11, 8) DEFAULT NULL,
ADD COLUMN `contract_status` VARCHAR(50) DEFAULT 'active',
ADD COLUMN `systems_config` LONGTEXT DEFAULT NULL COMMENT 'JSON com configurações de sistemas';

-- Adiciona índices para melhorar a performance de buscas
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD INDEX `idx_cep` (`cep`),
ADD INDEX `idx_state` (`state`),
ADD INDEX `idx_contract_status` (`contract_status`);

-- Garante que o campo de configuração seja um JSON válido
UPDATE `glpi_plugin_newbase_company_extras` SET `systems_config` = '{}' WHERE `systems_config` IS NULL;
```

### Método 2: Via Linha de Comando (MySQL CLI)

1. Abra um terminal ou prompt de comando.
2. Navegue até a pasta onde o arquivo `.sql` está localizado.
3. Execute o comando de importação do MySQL.

**Exemplo (usando o Terminal do Laragon):**

```bash
# Navegue até a pasta de migrations do plugin
cd D:\laragon\www\glpi\plugins
ewbase\install\mysql\migrations

# Execute o SQL no banco 'glpi' com o usuário 'root'
mysql -u root glpi < 2.1.1-add_company_fields.sql
```

### Como Verificar se a Migration Funcionou

Após executar o script, você pode confirmar que a estrutura da tabela foi alterada com o seguinte comando SQL:

```sql
DESCRIBE glpi_plugin_newbase_company_extras;
```

A saída deve listar todas as novas colunas (`inscricao_estadual`, `cep`, `systems_config`, etc.).
