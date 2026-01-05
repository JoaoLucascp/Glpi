# GUIA COMPLETO     - MODERNIZACAO PLUGIN NEWBASE

**Versao:** 2.0
**Data:** 2026-01-05
**Autor:** Joao Lucas


## INDICE

1. ARQUIVOS FORNECIDOS
2. ANALISE DOS RELATORIOS
3. PROBLEMAS ENCONTRADOS
4. SCRIPTS DE CORRECAO
5. ORDEM DE EXECUCAO
6. PASSO A PASSO DETALHADO
7. SOLUCAO DE PROBLEMAS
8. CHECKLIST FINAL

## 1. ARQUIVOS FORNECIDOS

Voce recebeu 6 scripts PowerShell para modernizacao completa:

1. audit_plugin_v2.ps1
       - Auditoria completa do plugin
       - Gera relatorio detalhado
       - Calcula score de qualidade

2. fix_plugin_v2.ps1
       - Correcoes automaticas basicas
       - Adiciona strict_types
       - Remove 'use' incorretos
       - Remove BOM UTF-8

3. add_rightname.ps1
       - Adiciona $rightname a todas as classes
       - Necessario para permissoes GLPI

4. add_phpdoc.ps1
       - Adiciona PHPDoc completo
       - Documentacao de classes

5. fix_hook.ps1
       - Adiciona funcoes install/uninstall
       - Corrige hook.php

6. run_all_fixes.ps1
       - Script master
       - Executa todos os scripts em ordem
       - Cria backup automatico

## 2. ANALISE DOS RELATORIOS

RELATORIO DE AUDITORIA (audit_20260105_141615.txt):

[OK] Arquivos PHP: 35 encontrados
[OK] Sintaxe PHP: Todos validos (35/35)
[AVISO] Padroes de risco: 101 ocorrencias encontradas
[AVISO] Strict types: 2 arquivos sem
[ERRO] Score: 0/100 (calculo incorreto no script antigo)

PRINCIPAIS PROBLEMAS:

1. Uso de $_GET e $_POST direto (101 ocorrencias)
       - Risco: Medio
       - Solucao: Validar com filter_input() ou GLPI methods

2. Uso de curl_exec() (5 ocorrencias)
       - Risco: Alto (falso positivo     - curl_exec e seguro)
       - Localizacao: ajax/cnpj_proxy.php, src/Common.php

3. Strict types faltando (2 arquivos)
       - ajax/cnpj_proxy.php
       - tools/cleanup_db.php
       - Solucao: fix_plugin_v2.ps1

4. BOM UTF-8 em 9 arquivos
       - Causa warnings no PHP
       - Solucao: fix_plugin_v2.ps1

5. $rightname ausente em TODAS as classes
       - Critical: Sem isso, permissoes nao funcionam
       - Solucao: add_rightname.ps1

6. PHPDoc faltando em 8 classes
       - Importante: Documentacao
       - Solucao: add_phpdoc.ps1

7. hook.php sem install/uninstall
       - Critical: Plugin nao pode ser instalado
       - Solucao: fix_hook.ps1

## 3. PROBLEMAS ENCONTRADOS (PRIORIZADOS)

PRIORIDADE CRITICA (RESOLVER PRIMEIRO):

[!] hook.php sem funcoes install/uninstall
    Script: fix_hook.ps1
    Impacto: Plugin nao instala no GLPI

[!] Todas as classes sem $rightname
    Script: add_rightname.ps1
    Impacto: Sistema de permissoes nao funciona

PRIORIDADE ALTA:

[!] 2 arquivos sem declare(strict_types=1)
    Script: fix_plugin_v2.ps1
    Impacto: Tipagem fraca, bugs possiveis

[!] 9 arquivos com BOM UTF-8
    Script: fix_plugin_v2.ps1
    Impacto: Warnings no GLPI

PRIORIDADE MEDIA:

[ ] 8 classes sem PHPDoc
    Script: add_phpdoc.ps1
    Impacto: Documentacao incompleta

[ ] 101 padroes de risco ($_GET, $_POST)
    Script: Manual (validacao)
    Impacto: Seguranca

## 4. SCRIPTS DE CORRECAO

### AUDIT_PLUGIN_V2.PS1

**O que faz:**
    - Lista todos os arquivos PHP
    - Valida sintaxe PHP
    - Busca padroes de risco
    - Verifica arquivos obrigatorios
    - Verifica strict_types
    - Gera relatorio completo

**Quando usar:**
    - Antes de iniciar correcoes
    - Apos aplicar correcoes
    - Para monitorar qualidade

**Exemplo:**
powershell.exe -ExecutionPolicy Bypass -File audit_plugin_v2.ps1

### FIX_PLUGIN_V2.PS1

**O que faz:**
    - Adiciona declare(strict_types=1)
    - Remove 'use' incorretos
    - Remove BOM UTF-8
    - Verifica PHPDoc

