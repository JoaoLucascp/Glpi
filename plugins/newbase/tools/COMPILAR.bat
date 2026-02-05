@echo off
chcp 65001 >nul
echo.
echo =========================================
echo   Compilando Traducoes - Newbase Plugin
echo =========================================
echo.

cd /d "%~dp0locales"

echo [INFO] Compilando pt_BR...
php -r "exec('msgfmt -o pt_BR.mo pt_BR.po 2>&1', $out, $ret); if ($ret===0) echo 'OK'; else system('php ../compile_locales.php');"
if exist pt_BR.mo (
    echo [OK] pt_BR.mo gerado
) else (
    echo [AVISO] Usando fallback PHP...
    cd ..
    php compile_locales.php
    cd locales
)

echo.
echo [INFO] Compilando en_GB...
php -r "exec('msgfmt -o en_GB.mo en_GB.po 2>&1', $out, $ret); if ($ret===0) echo 'OK'; else echo 'Usando fallback';"
if not exist en_GB.mo (
    cd ..
    php compile_locales.php
    cd locales
)

echo.
echo =========================================
echo   Compilacao Concluida!
echo =========================================
echo.

if exist pt_BR.mo echo [√] pt_BR.mo
if exist en_GB.mo echo [√] en_GB.mo

echo.
echo Proximo passo:
echo 1. Reinicie o Apache (Laragon: F12)
echo 2. Limpe cache do navegador
echo 3. Teste o plugin no GLPI
echo.
pause
