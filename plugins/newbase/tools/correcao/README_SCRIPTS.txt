# SCRIPTS DE MODERNIZACAO - PLUGIN NEWBASE
# ============================================================
# Versao: 2.0
# Data: 2026-01-05
# Compatibilidade: PowerShell 5.1+ (Windows)
# ============================================================

## ARQUIVOS INCLUSOS

1. audit_plugin_v2.ps1        - Auditoria completa
2. fix_plugin_v2.ps1          - Correcoes automaticas
3. add_rightname.ps1          - Adiciona $rightname
4. add_phpdoc.ps1             - Adiciona PHPDoc
5. fix_hook.ps1               - Corrige hook.php
6. run_all_fixes.ps1          - Script master (executa todos)
7. GUIA_COMPLETO_MODERNIZACAO.txt - Guia detalhado

## INSTALACAO

1. Copie TODOS os arquivos .ps1 para:
   D:\laragon\www\glpi\plugins\newbase\tools\

2. Abra PowerShell

3. Navegue ate a pasta:
   cd D:\laragon\www\glpi\plugins\newbase

## USO RAPIDO

OPCAO 1: Automatico (Recomendado)
----------------------------------
powershell.exe -ExecutionPolicy Bypass -File .\tools\run_all_fixes.ps1 -DryRun
powershell.exe -ExecutionPolicy Bypass -File .\tools\run_all_fixes.ps1

OPCAO 2: Manual
---------------
powershell.exe -ExecutionPolicy Bypass -File .\tools\audit_plugin_v2.ps1
powershell.exe -ExecutionPolicy Bypass -File .\tools\fix_plugin_v2.ps1
powershell.exe -ExecutionPolicy Bypass -File .\tools\add_rightname.ps1
powershell.exe -ExecutionPolicy Bypass -File .\tools\add_phpdoc.ps1
powershell.exe -ExecutionPolicy Bypass -File .\tools\fix_hook.ps1

## PRINCIPAIS PROBLEMAS CORRIGIDOS

[FIX] 2 arquivos sem declare(strict_types=1)
[FIX] 9 arquivos com BOM UTF-8
[FIX] 7 arquivos com 'use' incorretos
[FIX] Todas as classes sem $rightname
[FIX] 8 classes sem PHPDoc
[FIX] hook.php sem install/uninstall

## RESULTADO ESPERADO

- Score: 95-100/100
- Plugin 100% compativel GLPI 10.0.20
- Pronto para producao

## SUPORTE

Leia o arquivo: GUIA_COMPLETO_MODERNIZACAO.txt