**Quando usar:**
    - Apos auditoria
    - Antes de outros scripts

**Exemplo:**
*Testar sem modificar:*
powershell.exe -ExecutionPolicy Bypass -File fix_plugin_v2.ps1 -DryRun

**Aplicar correcoes:**

powershell.exe -ExecutionPolicy Bypass -File fix_plugin_v2.ps1

### ADD_RIGHTNAME.PS1

**O que faz:**
    - Adiciona $rightname a todas as classes
    - Define permissoes corretas

**Classes afetadas:**
    - CompanyData
    - Address
    - System
    - Task
    - TaskSignature
    - Config

**Exemplo:**
powershell.exe -ExecutionPolicy Bypass -File add_rightname.ps1


### ADD_PHPDOC.PS1

**O que faz:**
    - Adiciona PHPDoc completo
    - Documenta classes

**Exemplo:**
powershell.exe -ExecutionPolicy Bypass -File add_phpdoc.ps1

### FIX_HOOK.PS1

**O que faz:**
    - Adiciona plugin_newbase_install()
    - Adiciona plugin_newbase_uninstall()
    - Corrige hook.php

**Exemplo:**
powershell.exe -ExecutionPolicy Bypass -File fix_hook.ps1

### RUN_ALL_FIXES.PS1

**O que faz:**
    - Executa todos os scripts em ordem
    - Cria backup automatico
    - Gera relatorio final

**Exemplo:**
*Testar sem modificar:*
powershell.exe -ExecutionPolicy Bypass -File run_all_fixes.ps1 -DryRun

*Aplicar todas as correcoes:*
  powershell.exe -ExecutionPolicy Bypass -File run_all_fixes.ps1

## 5. ORDEM DE EXECUCAO RECOMENDADA

```powershell
### OPCAO 1: USAR SCRIPT MASTER (RECOMENDADO)

1. Copiar todos os scripts para:
D:\laragon\www\glpi\plugins\newbase\tools\

2. Executar script master:
powershell.exe -ExecutionPolicy Bypass -File run_all_fixes.ps1 -DryRun

3. Se tudo OK, executar de verdade:
powershell.exe -ExecutionPolicy Bypass -File run_all_fixes.ps1

Pronto! Todos os scripts serao executados automaticamente.

### OPCAO 2: EXECUTAR MANUALMENTE (CONTROLE TOTAL)-----

1. AUDITORIA INICIAL
powershell.exe -ExecutionPolicy Bypass -File audit_plugin_v2.ps1

2. CORRECOES BASICAS
powershell.exe -ExecutionPolicy Bypass -File fix_plugin_v2.ps1

3. ADICIONAR RIGHTNAME
powershell.exe -ExecutionPolicy Bypass -File add_rightname.ps1

4. ADICIONAR PHPDOC
powershell.exe -ExecutionPolicy Bypass -File add_phpdoc.ps1

5. CORRIGIR HOOK
powershell.exe -ExecutionPolicy Bypass -File fix_hook.ps1

6. AUDITORIA FINAL
powershell.exe -ExecutionPolicy Bypass -File audit_plugin_v2.ps1
```

## 6. PASSO A PASSO DETALHADO

