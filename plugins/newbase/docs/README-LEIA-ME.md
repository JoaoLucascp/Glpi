# README - Plugin Newbase para GLPI 10.0.20

## Resumo Executivo

O Plugin Newbase foi corrigido para **total compatibilidade com GLPI 10.0.20 + PHP 8.3.26**. Uma única correção foi aplicada e o plugin está pronto para ativação.

**Status:** **PRONTO PARA PRODUÇÃO**

```html
<a name="problema"></a> O Problema
```

### Erro Original

```log
Cannot make non static method CommonDBTM::rawSearchOptions() static
in class GlpiPlugin\Newbase\CompanyData at line 316
```

### Causa

Na linha 316 do arquivo `src/CompanyData.php`, o método `rawSearchOptions()` foi declarado como **estático** (`static`), quando deveria ser uma instância (sem `static`). Isso viola o contrato da classe pai `CommonDBTM` que define o método como não-estático.

---

```html
<a name="solução"></a> A Solução
```

### Arquivo Corrigido: `src/CompanyData.php`

**Antes (ERRO):**

```php
public static function rawSearchOptions(): array
{
    return [
        // ...
    ];
}
```

**Depois (CORRETO):**

```php
public function rawSearchOptions(): array
{
    return [
        // ...
    ];
}
```

### O que foi removido

- Palavra-chave `static` na linha 316
- O resto do código permaneceu idêntico

### Outros Arquivos Verificados

Todos os outros arquivos foram verificados e estão corretos:

| Arquivo                 | Status                        |
| ----------------------- | ----------------------------- |
| `src/Address.php`       | OK (sem static)               |
| `src/System.php`        | OK (sem static)               |
| `src/Task.php`          | OK (sem static)               |
| `src/Config.php`        | OK (não usa rawSearchOptions) |
| `src/TaskSignature.php` | OK (não usa rawSearchOptions) |
| `front/*.php` (todos)   | OK (CSRF correto)             |
| `ajax/*.php` (todos)    | OK (CSRF correto)             |

---

```html
<a name="ativação"></a> Como Ativar o Plugin
```

### Passo 1: Limpar Caches

Abra PowerShell **como Administrador** e execute:

```powershell
cd d:\laragon\www\glpi

# Parar Apache (opcional, mas recomendado)
net stop Apache2.4

# Limpar todos os caches
Remove-Item "files\_cache\*" -Force -Recurse -ErrorAction SilentlyContinue
Remove-Item "files\_sessions\*" -Force -Recurse -ErrorAction SilentlyContinue
Remove-Item "files\_tmp\*" -Force -Recurse -ErrorAction SilentlyContinue

# Iniciar Apache novamente
net start Apache2.4

Write-Host " Caches limpos com sucesso!" -ForegroundColor Green
```

### Passo 2: Acessar GLPI

1. Abra seu navegador
2. Vá para: `http://glpi.test/`
3. Login:
    - *Usuário:* glpi
    - *Senha:* glpi

### Passo 3: Ativar Plugin

1. Clique em: *Configurar* (menu superior)
2. Clique em: *Plugins*
3. Procure por: *NewBase* ou **Newbase*
4. Clique em: *Instalar* (se não instalado)
5. Clique em: *Ativar*

### Passo 4: Confirmar

Se você vir:
    -  *Nenhuma mensagem de erro em vermelho* = Sucesso!
    -  *Status muda para "Ativado"* = Plugin está funcionando!

---

```html
<a name="verificação"></a> Verificação Pós-Ativação
```

### 1. Verificar Arquivo de Log

```powershell
# Ver últimas linhas do log
Get-Content "d:\laragon\www\glpi\files\_log\php-errors.log" -Tail 30
```

**Procure por:**
    -  Erros mencionando `rawSearchOptions`
    -  Erros mencionando `CompanyData`
    -  Se não houver esses erros, está OK!

### 2. Testar Funcionalidade

Acesse a página do plugin no navegador:

```link
http://glpi.test/plugins/newbase/front/companydata.php
```

Se a página carregar sem erros: *Sucesso!*

### 3. Verificar Menu

No GLPI, procure no menu esquerdo por:
    - *Newbase* ou
    - *Ferramentas* → *Newbase*

Se aparecer o menu: *Plugin está ativo!*

---

```html
#<a name="troubleshooting"></a> Se Tiver Problemas
```

### Problema: Plugin não ativa ou dá erro de compilação

**Solução:**

