# LOG DE PROBLEMAS E SOLUÇÕES - PLUGIN NEWBASE

## Histórico de Versões e Correções

Este documento centraliza todos os erros encontrados, as soluções aplicadas e o roadmap de refatoração do plugin Newbase, abrangendo as versões v2.1.0 e v2.1.1.

---

## ERROS ENCONTRADOS E CORRIGIDOS (v2.1.0)

### 🔴 ERRO 1: Token CSRF não adicionado em formulário CommonDBTM (Task.php)

- **Causa Raiz:** O método `showFormHeader()` em classes que estendem `CommonDBTM` não adiciona automaticamente o token CSRF no GLPI 10.0.20+.
- **Solução:** Adicionar `echo Html::hidden('_glpi_csrf_token');` manualmente após a chamada de `showFormHeader()` em `src/Task.php`.
- **Impacto:** Crítico - Usuários não conseguiam criar ou editar tarefas.

### 🔴 ERRO 2: Token CSRF não adicionado em formulário CommonDBTM (System.php)

- **Causa Raiz:** Idêntica ao ERRO 1, afetando a classe `System`.
- **Solução:** Adicionar `echo Html::hidden('_glpi_csrf_token');` manualmente após a chamada de `showFormHeader()` em `src/System.php`.
- **Impacto:** Crítico - Usuários não conseguiam criar ou editar sistemas.

### 🔴 ERRO 3: AJAX validando CSRF incorretamente (6 arquivos)

- **Causa Raiz:** Scripts AJAX chamavam `Session::checkCSRF($_POST)` explicitamente, o que conflita com a validação automática do GLPI 10.0.20+ que utiliza o header `X-Glpi-Csrf-Token`.
- **Solução:** Implementar um fallback que suporta tanto o header (`$_SERVER['HTTP_X_GLPI_CSRF_TOKEN']`) quanto o `$_POST['_glpi_csrf_token']` em todos os 6 arquivos AJAX afetados.
- **Impacto:** Crítico - Todas as funcionalidades AJAX (cálculo de km, busca de CNPJ, upload de assinatura, etc.) falhavam.

### 🔴 ERRO 4: AJAX mapData.php sem validação CSRF

- **Causa Raiz:** O arquivo `ajax/mapData.php` não possuía nenhuma validação CSRF.
- **Solução:** Adicionar o mesmo bloco de validação CSRF com fallback (header/POST) que foi implementado nos outros arquivos AJAX.
- **Impacto:** Crítico - Mapas interativos não funcionavam (mapa ficava vazio).

### 🔴 ERRO 5: system.form.php sem try-catch na validação CSRF

- **Causa Raiz:** A validação `Session::checkCSRF($_POST)` era feita sem um bloco `try-catch`, resultando em uma página de erro branca em caso de falha.
- **Solução:** Envolver a chamada `Session::checkCSRF($_POST)` em um bloco `try-catch` para capturar a exceção e exibir uma mensagem de erro amigável com `Session::addMessageAfterRedirect`.
- **Impacto:** Crítico - Experiência do usuário degradada com erros não tratados.

### 🔴 ERRO 6: Menu de empresas não aparecia

- **Causa Raiz:** O método `Menu::getMenuContent()` não incluía a lógica para adicionar o link do `CompanyData` no menu principal do plugin.
- **Solução:** Adicionar o bloco de verificação `class_exists` e `canCreate` para `CompanyData`, similar ao que já existia para outras classes.
- **Impacto:** Crítico - Usuários não conseguiam acessar a funcionalidade de empresas.

### 🔴 ERRO 7: TypeError em Menu::canView() e menu ausente

- **Causa Raiz:** `Session::haveRight()` retorna um `int`, mas o código esperava um `bool`, causando falha na lógica de exibição do menu.
- **Solução:** Fazer o cast explícito para `(bool)` no retorno de `Session::haveRight()` em todos os métodos `canView()`, `canCreate()`, etc.
- **Impacto:** Crítico - Menu do plugin não aparecia na interface.

### 🔴 ERRO 8: Endpoints AJAX sem padrões GLPI 10.0.20

- **Causa Raiz:** Arquivos AJAX não seguiam as melhores práticas, como uso de guard clauses, headers de segurança e respostas padronizadas.
- **Solução:** Refatorar os endpoints para usar uma classe `AjaxHandler` centralizada.
- **Impacto:** Moderado - Código funcional, mas de difícil manutenção.

### 🔴 ERRO 9: Agrupamentos SQL incorretos em index.php

- **Causa Raiz:** O `DB->request()` do GLPI escapava expressões como `COUNT(*)`, tratando-as como nomes de coluna, o que gerava um erro SQL.
- **Solução:** Utilizar `new \QueryExpression('COUNT(*) AS total')` para evitar o escape automático.
- **Impacto:** Crítico - Dashboard do plugin falhava ao carregar as estatísticas.

### 🔴 ERRO 10: Menu de empresas e ordem de serviço faltando

