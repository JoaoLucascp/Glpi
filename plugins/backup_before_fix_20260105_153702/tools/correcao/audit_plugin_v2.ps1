# ============================================================
# SCRIPT DE AUDITORIA PLUGIN NEWBASE - VERSAO CORRIGIDA
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Auditoria completa do plugin NewBase
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase"
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "     AUDITORIA COMPLETA - PLUGIN NEWBASE GLPI 10.0.20     " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se o diretorio existe
if (-not (Test-Path $PluginPath)) {
    Write-Host "[ERRO] Diretorio nao encontrado: $PluginPath" -ForegroundColor Red
    exit 1
}

# Criar diretorio de relatorios
$ReportDir = Join-Path $PluginPath "audit_reports"
if (-not (Test-Path $ReportDir)) {
    New-Item -ItemType Directory -Path $ReportDir | Out-Null
}

$Timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$ReportFile = Join-Path $ReportDir "audit_$Timestamp.txt"

# ============================================================
# FUNCAO: Escrever no relatorio
# ============================================================
function Write-Report {
    param([string]$Message)
    Add-Content -Path $ReportFile -Value $Message -Encoding UTF8
    Write-Host $Message
}

# ============================================================
# PASSO 1: LISTAGEM DE ARQUIVOS PHP
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 1: LISTAGEM DE TODOS OS ARQUIVOS PHP" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report "============================================================"
Write-Report "RELATORIO DE AUDITORIA - PLUGIN NEWBASE"
Write-Report "Data: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Report "============================================================"
Write-Report ""
Write-Report "PASSO 1: LISTAGEM DE ARQUIVOS PHP"
Write-Report "------------------------------------------------------------"

$PhpFiles = Get-ChildItem -Path $PluginPath -Filter "*.php" -Recurse -File | 
    Where-Object { $_.FullName -notmatch "\\vendor\\|\\backup" } |
    Sort-Object FullName

$PhpCount = $PhpFiles.Count
Write-Report "Total de arquivos PHP encontrados: $PhpCount"
Write-Report ""

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    Write-Report "  [PHP] $RelativePath"
}

# ============================================================
# PASSO 2: VALIDACAO DE SINTAXE PHP
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 2: VALIDACAO DE SINTAXE PHP" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report ""
Write-Report "PASSO 2: VALIDACAO DE SINTAXE PHP"
Write-Report "------------------------------------------------------------"

$PhpExe = "D:\laragon\bin\php\php-8.3.26\php.exe"
if (-not (Test-Path $PhpExe)) {
    Write-Host "[AVISO] PHP nao encontrado em $PhpExe" -ForegroundColor Yellow
    Write-Host "        Tentando usar 'php' do PATH..." -ForegroundColor Yellow
    $PhpExe = "php"
}

$SyntaxErrors = 0
$SyntaxValid = 0

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    
    try {
        $result = & $PhpExe -l $file.FullName 2>&1
        
        if ($LASTEXITCODE -eq 0) {
            $SyntaxValid++
            Write-Host "  [OK]   VALIDO: $RelativePath" -ForegroundColor Green
            Write-Report "  [OK]   VALIDO: $RelativePath"
        } else {
            $SyntaxErrors++
            Write-Host "  [ERRO] INVALIDO: $RelativePath" -ForegroundColor Red
            Write-Report "  [ERRO] INVALIDO: $RelativePath"
            Write-Report "         $result"
        }
    } catch {
        $SyntaxErrors++
        Write-Host "  [ERRO] Erro ao validar: $RelativePath" -ForegroundColor Red
        Write-Report "  [ERRO] Erro ao validar: $RelativePath"
    }
}

Write-Report ""
Write-Report "RESULTADO VALIDACAO:"
Write-Report "  [OK]   Arquivos validos: $SyntaxValid"
Write-Report "  [ERRO] Arquivos com erro: $SyntaxErrors"

# ============================================================
# PASSO 3: BUSCA DE PADROES DE RISCO
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 3: BUSCA DE PADROES DE RISCO" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report ""
Write-Report "PASSO 3: BUSCA DE PADROES DE RISCO"
Write-Report "------------------------------------------------------------"

