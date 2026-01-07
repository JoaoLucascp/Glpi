@echo off
REM ═══════════════════════════════════════════════════════════════════════════
REM Instalador Rapido - Patch Newbase
REM Versão 1.0.0
REM Sistema: Windows + Laragon
REM ═══════════════════════════════════════════════════════════════════════════

setlocal enabledelayedexpansion

REM Cores (códigos ANSI para Windows Terminal)
set "GREEN=[92m"
set "RED=[91m"
set "YELLOW=[93m"
set "BLUE=[94m"
set "RESET=[0m"

echo.
echo NEWBASE - INSTALADOR DO PATCH AUTOMATICO
echo Versao 1.0.0
echo.

REM Detectar se está rodando como administrador
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERRO: Este script deve ser executado como Administrador
    echo.
    echo Passos:
    echo   1. Pressione Win + X
    echo   2. Selecione "Terminal (Administrador)"
    echo   3. Navegue até: D:\laragon\www\glpi\plugins\newbase
    echo   4. Execute: install_patch.bat
    echo.
    pause
    exit /b 1
)

echo Executando como Administrador
echo.

REM Detectar paths
set "LARAGON_PATH=d:\laragon"
set "PLUGIN_PATH=%LARAGON_PATH%\www\glpi\plugins\newbase"
set "GLPI_PATH=%LARAGON_PATH%\www\glpi"

REM Verificar se Laragon existe
if not exist "%LARAGON_PATH%" (
    echo Laragon não encontrado em: %LARAGON_PATH
    echo.
    echo Verificar:
    echo   - Laragon esta instalado?
    echo   - Esta no caminho padrao d:\laragon?
    echo.
    pause
    exit /b 1
)

echo Laragon detectado: %LARAGON_PATH

REM Verificar se plugin existe
if not exist "%PLUGIN_PATH%" (
    echo Plugin Newbase nao encontrado em: %PLUGIN_PATH
    echo.
    pause
    exit /b 1
)

echo Plugin Newbase detectado: %PLUGIN_PATH
echo.

REM Criar diretório tools se não existir
if not exist "%PLUGIN_PATH%\tools" (
    echo Criando diretorio tools...
    mkdir "%PLUGIN_PATH%\tools"
    echo Diretorio criado
)

REM Verificar se o arquivo de patch já existe
if exist "%PLUGIN_PATH%\tools\fix_newbase_errors.php" (
    echo Arquivo fix_newbase_errors.php ja existe
    set /p "overwrite=Deseja sobrescrever? (S/N): "
    if /i not "!overwrite!"=="S" (
        echo Instalacao cancelada
        pause
        exit /b 0
    )
)

echo.
echo INICIANDO PATCH
echo.

REM Executar o patch
cd "%PLUGIN_PATH%\tools"

REM Detectar caminho do PHP no Laragon
set "PHP_PATH=%LARAGON_PATH%\bin\php\php8.3.26\php.exe"

REM Se não encontrar, procurar por versão alternativa
if not exist "%PHP_PATH%" (
    for /r "%LARAGON_PATH%\bin\php" %%F in (php.exe) do (
        set "PHP_PATH=%%F"
        goto :found_php
    )
)

:found_php
if not exist "%PHP_PATH%" (
    echo PHP nao encontrado no Laragon!
    echo.
    pause
    exit /b 1
)

echo PHP encontrado: %PHP_PATH
echo.

REM Executar script de correção
"%PHP_PATH%" fix_newbase_errors.php

if %errorLevel% equ 0 (
    echo.
    echo PATCH EXECUTADO COM SUCESSO
    echo.
    echo PROXIMOS PASSOS:%RESET%
    echo.
    echo 1. Abra seu navegador: http://localhost/glpi
    echo    (ou http://glpi.test/public se usar VHOST)
    echo.
    echo 2. Acesse como administrador (Login: glpi, Senha: glpi)
    echo.
    echo 3. Va em: Configurar ^> Plugins
    echo.
    echo 4. Localize "NewBase" e:
    echo    - Clique em "Desinstalar"
    echo    - Aguarde completar
    echo    - Clique em "Instalar"
    echo    - Clique em "Ativar"
    echo.
    echo 5. Teste as funcionalidades:
    echo    - Plugins ^> NewBase ^> Dados de Empresas
    echo    - Plugins ^> NewBase ^> Tarefas
    echo    - Dashboard (volte à tela inicial)
    echo.
    echo 6. Verifique se nao ha mais erros
    echo.
    echo Backup salvo em: %PLUGIN_PATH%\backup_fixes_*
    echo.
) else (
    echo.
    echo ERRO NA EXECUCAO DO PATCH
    echo.
    echo Verifique os erros acima e tente novamente.
    echo.
)

pause
