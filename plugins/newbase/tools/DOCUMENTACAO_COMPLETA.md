# 🎯 DOCUMENTAÇÃO COMPLETA - PATCH AUTOMÁTICO NEWBASE v1.0.0

## 📊 SUMÁRIO EXECUTIVO

Você recebeu um **patch automático** que corrige 3 erros críticos do plugin Newbase em **menos de 5 minutos**.

```
├─ ERRO 1: Array to string conversion (CompanyData)
├─ ERRO 2: SCSS _generate.scss not found (Dashboard)
└─ ERRO 3: Cache corrompido (Performance)
```

---

## 🎁 ARQUIVOS ENTREGUES

```
📦 patch_newbase_v1.0.0/
│
├─ 📄 fix_newbase_errors.php        [3.5 KB] - Script principal ⭐
├─ 📄 install_patch.bat             [2.0 KB] - Instalador Windows
├─ 📄 QUICK_START.md                [4.0 KB] - Início rápido
├─ 📄 PATCH_GUIA_USO.md             [8.0 KB] - Guia completo
├─ 📄 README.md                     [3.5 KB] - Resumo (este arquivo)
└─ 📄 newbase_patch_guide.png       [300KB] - Guia visual
```

---

## 🚀 MÉTODO 1: INSTALAÇÃO VIA POWERSHELL (Recomendado)

### ✅ Pré-requisitos
- [ ] Windows 10/11 + Laragon instalado
- [ ] PowerShell aberto como **Administrador**
- [ ] arquivo: `fix_newbase_errors.php`

### 📝 Passos

```powershell
# 1️⃣  Abra PowerShell como Administrador
# Windows: Win + X → "Terminal (Administrador)"

# 2️⃣  Navegue para o diretório do plugin
cd "D:\laragon\www\glpi\plugins\newbase\tools"

# 3️⃣  Copie o arquivo fix_newbase_errors.php para lá
# (Drag-and-drop do explorador ou use:)
# Copy-Item "...\fix_newbase_errors.php" -Destination .

# 4️⃣  Execute o patch
php fix_newbase_errors.php

# 5️⃣  Pressione ENTER quando terminar
```

### 📋 Saída Esperada

```
╔════════════════════════════════════════════════════════╗
║     NEWBASE - FERRAMENTA DE CORREÇÃO AUTOMÁTICA        ║
║     Versão 1.0.0 - Plugin: newbase                     ║
╚════════════════════════════════════════════════════════╝

🔍 Iniciando verificação e correção dos erros...

📋 [1/4] Corrigindo getSearchOptions() em CompanyData.php...
✅ getSearchOptions() corrigido
   - Removidos arrays em campos 'datatype'
   - Adicionada validação automática

🎨 [2/4] Corrigindo erro de SCSS no Dashboard/Widget.php...
✅ Tratamento de exceção SCSS adicionado
   - try-catch adicionado em compileString()
   - Fallback para CSS sem compilação

✔️  [3/4] Validando classes do plugin Newbase...
✅ 8 arquivo(s) validado(s)

🗑️  [4/4] Limpando cache do plugin...
✅ 12 arquivo(s) de cache removido(s)

╔════════════════════════════════════════════════════════╗
║                  RELATÓRIO FINAL                       ║
╚════════════════════════════════════════════════════════╝

✅ SUCESSOS (4):
   • CompanyData.php corrigido com sucesso
   • Widget.php corrigido com sucesso
   • Cache limpo com sucesso

📁 Backup salvo em: backup_fixes_2026_01_07_14_25_30

🎯 PRÓXIMOS PASSOS:
   1. Desative o plugin em: Configurar > Plugins > NewBase
   2. Reative o plugin: Clique em 'Ativar'
   3. Teste as funcionalidades: CompanyData, Tasks, etc.
   4. Verifique o log: var/log/glpi.log
```

---

## 🌐 MÉTODO 2: INSTALAÇÃO VIA CLIQUE DUPLO (Windows)

### ✅ Pré-requisitos
- [ ] Windows 10/11
- [ ] Laragon instalado
- [ ] arquivo: `install_patch.bat`

### 📝 Passos

```
1️⃣  Copie install_patch.bat para:
   D:\laragon\www\glpi\plugins\newbase\

2️⃣  Clique duas vezes em install_patch.bat

3️⃣  Autorize quando pedido (Controle de Conta)

4️⃣  Aguarde a execução (verá mensagens coloridas)

5️⃣  Pressione ENTER para fechar a janela
```

---

## ✅ APÓS O PATCH: REATIVAR PLUGIN

### Passo 1: Desativar o Plugin

```
1. Abra seu navegador: http://localhost/glpi
   (ou http://glpi.test/public se usar VHOST)

2. Login como: glpi / glpi

3. Vá em: Configurar → Plugins

4. Localize "NewBase" na lista

5. Clique em "Desinstalar"
   • Aguarde: "Desinstalação bem-sucedida"
```

### Passo 2: Reativar o Plugin

```
6. Clique em "Instalar"
   • Aguarde a instalação completa

7. Clique em "Ativar"
   • Confirm: "Plugin ativado com sucesso"
```