# Padroes perigosos a buscar
$DangerousPatterns = @(
    @{Pattern = "mysqli_"; Description = "uso de mysqli_*"; Risk = "CRITICO"},
    @{Pattern = "mysql_"; Description = "uso de mysql_*"; Risk = "CRITICO"},
    @{Pattern = "eval\("; Description = "uso de eval()"; Risk = "CRITICO"},
    @{Pattern = "exec\("; Description = "uso de exec()"; Risk = "ALTO"},
    @{Pattern = "system\("; Description = "uso de system()"; Risk = "ALTO"},
    @{Pattern = "shell_exec\("; Description = "uso de shell_exec()"; Risk = "ALTO"},
    @{Pattern = "passthru\("; Description = "uso de passthru()"; Risk = "ALTO"},
    @{Pattern = '\$_GET\['; Description = "acesso direto a \$_GET"; Risk = "MEDIO"},
    @{Pattern = '\$_POST\['; Description = "acesso direto a \$_POST"; Risk = "MEDIO"},
    @{Pattern = '\$_REQUEST\['; Description = "acesso direto a \$_REQUEST"; Risk = "MEDIO"},
    @{Pattern = '\$_COOKIE\['; Description = "acesso direto a \$_COOKIE"; Risk = "MEDIO"},
    @{Pattern = "md5\("; Description = "uso de md5"; Risk = "MEDIO"},
    @{Pattern = "sha1\("; Description = "uso de sha1"; Risk = "MEDIO"}
)

$TotalRisks = 0

foreach ($pattern in $DangerousPatterns) {
    Write-Host ""
    Write-Host "Buscando: $($pattern.Description) [Risco: $($pattern.Risk)]" -ForegroundColor Cyan
    Write-Report ""
    Write-Report "Padrao: $($pattern.Description) [Risco: $($pattern.Risk)]"
    
    $Found = $false
    
    foreach ($file in $PhpFiles) {
        $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
        $LineNumber = 0
        
        Get-Content $file.FullName | ForEach-Object {
            $LineNumber++
            if ($_ -match $pattern.Pattern) {
                if (-not $Found) {
                    $Found = $true
                }
                $TotalRisks++
                
                $Line = $_.Trim()
                if ($Line.Length -gt 80) {
                    $Line = $Line.Substring(0, 77) + "..."
                }
                
                Write-Host "  [RISCO] $RelativePath : linha $LineNumber" -ForegroundColor Yellow
                Write-Host "          $Line" -ForegroundColor Gray
                
                Write-Report "  [RISCO] $RelativePath : linha $LineNumber"
                Write-Report "          $Line"
            }
        }
    }
    
    if (-not $Found) {
        Write-Host "  [OK] Nenhuma ocorrencia encontrada" -ForegroundColor Green
        Write-Report "  [OK] Nenhuma ocorrencia encontrada"
    }
}

Write-Report ""
Write-Report "Total de padroes de risco encontrados: $TotalRisks"

# ============================================================
# PASSO 4: VERIFICACAO DE ARQUIVOS OBRIGATORIOS
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 4: VERIFICACAO DE ARQUIVOS OBRIGATORIOS" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report ""
Write-Report "PASSO 4: VERIFICACAO DE ARQUIVOS OBRIGATORIOS"
Write-Report "------------------------------------------------------------"

$RequiredFiles = @(
    "setup.php",
    "hook.php",
    "composer.json",
    "README.md",
    "CHANGELOG.md",
    "VERSION"
)

$MissingFiles = 0

foreach ($reqFile in $RequiredFiles) {
    $FilePath = Join-Path $PluginPath $reqFile
    if (Test-Path $FilePath) {
        Write-Host "  [OK]   $reqFile" -ForegroundColor Green
        Write-Report "  [OK]   $reqFile"
    } else {
        $MissingFiles++
        Write-Host "  [ERRO] $reqFile (FALTANTE)" -ForegroundColor Red
        Write-Report "  [ERRO] $reqFile (FALTANTE)"
    }
}