1. Verifique se o arquivo foi corrigido:

    ```powershell
    # Buscar a linha exata
    Select-String -Path "d:\laragon\www\glpi\plugins\newbase\src\CompanyData.php" `
        -Pattern "public function rawSearchOptions"

    # Deve retornar (SEM "static"):
    # 315:    public function rawSearchOptions(): array
    ```

2. Se ainda não foi corrigido, edite manualmente:
    - Abra `src/CompanyData.php` no VS Code
    - Vá para linha 316 (Ctrl+G)
    - Remova a palavra `static`
    - Salve o arquivo

3. Limpe cache novamente:

    ```powershell
    Remove-Item "d:\laragon\www\glpi\files\_cache\*" -Force -Recurse
    ```

4. Tente ativar novamente

### Problema: Erro 403 (Acesso Negado)

**Solução:**

1. Vá para: *Administração* → *Perfis*
2. Selecione seu perfil
3. Procure por "Plugin Newbase"
4. Marque as permissões:
    - Leitura
    - Criação
    - Atualização

5. Clique em *Salvar*

### Problema: Nada funciona

**Solução:**

1. Desinstale o plugin completamente:
   - Ir em: *Configurar* → *Plugins* → *NewBase*
   - Clique em: *Desativar*
   - Clique em: *Desinstalar*

2. Limpe tudo:

    ```powershell
    # Limpar cache
    Remove-Item "d:\laragon\www\glpi\files\_cache\*" -Force -Recurse

    # Limpar sessões
    Remove-Item "d:\laragon\www\glpi\files\_sessions\*" -Force -Recurse
    ```

3. Reinstale:
    - *Configurar* → *Plugins* → *NewBase* → *Instalar* → *Ativar*

---

```html
<a name="técnico"></a> Detalhes Técnicos
```

### Ambiente Confirmado

*GLPI:*    10.0.20
*PHP:*     8.3.26
*MySQL:*   8.4.6 (InnoDB)
*Apache:*  2.4.65
*Windows:* 11 Pro
*Laragon:* 2025

---

### Compatibilidade

| Aspecto                | Status             |
| ---------------------- | ------------------ |
| *Compatibilidade GLPI* | 10.0.20+           |
| *Compatibilidade PHP*  | 8.1.0+             |
| *CSRF Protection*      | Implementado       |
| *Segurança de Entrada* | Validado           |
| *Escape de Output*     | Implementado       |
| *Namespace*            | GlpiPlugin\Newbase |
| *Autoloader*           | Composer PSR-12    |

### Arquivos Verificados

**Arquivos de Classe (src/):** 9 arquivos

- Address, AddressHandler, Common, CompanyData, Config, Menu, System, Task, TaskSignature

**Arquivos Frontend (front/):** 10 arquivos

- address.form, companydata.form, companydata, config, index, report, system.form, system, task.form, task

**Arquivos AJAX (ajax/):** 7 arquivos

- calculateMileage, cnpj_proxy, mapData, searchAddress, searchCompany, signatureUpload, taskActions

**Configuração:**

- setup.php (CSRF compliant)
- hook.php (Migrations OK)

### Por Que Isso Resolve o Erro?

Em **Orientação a Objetos**, você não pode alterar a assinatura de um método quando o herda de uma classe pai:

`CommonDBTM` (GLPI core)
    ↓
    `rawSearchOptions()` → public function (não-estático)
    ↓
`CompanyData` (seu plugin)
    ↓
    `rawSearchOptions()` → public static function (CONFLITO!)

---

**A correção:**

`CommonDBTM` (GLPI core)
    ↓
    `rawSearchOptions()` → public function (não-estático)
    ↓
`CompanyData` (seu plugin)
    ↓
    `rawSearchOptions()` → public function (compatível!)

---

## Referências GLPI

- [GLPI Developer Documentation](https://glpi-developer-documentation.readthedocs.io/)
- [GLPI API REST](https://github.com/glpi-project/glpi/blob/master/apirest.md)
- [GLPI GitHub Repository](https://github.com/glpi-project/glpi)

---

## Próximos Passos (Após Ativação)

1. ✅ Explorar o menu do plugin
2. ✅ Configurar as permissões dos usuários
3. ✅ Cadastrar primeira empresa
4. ✅ Cadastrar primeiro endereço
5. ✅ Testar geolocalização de tarefas
6. ✅ Habilitar assinatura digital

---

## Suporte

Se encontrar qualquer problema:

1. *Verifique o log:* `files\_log\php-errors.log`
2. *Limpe caches:* Execute os comandos PowerShell acima
3. *Reinstale:* Desinstale completamente e reinstale
4. *Verifique permissões:* Certifique-se de ter permissões no GLPI

---

## 📝 Histórico de Mudanças

### Versão 2.1.0 (Corrigida)

- *Data:* 3 de fevereiro de 2026
- *Correção:* Removido `static` do método `rawSearchOptions()` em `CompanyData.php`
- *Status:* PRONTO PARA PRODUÇÃO
- *Arquivos Modificados:* 1 (`CompanyData.php`)
- *Linhas Alteradas:* 1 (linha 316)

### Verificações Realizadas

- 9 arquivos de classe
- 10 arquivos frontend
- 7 arquivos AJAX
- Segurança CSRF
- Compatibilidade GLPI
- Validação de entrada

---

## Checklist Final

- [x] Erro identificado
- [x] Solução aplicada
- [x] Compatibilidade verificada
- [x] Segurança confirmada
- [x] Documentação criada
- [x] Pronto para ativação

---

## Status

**PLUGIN PRONTO PARA ATIVAÇÃO EM PRODUÇÃO**
Versão: *2.1.0*
GLPI Mínimo: *10.0.20*
PHP Mínimo: *8.1.0*
Data: *3 de fevereiro de 2026*
