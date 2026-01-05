# INSTRUÇÕES PARA CORREÇÃO DO BOM UTF-8

## PROBLEMA IDENTIFICADO
Erro: `Fatal error: strict_types declaration must be the very first statement`

**Causa:** Arquivos PHP contêm BOM UTF-8 (bytes EF BB BF) no início

## SOLUÇÃO - OPÇÃO 1: AUTOMÁTICA (RECOMENDADO)

1. Abra o explorador de arquivos em:
   ```
   D:\laragon\www\glpi\plugins\newbase
   ```

2. Dê um duplo clique no arquivo:
   ```
   EXECUTAR_REMOCAO_BOM.bat
   ```

3. Aguarde a execução do script

4. Teste o resultado:
   ```powershell
   cd D:\laragon\www\glpi\plugins\newbase
   composer dump-autoload
   php -r "require 'vendor/autoload.php'; var_dump(class_exists('GlpiPlugin\Newbase\Config'));"
   ```
   
   **Resultado esperado:** `bool(true)` (sem erros)

## SOLUÇÃO - OPÇÃO 2: MANUAL VIA POWERSHELL

```powershell
cd D:\laragon\www\glpi\plugins\newbase
python REMOVER_BOM_FINAL.py
```

## SOLUÇÃO - OPÇÃO 3: MANUAL VIA NOTEPAD++

1. Abra cada arquivo PHP em Notepad++
2. Menu: Encoding → Convert to UTF-8 without BOM
3. Salve o arquivo (Ctrl+S)

**Arquivos a corrigir:**
- src/Config.php
- src/Address.php
- src/Common.php
- src/CompanyData.php
- src/System.php
- src/Task.php
- src/TaskSignature.php
- src/Ajax/AddressHandler.php
- setup.php
- hook.php

## VERIFICAÇÃO FINAL

Após remover o BOM de todos os arquivos:

```powershell
cd D:\laragon\www\glpi\plugins\newbase
composer dump-autoload
php -r "require 'vendor/autoload.php'; var_dump(class_exists('GlpiPlugin\Newbase\Config'));"
```

Se retornar `bool(true)`, o problema está resolvido!

## REINSTALAR O PLUGIN NO GLPI

1. Acesse: http://localhost/glpi
2. Menu: Configurar → Plugins
3. Localize: Newbase
4. Clique em: Instalar
5. Clique em: Ativar

## ARQUIVOS CRIADOS PARA VOCÊ

✅ `REMOVER_BOM_FINAL.py` - Script Python para remoção automática
✅ `EXECUTAR_REMOCAO_BOM.bat` - Atalho para executar o script
✅ `INSTRUCOES_CORRECAO_BOM.md` - Este arquivo de instruções

---

**Data:** 2026-01-01
**Status:** Pronto para execução
