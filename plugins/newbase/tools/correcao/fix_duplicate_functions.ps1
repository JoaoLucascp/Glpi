# ============================================================
# REMOVER FUNCOES DUPLICADAS - PLUGIN NEWBASE
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Remove plugin_newbase_install e uninstall do setup.php
# Problema: Funcoes declaradas em setup.php E hook.php
# Solucao: Manter apenas no hook.php (convencao GLPI)
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [switch]$DryRun = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  REMOVER FUNCOES DUPLICADAS - PLUGIN NEWBASE             " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

$SetupFile = Join-Path $PluginPath "setup.php"
$HookFile = Join-Path $PluginPath "hook.php"

# ============================================================
# VERIFICAR ARQUIVOS
# ============================================================
Write-Host "[INFO] Verificando arquivos..." -ForegroundColor Cyan

if (-not (Test-Path $SetupFile)) {
    Write-Host "[ERRO] setup.php nao encontrado: $SetupFile" -ForegroundColor Red
    exit 1
}

if (-not (Test-Path $HookFile)) {
    Write-Host "[ERRO] hook.php nao encontrado: $HookFile" -ForegroundColor Red
    exit 1
}

# ============================================================
# ANALISAR SETUP.PHP
# ============================================================
Write-Host ""
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
Write-Host "Analisando: setup.php" -ForegroundColor Cyan
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow

$SetupContent = Get-Content $SetupFile -Raw

# Verificar se tem install no setup.php
$HasInstallInSetup = $SetupContent -match 'function\s+plugin_newbase_install'
$HasUninstallInSetup = $SetupContent -match 'function\s+plugin_newbase_uninstall'

Write-Host "[INFO] plugin_newbase_install no setup.php: $HasInstallInSetup" -ForegroundColor Cyan
Write-Host "[INFO] plugin_newbase_uninstall no setup.php: $HasUninstallInSetup" -ForegroundColor Cyan

# ============================================================
# ANALISAR HOOK.PHP
# ============================================================
Write-Host ""
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
Write-Host "Analisando: hook.php" -ForegroundColor Cyan
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow

$HookContent = Get-Content $HookFile -Raw

# Verificar se tem install no hook.php
$HasInstallInHook = $HookContent -match 'function\s+plugin_newbase_install'
$HasUninstallInHook = $HookContent -match 'function\s+plugin_newbase_uninstall'

Write-Host "[INFO] plugin_newbase_install no hook.php: $HasInstallInHook" -ForegroundColor Cyan
Write-Host "[INFO] plugin_newbase_uninstall no hook.php: $HasUninstallInHook" -ForegroundColor Cyan

# ============================================================
# DECIDIR ACAO
# ============================================================
Write-Host ""
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
Write-Host "Decisao de correcao" -ForegroundColor Cyan
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow

if ($HasInstallInSetup -and $HasInstallInHook) {
    Write-Host "[AVISO] Funcao plugin_newbase_install DUPLICADA!" -ForegroundColor Yellow
    Write-Host "[ACAO]  Remover do setup.php (manter apenas no hook.php)" -ForegroundColor Cyan
    $NeedsFix = $true
} elseif ($HasInstallInSetup -and -not $HasInstallInHook) {
    Write-Host "[ERRO] Funcao install apenas no setup.php (deveria estar no hook.php)" -ForegroundColor Red
    Write-Host "[ACAO]  Mover para hook.php manualmente" -ForegroundColor Yellow
    $NeedsFix = $false
} elseif (-not $HasInstallInSetup -and $HasInstallInHook) {
    Write-Host "[OK] Funcao install apenas no hook.php (correto!)" -ForegroundColor Green
    $NeedsFix = $false
} else {
    Write-Host "[ERRO] Funcao install nao encontrada em nenhum arquivo!" -ForegroundColor Red
    $NeedsFix = $false
}

if (-not $NeedsFix) {
    Write-Host ""
    Write-Host "[INFO] Nenhuma correcao necessaria" -ForegroundColor Green
    exit 0
}

# ============================================================
# REMOVER FUNCOES DO SETUP.PHP
# ============================================================
Write-Host ""
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow
Write-Host "Removendo funcoes do setup.php" -ForegroundColor Cyan
Write-Host "------------------------------------------------------------" -ForegroundColor Yellow

# Padrao para encontrar a funcao install completa (incluindo corpo)
$InstallPattern = 'function\s+plugin_newbase_install\s*\([^)]*\)\s*\{[^}]*(?:\{[^}]*\}[^}]*)*\}'

