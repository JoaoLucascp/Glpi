# ✅ Checklist de Verificação - Correção CSRF Token

## Antes de Testar

- [ ] Salvar todos os arquivos modificados
- [ ] Reiniciar o servidor Apache (Laragon)
- [ ] Limpar cache do navegador (Ctrl + Shift + Delete)

## Arquivos Corrigidos

### Backend (PHP)
- [x] `plugins/newbase/ajax/searchCompany.php` - Linha 52 e 79
- [x] `plugins/newbase/ajax/searchAddress.php` - Linha 53 e 79

### Frontend (JavaScript)
- [x] `plugins/newbase/js/newbase.js` - Linhas 39-49

## Testes a Realizar

### 1. Teste de Busca por CNPJ
- [ ] Acessar: http://glpi.test/public/plugins/newbase/front/companydata.form.php
- [ ] Clicar em "Adicionar" para criar nova empresa
- [ ] Digitar CNPJ: `11507196000121`
- [ ] Clicar no botão de busca (🔍)
- [ ] **Esperado:** Campos preenchidos automaticamente
- [ ] **Não deve:** Aparecer erro "An error occurred"

### 2. Teste de Busca por CEP
- [ ] No mesmo formulário
- [ ] Digitar CEP: `29903200`
- [ ] Clicar no botão de busca (🔍)
- [ ] **Esperado:** Endereço preenchido (Logradouro, Cidade, Estado)
- [ ] **Não deve:** Aparecer erro

### 3. Verificação do Console
- [ ] Abrir Console do Navegador (F12)
- [ ] Procurar mensagem: `Newbase: CSRF Token configurado globalmente.`
- [ ] **Não deve** aparecer: `CSRF token not found in meta tags`

### 4. Teste de Salvamento
- [ ] Preencher todos os campos obrigatórios
- [ ] Clicar em "Adicionar"
- [ ] **Esperado:** Empresa salva com sucesso
- [ ] **Não deve:** Erro de validação CSRF

## Verificação de Logs

### Console do Navegador (F12 > Console)
Mensagens esperadas:
```
✅ Newbase: CSRF Token configurado globalmente.
```

Mensagens que NÃO devem aparecer:
```
❌ Newbase: CSRF token not found in meta tags
❌ ArgumentCountError: Too few arguments
❌ 403 Forbidden
```

### Logs do GLPI
Arquivo: `files/_log/php-errors.log`

**Não deve conter:**
```
❌ ArgumentCountError in searchCompany.php
❌ ArgumentCountError in searchAddress.php
```

## Se Algo Der Errado

### Erro: "CSRF token not found"
**Solução:**
1. Verificar se `Html::header()` está sendo chamado em `front/companydata.form.php`
2. Limpar cache do navegador
3. Recarregar página com Ctrl + F5

### Erro: "ArgumentCountError"
**Solução:**
1. Verificar se todos os `Session::checkCSRF()` têm `$_POST` como argumento
2. Confirmar que os arquivos foram salvos corretamente
3. Reiniciar Apache

### Erro: "An error occurred. Please try again"
**Solução:**
1. Abrir Console (F12)
2. Verificar mensagem de erro exata
3. Verificar Network tab para ver resposta do servidor
4. Verificar logs em `files/_log/php-errors.log`

## Confirmação Final

Marque abaixo quando todos os testes passarem:

- [ ] ✅ Busca por CNPJ funcionando
- [ ] ✅ Busca por CEP funcionando
- [ ] ✅ Console sem erros
- [ ] ✅ Salvamento de empresa funcionando
- [ ] ✅ Logs sem erros

---

**Status:** 🟢 CORREÇÃO APLICADA COM SUCESSO

**Data:** 2026-02-06  
**Responsável:** João Lucas  
**Assistente:** Claude (Anthropic AI)
