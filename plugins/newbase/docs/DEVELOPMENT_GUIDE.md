# Newbase Plugin - Guia de Desenvolvimento

## 📖 Padrões e Boas Práticas

---

## 1. ESTRUTURA DE ARQUIVOS

```
newbase/
├── src/                          # Classes principais (PSR-4 namespace)
│   ├── Common.php               # Classe base abstrata
│   ├── Address.php              # Gerenciamento de endereços
│   ├── CompanyData.php          # Dados de empresas
│   ├── Config.php               # Configurações
│   ├── Menu.php                 # Menu do plugin
│   ├── System.php               # Sistemas telefônicos
│   ├── Task.php                 # Tarefas com GPS
│   ├── TaskSignature.php        # Assinaturas
│   └── AddressHandler.php       # Handler de endereços
├── front/                        # Controllers (acesso via web)
│   ├── index.php                # Dashboard
│   ├── config.php               # Configuração
│   ├── address.form.php         # Formulário de endereço
│   ├── address.php              # Lista de endereços
│   ├── companydata.form.php     # Formulário de empresa
│   ├── companydata.php          # Lista de empresas
│   ├── system.form.php          # Formulário de sistema
│   ├── system.php               # Lista de sistemas
│   ├── task.form.php            # Formulário de tarefa
│   ├── task.php                 # Lista de tarefas
│   └── report.php               # Relatórios
├── ajax/                         # Endpoints AJAX
│   ├── cnpj_proxy.php           # Consulta CNPJ
│   ├── searchAddress.php        # Busca de endereço
│   ├── searchCompany.php        # Busca de empresa
│   ├── taskActions.php          # Ações de tarefa
│   ├── mapData.php              # Dados do mapa
│   ├── calculateMileage.php     # Cálculo de quilometragem
│   ├── signatureUpload.php      # Upload de assinatura
│   └── .php-cs-fixer.dist.php   # Linter config
├── css/                          # Estilos
│   ├── newbase.css
│   ├── forms.css
│   └── responsive.css
├── js/                           # Scripts
│   ├── newbase.js
│   ├── forms.js
│   ├── map.js
│   ├── signature.js
│   ├── mileage.js
│   ├── mobile.js
│   └── jquery.mask.min.js
├── locales/                      # Internacionalização
│   ├── pt_BR.po
│   └── pt_BR.mo
├── install/mysql/               # Scripts SQL
│   ├── 2.0.0.sql
│   └── 2.1.0.sql
├── docs/                         # Documentação
│   └── README-LEIA-ME.md
├── vendor/                       # Dependências Composer
├── setup.php                     # Plugin setup (OBRIGATÓRIO)
├── hook.php                      # Hooks de instalação (OBRIGATÓRIO)
├── composer.json                 # Metadados do projeto
├── VERSION                       # Versão atual
├── README.md                     # Documentação principal
├── CHANGELOG.md                  # Histórico de versões
└── REFACTORING_REPORT.md         # Relatório desta refatoração
```

---

## 2. NAMESPACE E AUTOLOADING

### Namespace:
```php
namespace GlpiPlugin\Newbase;
```

### Autoloading (composer.json):
```json
"autoload": {
    "psr-4": {
        "GlpiPlugin\\Newbase\\": "src/"
    }
}
```

### Como usar:
```php
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\CompanyData;

$task = new Task();
$company = new CompanyData();
```

---

## 3. CRIANDO NOVAS CLASSES

### Estrutura básica:
```php
<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;

/**
 * MyClass - Brief description
 * 
 * Detailed description of what this class does.
 * 
 * @package   GlpiPlugin\Newbase
 * @author    Your Name
 * @copyright 2026 Your Name
 * @license   GPLv2+
 * @version   2.1.0
 */
class MyClass extends CommonDBTM
{
    // Rights management
    public static string $rightname = 'plugin_newbase';

    // Enable history
    public bool $dohistory = true;

    /**
     * Get type name
     *
     * @param int $nb Number of items
     *
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Items', 'newbase') : __('Item', 'newbase');
    }

    /**
     * Get table name
     *
     * @param string|null $classname Class name
     *
     * @return string Table name
     */
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_myclasses';  // Note: plural with 's'
    }
}
```