# Padrao para encontrar a funcao uninstall completa
$UninstallPattern = 'function\s+plugin_newbase_uninstall\s*\([^)]*\)\s*\{[^}]*(?:\{[^}]*\}[^}]*)*\}'

$NewSetupContent = $SetupContent

# Remover install
if ($HasInstallInSetup) {
    # Usar regex mais simples - procurar pela funcao ate o ultimo }
    $InstallStart = [regex]::Match($NewSetupContent, 'function\s+plugin_newbase_install').Index
    $BraceCount = 0
    $InFunction = $false
    $InstallEnd = $InstallStart

    for ($i = $InstallStart; $i -lt $NewSetupContent.Length; $i++) {
        $char = $NewSetupContent[$i]

        if ($char -eq '{') {
            $BraceCount++
            $InFunction = $true
        } elseif ($char -eq '}') {
            $BraceCount--
            if ($InFunction -and $BraceCount -eq 0) {
                $InstallEnd = $i + 1
                break
            }
        }
    }

    if ($InstallEnd -gt $InstallStart) {
        $FunctionToRemove = $NewSetupContent.Substring($InstallStart, $InstallEnd - $InstallStart)
        Write-Host "[INFO] Encontrada funcao install (tamanho: $($FunctionToRemove.Length) chars)" -ForegroundColor Cyan

        $NewSetupContent = $NewSetupContent.Remove($InstallStart, $InstallEnd - $InstallStart)
        Write-Host "[OK] Funcao plugin_newbase_install removida" -ForegroundColor Green
    }
}

# Remover uninstall
if ($HasUninstallInSetup) {
    $UninstallStart = [regex]::Match($NewSetupContent, 'function\s+plugin_newbase_uninstall').Index
    $BraceCount = 0
    $InFunction = $false
    $UninstallEnd = $UninstallStart

    for ($i = $UninstallStart; $i -lt $NewSetupContent.Length; $i++) {
        $char = $NewSetupContent[$i]

        if ($char -eq '{') {
            $BraceCount++
            $InFunction = $true
        } elseif ($char -eq '}') {
            $BraceCount--
            if ($InFunction -and $BraceCount -eq 0) {
                $UninstallEnd = $i + 1
                break
            }
        }
    }

    if ($UninstallEnd -gt $UninstallStart) {
        $FunctionToRemove = $NewSetupContent.Substring($UninstallStart, $UninstallEnd - $UninstallStart)
        Write-Host "[INFO] Encontrada funcao uninstall (tamanho: $($FunctionToRemove.Length) chars)" -ForegroundColor Cyan

        $NewSetupContent = $NewSetupContent.Remove($UninstallStart, $UninstallEnd - $UninstallStart)
        Write-Host "[OK] Funcao plugin_newbase_uninstall removida" -ForegroundColor Green
    }
}

# Limpar linhas em branco duplicadas
$NewSetupContent = $NewSetupContent -replace "`n`n`n+", "`n`n"

# ============================================================
# SALVAR ALTERACOES
# ============================================================
if (-not $DryRun) {
    Write-Host ""
    Write-Host "[INFO] Salvando setup.php..." -ForegroundColor Cyan

    # Criar backup
    $BackupFile = $SetupFile -replace '\.php$', '.php.backup'
    Copy-Item $SetupFile $BackupFile -Force
    Write-Host "[OK] Backup criado: $BackupFile" -ForegroundColor Green

    # Salvar sem BOM
    $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
    [System.IO.File]::WriteAllText($SetupFile, $NewSetupContent, $Utf8NoBom)
    Write-Host "[OK] Arquivo setup.php atualizado" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "[DRY-RUN] As seguintes alteracoes seriam feitas:" -ForegroundColor Cyan
    Write-Host "- Remover plugin_newbase_install do setup.php" -ForegroundColor Gray
    Write-Host "- Remover plugin_newbase_uninstall do setup.php" -ForegroundColor Gray
}

# ============================================================
# RESUMO FINAL
# ============================================================
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  RESUMO" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Acoes executadas:" -ForegroundColor White
Write-Host "  [OK] Funcoes removidas do setup.php" -ForegroundColor Green
Write-Host "  [OK] Funcoes mantidas no hook.php" -ForegroundColor Green
Write-Host "  [OK] Backup criado: setup.php.backup" -ForegroundColor Green
Write-Host ""

if ($DryRun) {
    Write-Host "[INFO] Execute sem -DryRun para aplicar as alteracoes" -ForegroundColor Yellow
} else {
    Write-Host "[OK] Correcao concluida com sucesso!" -ForegroundColor Green
    Write-Host "[INFO] Teste novamente no GLPI: http://glpi.test/public" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "[IMPORTANTE] Se ainda houver erro, envie o novo log" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
