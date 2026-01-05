# ============================================================
# SCRIPT PARA ADICIONAR PHPDOC AS CLASSES - NEWBASE
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Adiciona PHPDoc completo a todas as classes
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [switch]$DryRun = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  ADICIONAR PHPDOC AS CLASSES - PLUGIN NEWBASE            " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

# Classes principais e suas descricoes
$Classes = @(
    @{
        File = "src\CompanyData.php"
        Class = "CompanyData"
        Description = "Gerenciamento de dados de empresas (CNPJ, razao social, contatos)"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\Address.php"
        Class = "Address"
        Description = "Gerenciamento de enderecos com geocodificacao e busca por CEP"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\System.php"
        Class = "System"
        Description = "Gerenciamento de sistemas telefonicos (IPBX, PABX, Chatbot)"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\Task.php"
        Class = "Task"
        Description = "Gerenciamento de tarefas com geolocalizacao e assinatura digital"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\TaskSignature.php"
        Class = "TaskSignature"
        Description = "Gerenciamento de assinaturas digitais de tarefas"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\Config.php"
        Class = "Config"
        Description = "Gerenciamento de configuracoes do plugin"
        Package = "PluginNewbase"
        Since = "2.0.0"
    },
    @{
        File = "src\Common.php"
        Class = "Common"
        Description = "Classe base com metodos utilitarios comuns"
        Package = "PluginNewbase"
        Since = "2.0.0"
    }
)

$Fixed = 0
$AlreadyOk = 0
$Failed = 0

foreach ($classInfo in $Classes) {
    $FilePath = Join-Path $PluginPath $classInfo.File
    $ClassName = $classInfo.Class
    
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
    
    # Verificar se ja tem PHPDoc antes da classe
    if ($Content -match "/\*\*[^*]*@package[^*]*\*/\s*class\s+$ClassName") {
        Write-Host "[OK] Classe ja possui PHPDoc completo" -ForegroundColor Green
        $AlreadyOk++
        continue
    }
    
    # Criar PHPDoc
    $PHPDoc = @"
/**
 * $($classInfo.Description)
 *
 * @package   $($classInfo.Package)
 * @author    Joao Lucas
 * @copyright Copyright (c) 2025 Joao Lucas
 * @license   GPLv2+
 * @since     $($classInfo.Since)
 */
"@
    
    # Encontrar a posicao da declaracao da classe
    if ($Content -match "(class\s+$ClassName)") {
        $ClassDeclaration = $matches[1]
        
        # Inserir PHPDoc antes da classe
        $NewContent = $Content -replace [regex]::Escape($ClassDeclaration), ($PHPDoc + "`n" + $ClassDeclaration)
        
        if (-not $DryRun) {
            # Salvar sem BOM
            $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllText($FilePath, $NewContent, $Utf8NoBom)
            Write-Host "[FIX] PHPDoc adicionado" -ForegroundColor Green
        } else {
            Write-Host "[DRY-RUN] Seria adicionado PHPDoc:" -ForegroundColor Cyan
            Write-Host $PHPDoc -ForegroundColor Gray
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
Write-Host "  [OK]   Classes ja com PHPDoc: $AlreadyOk" -ForegroundColor Green
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
