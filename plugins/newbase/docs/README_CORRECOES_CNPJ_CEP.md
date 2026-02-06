# ✅ CORREÇÕES APLICADAS - Busca CNPJ/CEP

## 🎯 Resumo das Alterações

### Arquivos Modificados:
1. ✅ `front/companydata.form.php` - Adicionada meta tag CSRF
2. ✅ `js/newbase.js` - Corrigida captura e envio do token CSRF
3. ✅ `tools/verificar_mysql.ps1` - Novo script de diagnóstico MySQL
4. ✅ `tools/test_ajax_endpoints.php` - Novo script de teste API
5. ✅ `docs/GUIA_CORRECAO_CNPJ_CEP.md` - Guia completo de testes

---

## 🚀 PRÓXIMOS PASSOS (FAÇA AGORA!)

### 1️⃣ Verificar MySQL
```powershell
cd D:\laragon\www\glpi\plugins\newbase\tools
.\verificar_mysql.ps1
```

**Se MySQL não estiver rodando:**
- Abra Laragon
- Clique "Start All"
- Aguarde ícone verde

---

### 2️⃣ Limpar Cache do Navegador
**Chrome/Edge:** F12 → Botão direito em Reload → "Limpar cache e recarregar forçado"  
**Firefox:** Ctrl+Shift+Delete → Marcar "Cache" → Limpar

---

### 3️⃣ Testar no Navegador

1. **Acesse:**
   ```
   http://glpi.test/plugins/newbase/front/companydata.form.php
   ```

2. **Teste CNPJ:**
   - Digite: `00.000.000/0001-91`
   - Clique na lupa 🔍
   - **Esperado:** Campos preenchidos automaticamente

3. **Teste CEP:**
   - Digite: `01310-100`
   - Clique na lupa 🔍
   - **Esperado:** Endereço, cidade e estado preenchidos

4. **Verifique o Console (F12):**
   - ✅ **Deve aparecer:** `"Newbase: CSRF Token configurado globalmente"`
   - ❌ **NÃO deve aparecer:** `"CSRF token not found"`

---

## 🐛 Troubleshooting

### Erro: "CSRF token not found"
**Solução:**
1. Verificar se linha 304 do `companydata.form.php` tem a meta tag
2. Recarregar página com cache limpo (Ctrl+F5)

### Erro: HTTP 403 Forbidden
**Solução:**
1. Verificar no DevTools → Network → searchCompany.php → Payload
2. Confirmar que `_glpi_csrf_token` está sendo enviado

### Erro: MySQL Connection
**Solução:**
1. Execute `verificar_mysql.ps1`
2. Inicie Laragon se necessário

---

## 📊 Como Verificar se Funcionou

### ✅ SUCESSO - Você deve ver:

**No Console (F12):**
```
Newbase Plugin initializing...
Newbase: CSRF Token configurado globalmente.
Newbase Plugin initialized in XX.XXms
```

**Na Tela:**
- Notificação verde de sucesso
- Campos preenchidos automaticamente

**No Network (F12 → Network):**
- Status: `200 OK`
- Response: `{"success": true, "data": {...}}`

---

## 📁 Estrutura de Arquivos Alterados

```
plugins/newbase/
├── front/
│   └── companydata.form.php     [MODIFICADO] ← Meta tag CSRF
├── js/
│   └── newbase.js               [MODIFICADO] ← Captura token CSRF
├── tools/
│   ├── verificar_mysql.ps1      [NOVO]      ← Diagnóstico MySQL
│   └── test_ajax_endpoints.php  [NOVO]      ← Teste APIs
└── docs/
    └── GUIA_CORRECAO_CNPJ_CEP.md [NOVO]      ← Guia completo
```

---

## 🎓 O que foi corrigido

### Problema 1: Meta tag CSRF ausente
**Antes:**
```php
Html::getCoreVariablesForJavascript();
// Faltava a meta tag aqui!
```

**Depois:**
```php
Html::getCoreVariablesForJavascript();
echo "<meta name='glpi:csrf_token' content='" . Session::getNewCSRFToken() . "'>\n";
```

### Problema 2: Token não enviado no AJAX
**Antes:**
```javascript
data: { cnpj: cnpj }
```

**Depois:**
```javascript
const csrfToken = $('meta[name="glpi:csrf_token"]').attr('content');
data: { 
    cnpj: cnpj,
    _glpi_csrf_token: csrfToken
}
```

### Problema 3: MySQL não verificado
**Solução:** Script PowerShell `verificar_mysql.ps1` criado

---

## 📞 Precisa de Ajuda?

**Colete estas informações:**
1. Saída do `verificar_mysql.ps1`
2. Console do navegador (screenshot)
3. Network tab (request/response da busca CNPJ)
4. Últimas 20 linhas de:
   - `D:\laragon\www\glpi\files\_log\newbase_plugin.log`
   - `D:\laragon\www\glpi\files\_log\php-errors.log`

---

## ✨ Teste Rápido de 2 Minutos

```powershell
# 1. Verificar MySQL
.\tools\verificar_mysql.ps1

# 2. Abrir navegador
start http://glpi.test/plugins/newbase/front/companydata.form.php

# 3. Testar CNPJ
# Digite: 00.000.000/0001-91
# Clique na lupa

# 4. Verificar Console (F12)
# Deve mostrar: "CSRF Token configurado globalmente"
```

---

**Data:** 06/02/2026  
**Versão Plugin:** Newbase 2.1.0  
**GLPI:** 10.0.20  
**PHP:** 8.3.26  
**MySQL:** 8.4.6
