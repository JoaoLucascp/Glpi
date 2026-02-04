# GUIA DE TESTES - PLUGIN NEWBASE v2.1.0

## TESTE RÁPIDO (5 minutos)

### 1. Limpar Cache do GLPI

```powershell
# Execute no PowerShell
cd D:\laragon\www\glpi
Remove-Item "files\_cache\*" -Force -Recurse
Remove-Item "files\_sessions\*" -Force -Recurse
```

### 2. Reinstalar o Plugin

1. Acesse: [http://glpi.test/front/plugin.php]
2. Localize o plugin *Newbase*
3. Clique em *Desinstalar* (se já estiver instalado)
4. Clique em *Instalar*
5. Clique em *Ativar*

*Resultado Esperado:*

- Nenhum erro deve aparecer
- Plugin deve ativar com sucesso
- Mensagem: "O plug-in Newbase não é compatível com CSRF!" *NÃO deve aparecer*

---

## TESTE COMPLETO (15 minutos)

### 3. Testar Páginas de Visualização

#### 3.1 Dashboard

- URL: [http://glpi.test/plugins/newbase/front/index.php]
- * Deve:* Carregar sem erros
- * Deve:* Mostrar estatísticas
- *Não deve:* Mostrar erros de CSRF

#### 3.2 Listagem de Empresas

- URL: [http://glpi.test/plugins/newbase/front/companydata.php]
- * Deve:* Carregar a busca do GLPI
- *Não deve:* Erros de CSRF

#### 3.3 Listagem de Sistemas

- URL: [http://glpi.test/plugins/newbase/front/system.php]
- * Deve:* Carregar sem erros
- *Não deve:* Erros de CSRF

#### 3.4 Listagem de Tarefas

- URL: [http://glpi.test/plugins/newbase/front/task.php]
- * Deve:* Carregar sem erros
- *❌ Não deve:* Erros de CSRF

### 4. Testar Formulários

#### 4.1 Criar Nova Empresa

1. Acesse: [http://glpi.test/plugins/newbase/front/companydata.form.php]
2. Preencha os campos:
   - Nome: "Empresa Teste"
   - CNPJ: "00.000.000/0000-00"
3. Clique em *Salvar*

*Resultado Esperado:*

- Empresa deve ser criada com sucesso
- Redirecionamento para a página da empresa
- Sem erros de CSRF

#### 4.2 Criar Novo Sistema

1. Acesse: [http://glpi.test/plugins/newbase/front/system.form.php]
2. Preencha:
   - Nome: "Sistema Teste"
   - Tipo: "PABX"
3. Clique em *Salvar*

*Resultado Esperado:*

- Sistema criado com sucesso
- Sem erros

#### 4.3 Criar Nova Tarefa

1. Acesse: [http://glpi.test/plugins/newbase/front/task.form.php]
2. Preencha:
   - Título: "Tarefa Teste"
   - Descrição: "Teste de criação"
3. Clique em *Salvar*

*Resultado Esperado:*

- Tarefa criada com sucesso
- Sem erros

---

## TESTE DE BUSCA

### 5. Testar Motor de Busca do GLPI

#### 5.1 Buscar Sistemas

1. Vá para: [http://glpi.test/plugins/newbase/front/system.php]
2. Use o campo de busca do GLPI
3. Tente filtrar por tipo de sistema

*Resultado Esperado:*

- Busca deve funcionar normalmente
- Filtros devem aparecer (ID, Nome, Tipo, Status)
- Sem erro "rawSearchOptions"

#### 5.2 Buscar Tarefas

1. Vá para: [http://glpi.test/plugins/newbase/front/task.php]
2. Use o campo de busca
3. Tente filtrar por status

*Resultado Esperado:*

- Busca funcional
- Filtros corretos
- Sem erros

---

## VERIFICAÇÃO DE ERROS

### 6. Verificar Logs

#### 6.1 Log de PHP

```powershell
Get-Content "D:\laragon\www\glpi\files\_log\php-errors.log" -Tail 50
```

*Resultado Esperado:*

- Nenhum erro relacionado ao plugin Newbase
- Sem "CSRF"
- Sem "rawSearchOptions"
- Sem "static method"

#### 6.2 Log do Plugin

```powershell
Get-Content "D:\laragon\www\glpi\files\_log\newbase.log" -Tail 30
```

*Resultado Esperado:*

- Log de instalação bem-sucedida
- Sem erros

---

## CHECKLIST FINAL

Marque cada item após testar:

### Instalação

- [V] Plugin instalou sem erros
- [V] Plugin ativou sem erros
- [V] Nenhuma mensagem de "incompatível com CSRF"

### Páginas de Visualização

- [V] Dashboard carrega corretamente
- [ ] Listagem de empresas funciona
- [ ] Listagem de sistemas funciona
- [ ] Listagem de tarefas funciona
- [ ] Relatórios funcionam

### Formulários

- [ ] Criar empresa funciona
- [ ] Criar sistema funciona
- [ ] Criar tarefa funciona
- [ ] Editar registros funciona
- [ ] Deletar registros funciona

### Busca GLPI

- [ ] Busca de sistemas funciona
- [ ] Busca de tarefas funciona
- [ ] Filtros funcionam corretamente

### Logs

- [ ] Nenhum erro de CSRF
- [ ] Nenhum erro de type hint
- [ ] Nenhum erro de método estático

---

## SE ENCONTRAR PROBLEMAS

### Problema: "Plugin não é compatível com CSRF"

*Solução:*

1. Verificar se `hook.php` tem: `$PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;`
2. Limpar cache
3. Reinstalar plugin

### Problema: Erro em rawSearchOptions

*Solução:*

1. Verificar se todos os métodos `rawSearchOptions()` têm `: array`
2. Verificar se não têm `static`

### Problema: Erro CSRF em páginas de listagem

*Solução:*

- Verificar se não há `Session::checkCSRF($_POST)` em páginas de visualização
- Deve estar APENAS em formulários dentro do bloco POST

---

## SUCESSO

Se todos os testes passarem, seu plugin está 100% funcional e compatível com GLPI 10.0.20!

---

*Última Atualização:* 04/02/2026
*Versão do Plugin:* 2.1.0
*Desenvolvedor:* João Lucas
