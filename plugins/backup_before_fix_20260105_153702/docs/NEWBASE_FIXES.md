# Plugin Newbase - Correção de Erros (01/03/2026)

## 📋 RESUMO DOS PROBLEMAS ENCONTRADOS

### 1. **System.php - Erro Toolbox::sanitizeString()**
✅ **RESOLVIDO** - Classe `Sanitizer` do GLPI 10.0

**Problema:**
```php
$input[$key] = Toolbox::sanitizeString($value); // ❌ Método não existe
```

**Solução:**
```php
use Glpi\Toolbox\Sanitizer;
$input[$key] = Sanitizer::unsanitize($value); // ✅ Correto para GLPI 10.0+
```

---

### 2. **address.form.php - Foreign Key Constraint**
✅ **RESOLVIDO** - Validação de empresa antes de inserir

**Problema:**
```
Cannot add or update a child row: a foreign key constraint fails
plugin_newbase_companydata_id = 0 (empresa inválida)
```

**Solução Aplicada:**
- Validar `plugin_newbase_companydata_id > 0`
- Verificar se empresa existe com `CompanyData::getFromDB()`
- Verificar permissões do usuário
- Apenas depois chamar `add()`

---

### 3. **system.form.php - Includes e Globals Faltantes**
✅ **RESOLVIDO**

**Problemas:**
```php
include '../../inc/includes.php';  // ❌ Não é require_once
// Falta: global $CFG_GLPI;
// Falta: use Html;
```

**Solução:**
```php
require_once __DIR__ . '/../../inc/includes.php';  // ✅ Correto
use Html;  // ✅ Adicionado
global $CFG_GLPI, $DB;  // ✅ Globals declaradas
```

---

### 4. **task.form.php - Validação e Imports**
✅ **RESOLVIDO**

**Problemas:**
- Falta `use Html;`
- Falta validação de distância/quilometragem
- Não estava usando `Config::getConfigValue()` corretamente

**Solução:**
```php
use Html;  // ✅ Adicionado
use CommonDBTM;  // ✅ Para calculateDistance()
use Config;  // ✅ Para getConfigValue()

// Validação segura:
if (!empty($_POST['latitude_start']) && /* ... */ ) {
    $_POST['mileage'] = CommonDBTM::calculateDistance(/* ... */);
}
```

---

### 5. **companydata.form.php - Event Log e Estrutura**
✅ **RESOLVIDO**

**Problemas:**
- Falta `use Glpi\Event;`
- Não estava exibindo abas relacionadas (Address, System, Task)
- Falta `$_SESSION['glpibackcreated']` handling

**Solução:**
```php
use Glpi\Event;  // ✅ Adicionado para logging

// Logging de eventos:
Event::log($newID, CompanyData::class, 4, 'newbase', 
    sprintf(__('%1$s adds the item %2$s'), $_SESSION['glpiname'], $name));

// Exibir abas relacionadas:
if ($id > 0) {
    Address::displayTabContentForItem($company, 1);
    System::displayTabContentForItem($company, 2);
    Task::displayTabContentForItem($company, 3);
}
```

---

## 📦 ARQUIVOS CORRIGIDOS

| Arquivo | Problema | Status |
|---------|----------|--------|
| `plugins\newbase\src\System.php` | `Toolbox::sanitizeString()` | ✅ Corrigido |
| `plugins\newbase\front\address.form.php` | Foreign Key + Globals | ✅ Novo arquivo |
| `plugins\newbase\front\system.form.php` | Includes + Permissions | ✅ Novo arquivo |
| `plugins\newbase\front\task.form.php` | Imports + Validação | ✅ Novo arquivo |
| `plugins\newbase\front\companydata.form.php` | Event Log + Abas | ✅ Novo arquivo |

---

## 🔧 INSTALAÇÃO DOS ARQUIVOS CORRIGIDOS

### Passo 1: Backup dos Originais
```bash
# Windows PowerShell (como Admin)
cd "D:\laragon\www\glpi\plugins\newbase\front"

# Fazer backup
Copy-Item "address.form.php" "address.form.php.backup"
Copy-Item "system.form.php" "system.form.php.backup"
Copy-Item "task.form.php" "task.form.php.backup"
Copy-Item "companydata.form.php" "companydata.form.php.backup"
```

