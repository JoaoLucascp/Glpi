# ✅ CHECKLIST RÁPIDA - Plugin Newbase

**Use esta checklist para validar rapidamente o plugin**

---

## 📋 CHECKLIST DE INSTALAÇÃO

### Pré-Instalação
- [ ] GLPI 10.0.20+ instalado
- [ ] PHP 8.1+ rodando
- [ ] MySQL 8.0+ ativo
- [ ] Laragon funcionando
- [ ] Cache limpo

### Instalação
- [ ] Plugin aparece na lista de plugins
- [ ] Botão "Instalar" clicado
- [ ] Instalação concluída sem erros
- [ ] Plugin ativado com sucesso
- [ ] Sem mensagens de erro no topo da página

---

## 🎯 CHECKLIST FUNCIONAL

### Menu Principal
- [ ] Menu "Newbase" aparece em "Ferramentas"
- [ ] Ícone de prédio (building) está visível
- [ ] Menu pode ser clicado

### Submenus
- [ ] "Company Data" aparece
- [ ] "Systems" aparece
- [ ] "Field Tasks" aparece
- [ ] "Reports" aparece
- [ ] "Configuration" aparece

### Ícones
- [ ] Todos os ícones Tabler aparecem
- [ ] Sem ícones quebrados
- [ ] Ícones têm tamanho correto

---

## 🔧 CHECKLIST TÉCNICA

### Arquivos
- [ ] `setup.php` existe
- [ ] `hook.php` existe
- [ ] `src/Menu.php` existe
- [ ] `composer.json` existe
- [ ] `VERSION` existe

### Banco de Dados
- [ ] Tabela `glpi_plugin_newbase_addresses` criada
- [ ] Tabela `glpi_plugin_newbase_systems` criada
- [ ] Tabela `glpi_plugin_newbase_tasks` criada
- [ ] Tabela `glpi_plugin_newbase_task_signatures` criada
- [ ] Tabela `glpi_plugin_newbase_company_extras` criada
- [ ] Tabela `glpi_plugin_newbase_config` criada

### Logs
- [ ] Sem erros em `php-errors.log`
- [ ] Sem erros em `newbase.log`
- [ ] Log de instalação positivo

---

## 🧪 CHECKLIST DE TESTES

### Company Data
- [ ] Página carrega sem erro
- [ ] Formulário de adição funciona
- [ ] Busca por CNPJ funciona
- [ ] Dados são salvos corretamente

### Systems
- [ ] Página carrega sem erro
- [ ] Pode adicionar sistema
- [ ] Tipos de sistema aparecem
- [ ] Configuração pode ser salva

### Tasks
- [ ] Página carrega sem erro
- [ ] Pode criar tarefa
- [ ] GPS funciona (se disponível)
- [ ] Assinatura digital funciona

### Reports
- [ ] Página carrega sem erro
- [ ] Relatórios são gerados
- [ ] Dados aparecem corretamente

### Configuration
- [ ] Página carrega sem erro
- [ ] Configurações aparecem
- [ ] Mudanças são salvas

---

## 🔒 CHECKLIST DE SEGURANÇA

### CSRF
- [ ] `csrf_compliant` está em setup.php
- [ ] Formulários têm token CSRF
- [ ] Envio de formulário funciona

### Permissões
- [ ] Verificação de READ funciona
- [ ] Verificação de UPDATE funciona
- [ ] Usuário sem permissão não acessa

### SQL
- [ ] Queries usam $DB->request()
- [ ] Sem SQL direto nos arquivos
- [ ] Prepared statements usados

---

## 📊 CHECKLIST DE QUALIDADE

### Código
- [ ] Sem erros de sintaxe PHP
- [ ] Type hints 100%
- [ ] PHPDoc presente
- [ ] PSR-12 compliance

### Performance
- [ ] Páginas carregam rápido (<2s)
- [ ] Queries otimizadas
- [ ] Cache funciona

### UX
- [ ] Interface intuitiva
- [ ] Mensagens claras
- [ ] Feedback visual presente

---

## 🚀 CHECKLIST DE PRODUÇÃO

### Antes do Deploy
- [ ] Todos os testes passaram
- [ ] Backup do banco criado
- [ ] Documentação atualizada
- [ ] Versão correta no VERSION

### Durante o Deploy
- [ ] Plugin instalado com sucesso
- [ ] Configurações aplicadas
- [ ] Permissões configuradas
- [ ] Logs verificados

### Após o Deploy
- [ ] Funcionalidades testadas
- [ ] Usuários notificados
- [ ] Documentação disponível
- [ ] Suporte preparado

---

## 📝 NOTAS

Use este espaço para anotar observações:

```
Data do teste: ___/___/______
Testado por: _________________
Versão GLPI: _________________
Versão Plugin: _______________

Observações:
_________________________________
_________________________________
_________________________________
_________________________________
```

---

## ✅ RESULTADO FINAL

**APROVADO** ☐  
**REPROVADO** ☐  
**NECESSITA CORREÇÕES** ☐

---

**Assinatura:** ________________  
**Data:** ___/___/______
