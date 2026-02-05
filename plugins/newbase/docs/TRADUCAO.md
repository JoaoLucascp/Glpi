# Guia de Internacionalização - Plugin Newbase

## ✅ Arquivos Criados

1. **locales/pt_BR.po** - Traduções em Português (atualizado)
2. **locales/en_GB.po** - Traduções em Inglês (novo)
3. **compile_locales.php** - Script de compilação

## 🔧 Como Compilar os Arquivos de Tradução

### Opção 1: Usando o Script PHP (Recomendado)

Abra o terminal do Laragon e execute:

```bash
cd D:\laragon\www\glpi\plugins\newbase
php compile_locales.php
```

### Opção 2: Usando msgfmt (se tiver gettext instalado)

```bash
cd D:\laragon\www\glpi\plugins\newbase\locales
msgfmt pt_BR.po -o pt_BR.mo
msgfmt en_GB.po -o en_GB.mo
```

### Opção 3: Online (poedit.net)

1. Acesse: https://localise.biz/free/converter
2. Upload do arquivo .po
3. Download do arquivo .mo
4. Salve em `locales/`

## 📝 Como Usar as Traduções no Código

### Em arquivos PHP:

```php
// Texto simples
echo __('Company Data', 'newbase');

// Com variáveis
echo sprintf(__('Total: %d companies', 'newbase'), $count);

// Plural
echo _n('company', 'companies', $count, 'newbase');
```

### Em arquivos JavaScript:

```javascript
// No HTML, use data attributes
<button data-i18n="Save">Save</button>

// Depois traduza com PHP antes:
var translations = {
    save: '<?php echo __('Save', 'newbase'); ?>',
    cancel: '<?php echo __('Cancel', 'newbase'); ?>'
};
```

## 🌍 Como o Usuário Escolhe o Idioma

O GLPI detecta automaticamente o idioma baseado em:

1. **Preferência do usuário** (Meu perfil > Idioma)
2. **Navegador** (Accept-Language header)
3. **Padrão do GLPI** (Configuração > Geral > Idioma padrão)

## 📚 Estrutura de Arquivos de Localização

```
locales/
├── pt_BR.po   (Texto editável - Português)
├── pt_BR.mo   (Compilado - Português)
├── en_GB.po   (Texto editável - Inglês)
└── en_GB.mo   (Compilado - Inglês)
```

## ✏️ Como Adicionar Novas Traduções

1. Abra `locales/pt_BR.po` e `locales/en_GB.po`
2. Adicione no final:

```
msgid "New Text"
msgstr "Novo Texto"  # pt_BR

msgid "New Text"
msgstr "New Text"    # en_GB
```

3. Compile novamente com `php compile_locales.php`
4. Reinicie o Apache (F12 no Laragon)

## 🔍 Exemplo Prático

### Antes (sem tradução):
```php
echo "<h1>Company Data</h1>";
echo "<button>Save</button>";
```

### Depois (com tradução):
```php
echo "<h1>" . __('Company Data', 'newbase') . "</h1>";
echo "<button>" . __('Save', 'newbase') . "</button>";
```

### Resultado:
- **Português**: "Dados da Empresa" / "Salvar"
- **Inglês**: "Company Data" / "Save"

## 🚀 Próximos Passos

1. ✅ Arquivos .po criados
2. ⏳ Execute `php compile_locales.php`
3. ⏳ Atualize o código para usar `__()`
4. ⏳ Teste mudando o idioma no GLPI

## 📖 Referências

- GLPI i18n: https://glpi-developer-documentation.readthedocs.io/en/master/plugins/index.html#internationalization
- GNU Gettext: https://www.gnu.org/software/gettext/manual/gettext.html
- PO Editor: https://poedit.net/

## 🐛 Resolução de Problemas

### "Tradução não aparece"
1. Verifique se o arquivo .mo existe
2. Reinicie o Apache
3. Limpe o cache do navegador
4. Verifique o idioma do usuário no GLPI

### "Caracteres estranhos (Ã, Ã§, Ã£)"
- Certifique-se que os arquivos estão em UTF-8
- Verifique o charset no .po: `charset=UTF-8`

### "msgfmt não encontrado"
- Use o script PHP: `php compile_locales.php`
- Ou instale gettext: https://mlocati.github.io/articles/gettext-iconv-windows.html
