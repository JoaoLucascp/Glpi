# 🔧 GUIA DE CORREÇÃO E TESTES - Plugin Newbase
## Correções Aplicadas para Busca CNPJ/CEP

---

## 📋 PROBLEMAS CORRIGIDOS

### ✅ 1. CSRF Token Meta Tag Ausente
**Problema:** JavaScript não encontrava `<meta name="glpi:csrf_token">`  
**Solução:** Adicionada meta tag no `companydata.form.php` linha 304

### ✅ 2. Token CSRF não enviado no AJAX
**Problema:** Requests AJAX sem token CSRF → HTTP 403  
**Solução:** JavaScript agora captura token e envia em `_glpi_csrf_token`

### ✅ 3. MySQL Connection Refused
**Problema:** `HY000/2002: Nenhuma conexão pôde ser feita`  
**Solução:** Verificação criada em `tools/verificar_mysql.ps1`

---

## 🚀 PASSO A PASSO DE TESTE

### **ETAPA 1: Verificar MySQL (CRÍTICO)**

```powershell
# Execute no PowerShell (Administrador):
cd D:\laragon\www\glpi\plugins\newbase\tools
.\verificar_mysql.ps1
```

**O que o script verifica:**
- ✔️ Serviços MySQL rodando
- ✔️ Porta 3306 aberta
- ✔️ Processos MySQL ativos
- ✔️ Conexão TCP localhost:3306
- ✔️ Configuração GLPI (config_db.php)
- ✔️ Regras de Firewall

**Resultado Esperado:**
```
[OK] MySQL está rodando e acessível!
```

**Se MySQL não estiver rodando:**
1. Abra o Laragon
2. Clique em **"Start All"**
3. Aguarde ícone verde do MySQL
4. Execute o script novamente

---

### **ETAPA 2: Verificar Arquivos Corrigidos**

#### **Arquivo 1:** `front/companydata.form.php`

Procure pela linha **~304**:
```php
// CRÍTICO: Adicionar meta tag CSRF para o JavaScript
echo "<meta name='glpi:csrf_token' content='" . Session::getNewCSRFToken() . "'>\n";
```

✅ **Deve estar presente após `Html::getCoreVariablesForJavascript()`**

---

#### **Arquivo 2:** `js/newbase.js`

Procure pela função `initSearchButtons()` (linha ~328):

```javascript
// Capturar token CSRF da meta tag
const csrfToken = $('meta[name="glpi:csrf_token"]').attr('content');

if (!csrfToken) {
    console.error('CSRF token não encontrado!');
    Newbase.notify('Erro de segurança. Recarregue a página.', 'error');
    return;
}
```

✅ **Deve capturar token antes de cada AJAX**

---

### **ETAPA 3: Limpar Cache do Navegador**

**IMPORTANTE:** Cache antigo pode causar problemas!

#### **Google Chrome/Edge:**
1. Abra DevTools (F12)
2. Clique com botão direito no ícone de **Reload**
3. Selecione **"Limpar cache e recarregar forçado"**

#### **Firefox:**
1. `Ctrl + Shift + Delete`
2. Marcar apenas **"Cache"**
3. Clicar **"Limpar agora"**

---

### **ETAPA 4: Teste Funcional - Busca CNPJ**

1. **Acesse:**
   ```
   http://glpi.test/plugins/newbase/front/companydata.form.php
   ```

2. **Preencha o CNPJ de Teste:**
   ```
   CNPJ: 00.000.000/0001-91
   ```
   (Este é o CNPJ do Banco do Brasil - público)

3. **Clique na lupa (🔍) ao lado do campo CNPJ**

4. **Abra o Console do Navegador (F12 → Console)**

---

### **ETAPA 5: Análise de Resultados**

#### **✅ SUCESSO - O que você deve ver:**

**No Console:**
```javascript
Newbase Plugin initializing...
Newbase: CSRF Token configurado globalmente.
Newbase Plugin initialized in XX.XXms
```

**Na Tela:**
- ✔️ Notificação verde: "Dados da empresa carregados do banco de dados do governo"
- ✔️ Campos preenchidos automaticamente:
  - Nome Empresarial
  - Nome Fantasia
  - Endereço
  - Cidade
  - Estado
  - CEP

**No Network (F12 → Network → searchCompany.php):**
```
Status: 200 OK
Response:
{
  "success": true,
  "source": "api",
  "data": { ... },
  "message": "Dados da empresa carregados..."
}
```

---

#### **❌ ERRO - Possíveis Problemas:**

##### **Erro 1: CSRF Token Missing**
```javascript
Newbase: CSRF token not found in meta tags
```

**Solução:**
- Verificar se a linha 304 do `companydata.form.php` está correta
- Recarregar a página com cache limpo

---

##### **Erro 2: HTTP 403 Forbidden**
```
Status: 403 Forbidden
Access denied. Please refresh the page and try again.
```

