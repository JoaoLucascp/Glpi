# ⚡ TESTE RÁPIDO - 2 Minutos

## 🔄 Passo 1: Reiniciar Tudo (30 segundos)

**No Laragon:**
1. Clique em "Stop All" ⏹️
2. Aguarde 3 segundos ⏱️
3. Clique em "Start All" ▶️

---

## 🧹 Passo 2: Limpar Cache (30 segundos)

**No Navegador:**
1. Pressione: `Ctrl + Shift + Delete`
2. Selecione:
   - ✅ Cookies e outros dados de sites
   - ✅ Imagens e arquivos em cache
3. Clique em "Limpar dados"

**OU simplesmente:**
- Abrir aba anônima: `Ctrl + Shift + N`

---

## 🔍 Passo 3: Verificar Token (30 segundos)

**Acesse:**
```
http://glpi.test/public/plugins/newbase/front/companydata.form.php
```

**Abra Console (F12) e procure:**
```
✅ Newbase: CSRF Token configurado globalmente.
```

**NÃO deve aparecer:**
```
❌ CSRF token not found in meta tags
```

**Se aparecer o ✅ = Funcionou!**

---

## 🧪 Passo 4: Testar CNPJ (30 segundos)

1. **Digite:** `11507196000121`
2. **Clique:** Botão 🔍 ao lado do campo CNPJ
3. **Aguarde:** 2-3 segundos
4. **Resultado esperado:**
   - ✅ Campos preenchidos automaticamente
   - ✅ Sem erros no console
   - ✅ Mensagem de sucesso

---

## 📍 Passo 5: Testar CEP (30 segundos)

1. **Digite:** `29903200`
2. **Clique:** Botão 🔍 ao lado do campo CEP
3. **Aguarde:** 2-3 segundos
4. **Resultado esperado:**
   - ✅ Endereço preenchido (Logradouro, Cidade, Estado)
   - ✅ Sem erros no console
   - ✅ Mensagem de sucesso

---

## ✅ SUCESSO!

Se todos os passos acima funcionaram:
- 🎉 **Correção aplicada com sucesso!**
- 🎉 **Plugin 100% funcional!**
- 🎉 **Compatível com GLPI 10.0.20!**

---

## ❌ SE NÃO FUNCIONOU

### Erro: "CSRF token not found"

**Verificar:**
1. Abra: `D:\laragon\www\glpi\plugins\newbase\front\companydata.form.php`
2. Procure por linha ~302:
   ```php
   echo Html::getCoreVariablesForJavascript();
   ```
3. Se NÃO existir, arquivo não foi salvo corretamente

**Solução:**
- Edite o arquivo manualmente
- Adicione essa linha logo após `Html::header(...)`

### Erro: Campos não preenchem

**Verificar Console (F12):**
- Ver mensagens de erro específicas
- Copiar erro e reportar

**Verificar Logs:**
```
D:\laragon\www\glpi\files\_log\php-errors.log
```

---

## 📊 Resumo Visual

```
ANTES ❌
┌─────────────────────────────┐
│ Digite CNPJ: 11507196000121 │
│ [🔍] Buscar                  │
└─────────────────────────────┘
        ↓
Console: ❌ CSRF token not found
        ↓
Erro: An error occurred. Please try again.

DEPOIS ✅
┌─────────────────────────────┐
│ Digite CNPJ: 11507196000121 │
│ [🔍] Buscar                  │
└─────────────────────────────┘
        ↓
Console: ✅ CSRF Token configurado globalmente
        ↓
Sucesso: Campos preenchidos automaticamente!
```

---

**⏱️ Tempo Total:** ~2 minutos  
**🎯 Sucesso Esperado:** 100%  

**TESTE AGORA!** 🚀