- **Causa Raiz:** Semelhante ao ERRO 6, a função `Menu::getMenuContent()` não construía todos os sub-menus necessários.
- **Solução:** Adicionar os blocos de código faltantes para gerar os links de `CompanyData` e o formulário de tarefas.
- **Impacto:** Crítico – usuários não conseguiam acessar as páginas de gestão de empresas ou abrir o formulário de tarefa diretamente pelo menu.

### 🔴 ERRO 11: taskActions.php - Coordenadas GPS não validadas para range

- **Causa Raiz:** O endpoint aceitava e salvava valores de latitude e longitude fora do range válido (e.g., 999), tornando-os inúteis.
- **Solução:** Adicionar a validação com `Common::validateCoordinates()` antes de salvar os dados.
- **Impacto:** Médio - Dados inválidos eram armazenados, afetando mapas.

### 🔴 ERRO 12: taskActions.php - Valor 'NULL' como string ao invés de null

- **Causa Raiz:** Ao reabrir uma tarefa, o código salvava a string literal `'NULL'` no campo `date_end`, em vez do valor `null` do PHP.
- **Solução:** Alterar `$update_data['date_end'] = 'NULL';` para `$update_data['date_end'] = null;`.
- **Impacto:** Crítico - Dados corrompidos no banco, quebrando relatórios e queries.

### 🔴 ERRO 13: cnpj_proxy.php - Dados sensíveis registrados em logs em plain text

- **Causa Raiz:** O arquivo de log registrava CNPJs e nomes de empresas sem qualquer mascaramento.
- **Solução:** Criar e aplicar funções de mascaramento (`maskCNPJ`, `maskName`) antes de registrar os dados no log.
- **Impacto:** Crítico - Violação de LGPD e risco de compliance.

### 🔴 ERRO 14: Múltiplas vulnerabilidades XSS em front/index.php e front/report.php

- **Causa Raiz:** Dados eram impressos diretamente no HTML sem o devido escape.
- **Solução:** Aplicar `htmlspecialchars()` ou cast para `(int)` em todas as variáveis antes de exibi-las.
- **Impacto:** Médio - Risco potencial de XSS.

### 🔴 ERRO 15: Duplicação crítica de código entre AddressHandler.php e Common.php

- **Causa Raiz:** A lógica para consultar CEP via API estava duplicada em duas classes diferentes.
- **Solução:** Centralizar a funcionalidade no método `Common::fetchAddressByCEP()` e fazer com que `AddressHandler` apenas delegue a chamada.
- **Impacto:** Médio - Dívida técnica e risco de inconsistências.

### 🔴 ERRO 16: Direct $_GET usage sem wrapper GLPI em Task.php

- **Causa Raiz:** Acesso direto a superglobais como `$_GET` e `$_SESSION` em vez de usar os wrappers do GLPI (`filter_input()`, `Session::getActiveEntity()`).
- **Solução:** Substituir todos os acessos diretos pelos métodos apropriados do GLPI.
- **Impacto:** Médio - Código não segue os padrões do framework.

### 🔴 ERRO 17: Endpoint de CNPJ usando cnpj_proxy.php externo (obsoleto)

- **Causa Raiz:** O endpoint `searchCompany.php` dependia de outro endpoint (`cnpj_proxy.php`) para consultas externas, criando uma arquitetura frágil e duplicada.
- **Solução:** Unificar toda a lógica de busca (local e externa) dentro de `searchCompany.php` e descontinuar `cnpj_proxy.php`.
- **Impacto:** Crítico - Dificuldade de depuração e manutenção.

---

## CORREÇÕES APLICADAS (v2.1.1)

### 🔴 ERRO 18: Campos essenciais faltando na tabela company_extras

- **Causa Raiz:** A tabela `glpi_plugin_newbase_company_extras` não possuía campos para endereço completo, inscrições, status do contrato e configurações de sistemas.
- **Solução:** Criar uma migration SQL (`2.1.1-add_company_fields.sql`) para adicionar as colunas faltantes.
- **Impacto:** Crítico - Impossível cadastrar empresas completamente.

### 🔴 ERRO 19: Formulário CompanyData.php incompleto

- **Causa Raiz:** O formulário de empresas não renderizava os campos que foram adicionados à tabela no ERRO 18.
- **Solução:** Atualizar o método `showForm()` em `src/CompanyData.php` para incluir todos os novos campos.
- **Impacto:** Crítico - Interface incompleta.

### 🔴 ERRO 20: Falta implementação de tabs em CompanyData

- **Causa Raiz:** A classe não implementava os métodos `getTabNameForItem()` e `displayTabContentForItem()` para exibir as seções de sistemas como abas.
- **Solução:** Implementar os métodos de abas para exibir um novo painel "Configurações de Sistemas".
- **Impacto:** Crítico - Funcionalidade principal do plugin inacessível.

### 🟡 ERRO 21: Link de Relatórios ausente no Menu.php

- **Causa Raiz:** O menu não registrava o link para a página de relatórios.
- **Solução:** Adicionar a entrada para `report.php` no método `getMenuContent()`.
- **Impacto:** Médio - Funcionalidade inacessível via menu.

