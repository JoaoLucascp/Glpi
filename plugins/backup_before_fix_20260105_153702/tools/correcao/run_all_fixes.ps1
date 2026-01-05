# ============================================================
# SCRIPT MASTER - MODERNIZACAO COMPLETA PLUGIN NEWBASE
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Executa todas as correcoes em ordem otimizada
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [string]$ScriptsPath = "D:\laragon\www\glpi\plugins\newbase\tools",
    [switch]$DryRun = $false,
    [switch]$SkipBackup = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  MODERNIZACAO COMPLETA - PLUGIN NEWBASE GLPI 10.0.20     " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

# Verificar se o diretorio existe
if (-not (Test-Path $PluginPath)) {
    Write-Host "[ERRO] Diretorio do plugin nao encontrado: $PluginPath" -ForegroundColor Red
    exit 1
}

# Verificar se o diretorio de scripts existe
if (-not (Test-Path $ScriptsPath)) {
    Write-Host "[ERRO] Diretorio de scripts nao encontrado: $ScriptsPath" -ForegroundColor Red
    Write-Host "[INFO] Scripts esperados em: $ScriptsPath" -ForegroundColor Yellow
    exit 1
}

# Lista de scripts a executar em ordem
$Scripts = @(
    @{
        Name = "audit_plugin_v2.ps1"
        Description = "Auditoria completa do plugin"
        Required = $false
    },
    @{
        Name = "fix_plugin_v2.ps1"
        Description = "Correcoes automaticas basicas"
        Required = $true
    },
    @{
        Name = "add_rightname.ps1"
        Description = "Adicionar \$rightname as classes"
        Required = $true
    },
    @{
        Name = "add_phpdoc.ps1"
        Description = "Adicionar PHPDoc as classes"
        Required = $true
    },
    @{
        Name = "fix_hook.ps1"
        Description = "Corrigir funcoes de hook"
        Required = $true
    }
)

Write-Host "Ordem de execucao:" -ForegroundColor Cyan
Write-Host ""
$i = 1
foreach ($script in $Scripts) {
    $status = if ($script.Required) { "[OBRIGATORIO]" } else { "[OPCIONAL]" }
    Write-Host "  $i. $($script.Name) - $($script.Description) $status" -ForegroundColor White
    $i++
}
Write-Host ""

if ($DryRun) {
    Write-Host "[INFO] Modo DRY-RUN ativo - executando pre-visualizacao..." -ForegroundColor Yellow
    Write-Host ""
}

# Criar backup antes de iniciar
if (-not $SkipBackup -and -not $DryRun) {
    Write-Host "============================================================" -ForegroundColor Yellow
    Write-Host "  ETAPA 0: CRIANDO BACKUP COMPLETO" -ForegroundColor Yellow
    Write-Host "============================================================" -ForegroundColor Yellow
    Write-Host ""
    
    $BackupTimestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $BackupName = "backup_before_modernization_$BackupTimestamp"
    $BackupDir = Join-Path (Split-Path $PluginPath -Parent) $BackupName
    
    Write-Host "[INFO] Criando backup em: $BackupDir" -ForegroundColor Cyan
    
    try {
        New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
        
        Get-ChildItem -Path $PluginPath -Recurse | Where-Object {
            $_.FullName -notmatch "\\backup_|\\vendor\\"
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
        Write-Host "[AVISO] Deseja continuar sem backup? (S/N)" -ForegroundColor Yellow
        $response = Read-Host
        if ($response -ne 'S' -and $response -ne 's') {
            Write-Host "[INFO] Operacao cancelada pelo usuario" -ForegroundColor Yellow
            exit 0
        }
    }
}

# Executar cada script
$StepNumber = 1
$SuccessCount = 0
$FailCount = 0
$SkipCount = 0

foreach ($script in $Scripts) {
    Write-Host ""
    Write-Host "============================================================" -ForegroundColor Yellow
    Write-Host "  ETAPA $StepNumber: $($script.Description)" -ForegroundColor Yellow
    Write-Host "============================================================" -ForegroundColor Yellow
    Write-Host ""
    
    $ScriptPath = Join-Path $ScriptsPath $script.Name
    
    if (-not (Test-Path $ScriptPath)) {
        Write-Host "[AVISO] Script nao encontrado: $ScriptPath" -ForegroundColor Yellow
        
        if ($script.Required) {
            Write-Host "[ERRO] Script obrigatorio ausente - abortando" -ForegroundColor Red
            $FailCount++
            break
        } else {
            Write-Host "[INFO] Script opcional - pulando" -ForegroundColor Cyan
            $SkipCount++
            $StepNumber++
            continue
        }
    }
    
    Write-Host "[INFO] Executando: $($script.Name)" -ForegroundColor Cyan
    Write-Host ""
    
    try {
        # Executar script
        $params = @{
            PluginPath = $PluginPath
        }
        
        if ($DryRun) {
            $params.DryRun = $true
        }
        
        & $ScriptPath @params
        
        if ($LASTEXITCODE -eq 0 -or $null -eq $LASTEXITCODE) {
            Write-Host ""
            Write-Host "[OK] Etapa $StepNumber concluida com sucesso" -ForegroundColor Green
            $SuccessCount++
        } else {
            Write-Host ""
            Write-Host "[ERRO] Etapa $StepNumber falhou (codigo: $LASTEXITCODE)" -ForegroundColor Red
            $FailCount++
            
            if ($script.Required) {
                Write-Host "[ERRO] Script obrigatorio falhou - abortando" -ForegroundColor Red
                break
            }
        }
    } catch {
        Write-Host ""
        Write-Host "[ERRO] Erro ao executar script: $_" -ForegroundColor Red
        $FailCount++
        
        if ($script.Required) {
            Write-Host "[ERRO] Script obrigatorio falhou - abortando" -ForegroundColor Red
            break
        }
    }
    
    $StepNumber++
}

# Resumo final
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  RESUMO DA MODERNIZACAO" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Total de etapas: $($Scripts.Count)" -ForegroundColor White
Write-Host "  [OK]    Sucesso: $SuccessCount" -ForegroundColor Green
Write-Host "  [ERRO]  Falha: $FailCount" -ForegroundColor Red
Write-Host "  [AVISO] Pulado: $SkipCount" -ForegroundColor Yellow
Write-Host ""

if ($FailCount -eq 0) {
    Write-Host "[OK] MODERNIZACAO CONCLUIDA COM SUCESSO!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Proximos passos:" -ForegroundColor Cyan
    Write-Host "  1. Revisar os relatorios em: $PluginPath\audit_reports\" -ForegroundColor White
    Write-Host "  2. Testar o plugin no GLPI" -ForegroundColor White
    Write-Host "  3. Verificar logs de erro: D:\laragon\www\glpi\files\_log\" -ForegroundColor White
} else {
    Write-Host "[AVISO] MODERNIZACAO CONCLUIDA COM ERROS" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Revise os erros acima e execute novamente" -ForegroundColor Yellow
    
    if (-not $DryRun -and (Test-Path $BackupDir)) {
        Write-Host ""
        Write-Host "[INFO] Backup disponivel em: $BackupDir" -ForegroundColor Cyan
        Write-Host "       Para restaurar, copie o conteudo de volta para: $PluginPath" -ForegroundColor Cyan
    }
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