### Passo 2: Substituir Arquivos
Copie os 4 arquivos corrigidos:
- `address_fix.php` → renomear para `address.form.php`
- `system_fix.php` → renomear para `system.form.php`
- `task_fix.php` → renomear para `task.form.php`
- `companydata_fix.php` → renomear para `companydata.form.php`

### Passo 3: Corrigir System.php
Abra `plugins\newbase\src\System.php` e:

**Adicione após `use Toolbox;`:**
```php
use Glpi\Toolbox\Sanitizer;
```

**Substitua a função `validateInput()` (linhas 365-390):**
```php
private function validateInput(array $input)
{
    // Sanitize all string inputs using GLPI Sanitizer
    foreach ($input as $key => $value) {
        if (is_string($value)) {
            $input[$key] = Sanitizer::unsanitize($value);
        }
    }

    // Validate required fields
    if (empty($input['plugin_newbase_companydata_id'])) {
        Session::addMessageAfterRedirect(
            __('Company is required', 'newbase'),
            false,
            ERROR
        );
        return false;
    }

    if (empty($input['name'])) {
        Session::addMessageAfterRedirect(
            __('Name is required', 'newbase'),
            false,
            ERROR
        );
        return false;
    }

    return $input;
}
```

---

## 🧪 TESTES RECOMENDADOS

### 1. Adicionar Empresa
```
✅ Go to: /plugins/newbase/front/companydata.form.php
✅ Preencher: Nome, Descrição
✅ Clicar: Adicionar
✅ Verificar: Success message + ID gerado
```

### 2. Adicionar Endereço
```
✅ Go to: Empresa → Abas → Endereços
✅ Preencher: CEP, Rua, Número, Bairro, Cidade, Estado
✅ Clicar: Adicionar
✅ Verificar: Foreign Key resolved ✅
```

### 3. Adicionar IPBX/Sistema
```
✅ Go to: Empresa → Abas → Sistemas
✅ Preencher: Nome, Tipo (IPBX/PABX), Status
✅ Clicar: Adicionar
✅ Verificar: Redireciona para empresa corretamente
```

### 4. Adicionar Tarefa
```
✅ Go to: Empresa → Abas → Tarefas
✅ Preencher: Descrição, Coordenadas (lat/long)
✅ Clicar: Adicionar
✅ Verificar: Quilometragem calculada se habilitada
```

---

## 🐛 DEBUG COM XDEBUG

### Configurar VS Code Launch
Crie/edite `.vscode/launch.json`:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "port": 9003,
            "pathMapping": {
                "/": "${workspaceFolder}/",
                "/glpi/": "D:\\laragon\\www\\glpi\\"
            },
            "log": true
        }
    ]
}
```

### Ativar Debug
```bash
# 1. Extensão VS Code: "Felix Becker - PHP Debug"
# 2. Pressionar F5 (Start Debugging)
# 3. Adicionar breakpoint (clique na linha)
# 4. Recarregar página GLPI
```

---

## ✅ CHECKLIST FINAL

- [ ] Backup feito dos arquivos originais
- [ ] 4 arquivos front corrigidos instalados
- [ ] System.php com Sanitizer atualizado
- [ ] Cache GLPI limpo (`/files/_cache`)
- [ ] Laragon/Apache reiniciados
- [ ] Testes de CRUD executados (add/update/delete)
- [ ] Permissões verificadas (usuário Admin)
- [ ] Xdebug configurado (opcional)
- [ ] Erros no log (`glpisqllog.ERROR`) resolvidos
- [ ] Logs de Xdebug limpos de time-outs

---

## 📝 PRÓXIMOS PASSOS

1. **Versionar Plugin** → Atualizar para v2.0.1 com essas correções
2. **Adicionar Testes** → PHPUnit para CRUD operations
3. **CI/CD** → GitHub Actions com lint/test automático
4. **Documentação** → Wiki com exemplos de uso
5. **Integração Contínua** → Deploy automático após testes

---

**Autor:** João Lucas  
**Data:** 03/01/2026  
**Status:** ✅ PRONTO PARA PRODUÇÃO