### Type hints obrigatórios:
```php
// ❌ BAD
public function save($data) {
    return $this->add($data);
}

// ✅ GOOD
public function save(array $data): bool|int|false {
    return $this->add($data);
}
```

### Documentação PHPDoc:
```php
/**
 * Brief description (one line)
 *
 * Longer description explaining the method behavior,
 * parameters, and return value.
 *
 * @param string $name   Name parameter
 * @param int    $count  Count parameter
 * @param bool   $active Whether active
 *
 * @return array Result data
 * @throws Exception If something goes wrong
 */
public function doSomething(string $name, int $count, bool $active = true): array
{
    // Implementation
}
```

---

## 4. CRIANDO FORMULÁRIOS

### No Controller (front/myclass.form.php):
```php
<?php

declare(strict_types=1);

include '../../../inc/includes.php';

Session::checkLoginUser();
Session::checkRight('plugin_newbase', READ);

use GlpiPlugin\Newbase\MyClass;

$item = new MyClass();

// If ID provided, load item
if (isset($_GET['id'])) {
    $item->getFromDB((int)$_GET['id']);
    
    if (!$item->canViewItem()) {
        Session::addMessageAfterRedirect(
            __('You cannot view this item'),
            false,
            ERROR
        );
        Html::redirect($item->getSearchURL());
    }
}

// Handle form submission
if (isset($_POST['add']) || isset($_POST['update'])) {
    Session::checkCSRF($_POST);
    
    if (isset($_POST['add'])) {
        if (!$item->canCreate()) {
            Session::addMessageAfterRedirect(
                __('You cannot create this item'),
                false,
                ERROR
            );
            Html::back();
        }
        $item->add($_POST);
    } elseif (isset($_POST['update'])) {
        if (!$item->canUpdate()) {
            Session::addMessageAfterRedirect(
                __('You cannot update this item'),
                false,
                ERROR
            );
            Html::back();
        }
        $item->update($_POST);
    }
    
    Html::back();
}

// Display page
Html::header(
    MyClass::getTypeName(),
    $_SERVER['PHP_SELF'],
    'tools',
    'GlpiPlugin\Newbase\Menu'
);

$item->display(['id' => $_GET['id'] ?? 0]);

Html::footer();
```

---

## 5. ENDPOINTS AJAX

### Estrutura padrão:
```php
<?php

declare(strict_types=1);

include '../../../inc/includes.php';

Session::checkLoginUser();

// Validate method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'error' => 'POST only']));
}

// Validate CSRF
Session::checkCSRF($_POST);

// Check permissions
if (!Session::haveRight('plugin_newbase', READ)) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'error' => 'No permission']));
}

// Set headers
header('Content-Type: application/json; charset=utf-8');

try {
    // Validate input
    if (empty($_POST['id'])) {
        http_response_code(400);
        throw new Exception('ID is required');
    }

    $id = (int)$_POST['id'];

    // Do something
    $item = new MyClass();
    if (!$item->getFromDB($id)) {
        http_response_code(404);
        throw new Exception('Item not found');
    }

    // Return result
    echo json_encode([
        'success' => true,
        'data' => $item->fields,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);

    Toolbox::logInFile('newbase_error', $e->getMessage());
}
```

---

## 6. CONSULTAS AO BANCO DE DADOS

### ✅ CORRETO - Use API GLPI:
```php
global $DB;

// SELECT
$result = $DB->request([
    'FROM'  => 'glpi_plugin_newbase_myclasses',
    'WHERE' => [
        'is_deleted' => 0,
        'entities_id' => $_SESSION['glpiactive_entity'],
    ],
    'ORDER' => ['name' => 'ASC'],
    'LIMIT' => 10,
]);

foreach ($result as $row) {
    echo $row['id'];
}

// INSERT
$DB->insert('glpi_plugin_newbase_myclasses', [
    'name' => 'Test',
    'entities_id' => $_SESSION['glpiactive_entity'],
]);

// UPDATE
$DB->update('glpi_plugin_newbase_myclasses', [
    'name' => 'Updated',
], [
    'id' => 1,
]);

// DELETE
$DB->delete('glpi_plugin_newbase_myclasses', [
    'id' => 1,
]);
```

### ❌ NUNCA FAÇA:
```php
// SQL concatenation - SQL INJECTION RISK!
$query = "SELECT * FROM glpi_plugin_newbase_myclasses WHERE name = '{$_GET['name']}'";
$result = $DB->query($query);

// Direct mysqli - Lost GLPI abstraction
mysqli_query("SELECT ...");
```