**Solução:**
- Verificar se `_glpi_csrf_token` está sendo enviado no POST
- Inspecionar no DevTools → Network → searchCompany.php → Payload

---

##### **Erro 3: MySQL Connection Error**
```
mysqli::real_connect(): (HY000/2002): Nenhuma conexão...
```

**Solução:**
1. Execute `verificar_mysql.ps1`
2. Inicie o Laragon
3. Verifique `config_db.php`:
   ```php
   $dbhost = 'localhost';  // ou '127.0.0.1'
   $dbport = '3306';
   ```

---

##### **Erro 4: API Externa Timeout**
```
{
  "success": false,
  "message": "Empresa não encontrada ou erro na API"
}
```

**Causas:**
- ✔️ CNPJ inválido
- ✔️ BrasilAPI fora do ar
- ✔️ Firewall bloqueando cURL

**Teste Alternativo:**
```powershell
# Teste manual da API:
curl https://brasilapi.com.br/api/cnpj/v1/00000000000191
```

---

### **ETAPA 6: Teste Funcional - Busca CEP**

1. **Preencha o CEP de Teste:**
   ```
   CEP: 01310-100
   ```
   (Avenida Paulista, São Paulo)

2. **Clique na lupa (🔍) ao lado do campo CEP**

3. **Resultado Esperado:**
   - ✔️ Campo "Endereço" preenchido: "Avenida Paulista"
   - ✔️ Campo "Cidade": "São Paulo"
   - ✔️ Campo "Estado": "SP"

---

## 🐛 TROUBLESHOOTING AVANÇADO

### **Verificar Logs do GLPI:**

```powershell
# Abra o log do plugin:
notepad D:\laragon\www\glpi\files\_log\newbase_plugin.log
```

**O que procurar:**
- ✔️ `"Company found in database: CNPJ XXXXXXXX"`
- ✔️ `"API search successful for CNPJ: XXXXXXXX"`
- ❌ `"ViaCEP CURL Error for CEP..."`
- ❌ `"ERROR in searchCompany.php..."`

---

### **Verificar Log do PHP:**

```powershell
notepad D:\laragon\www\glpi\files\_log\php-errors.log
```

**Erros Comuns:**
```
[WARNING] mysqli::real_connect(): (HY000/2002)
→ MySQL não está rodando

[ERROR] Undefined index: _glpi_csrf_token
→ Token não está sendo enviado
```

---

### **Teste Manual via cURL:**

#### **Teste CNPJ:**
```bash
curl -X POST http://glpi.test/plugins/newbase/ajax/searchCompany.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "cnpj=00000000000191&_glpi_csrf_token=SEU_TOKEN_AQUI"
```

#### **Teste CEP:**
```bash
curl -X POST http://glpi.test/plugins/newbase/ajax/searchAddress.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "cep=01310100&_glpi_csrf_token=SEU_TOKEN_AQUI"
```

**Como obter o token:**
1. Inspecione a página (F12)
2. Console → Digite:
   ```javascript
   $('meta[name="glpi:csrf_token"]').attr('content')
   ```

---

## 📊 CHECKLIST FINAL

Antes de considerar concluído, verifique:

- [ ] MySQL rodando (porta 3306 aberta)
- [ ] Meta tag CSRF presente no HTML
- [ ] Token CSRF enviado em requests AJAX
- [ ] Busca CNPJ preenchendo campos
- [ ] Busca CEP preenchendo campos
- [ ] Console sem erros JavaScript
- [ ] Network sem erros HTTP 403/500
- [ ] Logs sem erros críticos

---

## 🎯 COMANDOS RÁPIDOS

### **Verificar MySQL:**
```powershell
cd D:\laragon\www\glpi\plugins\newbase\tools
.\verificar_mysql.ps1
```

### **Limpar Cache GLPI:**
```powershell
Remove-Item D:\laragon\www\glpi\files\_cache\* -Recurse -Force
```

### **Reiniciar Laragon:**
1. Abra Laragon
2. Menu → "Stop All"
3. Aguarde 5 segundos
4. Menu → "Start All"

---

## 📞 SUPORTE

Se mesmo após seguir este guia o problema persistir:

1. **Colete as informações:**
   - Saída do `verificar_mysql.ps1`
   - Console do navegador (F12)
   - Network tab (request/response completo)
   - Últimas 20 linhas do `php-errors.log`
   - Últimas 20 linhas do `newbase_plugin.log`

2. **Verifique:**
   - Versão do GLPI: `10.0.20`
   - Versão do PHP: `8.3.26`
   - Versão do MySQL: `8.4.6`

---

## ✨ PRÓXIMOS PASSOS

Após tudo funcionar:

1. **Teste com CNPJs reais da sua empresa**
2. **Verifique integração com Entidades do GLPI**
3. **Teste permissões de usuários diferentes**
4. **Documente CNPJs de teste para equipe**

---

**Última atualização:** 06/02/2026  
**Autor:** Claude (Assistente IA)  
**Plugin:** Newbase v2.1.0
