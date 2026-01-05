# ============================================================
# SCRIPT PARA CORRIGIR HOOK.PHP - PLUGIN NEWBASE
# ============================================================
# Autor: Joao Lucas
# Data: 2026-01-05
# Descricao: Adiciona funcoes install/uninstall ao hook.php
# Compatibilidade: PowerShell 5.1+ (Windows)
# Encoding: UTF-8 sem BOM / ASCII only
# ============================================================

param(
    [string]$PluginPath = "D:\laragon\www\glpi\plugins\newbase",
    [switch]$DryRun = $false
)

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  CORRIGIR HOOK.PHP - PLUGIN NEWBASE                       " -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""

if ($DryRun) {
    Write-Host "[MODO DRY-RUN] Nenhuma alteracao sera feita" -ForegroundColor Yellow
    Write-Host ""
}

$HookFile = Join-Path $PluginPath "hook.php"

if (-not (Test-Path $HookFile)) {
    Write-Host "[ERRO] Arquivo hook.php nao encontrado em: $HookFile" -ForegroundColor Red
    exit 1
}

Write-Host "[INFO] Lendo hook.php..." -ForegroundColor Cyan

$Content = Get-Content $HookFile -Raw

# Verificar se ja tem as funcoes
$HasInstall = $Content -match "function plugin_newbase_install\("
$HasUninstall = $Content -match "function plugin_newbase_uninstall\("

if ($HasInstall -and $HasUninstall) {
    Write-Host "[OK] hook.php ja possui as funcoes install e uninstall" -ForegroundColor Green
    exit 0
}

Write-Host ""
Write-Host "Status atual do hook.php:" -ForegroundColor Yellow
Write-Host "  - plugin_newbase_install(): $(if ($HasInstall) { '[OK] Presente' } else { '[ERRO] Ausente' })" -ForegroundColor $(if ($HasInstall) { 'Green' } else { 'Red' })
Write-Host "  - plugin_newbase_uninstall(): $(if ($HasUninstall) { '[OK] Presente' } else { '[ERRO] Ausente' })" -ForegroundColor $(if ($HasUninstall) { 'Green' } else { 'Red' })
Write-Host ""

# Funcao de instalacao
$InstallFunction = @'

/**
 * Funcao de instalacao do plugin
 *
 * @return boolean
 */
function plugin_newbase_install()
{
    global $DB;
    
    $migration = new Migration(PLUGIN_NEWBASE_VERSION);
    
    // Carregar SQL de instalacao
    $sqlFile = PLUGIN_NEWBASE_DIR . '/install/mysql/2.0.0.sql';
    
    if (!file_exists($sqlFile)) {
        echo "Arquivo SQL nao encontrado: $sqlFile\n";
        return false;
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Dividir em comandos individuais
    $commands = array_filter(
        array_map('trim', explode(';', $sql)),
        function ($cmd) {
            return !empty($cmd);
        }
    );
    
    // Executar cada comando
    foreach ($commands as $command) {
        if (!empty($command)) {
            try {
                $DB->query($command) or die("Erro ao executar: $command\n" . $DB->error());
            } catch (Exception $e) {
                echo "Erro na instalacao: " . $e->getMessage() . "\n";
                return false;
            }
        }
    }
    
    $migration->executeMigration();
    
    return true;
}
'@

# Funcao de desinstalacao
$UninstallFunction = @'

/**
 * Funcao de desinstalacao do plugin
 *
 * @return boolean
 */
function plugin_newbase_uninstall()
{
    global $DB;
    
    $tables = [
        'glpi_plugin_newbase_companydatas',
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_tasksignatures',
        'glpi_plugin_newbase_configs'
    ];
    
    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`") or die("Erro ao remover tabela $table\n");
    }
    
    // Remover direitos
    $query = "DELETE FROM `glpi_profilerights` 
              WHERE `name` LIKE 'plugin_newbase_%'";
    $DB->query($query);
    
    // Remover displays
    $query = "DELETE FROM `glpi_displaypreferences` 
              WHERE `itemtype` LIKE 'PluginNewbase%'";
    $DB->query($query);
    
    return true;
}
'@

# Adicionar funcoes ao final do arquivo (antes do ?>)
if ($Content -match '\?>$') {
    # Tem ?> no final
    $NewContent = $Content -replace '\?>', ($InstallFunction + "`n" + $UninstallFunction + "`n?>")
} else {
    # Nao tem ?> no final
    $NewContent = $Content + "`n" + $InstallFunction + "`n" + $UninstallFunction + "`n"
}

if ($DryRun) {
    Write-Host "[DRY-RUN] As seguintes funcoes seriam adicionadas:" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. plugin_newbase_install()" -ForegroundColor Yellow
    Write-Host "   - Le e executa o arquivo SQL de instalacao" -ForegroundColor Gray
    Write-Host "   - Cria todas as tabelas necessarias" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. plugin_newbase_uninstall()" -ForegroundColor Yellow
    Write-Host "   - Remove todas as tabelas do plugin" -ForegroundColor Gray
    Write-Host "   - Remove direitos e preferencias" -ForegroundColor Gray
    Write-Host ""
} else {
    # Salvar arquivo
    $Utf8NoBom = New-Object System.Text.UTF8Encoding $false
    [System.IO.File]::WriteAllText($HookFile, $NewContent, $Utf8NoBom)
    
    Write-Host "[FIX] Funcoes adicionadas ao hook.php:" -ForegroundColor Green
    Write-Host "  - plugin_newbase_install()" -ForegroundColor Cyan
    Write-Host "  - plugin_newbase_uninstall()" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "[OK] hook.php corrigido com sucesso" -ForegroundColor Green
}

Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host ""
