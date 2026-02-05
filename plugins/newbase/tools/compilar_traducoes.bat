@echo off
REM ========================================
REM Script para Compilar Traducoes
REM Plugin Newbase - GLPI 10.0.20
REM ========================================

echo.
echo ========================================
echo  Compilador de Traducoes - Newbase
echo ========================================
echo.

cd /d "%~dp0"

echo [1/3] Verificando arquivos .po...
if not exist "locales\pt_BR.po" (
    echo ERRO: Arquivo pt_BR.po nao encontrado!
    pause
    exit /b 1
)

if not exist "locales\en_GB.po" (
    echo ERRO: Arquivo en_GB.po nao encontrado!
    pause
    exit /b 1
)

echo      - pt_BR.po encontrado
echo      - en_GB.po encontrado
echo.

echo [2/3] Compilando traducoes...
php compile_locales.php
if errorlevel 1 (
    echo ERRO: Falha na compilacao!
    echo.
    echo Verifique se o PHP esta instalado e no PATH.
    echo Ou use o terminal do Laragon: Menu Laragon ^> Terminal
    pause
    exit /b 1
)
echo.

echo [3/3] Verificando arquivos .mo gerados...
if exist "locales\pt_BR.mo" (
    echo      - pt_BR.mo criado com sucesso!
) else (
    echo      - AVISO: pt_BR.mo nao foi gerado
)

if exist "locales\en_GB.mo" (
    echo      - en_GB.mo criado com sucesso!
) else (
    echo      - AVISO: en_GB.mo nao foi gerado
)
echo.

echo ========================================
echo  Compilacao Concluida!
echo ========================================
echo.
echo Proximos passos:
echo 1. Reinicie o Apache (F12 no Laragon)
echo 2. Limpe o cache do navegador (Ctrl+Shift+Del)
echo 3. No GLPI, va em Meu perfil ^> Idioma
echo 4. Escolha o idioma desejado
echo 5. Recarregue a pagina do plugin
echo.

pause
