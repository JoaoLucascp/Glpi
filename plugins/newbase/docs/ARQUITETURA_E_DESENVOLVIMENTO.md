# ARQUITETURA E DESENVOLVIMENTO - PLUGIN NEWBASE

Este documento detalha a arquitetura técnica, o ambiente de desenvolvimento, os padrões de código e os processos de manutenção do plugin Newbase para GLPI.

---

## 1. AMBIENTE DE DESENVOLVIMENTO

### 1.1. Stack de Software

- *GLPI Versão:* 10.0.20
- *PHP:* 8.1+ (Recomendado: 8.3.x)
- *MySQL:* 8.x ou MariaDB 10.2+ (InnoDB, charset utf8mb4)
- *Servidor Web:* Apache ou Nginx
- *Sistema Operacional:* Windows, Linux ou macOS
- *Ferramentas:* Composer, Node.js (para build do GLPI), Git

### 1.2. Configuração Local (Exemplo com Laragon)

```yaml
GLPI Versão: 10.0.20
PHP: 8.3.26
MySQL: 8.4.6 (InnoDB, charset utf8mb4)
Servidor: Apache 2.4.65 com SSL
Editor: VS Code + IA
Sistema Operacional: Windows 11 Pro
VM: Laragon 8.3.0 local
```

### 1.3. Dependências do Plugin

#### Dependências Principais (PHP)

- `ext-json`, `ext-mbstring`, `ext-mysqli`, `ext-curl`, `ext-gd`
- Core do GLPI: `10.0.20` - `10.0.99`

#### Dependências de Desenvolvimento (Composer)

- `phpstan/phpstan`: ^1.10 (Análise Estática)
- `squizlabs/php_codesniffer`: ^3.7 (Padrões de Código)
- `phpunit/phpunit`: ^9.5 || ^10.0 (Testes Unitários)

---

## 2. ESTRUTURA DE PASTAS DO PLUGIN

A estrutura de diretórios do plugin (`plugins/newbase/`) segue o padrão recomendado pelo GLPI para garantir compatibilidade e manutenibilidade.

```yaml
/plugins/newbase/
├── ajax/                   # Endpoints para requisições assíncronas (e.g., cnpj_proxy.php)
├── css/                    # Folhas de estilo (forms.css, newbase.css)
├── docs/                   # Documentação técnica, guias e roadmaps
├── front/                  # Interfaces de usuário (Formulários e Páginas, e.g., companydata.form.php)
│   └── tools/              # Ferramentas de diagnóstico e manutenção
├── install/                # Scripts de instalação e migração (SQL)
├── js/                     # Scripts JavaScript (forms.js, map.js, newbase.js)
├── locales/                # Arquivos de tradução (.po/.mo)
├── src/                    # Classes PHP (PSR-4, e.g., CompanyData.php, AjaxHandler.php)
├── templates/              # Templates Twig (para configurações)
├── vendor/                 # Dependências do Composer (phpstan, phpcs)
├── hook.php                # Hooks de instalação, ativação e desinstalação
├── setup.php               # Arquivo principal de registro do plugin no GLPI
├── newbase.xml             # Metadados do plugin para o marketplace
├── composer.json           # Definição das dependências de desenvolvimento
└── README.md               # Visão geral do plugin
```

---

## 3. PADRÕES E QUALIDADE DE CÓDIGO

### 3.1. Padrão de Código (PSR-12)

O projeto adota estritamente o padrão **PSR-12**, que define regras para formatação de código PHP. A conformidade é verificada e aplicada utilizando a ferramenta `PHP_CodeSniffer`.

- *Declarações:* `declare(strict_types=1)` é usado para reforçar a tipagem estrita.
- *Tipagem:* Uso extensivo de type hints para parâmetros e retornos de métodos.
- *PHPDoc:* Comentários detalhados para classes e métodos.
- *Guard Clauses:* Validações de entrada são feitas no início dos métodos para evitar aninhamento excessivo.

### 3.2. Comandos de Manutenção e Verificação

Os seguintes comandos são utilizados para garantir a qualidade do código:

