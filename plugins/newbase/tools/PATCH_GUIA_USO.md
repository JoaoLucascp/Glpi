# 🔧 GUIA DE USO - PATCH AUTOMÁTICO NEWBASE

**Versão:** 1.0.0  
**Plugin:** Newbase  
**Data:** 07/01/2026  
**Autor:** João Lucas (Newtel Soluções)

---

## 📋 O QUE ESTE PATCH CORRIGE

Este patch automático resolve **3 erros críticos**:

| Erro | Descrição | Impacto |
|------|-----------|---------|
| **Array to string conversion** | Campos `datatype` retornam arrays em vez de strings | Quebra o Search/listagem de dados |
| **SCSS Compilation Error** | Arquivo `_generate.scss` não encontrado | Dashboard não carrega com erro |
| **Cache inválido** | Arquivos temporários corrompidos | Performance degradada |

---

## 🚀 OPÇÃO 1: USAR VIA LINHA DE COMANDO (RECOMENDADO)

### Passo 1: Localize o arquivo de correção

O arquivo `fix_newbase_errors.php` deve estar em:
```
D:\laragon\www\glpi\plugins\newbase\tools\fix_newbase_errors.php
```

### Passo 2: Abra PowerShell como Administrador

1. Pressione `Win + X`
2. Selecione **"Terminal (Administrador)"** ou **"PowerShell (Administrador)"**
3. Navegue até o diretório do plugin:

```powershell
cd "D:\laragon\www\glpi\plugins\newbase\tools"
```

### Passo 3: Execute o patch

```powershell
php fix_newbase_errors.php
```

**Saída esperada:**
```
╔════════════════════════════════════════════════════════╗
║     NEWBASE - FERRAMENTA DE CORREÇÃO AUTOMÁTICA        ║
║     Versão 1.0.0 - Plugin: newbase                     ║
╚════════════════════════════════════════════════════════╝

🔍 Iniciando verificação e correção dos erros...

📋 [1/4] Corrigindo getSearchOptions() em CompanyData.php...
✅ getSearchOptions() corrigido
   - Removidos arrays em campos 'datatype'
   - Adicionada validação automática

🎨 [2/4] Corrigindo erro de SCSS no Dashboard/Widget.php...
✅ Tratamento de exceção SCSS adicionado
   - try-catch adicionado em compileString()
   - Fallback para CSS sem compilação

✔️  [3/4] Validando classes do plugin Newbase...
✅ 8 arquivo(s) validado(s)

🗑️  [4/4] Limpando cache do plugin...
✅ 12 arquivo(s) de cache removido(s)

╔════════════════════════════════════════════════════════╗
║                  RELATÓRIO FINAL                       ║
╚════════════════════════════════════════════════════════╝

✅ SUCESSOS (4):
   • CompanyData.php corrigido com sucesso
   • Widget.php corrigido com sucesso
   • Cache limpo com sucesso

📁 Backup salvo em: D:\laragon\www\glpi\plugins\newbase\backup_fixes_2026_01_07_14_25_30

🎯 PRÓXIMOS PASSOS:
   1. Desative o plugin em: Configurar > Plugins > NewBase
   2. Reative o plugin: Clique em 'Ativar'
   3. Teste as funcionalidades: CompanyData, Tasks, etc.
   4. Verifique o log: var/log/glpi.log
```

---

## 🌐 OPÇÃO 2: USAR VIA INTERFACE WEB

### Passo 1: Coloque o arquivo no diretório correto

```
D:\laragon\www\glpi\plugins\newbase\tools\fix_newbase_errors.php
```

### Passo 2: Acesse via navegador

1. Abra seu navegador
2. Digite: `http://localhost/glpi/plugins/newbase/tools/fix_newbase_errors.php`
3. Você verá uma tela de confirmação

### Passo 3: Clique em "Iniciar Correção"

A ferramenta executará automaticamente e mostrará o resultado em tempo real.

---

## ✅ VERIFICAÇÃO POS-CORREÇÃO

Após executar o patch, siga estes passos:

### 1. Desative e Reative o Plugin

```
GLPI Admin Panel:
1. Acesse: Configurar > Plugins
2. Localize "NewBase"
3. Clique em "Desinstalar"
4. Aguarde a desinstalação completa
5. Clique em "Instalar"
6. Clique em "Ativar"
```