# ============================================================
# PASSO 5: VERIFICACAO DE ESTRUTURA DE DIRETORIOS
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 5: VERIFICACAO DE ESTRUTURA" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report ""
Write-Report "PASSO 5: VERIFICACAO DE ESTRUTURA"
Write-Report "------------------------------------------------------------"

$RequiredDirs = @(
    "ajax",
    "css",
    "front",
    "js",
    "src",
    "locales",
    "install"
)

$MissingDirs = 0

foreach ($reqDir in $RequiredDirs) {
    $DirPath = Join-Path $PluginPath $reqDir
    if (Test-Path $DirPath) {
        $FileCount = (Get-ChildItem -Path $DirPath -Recurse -File | Measure-Object).Count
        Write-Host "  [OK]   $reqDir/ ($FileCount arquivos)" -ForegroundColor Green
        Write-Report "  [OK]   $reqDir/ ($FileCount arquivos)"
    } else {
        $MissingDirs++
        Write-Host "  [ERRO] $reqDir/ (FALTANTE)" -ForegroundColor Red
        Write-Report "  [ERRO] $reqDir/ (FALTANTE)"
    }
}

# ============================================================
# PASSO 6: VERIFICACAO DE declare(strict_types=1)
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  PASSO 6: VERIFICACAO DE declare(strict_types=1)" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

Write-Report ""
Write-Report "PASSO 6: VERIFICACAO DE declare(strict_types=1)"
Write-Report "------------------------------------------------------------"

$WithStrictTypes = 0
$WithoutStrictTypes = 0

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    $Content = Get-Content $file.FullName -Raw
    
    if ($Content -match "declare\(strict_types\s*=\s*1\)") {
        $WithStrictTypes++
    } else {
        $WithoutStrictTypes++
        Write-Host "  [ERRO] sem strict_types: $RelativePath" -ForegroundColor Red
        Write-Report "  [ERRO] sem strict_types: $RelativePath"
    }
}

Write-Report ""
Write-Report "Arquivos com strict_types: $WithStrictTypes"
Write-Report "Arquivos sem strict_types: $WithoutStrictTypes"

# ============================================================
# RESUMO FINAL
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  RESUMO FINAL" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

Write-Report ""
Write-Report "RESUMO FINAL"
Write-Report "------------------------------------------------------------"

# Calcular score
$MaxPoints = 100
$Score = $MaxPoints

# Penalidades
$Score -= ($SyntaxErrors * 10)
$Score -= ($TotalRisks * 0.5)
$Score -= ($MissingFiles * 5)
$Score -= ($MissingDirs * 10)
$Score -= ($WithoutStrictTypes * 2)

if ($Score -lt 0) { $Score = 0 }
$Score = [Math]::Round($Score)

$Summary = @"
Total PHP: $PhpCount
Validos: $SyntaxValid
Com erro de sintaxe: $SyntaxErrors
Padroes de risco: $TotalRisks
Arquivos obrigatorios faltantes: $MissingFiles
Diretorios obrigatorios faltantes: $MissingDirs
Com strict_types: $WithStrictTypes
Sem strict_types: $WithoutStrictTypes
Score: $Score/100
"@

Write-Report $Summary
Write-Host $Summary

$ScoreColor = "Green"
$Status = "EXCELENTE"
if ($Score -lt 90) { $ScoreColor = "Yellow"; $Status = "BOM" }
if ($Score -lt 70) { $ScoreColor = "Yellow"; $Status = "REGULAR" }
if ($Score -lt 50) { $ScoreColor = "Red"; $Status = "CRITICO" }

Write-Host ""
Write-Host "Status: $Status" -ForegroundColor $ScoreColor
Write-Report "Status: $Status"

Write-Report ""
Write-Report "FIM DO RELATORIO"

Write-Host ""
Write-Host "[INFO] Relatorio salvo em: $ReportFile" -ForegroundColor Cyan
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