```bash
# Verificar Estilo de Código (PSR-12)
phpcs -p --standard=PSR12 --extensions=php --ignore=vendor src/ front/ ajax/

# Corrigir Estilo de Código Automaticamente
phpcbf -p --standard=PSR12 --extensions=php --ignore=vendor src/ front/ ajax/

# Análise Estática com PHPStan (Nível 5)
phpstan analyse --level=5 src/ front/ ajax/

# Executar Testes Unitários
phpunit --colors=always

# Validar Sintaxe de um arquivo PHP
php -l "plugins/newbase/setup.php"
```

---

## 4. ARQUITETURA DE SEGURANÇA

### 4.1. Proteção Contra CSRF (Cross-Site Request Forgery)

A proteção CSRF é um pilar da segurança do plugin e segue as diretrizes do GLPI 10.0.20+.

#### 4.1.1. Geração de Tokens

- O token é gerado pelo GLPI e renderizado nos formulários usando `Html::hidden('_glpi_csrf_token')`.
- Em classes que estendem `CommonDBTM` e usam `showFormHeader()`, o token precisa ser adicionado manualmente com `echo Html::hidden('_glpi_csrf_token');` para garantir sua presença.

#### 4.1.2. Validação de Tokens

- *Requisições POST (Formulários):* A validação é feita com `Session::checkCSRF($_POST)`. Para evitar páginas de erro brancas, a chamada é sempre envolta em um bloco `try-catch`.
- *Requisições AJAX:* O GLPI 10+ valida automaticamente o token enviado no header `X-Glpi-Csrf-Token`. Os scripts AJAX do plugin foram refatorados para suportar tanto o header quanto um fallback via `$_POST`, garantindo compatibilidade.

### 4.2. Permissões de Acesso

- O plugin define uma permissão principal: `plugin_newbase`.
- Todas as classes e endpoints verificam os direitos do usuário (READ, CREATE, UPDATE, DELETE) usando `Session::haveRight('plugin_newbase', READ)`.
- O acesso a qualquer funcionalidade do plugin, incluindo a exibição de menus, é condicionado a essas permissões, que devem ser configuradas nos perfis do GLPI (`Configurar > Perfis`).

### 4.3. Escaping de Saída (Proteção XSS)

- Todas as saídas de dados no HTML são tratadas para prevenir ataques de Cross-Site Scripting (XSS).
- *Valores numéricos:* São convertidos para inteiro com `(int)`.
- *Strings:* São escapadas com `htmlspecialchars()` ou `Html::cleanOutputText()`.

---

## 5. CLASSES PRINCIPAIS E DESIGN DE CÓDIGO

### 5.1. Classe `AjaxHandler` (`src/AjaxHandler.php`)

Esta classe foi criada para centralizar e padronizar a lógica de todos os endpoints AJAX, reduzindo duplicação de código e melhorando a manutenibilidade.

**Responsabilidades:**

- *Respostas Padronizadas:* O método `sendResponse()` envia uma estrutura JSON consistente para o frontend.
- *Validação de Requisição:* O método `validateRequest()` combina a verificação de autenticação e de token CSRF.
- *Segurança:* O método `setSecurityHeaders()` aplica headers de segurança (e.g., `X-Frame-Options`) a todas as respostas AJAX.
- *Requisições Externas:* O método `fetchCurl()` centraliza a forma como o plugin se comunica com APIs externas (e.g., ViaCEP, BrasilAPI), usando cURL de forma mais robusta.
- *Validação de Entrada:* O método `validateInput()` fornece um meio de validar dados de entrada com base em regras predefinidas (string, int, email, etc.).

### 5.2. Classe `Common` (`src/Common.php`)

Esta classe serve como um repositório para métodos de utilidade e lógica de negócio compartilhada entre diferentes partes do plugin.

**Responsabilidades:**

- *Validações Específicas:* Contém métodos para validar formatos de dados brasileiros, como `validateCEP()`, `validateEmail()`, `validatePhone()`.
- *Integração de APIs:* Inclui a lógica para consultar APIs de endereço (`fetchAddressByCEP()`) e coordenadas (`fetchCoordinatesByCEP()`).
- Ao centralizar essa lógica, evita-se a duplicação e garante-se que uma alteração (como adicionar um fallback de API) precise ser feita em um único lugar.