### 2. Teste as Funcionalidades Principais

**CompanyData (Dados de Empresas):**
```
1. Acesse: Plugins > NewBase > Dados de Empresas
2. Verifique se a listagem carrega sem erros
3. Crie um novo registro
```

**Dashboard:**
```
1. Volte à página inicial do GLPI
2. Verifique se os gráficos carregam
3. Não deve haver erro SCSS
```

**Tasks (Tarefas):**
```
1. Acesse: Plugins > NewBase > Tarefas
2. Verifique se carrega sem avisos
```

### 3. Verifique o Log de Erros

```powershell
# Abra o arquivo de log
notepad "D:\laragon\www\glpi\var\log\glpi.log"
```

**Procure por:**
- ❌ NÃO deve haver: `Array to string conversion`
- ❌ NÃO deve haver: `_generate.scss file not found`
- ✅ DEVE aparecer: Erros anteriores resolvidos

---

## 🔄 ESPECIFICAÇÕES DO PATCH

### Arquivos Modificados

```
1. /plugins/newbase/src/CompanyData.php
   └─ Método: getSearchOptions()
   └─ Ação: Remove arrays em 'datatype', adiciona validação

2. /src/Dashboard/Widget.php (GLPI Core)
   └─ Linha: 2085
   └─ Ação: Adiciona try-catch para compilação SCSS

3. Cache & Temporários
   └─ Localização: /plugins/newbase/tmp/
   └─ Ação: Remove arquivos corrompidos
```

### Backup Automático

Cada execução cria um backup:
```
/plugins/newbase/backup_fixes_YYYY_MM_DD_HH_MM_SS/
├─ CompanyData.php.backup
├─ Widget.php.backup
└─ [outros arquivos modificados]
```

**Nunca exclua o diretório de backup!**

---

## 🛠️ TROUBLESHOOTING

### Erro: "Arquivo não encontrado: CompanyData.php"

**Causa:** Caminho incorreto  
**Solução:**
```powershell
# Verifique se o arquivo existe:
Test-Path "D:\laragon\www\glpi\plugins\newbase\src\CompanyData.php"

# Se retornar "False", procure pelo arquivo:
Get-ChildItem "D:\laragon\www\glpi\plugins" -Recurse -Filter "CompanyData.php"
```

### Erro: "Widget.php não encontrado"

**Causa:** Arquivo do GLPI não está no local esperado  
**Solução:** Não é crítico - o patch informará via aviso (⚠️)

### Patch não executa via CLI

**Causa:** PHP não está no PATH do Windows  
**Solução:**
```powershell
# Use o caminho completo do Laragon:
"C:\laragon\bin\php\php8.3.26\php.exe" fix_newbase_errors.php
```

### Após o patch, ainda há erros

**Solução:**
1. Restaure o backup:
   ```powershell
   Copy-Item "backup_fixes_*/CompanyData.php.backup" "CompanyData.php"
   ```

2. Execute novamente com mais detalhe:
   ```powershell
   php fix_newbase_errors.php 2>&1 | Tee-Object -FilePath error_log.txt
   ```

3. Compartilhe o `error_log.txt` com o desenvolvedor

---

## 📞 SUPORTE

Se encontrar problemas após usar o patch:

1. **Verifique o log:** `var/log/glpi.log`
2. **Restaure o backup:** Diretório `backup_fixes_*`
3. **Contate o desenvolvedor:** João Lucas (Newtel Soluções)
4. **Inclua na solicitação:**
   - Output completo do patch
   - Arquivo `glpi.log`
   - Estrutura do seu GLPI

---

## 🔒 SEGURANÇA

Este patch:
- ✅ NÃO modifica dados do banco de dados
- ✅ NÃO remove nenhum arquivo permanente
- ✅ Cria backup automático antes de modificar
- ✅ Usa regex testado e validado
- ✅ Segue padrões GLPI e PSR-12

---

## 📝 HISTÓRICO DE VERSÕES

| Versão | Data | Alterações |
|--------|------|-----------|
| 1.0.0 | 07/01/2026 | Release inicial |

---

## 📄 LICENÇA

GPLv2+ - Mesma licença do plugin Newbase

**Criado por:** João Lucas  
**Para:** Newtel Soluções  
**Data:** Janeiro 2026
