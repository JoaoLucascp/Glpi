# Correção do Erro de CSRF Token - Plugin Newbase

## 📋 Problema Identificado

O erro `ArgumentCountError: Too few arguments to function Session::checkCSRF()` ocorria porque o método `Session::checkCSRF()` no GLPI 10.0.20 exige obrigatoriamente um argumento (array contendo o token CSRF), mas o código estava chamando o método sem argumentos.

### Mensagens de Erro Original:
```
An error occurred. Please try again.
ArgumentCountError: Too few arguments to function Session::checkCSRF(), 0 passed in D:\laragon\www\glpi\plugins\newbase\ajax\searchCompany.php on line 53
```

---

## ✅ Correções Aplicadas

### 1. **Arquivo: `ajax/searchCompany.php`**

**Linhas corrigidas:** 52 e 79

**Antes:**
```php
// VERIFICAR TOKEN CSRF
Session::checkCSRF();
```

**Depois:**
```php
// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);
```

---

### 2. **Arquivo: `ajax/searchAddress.php`**

**Linhas corrigidas:** 53 e 79

**Antes:**
```php
// VERIFICAR TOKEN CSRF
Session::checkCSRF();
```

**Depois:**
```php
// VERIFICAR TOKEN CSRF
Session::checkCSRF($_POST);
```

---

### 3. **Arquivo: `js/newbase.js`**

**Linhas corrigidas:** 39-49

**Melhoria implementada:**

1. ✅ Validação se o token existe antes de configurar o AJAX
2. ✅ Envio do token tanto no **header** quanto no **data** (compatibilidade)
3. ✅ Log de aviso caso o token não seja encontrado

**Antes:**
```javascript
$(function() {
    var glpi_csrf_token = $('meta[name="glpi:csrf_token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-Glpi-Csrf-Token': glpi_csrf_token
        }
    });
    console.log('Newbase: CSRF Token configurado globalmente.');
});
```

**Depois:**
```javascript
$(function() {
    var glpi_csrf_token = $('meta[name="glpi:csrf_token"]').attr('content');
    
    if (!glpi_csrf_token) {
        console.warn('Newbase: CSRF token not found in meta tags');
        return;
    }
    
    $.ajaxSetup({
        headers: {
            'X-Glpi-Csrf-Token': glpi_csrf_token
        },
        data: {
            '_glpi_csrf_token': glpi_csrf_token
        }
    });
    console.log('Newbase: CSRF Token configurado globalmente.');
});
```

---

## 🔍 Verificações Realizadas

### ✅ Arquivo `front/companydata.form.php`
- **Linha 309:** Confirmado uso correto de `Html::header()`
- O cabeçalho do GLPI injeta automaticamente as meta tags necessárias, incluindo o token CSRF

---

## 🧪 Como Testar

### 1. **Limpar Cache do Navegador**
```
Ctrl + Shift + Delete
```

### 2. **Acessar o Formulário**
```
http://glpi.test/public/plugins/newbase/front/companydata.form.php
```

### 3. **Testar Busca por CNPJ**
1. Digite um CNPJ válido (ex: `11507196000121`)
2. Clique no botão de busca (🔍)
3. Verifique se os campos são preenchidos automaticamente
4. **NÃO** deve aparecer erro "An error occurred. Please try again"

### 4. **Testar Busca por CEP**
1. Digite um CEP válido (ex: `29903200`)
2. Clique no botão de busca (🔍)
3. Verifique se o endereço é preenchido
4. **NÃO** deve aparecer erro

### 5. **Verificar Console do Navegador**
Abra o console (F12) e procure por:
```
✅ Newbase: CSRF Token configurado globalmente.
```

**NÃO** deve aparecer:
```
❌ Newbase: CSRF token not found in meta tags
```

---

## 📊 Arquivos Modificados

| Arquivo | Linhas | Descrição |
|---------|--------|-----------|
| `ajax/searchCompany.php` | 52, 79 | Adicionado `$_POST` ao `Session::checkCSRF()` |
| `ajax/searchAddress.php` | 53, 79 | Adicionado `$_POST` ao `Session::checkCSRF()` |
| `js/newbase.js` | 39-49 | Melhorado tratamento do token CSRF no AJAX |

---

## 🛡️ Segurança

Todas as alterações mantêm a segurança do plugin:

- ✅ **CSRF Protection:** Token validado em todas as requisições POST
- ✅ **XSS Protection:** Sanitização de dados mantida
- ✅ **Validação de Entrada:** Todas as validações originais preservadas
- ✅ **Compatibilidade GLPI:** 100% compatível com GLPI 10.0.20+

---

## 📝 Notas Importantes

1. **Não modificamos código do GLPI Core** - Apenas arquivos do plugin
2. **Compatibilidade:** GLPI 10.0.20+ (método `Session::checkCSRF()` com argumento obrigatório)
3. **Backward Compatibility:** O código anterior funcionava em versões antigas do GLPI que não exigiam o argumento

---

## 🎯 Resultado Esperado

Após aplicar as correções, o formulário de cadastro de empresas deve:

1. ✅ Carregar sem erros
2. ✅ Buscar CNPJ com sucesso
3. ✅ Buscar CEP com sucesso
4. ✅ Salvar dados sem problemas
5. ✅ Validar CSRF em todas as requisições

---

## 📞 Suporte

Se o erro persistir após estas correções:

1. Verifique se o arquivo foi salvo corretamente
2. Limpe o cache do navegador (Ctrl + Shift + Delete)
3. Reinicie o Apache/Laragon
4. Verifique os logs em:
   - `files/_log/php-errors.log`
   - Console do navegador (F12)

---

**Data da Correção:** 2026-02-06  
**Versão do Plugin:** 2.1.0  
**Versão do GLPI:** 10.0.20  
**Autor:** Claude (Anthropic AI)