---

## 7. VALIDAÇÃO DE INPUT

### ✅ SEMPRE VALIDAR:
```php
// Validar números
if (!is_numeric($_POST['count'])) {
    throw new Exception('Count must be numeric');
}
$count = (int)$_POST['count'];

// Validar email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email');
}

// Validar CNPJ (use método da classe)
if (!Common::validateCNPJ($_POST['cnpj'])) {
    throw new Exception('Invalid CNPJ');
}

// Sanitizar strings
$name = strip_tags($_POST['name']);
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
```

---

## 8. TRATAMENTO DE ERROS

### ✅ USE TRY-CATCH:
```php
try {
    // Fazer algo
    $item = new MyClass();
    $item->getFromDB(1);
    
} catch (Exception $e) {
    // Log o erro
    Toolbox::logInFile('newbase_error', $e->getMessage());
    
    // Mostrar mensagem ao usuário
    Session::addMessageAfterRedirect(
        __('An error occurred'),
        false,
        ERROR
    );
    
    // Voltar
    Html::back();
}
```

---

## 9. LOCALIZAÇÃO (i18n)

### No código:
```php
// Singular/Plural
echo __('Item', 'newbase');        // Uma item
echo __('Items', 'newbase');       // Múltiplas items

// Com parâmetros
echo sprintf(
    __('Found %d items', 'newbase'),
    $count
);

// Localização automática em pt_BR.po
```

### Extração de strings:
```bash
cd /path/to/newbase
xgettext -L PHP --keyword=__ --keyword=_n:1,2 \
    -o locales/pt_BR.pot \
    $(find . -name "*.php" -type f | grep -v vendor)
```

---

## 10. CHECKLIST ANTES DE COMMITAR

- [ ] Código segue PSR-12
- [ ] Todos os parâmetros têm type hints
- [ ] Todos os retornos têm type hints
- [ ] Documentação PHPDoc completa
- [ ] Sem SQL injection risks
- [ ] CSRF tokens validados
- [ ] Permissões verificadas
- [ ] Erros tratados com try-catch
- [ ] Logging implementado onde necessário
- [ ] Testes passando (se houver)
- [ ] Mensagens localizáveis com `__()`
- [ ] Sem comentários em português (manter inglês)

---

## 11. VERSIONAMENTO

### Versão: MAJOR.MINOR.PATCH
- **MAJOR**: Mudanças incompatíveis (ex: 2.0.0 → 3.0.0)
- **MINOR**: Novas features compatíveis (ex: 2.0.0 → 2.1.0)
- **PATCH**: Bug fixes (ex: 2.1.0 → 2.1.1)

### Arquivos a atualizar:
1. `VERSION` - Apenas número
2. `setup.php` - Constante `NEWBASE_VERSION`
3. `hook.php` - Comentário no topo
4. `composer.json` - Field `extra.glpi.version`
5. `CHANGELOG.md` - Adicionar entrada
6. `install/mysql/X.Y.Z.sql` - Criar novo se houver mudanças DB

---

## 12. COMANDOS ÚTEIS

### PHP CodeSniffer (PSR-12):
```bash
# Verificar
./vendor/bin/phpcs -p --standard=PSR12 src/ front/ ajax/

# Corrigir automaticamente
./vendor/bin/phpcbf -p --standard=PSR12 src/ front/ ajax/
```

### PHPStan (Análise estática):
```bash
./vendor/bin/phpstan analyse --level=5 src/ front/ ajax/
```

### Composer:
```bash
# Instalar dependências
composer install

# Atualizar
composer update

# Otimizar autoloader
composer dump-autoload --optimize
```

---

## 13. LINKS ÚTEIS

- [GLPI Developer Docs](https://glpi-developer-documentation.readthedocs.io/)
- [GLPI API](https://github.com/glpi-project/glpi/blob/master/apirest.md)
- [PHP PSR-12](https://www.php-fig.org/psr/psr-12/)
- [Brasil API](https://brasilapi.com.br/docs)
- [ReceitaWS](https://www.receitaws.com.br/v1/)

---

**Última atualização**: 3 de Fevereiro de 2026
**Versão**: 2.1.0
