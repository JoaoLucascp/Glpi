# 🌍 Sistema de Tradução - Plugin Newbase

## 🎯 O que é isso?

Este sistema permite que seu plugin Newbase funcione em **Português** e **Inglês**, mudando automaticamente conforme o idioma escolhido pelo usuário no GLPI.

---

## ⚡ Início Rápido (3 minutos)

### 1️⃣ Compile as Traduções
```batch
Clique duplo em: compilar_traducoes.bat
```

### 2️⃣ Reinicie o Apache
```
Laragon > Pressione F12
```

### 3️⃣ Teste no GLPI
```
1. Acesse: http://glpi.test
2. Login: glpi / Senha: glpi
3. Clique no seu nome (Super-Admin)
4. Personalização > Idioma
5. Escolha: "Português (Brasil)" ou "English (United Kingdom)"
6. Salvar
7. Acesse o menu Newbase
```

✅ **Pronto!** O plugin agora está no idioma escolhido.

---

## 📂 Arquivos Importantes

```
newbase/
├── 📄 compilar_traducoes.bat    ← Execute este para compilar
├── 📄 compile_locales.php       ← Script de compilação
│
├── 📁 locales/                  ← Arquivos de tradução
│   ├── pt_BR.po                ← Português (editável)
│   ├── pt_BR.mo                ← Português (compilado)
│   ├── en_GB.po                ← Inglês (editável)
│   └── en_GB.mo                ← Inglês (compilado)
│
└── 📁 docs/                     ← Documentação
    ├── RESUMO_TRADUCAO.md      ← 📌 LEIA PRIMEIRO
    ├── GUIA_TRADUCOES.md       ← Exemplos práticos
    ├── TRADUCAO.md             ← Guia básico
    └── ANTES_DEPOIS.md         ← Comparação visual
```

---

## 🚀 Como Funciona?

### Automático
O GLPI detecta o idioma baseado em:
1. Preferência do usuário no perfil
2. Idioma do navegador
3. Idioma padrão do GLPI

### Exemplos

**No código PHP:**
```php
echo __('Company Data', 'newbase');
```

**Resultado:**
- 🇧🇷 Português: "Dados da Empresa"
- 🇬🇧 Inglês: "Company Data"

---

## 📚 Documentação Completa

### 🎓 Para Iniciantes
1. **LEIA PRIMEIRO:** `docs/RESUMO_TRADUCAO.md`
2. **Ver exemplos:** `docs/ANTES_DEPOIS.md`

### 💻 Para Desenvolvedores
1. **Guia completo:** `docs/GUIA_TRADUCOES.md`
2. **Guia básico:** `docs/TRADUCAO.md`

---

## ✏️ Como Adicionar Novas Traduções

### 1. Edite os arquivos .po
```bash
# Abra com VS Code ou Notepad++
locales/pt_BR.po
locales/en_GB.po
```

### 2. Adicione a tradução
```po
# Em pt_BR.po
msgid "New Text"
msgstr "Novo Texto"

# Em en_GB.po
msgid "New Text"
msgstr "New Text"
```

### 3. Compile
```batch
# Clique duplo
compilar_traducoes.bat
```

### 4. Reinicie Apache
```
Laragon > F12
```

### 5. Use no código
```php
echo __('New Text', 'newbase');
```

---

## 🎨 Traduções Incluídas

### ✅ Interface
- Dashboard completo
- Menu lateral
- Botões de ação
- Ações rápidas

### ✅ Formulários
- Campos de empresa
- Campos de endereço
- Campos de sistema
- Campos de tarefa

### ✅ Mensagens
- Sucesso
- Erro
- Aviso
- Confirmação

### ✅ Funcionalidades
- Geolocalização
- Assinatura digital
- Relatórios
- Configurações

**Total: 200+ termos traduzidos**

---

## 🐛 Problemas Comuns

### "Tradução não aparece"
```
✅ Execute: compilar_traducoes.bat
✅ Reinicie: Laragon (F12)
✅ Limpe: Cache do navegador (Ctrl+Shift+Del)
✅ Verifique: Idioma no perfil do GLPI
```

### "Caracteres estranhos"
```
✅ Arquivos já estão em UTF-8
✅ Se aparecer Ã, Ã§: recompile
```

### "Script não funciona"
```
✅ Use o terminal do Laragon
✅ Ou execute: php compile_locales.php
```

---

## 📊 Status Atual

- ✅ Arquivos .po criados (200+ traduções)
- ✅ Scripts de compilação prontos
- ✅ Documentação completa
- ✅ Menu usando traduções
- ⏳ Compilar .mo (você precisa fazer)
- ⏳ Aplicar em outros arquivos (opcional)

---

## 🎯 Próxima Ação

**FAÇA AGORA:**
```batch
1. Clique duplo em: compilar_traducoes.bat
2. Aguarde: "Compilação Concluída!"
3. Pressione: F12 no Laragon
4. Teste: Mude idioma no GLPI
```

---

## 💡 Dicas

1. **Sempre use 'newbase'** como segundo parâmetro
2. **Mantenha consistência** nas traduções
3. **Teste ambos idiomas** após mudanças
4. **Recompile sempre** após editar .po
5. **Reinicie Apache** após compilar

---

## 📞 Suporte

### Documentação
- `docs/RESUMO_TRADUCAO.md` - Resumo completo
- `docs/GUIA_TRADUCOES.md` - Exemplos práticos
- `docs/ANTES_DEPOIS.md` - Comparações visuais

### Comunidade
- GLPI Forum: https://forum.glpi-project.org/
- GLPI Docs: https://glpi-developer-documentation.readthedocs.io/

---

## ✅ Checklist Rápido

- [ ] Executei `compilar_traducoes.bat`
- [ ] Vi mensagem "Compilação Concluída!"
- [ ] Arquivos .mo foram criados
- [ ] Reiniciei o Apache (F12)
- [ ] Testei em Português
- [ ] Testei em Inglês
- [ ] Tudo funcionando!

---

**Versão:** 2.1.0  
**Data:** 05/02/2026  
**Idiomas:** 🇧🇷 Português + 🇬🇧 Inglês  
**Status:** ✅ Pronto para usar