```Powershell
### ETAPA 1: PREPARACAO

1. Abra o PowerShell (nao precisa ser administrador)

2. Navegue ate a pasta do plugin:
   cd D:\laragon\www\glpi\plugins\newbase

3. Crie a pasta tools se nao existir:
   mkdir tools -Force

4. Copie todos os 6 scripts .ps1 para a pasta tools/


### ETAPA 2: TESTE (DRY-RUN)

Teste sem modificar os arquivos:

1. Script master:
   .\tools\correcao\run_all_fixes.ps1 -DryRun

2. Ou scripts individuais:
   .\tools\correcao\audit_plugin_v2.ps1
   .\tools\correcao\fix_plugin_v2.ps1 -DryRun
   .\tools\correcao\add_rightname.ps1 -DryRun
   .\tools\correcao\add_phpdoc.ps1 -DryRun
   .\tools\correcao\fix_hook.ps1 -DryRun

3. Revise a saida     - deve mostrar o que seria feito


### ETAPA 3: BACKUP MANUAL (OPCIONAL)

Se quiser backup manual adicional:

1. Copie a pasta completa:
   Copy-Item -Path "D:\laragon\www\glpi\plugins\newbase" `
             -Destination "D:\laragon\www\glpi\plugins\newbase_backup_manual" `
             -Recurse

2. Ou use 7-Zip / WinRAR para criar arquivo compactado


### ETAPA 4: APLICAR CORRECOES-

Opcao A: Automatico (recomendado)
   .\tools\correcao\run_all_fixes.ps1

Opcao B: Manual
   .\tools\correcao\fix_plugin_v2.ps1
   .\tools\correcao\add_rightname.ps1
   .\tools\correcao\add_phpdoc.ps1
   .\tools\correcao\fix_hook.ps1


ETAPA 5: VERIFICACAO------

1. Execute auditoria final:
   .\tools\correcao\audit_plugin_v2.ps1

2. Verifique o relatorio:
   notepad .\audit_reports\audit_YYYYMMDD_HHMMSS.txt

3. Score deve ser > 90/100


ETAPA 6: TESTE NO GLPI--------

1. Acesse o GLPI:
   http://glpi.test/public

2. Va em: Configurar > Plugins

3. Se o plugin ja estiver instalado:
       - Desinstale
       - Reinstale

4. Se nao estiver instalado:
       - Instale
       - Ative

5. Teste as funcionalidades:
       - Criar empresa
       - Adicionar endereco
       - Criar sistema
       - Criar tarefa

6. Verifique logs:
   D:\laragon\www\glpi\files\_log\php-errors.log

## 7. SOLUCAO DE PROBLEMAS

ERRO: "Execucao de scripts esta desabilitada"---

Solucao:
  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

Ou execute com:
  powershell.exe -ExecutionPolicy Bypass -File script.ps1


ERRO: "PHP nao encontrado"-

Solucao 1: Ajustar caminho do PHP no script
  Abra audit_plugin_v2.ps1
  Linha 64: $PhpExe = "D:\laragon\bin\php\php-8.3.26\php.exe"
  Ajuste para seu caminho do PHP

Solucao 2: Adicionar PHP ao PATH
  1. Painel de Controle > Sistema
  2. Configuracoes Avancadas > Variaveis de Ambiente
  3. Adicione: D:\laragon\bin\php\php-8.3.26
  4. Reinicie PowerShell


ERRO: "Arquivo nao encontrado"---

Verifique:
  1. Scripts estao em: D:\laragon\www\glpi\plugins\newbase\tools\
  2. Plugin esta em: D:\laragon\www\glpi\plugins\newbase\
  3. Caminhos corretos nos parametros


ERRO: "Backup falhou"-------

Causas:
      - Disco cheio
      - Permissoes insuficientes
      - Caminhos muito longos

Solucao:
  Execute com -SkipBackup (nao recomendado):
  .\tools\correcao\run_all_fixes.ps1 -SkipBackup


AVISO: "Classes sem PHPDoc"

Normal. Execute:
  .\tools\correcao\add_phpdoc.ps1


AVISO: "Padroes de risco encontrados"----------

Validacao manual necessaria:
      - $_GET / $_POST: Adicionar validacao
      - curl_exec: Ja esta seguro
      - exec: Revisar uso


## 8. CHECKLIST FINAL

ANTES DE INICIAR:---
[ ] PowerShell funcionando
[ ] Scripts copiados para tools/
[ ] Backup manual criado (opcional)
[ ] GLPI funcionando
[ ] MySQL funcionando


APOS EXECUTAR SCRIPTS:--------
[ ] Todos os scripts executaram sem erro
[ ] Relatorio de auditoria revisado
[ ] Score > 90/100
[ ] Sem erros de sintaxe PHP
[ ] Sem warnings de BOM


TESTE NO GLPI:
[ ] Plugin desinstalado
[ ] Plugin reinstalado com sucesso
[ ] Menu Newbase aparece
[ ] Possivel criar empresa
[ ] Possivel adicionar endereco
[ ] Possivel criar sistema
[ ] Possivel criar tarefa
[ ] Sem erros em php-errors.log


DOCUMENTACAO:
-
[ ] README.md atualizado
[ ] CHANGELOG.md atualizado
[ ] Relatorios salvos
[ ] Backup arquivado


## RESULTADO ESPERADO

Apos seguir todos os passos:

[OK] Plugin 100% compativel com GLPI 10.0.20
[OK] Codigo PSR-12 compliant
[OK] Strict types em todos os arquivos
[OK] $rightname em todas as classes
[OK] PHPDoc completo
[OK] hook.php funcional
[OK] Sistema de permissoes funcionando
[OK] Pronto para producao

Score esperado: 95-100/100


## SUPORTE

Se encontrar problemas:

1. Verifique logs:
   D:\laragon\www\glpi\files\_log\php-errors.log

2. Revise relatorios:
   D:\laragon\www\glpi\plugins\newbase\audit_reports\

3. Teste com DryRun primeiro:
   .\tools\correcao\run_all_fixes.ps1 -DryRun

4. Restaure backup se necessario:
   Copy-Item -Path "backup_*" -Destination "newbase" -Recurse -Force


## FIM DO GUIA

Boa sorte com a modernizacao do plugin!

Para duvidas ou problemas, consulte:
    - Documentacao GLPI: https://glpi-developer-documentation.readthedocs.io/
    - Forum GLPI: https://forum.glpi-project.org/
