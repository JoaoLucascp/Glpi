# =========================================
# Script de Verificação MySQL - Laragon
# Plugin Newbase - GLPI 10.0.20
# =========================================

Write-Host "=================================" -ForegroundColor Cyan
Write-Host "VERIFICAÇÃO MYSQL - LARAGON" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan
Write-Host ""

# 1. Verificar Serviços MySQL
Write-Host "1. Verificando serviços MySQL/MariaDB..." -ForegroundColor Yellow
$mysqlServices = Get-Service | Where-Object { 
    $_.Name -like '*mysql*' -or 
    $_.Name -like '*mariadb*' 
}

if ($mysqlServices) {
    $mysqlServices | Format-Table Name, Status, DisplayName -AutoSize
} else {
    Write-Host "   [AVISO] Nenhum serviço MySQL/MariaDB encontrado!" -ForegroundColor Red
    Write-Host "   Laragon pode estar usando MySQL sem serviço Windows." -ForegroundColor Yellow
}
Write-Host ""

# 2. Verificar Porta 3306
Write-Host "2. Verificando porta 3306 (MySQL padrão)..." -ForegroundColor Yellow
$port3306 = Get-NetTCPConnection -LocalPort 3306 -ErrorAction SilentlyContinue

if ($port3306) {
    Write-Host "   [OK] Porta 3306 está ABERTA" -ForegroundColor Green
    $port3306 | Format-Table LocalAddress, LocalPort, State, OwningProcess -AutoSize
    
    # Descobrir qual processo está usando
    $processId = $port3306.OwningProcess | Select-Object -First 1
    $process = Get-Process -Id $processId -ErrorAction SilentlyContinue
    if ($process) {
        Write-Host "   Processo: $($process.ProcessName) (PID: $processId)" -ForegroundColor Cyan
    }
} else {
    Write-Host "   [ERRO] Porta 3306 NÃO está aberta!" -ForegroundColor Red
    Write-Host "   MySQL pode não estar rodando ou está em outra porta." -ForegroundColor Yellow
}
Write-Host ""

# 3. Verificar Processos MySQL
Write-Host "3. Verificando processos MySQL..." -ForegroundColor Yellow
$mysqlProcesses = Get-Process | Where-Object { 
    $_.ProcessName -like '*mysql*' -or 
    $_.ProcessName -like '*mariadb*' 
}

if ($mysqlProcesses) {
    Write-Host "   [OK] Processos MySQL encontrados:" -ForegroundColor Green
    $mysqlProcesses | Format-Table ProcessName, Id, CPU, WorkingSet -AutoSize
} else {
    Write-Host "   [AVISO] Nenhum processo MySQL rodando!" -ForegroundColor Red
}
Write-Host ""

# 4. Testar Conexão MySQL
Write-Host "4. Testando conexão MySQL (localhost:3306)..." -ForegroundColor Yellow
$testConnection = Test-NetConnection -ComputerName localhost -Port 3306 -WarningAction SilentlyContinue

if ($testConnection.TcpTestSucceeded) {
    Write-Host "   [OK] Conexão TCP bem-sucedida!" -ForegroundColor Green
} else {
    Write-Host "   [ERRO] Falha na conexão TCP!" -ForegroundColor Red
    Write-Host "   Possíveis causas:" -ForegroundColor Yellow
    Write-Host "   - MySQL não está rodando" -ForegroundColor Yellow
    Write-Host "   - Firewall bloqueando conexão" -ForegroundColor Yellow
    Write-Host "   - MySQL configurado em outra porta" -ForegroundColor Yellow
}
Write-Host ""

# 5. Verificar Arquivo de Configuração GLPI
Write-Host "5. Verificando configuração GLPI..." -ForegroundColor Yellow
$glpiConfigPath = "D:\laragon\www\glpi\config\config_db.php"

if (Test-Path $glpiConfigPath) {
    Write-Host "   [OK] Arquivo config_db.php encontrado" -ForegroundColor Green
    
    $configContent = Get-Content $glpiConfigPath -Raw
    
    # Extrair informações (sem mostrar senha)
    if ($configContent -match "dbhost\s*=\s*'([^']+)'") {
        Write-Host "   Host: $($Matches[1])" -ForegroundColor Cyan
    }
    if ($configContent -match "dbport\s*=\s*'([^']+)'") {
        Write-Host "   Porta: $($Matches[1])" -ForegroundColor Cyan
    }
    if ($configContent -match "dbdefault\s*=\s*'([^']+)'") {
        Write-Host "   Database: $($Matches[1])" -ForegroundColor Cyan
    }
    if ($configContent -match "dbuser\s*=\s*'([^']+)'") {
        Write-Host "   User: $($Matches[1])" -ForegroundColor Cyan
    }
} else {
    Write-Host "   [ERRO] config_db.php não encontrado!" -ForegroundColor Red
}
Write-Host ""

# 6. Verificar Firewall
Write-Host "6. Verificando regras de Firewall para MySQL..." -ForegroundColor Yellow
$firewallRules = Get-NetFirewallRule | Where-Object { 
    $_.DisplayName -like '*mysql*' -or 
    $_.DisplayName -like '*3306*' 
}

if ($firewallRules) {
    Write-Host "   [INFO] Regras de firewall encontradas:" -ForegroundColor Green
    $firewallRules | Format-Table DisplayName, Enabled, Direction, Action -AutoSize
} else {
    Write-Host "   [INFO] Nenhuma regra específica de MySQL no firewall" -ForegroundColor Yellow
    Write-Host "   (Isso é normal para Laragon local)" -ForegroundColor Cyan
}
Write-Host ""

# 7. DIAGNÓSTICO FINAL
Write-Host "=================================" -ForegroundColor Cyan
Write-Host "DIAGNÓSTICO" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan

if ($port3306 -and $testConnection.TcpTestSucceeded) {
    Write-Host "[OK] MySQL está rodando e acessível!" -ForegroundColor Green
    Write-Host ""
    Write-Host "PRÓXIMOS PASSOS:" -ForegroundColor Yellow
    Write-Host "1. Verifique os logs do GLPI em: D:\laragon\www\glpi\files\_log\" -ForegroundColor White
    Write-Host "2. Teste o plugin acessando o formulário de empresa" -ForegroundColor White
    Write-Host "3. Abra o Console do Navegador (F12) e verifique erros JS" -ForegroundColor White
} else {
    Write-Host "[ERRO] MySQL NÃO está acessível!" -ForegroundColor Red
    Write-Host ""
    Write-Host "SOLUÇÃO:" -ForegroundColor Yellow
    Write-Host "1. Abra o Laragon" -ForegroundColor White
    Write-Host "2. Clique em 'Start All'" -ForegroundColor White
    Write-Host "3. Aguarde MySQL iniciar (ícone verde)" -ForegroundColor White
    Write-Host "4. Execute este script novamente" -ForegroundColor White
}

Write-Host ""
Write-Host "=================================" -ForegroundColor Cyan
Write-Host "Script concluído!" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan
