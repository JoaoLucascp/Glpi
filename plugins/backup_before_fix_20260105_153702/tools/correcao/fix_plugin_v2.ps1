# ============================================================
# SCRIPT DE CORRECAO AUTOMATICA - PLUGIN NEWBASE V2
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Corrige automaticamente problemas comuns
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [switch]$DryRun = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "   CORRECAO AUTOMATICA - PLUGIN NEWBASE GLPI 10.0.20      " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

# Verificar se o diretorio existe
if (-not (Test-Path $PluginPath)) {
    Write-Host "[ERRO] Diretorio nao encontrado: $PluginPath" -ForegroundColor Red
    exit 1
}

# Criar backup antes de modificar (CORRIGIDO - sem recursao)
if (-not $DryRun) {
    $BackupTimestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $BackupName = "backup_before_fix_$BackupTimestamp"
    $BackupDir = Join-Path (Split-Path $PluginPath -Parent) $BackupName
    
    Write-Host "[INFO] Criando backup em: $BackupDir" -ForegroundColor Cyan
    
    try {
        # Criar diretorio de backup
        New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
        
        # Copiar arquivos excluindo backups anteriores e vendor
        Get-ChildItem -Path $PluginPath -Recurse | Where-Object {
            $_.FullName -notmatch "\\backup_before_fix_|\\backup_\d{4}_\d{2}_\d{2}|\\vendor\\"
        } | ForEach-Object {
            $DestPath = $_.FullName.Replace($PluginPath, $BackupDir)
            
            if ($_.PSIsContainer) {
                if (-not (Test-Path $DestPath)) {
                    New-Item -ItemType Directory -Path $DestPath -Force | Out-Null
                }
            } else {
                $DestDir = Split-Path $DestPath -Parent
                if (-not (Test-Path $DestDir)) {
                    New-Item -ItemType Directory -Path $DestDir -Force | Out-Null
                }
                Copy-Item -Path $_.FullName -Destination $DestPath -Force
            }
        }
        
        Write-Host "[OK] Backup criado com sucesso" -ForegroundColor Green
        Write-Host ""
    } catch {
        Write-Host "[ERRO] Falha ao criar backup: $_" -ForegroundColor Red
        Write-Host "[AVISO] Continuando sem backup..." -ForegroundColor Yellow
        Write-Host ""
    }
}

# Obter arquivos PHP
$PhpFiles = Get-ChildItem -Path $PluginPath -Filter "*.php" -Recurse -File | 
    Where-Object { $_.FullName -notmatch "\\vendor\\|\\backup" } |
    Sort-Object FullName

Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  CORRECAO 1: Adicionar declare(strict_types=1)" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

$FilesFixed = 0
$FilesAlreadyOk = 0

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    $Content = Get-Content $file.FullName -Raw
    
    # Verificar se ja tem strict_types
    if ($Content -match "declare\(strict_types\s*=\s*1\)") {
        $FilesAlreadyOk++
        Write-Host "  [OK]   $RelativePath (ja possui strict_types)" -ForegroundColor Green
        continue
    }
    
    # Verificar se comeca com <?php
    if ($Content -notmatch "^\s*<\?php") {
        Write-Host "  [AVISO] $RelativePath (nao comeca com <?php)" -ForegroundColor Yellow
        continue
    }
    
    # Adicionar declare apos <?php
    $NewContent = $Content -replace '(<\?php)', "`$1`n`ndeclare(strict_types=1);"
    
    if (-not $DryRun) {
        # Salvar sem BOM
        $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
        [System.IO.File]::WriteAllText($file.FullName, $NewContent, $Utf8NoBom)
    }
    
    $FilesFixed++
    Write-Host "  [FIX]  $RelativePath (strict_types adicionado)" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "Resultado strict_types: $FilesFixed arquivos corrigidos, $FilesAlreadyOk ja tinham strict_types" -ForegroundColor Green

# ============================================================
# CORRECAO 2: Remover use Session/Html/CommonDBTM
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  CORRECAO 2: Remover declaracoes 'use' incorretas" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

$UseRemoved = 0

