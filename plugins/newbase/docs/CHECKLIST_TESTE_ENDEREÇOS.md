# ✅ CHECKLIST DE TESTE - ENDEREÇOS

## 🎯 Teste Rápido (5 minutos)

### 1. Abrir Empresa
- [ ] Acesse: http://glpi.test/plugins/newbase/front/companydata.form.php?id=1
- [ ] Verifique se a empresa "Newtel Telecom" está carregada

### 2. Aba de Endereços
- [ ] Clique na aba "Endereços"
- [ ] Verifique se aparece o botão azul "Adicionar Endereço"

### 3. Adicionar Primeiro Endereço
- [ ] Clique em "Adicionar Endereço"
- [ ] **IMPORTANTE:** Não deve dar erro 404!
- [ ] Deve abrir o formulário de endereço

### 4. Testar Busca de CEP
- [ ] Digite CEP: `87035-700`
- [ ] Clique em "Buscar CEP"
- [ ] Verifique se preencheu:
  - [ ] Logradouro: Rua Pioneiro José Francisco Ribeiro
  - [ ] Bairro: Jardim Universo
  - [ ] Cidade: Maringá
  - [ ] Estado: PR

### 5. Completar Cadastro
- [ ] Número: `1055`
- [ ] Complemento: `Sala 201` (opcional)
- [ ] Latitude: `-23.3962500` (opcional)
- [ ] Longitude: `-51.9389730` (opcional)

### 6. Salvar
- [ ] Clique em "Adicionar"
- [ ] Deve voltar para aba de endereços
- [ ] Endereço deve aparecer na tabela

### 7. Verificar Listagem
- [ ] Veja se mostra:
  - [ ] CEP: 87035-700
  - [ ] Logradouro: Rua Pioneiro José Francisco Ribeiro
  - [ ] Número: 1055
  - [ ] Bairro: Jardim Universo
  - [ ] Cidade: Maringá
  - [ ] UF: PR
  - [ ] Coordenadas: -23.396250, -51.938973

### 8. Testar Edição
- [ ] Clique no ícone de lápis (✏️)
- [ ] Mude o número para `1060`
- [ ] Clique em "Salvar"
- [ ] Verifique se mudou na listagem

### 9. Testar Exclusão
- [ ] Clique no ícone de lixeira (🗑️)
- [ ] Confirme a exclusão
- [ ] Endereço deve sumir da lista

---

## ⚠️ Se Algo Der Errado

### Erro 404 no Formulário?
```bash
# Verifique se o arquivo existe:
dir D:\laragon\www\glpi\plugins\newbase\front\address.form.php
```

### Busca de CEP Não Funciona?
- Abra console do navegador (F12)
- Veja se há erros JavaScript
- Teste sua conexão com internet

### Não Salva o Endereço?
```bash
# Veja os logs:
notepad D:\laragon\www\glpi\files\_log\php-errors.log
```

### Erro de Permissão?
```sql
-- Verifique as permissões no MySQL:
SELECT * FROM glpi_profilerights 
WHERE name = 'plugin_newbase_companydata' 
AND profiles_id = 4;
```

---

## 📸 Resultado Esperado

Após completar todos os passos, você deve ter:

1. ✅ Formulário de endereço abrindo sem erro 404
2. ✅ Busca de CEP funcionando via ViaCEP
3. ✅ Endereço salvo e aparecendo na listagem
4. ✅ Edição funcionando corretamente
5. ✅ Exclusão funcionando com confirmação

---

## 🚨 Reporte Qualquer Erro

Se encontrar algum problema:

1. **Tire um print da tela**
2. **Copie a URL completa** que deu erro
3. **Verifique o log**: `D:\laragon\www\glpi\files\_log\php-errors.log`
4. **Anote a mensagem de erro exata**

---

## ✨ Próximo Passo

Depois de confirmar que endereços estão funcionando:
- [ ] Testar cadastro de Sistemas Telefônicos
- [ ] Testar cadastro de Tarefas
- [ ] Testar assinatura de tarefas
- [ ] Testar cálculo de quilometragem

---

**Tempo Estimado:** 5-10 minutos  
**Dificuldade:** Fácil  
**Pré-requisito:** Plugin Newbase 2.0.0 instalado e ativado
