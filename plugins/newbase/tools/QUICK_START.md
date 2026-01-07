# 🚀 PASSO A PASSO - INSTALAÇÃO DO PATCH NEWBASE

## 📦 ARQUIVOS QUE VOCÊ RECEBEU

```
patch_newbase/
├── fix_newbase_errors.php          ← Script de correção (PRINCIPAL)
├── install_patch.bat               ← Instalador automático (Windows)
├── PATCH_GUIA_USO.md              ← Guia completo de uso
└── QUICK_START.md                 ← Este arquivo (início rápido)
```

---

## ⚡ INÍCIO RÁPIDO (5 MINUTOS)

### **OPÇÃO A: Mais Fácil (Clique duplo)**

**Passo 1:** Copie os arquivos para seu plugin

```
Copiar estes 3 arquivos:
- fix_newbase_errors.php
- install_patch.bat
- PATCH_GUIA_USO.md

Para esta pasta:
D:\laragon\www\glpi\plugins\newbase\tools\
```

**Passo 2:** Clique duas vezes em `install_patch.bat`

Pronto! O patch será executado automaticamente.

---

### **OPÇÃO B: Via PowerShell (Mais Controle)**

**Passo 1:** Abra PowerShell como Administrador

```powershell
# Pressione: Win + X
# Selecione: "Terminal (Administrador)" ou "PowerShell (Administrador)"
```

**Passo 2:** Navegue até o plugin

```powershell
cd "D:\laragon\www\glpi\plugins\newbase\tools"
```

**Passo 3:** Execute o patch

```powershell
php fix_newbase_errors.php
```

**Resultado esperado:**
```
✅ getSearchOptions() corrigido
✅ Tratamento de exceção SCSS adicionado
✅ 8 arquivo(s) validado(s)
✅ 12 arquivo(s) de cache removido(s)

📁 Backup salvo em: backup_fixes_2026_01_07_14_25_30
```

---

## ✅ DEPOIS DO PATCH (MUITO IMPORTANTE!)

### Etapa 1: Desativar e Reativar o Plugin

**No seu navegador:**

1. Acesse: `http://localhost/glpi` (ou seu domínio)
2. Login como administrador
   - Usuário: `glpi`
   - Senha: `glpi`

3. Vá em: **Configurar → Plugins**

4. Localize **"NewBase"** na lista

5. Clique em **"Desinstalar"**
   - Aguarde até ver: "Desinstalação bem-sucedida"

6. Clique em **"Instalar"**
   - Aguarde a instalação

7. Clique em **"Ativar"**
   - Plugin ativado com sucesso!

### Etapa 2: Testar Funcionamento

**Teste 1 - CompanyData (Dados de Empresas):**
```
Menu: Plugins → NewBase → Dados de Empresas
Esperado: Página carrega SEM erros "Array to string"
```

**Teste 2 - Dashboard (Gráficos):**
```
Volte à página inicial (Dashboard)
Esperado: Gráficos carregam normalmente, SEM erro SCSS
```

**Teste 3 - Tarefas:**
```
Menu: Plugins → NewBase → Tarefas
Esperado: Lista de tarefas carrega normalmente
```

---

## 🔍 COMO SABER SE FUNCIONOU

### ✅ Sinais de Sucesso

- ✅ CompanyData abre sem warnings
- ✅ Dashboard não mostra erro de compilação SCSS
- ✅ Búsca de registros funciona
- ✅ Criar novo registro funciona
- ✅ Log do GLPI não tem erros antigos

### ❌ Sinais de Problema

- ❌ "Array to string conversion" ainda aparece
- ❌ Dashboard mostra erro de SCSS
- ❌ Página fica em branco
- ❌ Erro 500 ao abrir CompanyData

**Se tiver problemas:** Veja a seção "Troubleshooting" no PATCH_GUIA_USO.md

---

## 🆘 DESFAZER O PATCH (SE NECESSÁRIO)

Se algo der errado, você tem backup automático!

```powershell
# 1. Liste os backups
dir "D:\laragon\www\glpi\plugins\newbase\backup_fixes_*"

# 2. Copie o arquivo de volta (exemplo)
Copy-Item "D:\laragon\www\glpi\plugins\newbase\backup_fixes_2026_01_07_14_25_30\CompanyData.php.backup" `
          "D:\laragon\www\glpi\plugins\newbase\src\CompanyData.php" -Force
```

---

## 📊 O QUE O PATCH FAZ

| Ação | Arquivo | Linha | O que muda |
|------|---------|-------|-----------|
| Remove arrays em `datatype` | CompanyData.php | getSearchOptions() | `['string']` → `'string'` |
| Adiciona try-catch SCSS | Widget.php | 2085 | Evita exceção file not found |
| Valida classes PHP | Todos .php | - | Verifica sintaxe |
| Limpa cache | /tmp, /cache | - | Remove 12+ arquivos temporários |

---

## 📞 SUPORTE RÁPIDO

### Erro: "Script não executado"

```powershell
# Verifique se o PHP está acessível:
php --version

# Se não funcionar, use o caminho completo do Laragon:
"C:\laragon\bin\php\php8.3.26\php.exe" fix_newbase_errors.php
```

### Erro: "Plugin não ativa"

```
1. Verifique o log: var/log/glpi.log
2. Procure por: "Uncaught Exception"
3. Se encontrar erro, execute o patch novamente
4. Limpe o cache do navegador (Ctrl + Shift + Delete)
```

### Erro: "Acesso Negado"

```powershell
# PowerShell como administrador:
# Win + X → Terminal (Administrador)

# Depois execute:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
php fix_newbase_errors.php
```

---

## 📚 PRÓXIMOS PASSOS

Após confirmar que tudo funciona:

1. **Faça backup do seu plugin corrigido:**
   ```powershell
   Copy-Item "D:\laragon\www\glpi\plugins\newbase" `
             "D:\laragon\www\glpi\plugins\newbase.backup_$(Get-Date -Format 'yyyy-MM-dd')" -Recurse
   ```

2. **Atualize o VERSION do plugin:**
   - Edite: `plugins/newbase/VERSION`
   - Mude para: `2.0.1` ou superior

3. **Distribua o patch para sua equipe:**
   - Compartilhe os 3 arquivos
   - Envie este documento (QUICK_START.md)

---

## 🎯 RESUMO EM 3 PASSOS

```
1️⃣  Copie: fix_newbase_errors.php para plugins/newbase/tools/
2️⃣  Execute: php fix_newbase_errors.php
3️⃣  Reative: Plugins → NewBase → Desinstalar → Instalar → Ativar
```

**Pronto! Seus erros foram corrigidos! 🎉**

---

## 📄 VERSÃO E INFORMAÇÕES

- **Versão do Patch:** 1.0.0
- **Plugin:** Newbase 2.0.0
- **GLPI:** 10.0.20+
- **PHP:** 8.1+ (testado em 8.3.26)
- **Data:** 07/01/2026
- **Licença:** GPLv2+

---

## 💡 DICA PROFISSIONAL

Guarde estes 3 arquivos em um local seguro:
- `fix_newbase_errors.php`
- `PATCH_GUIA_USO.md`
- Este documento

Se precisar reaplicalos no futuro, basta copiar para `tools/` e executar novamente!

---

**Desenvolvido por:** João Lucas (Newtel Soluções)  
**Suporte:** Entre em contato se houver dúvidas