$InvalidUses = @(
    "use Session;",
    "use Html;",
    "use CommonDBTM;",
    "use Config;",
    "use DB;",
    "use Dropdown;",
    "use Entity;",
    "use Profile;",
    "use Toolbox;",
    "use User;"
)

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    $Content = Get-Content $file.FullName -Raw
    $Modified = $false
    
    foreach ($invalidUse in $InvalidUses) {
        if ($Content -match [regex]::Escape($invalidUse)) {
            $Content = $Content -replace [regex]::Escape($invalidUse), ""
            $Modified = $true
        }
    }
    
    if ($Modified) {
        # Remover linhas vazias duplicadas
        $Content = $Content -replace "(\r?\n){3,}", "`n`n"
        
        if (-not $DryRun) {
            $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllText($file.FullName, $Content, $Utf8NoBom)
        }
        
        $UseRemoved++
        Write-Host "  [FIX]  $RelativePath (removidos 'use' incorretos)" -ForegroundColor Cyan
    }
}

Write-Host ""
Write-Host "Resultado 'use' incorretos: $UseRemoved arquivos corrigidos" -ForegroundColor Green

# ============================================================
# CORRECAO 3: Verificar PHPDoc em classes
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  CORRECAO 3: Verificar PHPDoc em classes" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

$ClassesWithoutDoc = 0

foreach ($file in $PhpFiles) {
    # Verificar apenas arquivos em src/
    if ($file.FullName -notmatch "\\src\\") {
        continue
    }
    
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    $Content = Get-Content $file.FullName -Raw
    
    # Verificar se tem declaracao de classe
    if ($Content -match "class\s+(\w+)") {
        $ClassName = $matches[1]
        
        # Verificar se tem PHPDoc antes da classe
        if ($Content -notmatch "/\*\*[^*]*\*\s*/\s*class\s+$ClassName") {
            $ClassesWithoutDoc++
            Write-Host "  [AVISO] $RelativePath (classe $ClassName sem PHPDoc)" -ForegroundColor Yellow
        } else {
            Write-Host "  [OK]   $RelativePath (PHPDoc OK)" -ForegroundColor Green
        }
    }
}

Write-Host ""
Write-Host "Classes sem PHPDoc adequado: $ClassesWithoutDoc" -ForegroundColor Yellow

# ============================================================
# CORRECAO 4: Verificar encoding UTF-8 (sem BOM)
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host "  CORRECAO 4: Verificar encoding UTF-8 (sem BOM)" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Yellow
Write-Host ""

$FilesWithBOM = 0

foreach ($file in $PhpFiles) {
    $RelativePath = $file.FullName.Replace($PluginPath, "").TrimStart('\')
    
    # Ler primeiros 3 bytes
    $bytes = [System.IO.File]::ReadAllBytes($file.FullName)
    
    # Verificar BOM UTF-8 (EF BB BF)
    if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
        $FilesWithBOM++
        Write-Host "  [AVISO] $RelativePath (contem BOM UTF-8)" -ForegroundColor Yellow
        
        if (-not $DryRun) {
            # Remover BOM
            $Content = Get-Content $file.FullName -Raw
            $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllText($file.FullName, $Content, $Utf8NoBom)
            Write-Host "          [FIX] BOM removido" -ForegroundColor Cyan
        }
    } else {
        Write-Host "  [OK]   $RelativePath (UTF-8 sem BOM)" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "Arquivos com BOM: $FilesWithBOM" -ForegroundColor Yellow

# ============================================================
# RESUMO FINAL
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  RESUMO DAS CORRECOES" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "  - strict_types adicionados: $FilesFixed" -ForegroundColor Green
Write-Host "  - 'use' incorretos removidos: $UseRemoved" -ForegroundColor Green
Write-Host "  - arquivos com BOM removido: $FilesWithBOM" -ForegroundColor Green
Write-Host "  - classes sem PHPDoc: $ClassesWithoutDoc" -ForegroundColor Yellow
Write-Host ""

if (-not $DryRun) {
    if (Test-Path $BackupDir) {
        Write-Host "[INFO] Backup criado em: $BackupDir" -ForegroundColor Cyan
    }
    Write-Host ""
    Write-Host "[OK] Correcao automatica concluida" -ForegroundColor Green
} else {
    Write-Host "[INFO] Execute sem -DryRun para aplicar as correcoes" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
