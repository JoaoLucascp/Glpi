# Guia de Aplicação de Traduções no Código

## 📋 Exemplos Práticos de Uso

### 1. Menu Principal (src/Menu.php)

**ANTES:**
```php
$menu['newbase']['title'] = 'Newbase - Company Management';
```

**DEPOIS:**
```php
$menu['newbase']['title'] = __('Newbase - Company Management', 'newbase');
```

---

### 2. Dashboard (front/index.php)

**ANTES:**
```php
<h3>Total Companies</h3>
<h3>Phone Systems</h3>
<h3>Pending Tasks</h3>
```

**DEPOIS:**
```php
<h3><?php echo __('Total Companies', 'newbase'); ?></h3>
<h3><?php echo __('Phone Systems', 'newbase'); ?></h3>
<h3><?php echo __('Pending Tasks', 'newbase'); ?></h3>
```

---

### 3. Formulários (front/companydata.form.php)

**ANTES:**
```php
echo "<label>Name:</label>";
echo "<input type='text' name='name' placeholder='Company Name'>";
```

**DEPOIS:**
```php
echo "<label>" . __('Name', 'newbase') . ":</label>";
echo "<input type='text' name='name' placeholder='" . __('Name', 'newbase') . "'>";
```

---

### 4. Botões (em qualquer formulário)

**ANTES:**
```php
<button type="submit">Save</button>
<button type="button">Cancel</button>
<button type="button">Search</button>
```

**DEPOIS:**
```php
<button type="submit"><?php echo __('Save', 'newbase'); ?></button>
<button type="button"><?php echo __('Cancel', 'newbase'); ?></button>
<button type="button"><?php echo __('Search', 'newbase'); ?></button>
```

---

### 5. Mensagens de Erro/Sucesso (ajax/*)

**ANTES:**
```php
echo json_encode(['error' => 'Company not found']);
echo json_encode(['success' => 'Data loaded successfully!']);
```

**DEPOIS:**
```php
echo json_encode(['error' => __('Company not found', 'newbase')]);
echo json_encode(['success' => __('Data loaded successfully!', 'newbase')]);
```

---

### 6. Campos de Formulário com Plural (src/CompanyData.php)

**ANTES:**
```php
public static function getTypeName($nb = 0) {
    return $nb > 1 ? 'Companies' : 'Company';
}
```

**DEPOIS:**
```php
public static function getTypeName($nb = 0) {
    return _n('Company', 'Companies', $nb, 'newbase');
}
```

---

### 7. JavaScript com Traduções (js/newbase.js)

**No HTML/PHP:**
```php
<script>
var i18n = {
    save: '<?php echo __('Save', 'newbase'); ?>',
    cancel: '<?php echo __('Cancel', 'newbase'); ?>',
    loading: '<?php echo __('Loading...', 'newbase'); ?>',
    error: '<?php echo __('Error', 'newbase'); ?>',
    success: '<?php echo __('Success', 'newbase'); ?>'
};
</script>
<script src="../js/newbase.js"></script>
```

**No JavaScript:**
```javascript
// Usar as traduções
alert(i18n.loading);
console.log(i18n.success);
button.textContent = i18n.save;
```

---

### 8. Tabelas de Busca (src/CompanyData.php)

**ANTES:**
```php
$tab[] = [
    'id'   => '1',
    'name' => 'Name',
];
```

**DEPOIS:**
```php
$tab[] = [
    'id'   => '1',
    'name' => __('Name', 'newbase'),
];
```

---

### 9. Validações e Alertas (ajax/searchAddress.php)

**ANTES:**
```php
if (empty($cep)) {
    echo json_encode(['error' => 'ZIP Code is required']);
}
```

**DEPOIS:**
```php
if (empty($cep)) {
    echo json_encode(['error' => __('ZIP Code', 'newbase') . ' ' . __('Required field', 'newbase')]);
}
```

---

### 10. Configurações (src/Config.php)

**ANTES:**
```php
$config = [
    'enable_geolocation' => 'Enable Geolocation',
    'enable_signature' => 'Enable Signature'
];
```

**DEPOIS:**
```php
$config = [
    'enable_geolocation' => __('Enable Geolocation', 'newbase'),
    'enable_signature' => __('Enable Signature', 'newbase')
];
```

---

