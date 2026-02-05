# 🚀 COMANDOS RÁPIDOS - Plugin Newbase

**Copie e cole estes comandos no PowerShell para testar o plugin**

---

## 📋 1. LIMPAR CACHE DO GLPI

```powershell
# Navegar até o diretório do GLPI
cd D:\laragon\www\glpi

# Limpar cache
Remove-Item "files\_cache\*" -Force -Recurse -ErrorAction SilentlyContinue
Remove-Item "files\_sessions\*" -Force -Recurse -ErrorAction SilentlyContinue  
Remove-Item "files\_tmp\*" -Force -Recurse -ErrorAction SilentlyContinue

Write-Host "✅ Cache limpo com sucesso!" -ForegroundColor Green
```

---

## 🔍 2. VERIFICAR ARQUIVOS DO PLUGIN

```powershell
# Verificar se arquivos principais existem
$files = @(
    "D:\laragon\www\glpi\plugins\newbase\setup.php",
    "D:\laragon\www\glpi\plugins\newbase\hook.php",
    "D:\laragon\www\glpi\plugins\newbase\src\Menu.php",
    "D:\laragon\www\glpi\plugins\newbase\src\Config.php",
    "D:\laragon\www\glpi\plugins\newbase\VERSION"
)

Write-Host "`n📂 Verificando arquivos do plugin..." -ForegroundColor Cyan
foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "✅ $((Split-Path $file -Leaf))" -ForegroundColor Green
    } else {
        Write-Host "❌ $((Split-Path $file -Leaf)) - NÃO ENCONTRADO!" -ForegroundColor Red
    }
}
```

---

## 🔧 3. VALIDAR SINTAXE PHP

```powershell
# Validar sintaxe dos arquivos principais
Write-Host "`n🔧 Validando sintaxe PHP..." -ForegroundColor Cyan

$phpFiles = @(
    "plugins\newbase\setup.php",
    "plugins\newbase\hook.php",
    "plugins\newbase\src\Menu.php"
)

cd D:\laragon\www\glpi

foreach ($file in $phpFiles) {
    Write-Host "`nValidando: $file" -ForegroundColor Yellow
    php -l $file
}
```

---

## 📖 4. VER ÚLTIMOS LOGS

```powershell
# Ver log de erros PHP
Write-Host "`n📖 Últimos erros PHP:" -ForegroundColor Cyan
Get-Content "D:\laragon\www\glpi\files\_log\php-errors.log" -Tail 20 -ErrorAction SilentlyContinue

# Ver log do plugin
Write-Host "`n📖 Log do plugin Newbase:" -ForegroundColor Cyan
Get-Content "D:\laragon\www\glpi\files\_log\newbase.log" -Tail 20 -ErrorAction SilentlyContinue
```

---

## 🔍 5. BUSCAR ERROS ESPECÍFICOS

```powershell
# Buscar por erros CSRF
Write-Host "`n🔍 Buscando erros CSRF..." -ForegroundColor Cyan
Select-String -Path "D:\laragon\www\glpi\files\_log\php-errors.log" -Pattern "csrf" -ErrorAction SilentlyContinue | Select-Object -Last 5

