# ============================================================
# SCRIPT PARA ADICIONAR $rightname AS CLASSES - NEWBASE
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Adiciona public static $rightname a todas as classes
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [switch]$DryRun = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  ADICIONAR RIGHTNAME AS CLASSES - PLUGIN NEWBASE         " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

# Classes principais e seus rightnames
$Classes = @(
    @{File = "src\CompanyData.php"; Class = "CompanyData"; RightName = "plugin_newbase_companydata"},
    @{File = "src\Address.php"; Class = "Address"; RightName = "plugin_newbase_address"},
    @{File = "src\System.php"; Class = "System"; RightName = "plugin_newbase_system"},
    @{File = "src\Task.php"; Class = "Task"; RightName = "plugin_newbase_task"},
    @{File = "src\TaskSignature.php"; Class = "TaskSignature"; RightName = "plugin_newbase_tasksignature"},
    @{File = "src\Config.php"; Class = "Config"; RightName = "plugin_newbase_config"}
)

$Fixed = 0
$AlreadyOk = 0
$Failed = 0

foreach ($classInfo in $Classes) {
    $FilePath = Join-Path $PluginPath $classInfo.File
    $ClassName = $classInfo.Class
    $RightName = $classInfo.RightName
    
    Write-Host ""
    Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
    Write-Host "Classe: $ClassName (arquivo: $($classInfo.File))" -ForegroundColor Cyan
    Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
    
    if (-not (Test-Path $FilePath)) {
        Write-Host "[ERRO] Arquivo nao encontrado: $FilePath" -ForegroundColor Red
        $Failed++
        continue
    }
    
    $Content = Get-Content $FilePath -Raw
    
    # Verificar se ja tem $rightname
    if ($Content -match "public\s+static\s+\`$rightname") {
        Write-Host "[OK] Classe ja possui \$rightname" -ForegroundColor Green
        $AlreadyOk++
        continue
    }
    
    # Encontrar a posicao da declaracao da classe
    if ($Content -match "(class\s+$ClassName\s+extends\s+\w+\s*\{)") {
        $ClassDeclaration = $matches[1]
        
        # Criar a linha do $rightname com indentacao correta
        $RightNameLine = "`n    public static `$rightname = '$RightName';`n"
        
        # Inserir logo apos a abertura da classe
        $NewContent = $Content -replace [regex]::Escape($ClassDeclaration), ($ClassDeclaration + $RightNameLine)
        
        if (-not $DryRun) {
            # Salvar sem BOM
            $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllText($FilePath, $NewContent, $Utf8NoBom)
            Write-Host "[FIX] Adicionado: public static \$rightname = '$RightName';" -ForegroundColor Green
        } else {
            Write-Host "[DRY-RUN] Seria adicionado: public static \$rightname = '$RightName';" -ForegroundColor Cyan
        }
        
        $Fixed++
    } else {
        Write-Host "[ERRO] Nao foi possivel encontrar declaracao da classe" -ForegroundColor Red
        $Failed++
    }
}

# ============================================================
# RESUMO FINAL
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  RESUMO" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Classes processadas: $($Classes.Count)" -ForegroundColor White
Write-Host "  [OK]   Classes ja com \$rightname: $AlreadyOk" -ForegroundColor Green
Write-Host "  [FIX]  Classes corrigidas: $Fixed" -ForegroundColor Cyan
Write-Host "  [ERRO] Classes com erro: $Failed" -ForegroundColor Red
Write-Host ""

if ($DryRun) {
    Write-Host "[INFO] Execute sem -DryRun para aplicar as alteracoes" -ForegroundColor Yellow
} else {
    Write-Host "[OK] Alteracoes aplicadas com sucesso" -ForegroundColor Green
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
