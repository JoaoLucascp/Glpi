# CORREÇÃO DO ERRO: plugin_version_newbase method must be defined

## Problema Identificado
O GLPI 10.0.20 não conseguia carregar o plugin **Newbase** porque a função obrigatória `plugin_version_newbase()` não estava sendo encontrada corretamente.

### Mensagem de Erro
```
plugin_version_newbase method must be defined!
Unable to load plugin "newbase" information.
```

## Solução Implementada

### 1. **Reorganização do arquivo `setup.php`** ✅
- Adicionado a função `plugin_version_newbase()` no arquivo `setup.php`
- Esta função retorna um array com as informações necessárias do plugin:
  - Nome, versão, autor, licença
  - Requisitos de versão do GLPI e PHP
  - Descrição completa do plugin
  - Compliance CSRF

### 2. **Remoção de Duplicação no `hook.php`** ✅
- Removida a função duplicada `plugin_version_newbase()` do arquivo `hook.php`
- Mantidas as funções de instalação/desinstalação e hooks

### 3. **Estrutura Correta de Carregamento** ✅
A ordem de carregamento do GLPI é:
1. `setup.php` - Constantes e `plugin_version_newbase()`
2. `hook.php` - Instalação, desinstalação e hooks
3. Arquivos do plugin

### 4. **Limpeza de Cache** ✅
Executado script para limpar todos os caches do GLPI:
- `/files/_cache`
- `/files/_sessions`
- `/files/_tmp`

## Arquivos Modificados

### [setup.php](../setup.php)
- Adicionada função `plugin_version_newbase()`
- Mantidas constantes do plugin

### [hook.php](../hook.php)
- Removida função duplicada `plugin_version_newbase()`
- Mantidas todas as funções de instalação/desinstalação

## Próximos Passos

1. **Recarregue a página do GLPI**
   - URL: `http://glpi.test/public`
   - Faça login como administrador

2. **Acesse a página de plugins**
   - Vá em: **Configurar > Plugins**
   - Procure por **Newbase**

3. **Verifique se o erro desapareceu**
   - O plugin agora deve carregar corretamente
   - O ícone do plugin deve estar visível

4. **Reinstale se necessário**
   - Se o plugin ainda estiver com erro:
     - Clique em **Desinstalar**
     - Depois em **Instalar**
     - E finalmente **Ativar**

## Validação

Se tudo funcionou corretamente:
- ✅ Nenhuma mensagem de erro no log do PHP
- ✅ Plugin visível em Configurar > Plugins
- ✅ Plugin pode ser instalado/ativado
- ✅ No banco de dados, existem as tabelas: `glpi_plugin_newbase_*`

## Troubleshooting

Se ainda houver problemas:

1. **Verifique a constante do GLPI**
   ```php
   // Deve estar em hook.php ou setup.php
   if (!defined('GLPI_ROOT')) {
       die("Sorry. You can't access this file directly");
   }
   ```

2. **Verifique as permissões de arquivo**
   ```bash
   # Windows - verificar que os arquivos são legíveis
   icacls "d:\laragon\www\glpi\plugins\newbase\setup.php"
   ```

3. **Limpe novamente o cache**
   ```bash
   php plugins/newbase/clear_cache.php
   ```

4. **Verifique o log do PHP**
   - `files/_log/php-errors.log`
   - `files/_log/newbase.log`

## Referências

- [GLPI Developer Documentation](https://glpi-developer-documentation.readthedocs.io/)
- [Plugin Structure Guide](https://glpi-developer-documentation.readthedocs.io/en/latest/plugins/index.html)
- [GLPI Plugins Repository](https://github.com/glpi-project/glpi)

---

**Data**: 03 de fevereiro de 2026  
**Status**: ✅ Corrigido  
**Versão do Plugin**: 2.1.0  
**GLPI**: 10.0.20  
**PHP**: 8.3.26