# Buscar por erros do plugin
Write-Host "`n🔍 Buscando erros do Newbase..." -ForegroundColor Cyan
Select-String -Path "D:\laragon\www\glpi\files\_log\php-errors.log" -Pattern "newbase" -ErrorAction SilentlyContinue | Select-Object -Last 5
```

---

## 📊 6. VERIFICAR ESTRUTURA DO PLUGIN

```powershell
# Listar estrutura de diretórios
Write-Host "`n📊 Estrutura do plugin:" -ForegroundColor Cyan
tree D:\laragon\www\glpi\plugins\newbase /F /A | Select-Object -First 50
```

---

## 🗄️ 7. VERIFICAR TABELAS NO BANCO (MySQL)

```powershell
# Este comando precisa ser executado no MySQL
Write-Host "`n🗄️ Para verificar tabelas no banco, execute no MySQL:" -ForegroundColor Cyan
Write-Host @"
USE glpi;
SHOW TABLES LIKE 'glpi_plugin_newbase%';
"@ -ForegroundColor Yellow
```

**OU use este comando direto no PowerShell:**

```powershell
# Conectar ao MySQL via linha de comando
mysql -u root -p -e "USE glpi; SHOW TABLES LIKE 'glpi_plugin_newbase%';"
```

---

## 📝 8. CONTAR REGISTROS NAS TABELAS

```powershell
Write-Host "`n📝 Para contar registros, execute no MySQL:" -ForegroundColor Cyan
Write-Host @"
USE glpi;
SELECT 'addresses' as tabela, COUNT(*) as total FROM glpi_plugin_newbase_addresses
UNION ALL
SELECT 'systems', COUNT(*) FROM glpi_plugin_newbase_systems
UNION ALL
SELECT 'tasks', COUNT(*) FROM glpi_plugin_newbase_tasks
UNION ALL
SELECT 'company_extras', COUNT(*) FROM glpi_plugin_newbase_company_extras;
"@ -ForegroundColor Yellow
```

---

## 🔄 9. REINSTALAR PLUGIN (SOLUÇÃO DE PROBLEMAS)

```powershell
Write-Host "`n🔄 Para reinstalar o plugin:" -ForegroundColor Cyan
Write-Host @"
1. Acesse: http://glpi.test/
2. Vá em: Configurar > Plugins
3. Localize: Newbase
4. Clique em: Desativar (se ativo)
5. Clique em: Desinstalar
6. Limpe o cache (comando acima)
7. Clique em: Instalar
8. Clique em: Ativar
"@ -ForegroundColor Yellow
```

---

## 🚀 10. SCRIPT COMPLETO DE VERIFICAÇÃO

```powershell
# Script completo de verificação
Write-Host "`n" -NoNewline
Write-Host "═══════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "  VERIFICAÇÃO COMPLETA - Plugin Newbase" -ForegroundColor White
Write-Host "═══════════════════════════════════════════" -ForegroundColor Cyan

# 1. Verificar arquivos
Write-Host "`n1️⃣  Verificando arquivos..." -ForegroundColor Yellow
$setupExists = Test-Path "D:\laragon\www\glpi\plugins\newbase\setup.php"
$hookExists = Test-Path "D:\laragon\www\glpi\plugins\newbase\hook.php"
$menuExists = Test-Path "D:\laragon\www\glpi\plugins\newbase\src\Menu.php"

if ($setupExists -and $hookExists -and $menuExists) {
    Write-Host "   ✅ Todos os arquivos principais existem" -ForegroundColor Green
} else {
    Write-Host "   ❌ Alguns arquivos estão faltando!" -ForegroundColor Red
}

# 2. Validar sintaxe PHP
Write-Host "`n2️⃣  Validando sintaxe PHP..." -ForegroundColor Yellow
cd D:\laragon\www\glpi
$setupValid = php -l "plugins\newbase\setup.php" 2>&1 | Select-String "No syntax errors"
$hookValid = php -l "plugins\newbase\hook.php" 2>&1 | Select-String "No syntax errors"

if ($setupValid -and $hookValid) {
    Write-Host "   ✅ Sintaxe PHP está correta" -ForegroundColor Green
} else {
    Write-Host "   ❌ Há erros de sintaxe!" -ForegroundColor Red
}