## 🎯 Funções de Tradução Disponíveis

### `__($string, $domain)`
Traduz uma string simples.
```php
echo __('Name', 'newbase');
// PT: "Nome"
// EN: "Name"
```

### `_n($singular, $plural, $count, $domain)`
Traduz com plural automático.
```php
echo _n('company', 'companies', $count, 'newbase');
// $count = 1: "empresa" / "company"
// $count > 1: "empresas" / "companies"
```

### `_x($context, $string, $domain)`
Traduz com contexto (quando mesma palavra tem significados diferentes).
```php
echo _x('verb', 'Save', 'newbase');     // Salvar (verbo)
echo _x('noun', 'Save', 'newbase');     // Salvamento (substantivo)
```

### `sprintf(__($string, $domain), ...)`
Traduz com variáveis.
```php
echo sprintf(__('Found %d companies', 'newbase'), $total);
// PT: "Encontradas 5 empresas"
// EN: "Found 5 companies"
```

---

## 📂 Arquivos que Precisam de Tradução

### Prioridade Alta (Interface do Usuário)
- ✅ `front/index.php` - Dashboard
- ✅ `front/companydata.php` - Lista de empresas
- ✅ `front/companydata.form.php` - Formulário de empresa
- ✅ `front/system.php` - Lista de sistemas
- ✅ `front/system.form.php` - Formulário de sistema
- ✅ `front/task.php` - Lista de tarefas
- ✅ `front/task.form.php` - Formulário de tarefa
- ✅ `src/Menu.php` - Menu do plugin

### Prioridade Média (Classes)
- ✅ `src/CompanyData.php` - getTypeName(), getSearchOptions()
- ✅ `src/System.php` - getTypeName(), getSearchOptions()
- ✅ `src/Task.php` - getTypeName(), getSearchOptions()
- ✅ `src/Config.php` - Configurações

### Prioridade Baixa (AJAX)
- ✅ `ajax/searchCompany.php` - Mensagens de erro/sucesso
- ✅ `ajax/searchAddress.php` - Mensagens de erro/sucesso
- ✅ `ajax/taskActions.php` - Mensagens de erro/sucesso
- ✅ `ajax/signatureUpload.php` - Mensagens de erro/sucesso

---

## 🔄 Processo de Tradução Completo

1. **Adicionar tradução nos arquivos .po**
   ```
   msgid "New feature"
   msgstr "Novo recurso"  # pt_BR
   
   msgid "New feature"
   msgstr "New feature"   # en_GB
   ```

2. **Compilar**
   ```bash
   php compile_locales.php
   ```

3. **Usar no código**
   ```php
   echo __('New feature', 'newbase');
   ```

4. **Testar**
   - Mudar idioma do usuário no GLPI
   - Recarregar página
   - Verificar tradução

---

## ✨ Dicas Importantes

1. **SEMPRE use o segundo parâmetro 'newbase'**
   ```php
   __('Text', 'newbase')  // ✅ CERTO
   __('Text')             // ❌ ERRADO
   ```

2. **Não traduza nomes de classes ou variáveis**
   ```php
   'GlpiPlugin\Newbase\CompanyData'  // ✅ NÃO traduzir
   __('Company Data', 'newbase')      // ✅ Traduzir apenas o texto visível
   ```

3. **Mantenha consistência**
   - Use sempre a mesma tradução para o mesmo termo
   - Ex: "Save" sempre como "Salvar", não "Gravar"

4. **Textos dinâmicos**
   ```php
   // ❌ ERRADO
   __("Total: $count companies", 'newbase');
   
   // ✅ CERTO
   sprintf(__('Total: %d companies', 'newbase'), $count);
   ```

---

## 🧪 Como Testar

1. **No GLPI, vá em:**
   - Meu perfil (canto superior direito)
   - Personalização
   - Idioma > Escolha "English (United Kingdom)" ou "Português (Brasil)"

2. **Navegue pelo plugin e verifique:**
   - Menus traduzidos?
   - Botões traduzidos?
   - Mensagens traduzidas?
   - Formulários traduzidos?

3. **Teste casos especiais:**
   - Plurais (1 empresa vs 2 empresas)
   - Mensagens com variáveis
   - Erros do sistema

---

Próximo passo: Quer que eu aplique as traduções em algum arquivo específico?