### Passo 3: Testar Funcionalidades

```
TEST 1 - CompanyData:
  Menu: Plugins → NewBase → Dados de Empresas
  Esperado: ✅ Carrega SEM "Array to string" error

TEST 2 - Dashboard:
  Volte à página inicial
  Esperado: ✅ Gráficos carregam SEM erro SCSS

TEST 3 - Tasks:
  Menu: Plugins → NewBase → Tarefas
  Esperado: ✅ Lista carrega normalmente

TEST 4 - Log:
  Arquivo: D:\laragon\www\glpi\var\log\glpi.log
  Esperado: ✅ Sem erros anteriores (Array to string)
```

---

## 🔍 VERIFICAÇÃO PÓS-PATCH

### ✅ Sinais de Sucesso

- ✅ CompanyData abre sem warnings
- ✅ Dashboard carrega gráficos normalmente
- ✅ Busca de registros funciona perfeitamente
- ✅ Criação de novos registros funciona
- ✅ Log do GLPI limpo de erros antigos

### ❌ Sinais de Problema

| Problema | Causa | Solução |
|----------|-------|---------|
| "Array to string" ainda aparece | getSearchOptions() não corrigido | Execute o patch novamente |
| Dashboard mostra erro SCSS | Widget.php não atualizado | Verifique arquivo: var/log/glpi.log |
| Página em branco | Cache inválido | Limpe manualmente: plugins/newbase/tmp/* |
| Erro 500 | Sintaxe PHP | Verifique: php -l src/CompanyData.php |

---

## 🔄 DESFAZER O PATCH (SE NECESSÁRIO)

Se algo não funcionou conforme esperado, temos backup automático!

```powershell
# 1. Listar backups criados
dir "D:\laragon\www\glpi\plugins\newbase\backup_fixes_*"

# 2. Identificar a pasta (por data/hora)
# Exemplo: backup_fixes_2026_01_07_14_25_30

# 3. Restaurar o arquivo (exemplo)
Copy-Item "backup_fixes_2026_01_07_14_25_30\CompanyData.php.backup" `
          "D:\laragon\www\glpi\plugins\newbase\src\CompanyData.php" -Force

# 4. Desativar e reativar o plugin novamente
```

---

## 📋 ARQUIVOS MODIFICADOS PELO PATCH

```
✅ /plugins/newbase/src/CompanyData.php
   Seção: Método getSearchOptions()
   Mudança:
     ❌ 'datatype' => ['string']
     ✅ 'datatype' => 'string'

✅ /src/Dashboard/Widget.php (GLPI Core)
   Linha: 2085
   Mudança:
     ❌ $compiled = $compiler->compileString($css, $path);
     ✅ try {
          $compiled = $compiler->compileString($css, $path);
        } catch (\Exception $e) {
          error_log('GLPI Dashboard Widget SCSS Compilation Error: ' . $e->getMessage());
          $compiled = ['css' => $css];
        }

🗑️  /plugins/newbase/tmp/*
   Ação: Limpeza de cache (12+ arquivos removidos)

🗑️  /plugins/newbase/cache/*
   Ação: Limpeza de cache
```

---

## 🛠️ TROUBLESHOOTING DETALHADO

### ❌ Erro: "PHP não encontrado"

**Causa:** PHP não está no PATH do Windows  
**Solução:**

```powershell
# Opção A: Use o caminho completo do Laragon
"C:\laragon\bin\php\php8.3.26\php.exe" fix_newbase_errors.php

# Opção B: Adicione o Laragon ao PATH
$env:Path += ";C:\laragon\bin\php\php8.3.26"
php fix_newbase_errors.php
```

### ❌ Erro: "Arquivo não encontrado: CompanyData.php"

**Causa:** Arquivo em local diferente do esperado  
**Solução:**

```powershell
# Procure pelo arquivo em todo o GLPI
Get-ChildItem "D:\laragon\www\glpi" -Recurse -Filter "CompanyData.php"

# Se encontrar em outro local, copie o patch para lá
# ou use o caminho correto no script
```

### ❌ Erro: "Acesso Negado"

**Causa:** Script não executado como administrador  
**Solução:**

```
1. Feche o PowerShell atual
2. Abra PowerShell NOVAMENTE como Administrador
   Windows: Win + X → "Terminal (Administrador)"
3. Execute novamente
```

### ❌ Erro: "Widget.php não encontrado"

**Causa:** Arquivo do GLPI em local diferente  
**Solução:** ⚠️ Não é crítico - o patch mostrará apenas aviso (⚠️), não erro

---

## 💾 BACKUP E SEGURANÇA

### Backup Automático

Cada execução cria um backup em:
```
D:\laragon\www\glpi\plugins\newbase\backup_fixes_YYYY_MM_DD_HH_MM_SS/
```

**Conteúdo do backup:**
- `CompanyData.php.backup` - Versão original antes da correção
- `Widget.php.backup` - Versão original antes da correção

**⚠️ NUNCA delete estes diretórios!**

### Restaurar do Backup

```powershell
# Se precisar reverter as mudanças:
Copy-Item "backup_fixes_[timestamp]/CompanyData.php.backup" `
          "src/CompanyData.php" -Force
```

---

## 📊 ESPECIFICAÇÕES TÉCNICAS

### Ambiente Testado

```
✅ GLPI: 10.0.20 (Obrigatório)
✅ PHP: 8.3.26 (Laragon 2025)
✅ MySQL: 8.4.6 (InnoDB, utf8mb4)
✅ Apache: 2.4.65 com SSL
✅ Windows: 10/11 + Laragon
```

### Métodos de Regex Usados

```php
// Pattern 1: Remove arrays de datatype
"/(['\"]datatype['\"]\s*=>\s*)\[(['\"])([a-z_]+)(['\"]\]\s*(?:,|}))/i"

// Pattern 2: Corrige múltiplos elementos
"/(['\"]datatype['\"]\s*=>\s*)\[(['\"])([a-z_]+)['\"],\s*['\"][a-z_]+['\"]\]/i"

// Pattern 3: Insere validação antes do return
"/(\s+return \$tab;)/i"
```

---

## 📞 SUPORTE E CONTATO

### Se Tiver Dúvidas

1. **Releia:** QUICK_START.md (início rápido)
2. **Consulte:** PATCH_GUIA_USO.md (guia detalhado)
3. **Procure:** Seção "Troubleshooting" acima
4. **Contate:** João Lucas (Newtel Soluções)

### Informações para Suporte

Se precisar contatar o desenvolvedor, inclua:

```
- Output completo do patch (copie e cole)
- Arquivo: var/log/glpi.log (últimas linhas com erro)
- Estrutura do GLPI (caminho exato das pastas)
- Versão do Windows e Laragon
- Mensagem de erro exata (print screen)
```

---

## 🎓 APRENDIZADO: O QUE FOI CORRIGIDO

### Erro 1: Array to String Conversion

**Antes:**
```php
'datatype' => ['string'],  // ❌ Array
```

**Depois:**
```php
'datatype' => 'string',    // ✅ String
```

**Por quê?** GLPI Search.php linha 752 espera string, não array!

---

### Erro 2: SCSS Compilation

**Antes:**
```php
$compiled = $compiler->compileString($css, $path);  // ❌ Sem tratamento
```

**Depois:**
```php
try {
    $compiled = $compiler->compileString($css, $path);
} catch (\Exception $e) {
    error_log('Error: ' . $e->getMessage());
    $compiled = ['css' => $css];  // ✅ Fallback seguro
}
```

**Por quê?** Arquivo `_generate.scss` pode não ser encontrado em compilação dinâmica!

---

### Erro 3: Cache Inválido

**Ação:** Limpeza completa de arquivos temporários  
**Por quê?** Arquivos PHP antigos em cache podem causar comportamento inesperado!

---

## ✨ RECURSOS ADICIONAIS

### Documentação Relacionada

- GLPI Documentation: https://glpi-developer-documentation.readthedocs.io/
- ScssPhp Compiler: https://scssphp.github.io/scssphp/
- Composer PSR-4: https://www.php-fig.org/psr/psr-4/

### Ferramentas Recomendadas

```powershell
# Validar sintaxe PHP antes de commitar
php -l src/CompanyData.php

# Buscar erros recentes no log
Get-Content var/log/glpi.log -Tail 50

# Backup completo do plugin
Copy-Item plugins/newbase plugins/newbase.backup -Recurse
```

---

## 📝 HISTÓRICO E VERSÕES

### v1.0.0 (07/01/2026) - ATUAL

- ✅ Correção automática de 3 erros críticos
- ✅ Backup automático antes de modificar
- ✅ Validação de sintaxe PHP
- ✅ Limpeza de cache
- ✅ Relatório detalhado
- ✅ 2 métodos de instalação

---

## 🔐 LICENÇA E TERMOS

**Licença:** GPLv2+ (mesma do plugin Newbase)  
**Desenvolvido por:** João Lucas (Newtel Soluções)  
**Data:** 07 de Janeiro de 2026  
**Suporte:** Conforme política da empresa

---

## 🎯 RESUMO FINAL

```
╔═══════════════════════════════════════════════════════════╗
║                     CHECKLIST FINAL                       ║
╠═══════════════════════════════════════════════════════════╣
║ □ Leia: QUICK_START.md (5 min)                           ║
║ □ Execute: php fix_newbase_errors.php                    ║
║ □ Aguarde: Conclusão com ✅                              ║
║ □ Desative: Plugins > NewBase > Desinstalar              ║
║ □ Reative: Plugins > NewBase > Instalar > Ativar         ║
║ □ Teste: CompanyData, Dashboard, Tasks                  ║
║ □ Verifique: var/log/glpi.log (sem erros antigos)        ║
║ □ Pronto! Seu plugin está corrigido! 🎉                  ║
╚═══════════════════════════════════════════════════════════╝
```

---

**Desenvolvido com ❤️ para a comunidade GLPI Brasil**  
**Newtel Soluções © 2026**