# 3. Verificar logs
Write-Host "`n3️⃣  Verificando logs..." -ForegroundColor Yellow
$logPath = "D:\laragon\www\glpi\files\_log\php-errors.log"
if (Test-Path $logPath) {
    $recentErrors = Get-Content $logPath -Tail 5 -ErrorAction SilentlyContinue | Select-String "newbase"
    if ($recentErrors) {
        Write-Host "   ⚠️  Há erros recentes no log" -ForegroundColor Yellow
        Write-Host "   Execute: Get-Content `"$logPath`" -Tail 20" -ForegroundColor Gray
    } else {
        Write-Host "   ✅ Sem erros recentes" -ForegroundColor Green
    }
} else {
    Write-Host "   ℹ️  Arquivo de log não encontrado (normal em instalação nova)" -ForegroundColor Cyan
}

# 4. Verificar documentação
Write-Host "`n4️⃣  Verificando documentação..." -ForegroundColor Yellow
$docsExist = Test-Path "D:\laragon\www\glpi\plugins\newbase\docs\CORRECTIONS_APPLIED.md"
if ($docsExist) {
    Write-Host "   ✅ Documentação de correções criada" -ForegroundColor Green
} else {
    Write-Host "   ⚠️  Documentação não encontrada" -ForegroundColor Yellow
}

# Resumo final
Write-Host "`n" -NoNewline
Write-Host "═══════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "  RESUMO DA VERIFICAÇÃO" -ForegroundColor White
Write-Host "═══════════════════════════════════════════" -ForegroundColor Cyan

if ($setupExists -and $hookExists -and $menuExists -and $setupValid -and $hookValid) {
    Write-Host "`n✅ TUDO OK! Plugin pronto para uso!" -ForegroundColor Green
    Write-Host "`n📌 Próximos passos:" -ForegroundColor Cyan
    Write-Host "   1. Acesse: http://glpi.test/" -ForegroundColor White
    Write-Host "   2. Vá em: Configurar > Plugins" -ForegroundColor White
    Write-Host "   3. Instale e ative o plugin Newbase" -ForegroundColor White
} else {
    Write-Host "`n⚠️  Há problemas que precisam ser resolvidos!" -ForegroundColor Yellow
    Write-Host "   Consulte a documentação em:" -ForegroundColor White
    Write-Host "   docs\CORRECTIONS_APPLIED.md" -ForegroundColor Gray
}

Write-Host "`n"
```

---

## 📚 11. ABRIR DOCUMENTAÇÃO

```powershell
# Abrir documentação no navegador ou editor
code "D:\laragon\www\glpi\plugins\newbase\docs\CORRECTIONS_APPLIED.md"
code "D:\laragon\www\glpi\plugins\newbase\docs\QUICK_TEST_GUIDE.md"
code "D:\laragon\www\glpi\plugins\newbase\docs\EXECUTIVE_SUMMARY.md"
```

---

## 🌐 12. ABRIR GLPI NO NAVEGADOR

```powershell
# Abrir GLPI
Start-Process "http://glpi.test/"

# Abrir página de plugins
Start-Process "http://glpi.test/front/plugin.php"
```

---

## 💡 DICAS ÚTEIS

### Comando para ver todos os plugins
```powershell
Get-ChildItem "D:\laragon\www\glpi\plugins" -Directory | Select-Object Name
```

### Comando para ver tamanho do plugin
```powershell
$size = (Get-ChildItem "D:\laragon\www\glpi\plugins\newbase" -Recurse | Measure-Object -Property Length -Sum).Sum / 1MB
Write-Host "Tamanho do plugin: $([math]::Round($size, 2)) MB"
```

### Comando para contar arquivos PHP
```powershell
$phpCount = (Get-ChildItem "D:\laragon\www\glpi\plugins\newbase" -Recurse -Filter "*.php").Count
Write-Host "Total de arquivos PHP: $phpCount"
```

---

## 🎯 ATALHOS RÁPIDOS

```powershell
# Salve estes aliases no seu perfil PowerShell

# Alias para limpar cache
function Clear-GlpiCache {
    cd D:\laragon\www\glpi
    Remove-Item "files\_cache\*" -Force -Recurse -ErrorAction SilentlyContinue
    Write-Host "✅ Cache limpo!" -ForegroundColor Green
}

# Alias para ver logs
function Show-GlpiLogs {
    Get-Content "D:\laragon\www\glpi\files\_log\php-errors.log" -Tail 20
}

# Alias para verificar plugin
function Test-NewbasePlugin {
    cd D:\laragon\www\glpi
    php -l "plugins\newbase\setup.php"
    php -l "plugins\newbase\hook.php"
}

# Usar:
# Clear-GlpiCache
# Show-GlpiLogs
# Test-NewbasePlugin
```

---

**📌 Dica:** Salve este arquivo e mantenha aberto para consultas rápidas!