### 🟡 ERRO 22: Botão para listar empresas ausente no dashboard

- **Causa Raiz:** O dashboard (`front/index.php`) não tinha um botão para levar à lista de empresas cadastradas.
- **Solução:** Adicionar um botão "Empresas" que aponta para `companydata.php`.
- **Impacto:** Médio - Lista de empresas inacessível pela interface.

### 🔴 ERRO 23: Sistema de busca CNPJ sem proteção contra rate limit e cliques múltiplos

- **Causa Raiz:** Múltiplos cliques do usuário esgotavam o limite das APIs externas e `file_get_contents` se mostrava instável.
- **Solução:** Implementar cache de 5 minutos, debounce para bloquear cliques múltiplos no frontend (`forms.js`), e usar cURL no backend (`searchCompany.php`) para mais estabilidade.
- **Impacto:** Crítico - Funcionalidade de busca de CNPJ quase inutilizável.

### 🔴 ERRO 24: Campos decimais recebendo string vazia causando erro SQL

- **Causa Raiz:** O MySQL não aceita strings vazias (`''`) em campos do tipo `DECIMAL`, o que acontecia quando os campos de GPS não eram preenchidos.
- **Solução:** Nos métodos `prepareInputForAdd/Update`, converter explicitamente strings vazias para `null` antes de salvar.
- **Impacto:** Crítico - Impossível salvar empresas com coordenadas GPS vazias.

### 🔴 ERRO 26: Abas IPBX/PABX não aparecem (typo no nome do método)

- **Causa Raiz:** O método para definir abas estava nomeado como `defineTab()` em vez de `defineTabs()`, fazendo com que o GLPI não o reconhecesse.
- **Solução:** Renomear o método para `defineTabs()` em `src/CompanyData.php`.
- **Impacto:** Crítico - Configurações de sistemas (IPBX/PABX) inacessíveis.

---

## 🚀 ROADMAP DE REFATORAÇÃO (v2.1.0 → v2.2.0)

Este roadmap foi concluído e resultou em uma melhoria significativa na qualidade e manutenibilidade do código.

### Métricas Gerais da Refatoração

| Métrica                        | Resultado          |
| ------------------------------ | ------------------ |
| **Arquivos Modificados**       | 14 arquivos        |
| **Linhas Reduzidas**           | ~650 linhas totais |
| **Type Hints Adicionados**     | 13 métodos         |
| **Guard Clauses Adicionadas**  | 20+ métodos        |
| **Endpoints AJAX Refatorados** | 7 endpoints        |
| **Conformidade PSR-12**        | 100%               |

### Fases da Refatoração

- **FASE 1: ✅ COMPLETA - Código Comum (AjaxHandler)**
  - Criado o arquivo `src/AjaxHandler.php` para centralizar a lógica de endpoints AJAX (respostas JSON, validação CSRF, requisições cURL).
  - **Impacto:** Redução de ~90 linhas de código duplicado.

- **FASE 2: ✅ COMPLETA - Validações (Common.php)**
  - Expandida a classe `src/Common.php` com métodos de validação (CEP, email, telefone, coordenadas) e consulta de APIs externas.
  - **Impacto:** Eliminação de duplicação e centralização de regras de negócio.

- **FASE 3: ✅ COMPLETA - Type Hints**
  - Adicionados type hints de parâmetros e retorno em 13 métodos críticos nas classes `Task`, `System`, `Address`, `CompanyData`, etc.
  - **Impacto:** Melhoria na robustez e auxílio para análise estática.

- **FASE 4: ✅ COMPLETA - Guard Clauses + PHPDoc**
  - Implementadas guard clauses para validações de entrada no início dos métodos e melhorada a documentação PHPDoc.
  - **Impacto:** Código mais limpo e legível.

- **FASE 5: ✅ COMPLETA - Refatoração AJAX**
  - Refatorados 7 arquivos na pasta `ajax/` para utilizar a nova `AjaxHandler`, resultando em uma redução total de 458 linhas (16.5%).

### Tabela de Redução de Código (Fase 5)

| Arquivo                     | Antes (Linhas) | Depois (Linhas) | Redução          |
| --------------------------- | -------------- | --------------- | ---------------- |
| `ajax/cnpj_proxy.php`       | 450            | 364             | -86 (19%)        |
| `ajax/searchAddress.php`    | 408            | 279             | -129 (32%)       |
| `ajax/searchCompany.php`    | 384            | 307             | -77 (20%)        |
| `ajax/signatureUpload.php`  | 368            | 324             | -44 (12%)        |
| `ajax/calculateMileage.php` | 321            | 261             | -60 (19%)        |
| `ajax/mapData.php`          | 482            | 448             | -34 (7%)         |
| `ajax/taskActions.php`      | 368            | 340             | -28 (8%)         |
| **TOTAL**                   | **2,781**      | **2,323**       | **-458 (16.5%)** |
