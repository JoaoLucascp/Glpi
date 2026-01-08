# 🚀 GUIA RÁPIDO DE CORREÇÃO - Plugin Newbase

## ⚡ Execução em 5 Minutos

### 🔴 PROBLEMA
```
Unknown column 'glpi_plugin_newbase_companydata.id' in 'field list'
Warning: Array to string conversion in Search.php
```

---

## 📋 PASSO A PASSO

### 1️⃣ BACKUP (30 segundos)

```bash
# Backup do banco de dados
cd D:\laragon\www\glpi
mysqldump -u root glpi > backup_glpi_$(date +%Y%m%d_%H%M%S).sql

# Backup dos arquivos do plugin
cp -r plugins/newbase plugins/newbase_backup_$(date +%Y%m%d_%H%M%S)
```

### 2️⃣ EXECUTAR SCRIPT PHP (1 minuto)

```bash
# Copie o arquivo fix_searchoptions.php para a pasta do GLPI
cd D:\laragon\www\glpi\plugins\newbase

# Execute o script
php fix_searchoptions.php
```

**Ou via browser:**
```
http://glpi.test/plugins/newbase/fix_searchoptions.php
```

### 3️⃣ EXECUTAR SCRIPT SQL (1 minuto)

**Abra o MySQL:**
```bash
# Via Laragon Terminal
mysql -u root -p glpi

# Ou via HeidiSQL/phpMyAdmin
```

**Execute o SQL:**
```bash
# No terminal MySQL
source D:/laragon/www/glpi/plugins/newbase/fix_database.sql

# Ou copie e cole o conteúdo de fix_database.sql no HeidiSQL
```

### 4️⃣ LIMPAR CACHE (30 segundos)

```bash
cd D:\laragon\www\glpi

# Deletar cache
del /Q files\_cache\*
del /Q files\_sessions\*
del /Q files\_tmp\*

# Ou manualmente pelo Windows Explorer
```

### 5️⃣ REINSTALAR PLUGIN (2 minutos)

1. Acesse: `http://glpi.test/public`
2. Login: `glpi` / Senha: `glpi`
3. Menu: **Configurar > Plugins**
4. Localize **Newbase**
5. Clique em **Desativar**
6. Clique em **Desinstalar**
7. Clique em **Instalar**
8. Clique em **Ativar**

### 6️⃣ TESTAR (1 minuto)

1. Menu: **Plugins > Newbase > Company Data**
2. Clique em **Pesquisar**
3. Verifique se não há mais erros
4. Tente criar uma nova empresa
5. Faça uma pesquisa

---

## ✅ CHECKLIST DE VERIFICAÇÃO

- [ ] Backup do banco criado
- [ ] Backup dos arquivos criado
- [ ] Script PHP executado sem erros
- [ ] Script SQL executado sem erros
- [ ] Cache limpo
- [ ] Plugin reinstalado
- [ ] Pesquisa funcionando sem erros
- [ ] Variáveis não retornam "Undefined"
- [ ] Criação de empresa funciona

---

## 🔧 CORREÇÃO MANUAL (se os scripts não funcionarem)

### Arquivo: `/plugins/newbase/src/CompanyData.php`

**Localizar:**
```php
public function getSearchOptionsNew()
{
    // código antigo
}
```

**Substituir por:**
```php
public function getSearchOptionsNew()
{
    $tab = [];
    
    $tab[] = [
        'id' => 'common',
        'name' => __('Characteristics')
    ];
    
    $tab[] = [
        'id' => '1',
        'table' => $this->getTable(),
        'field' => 'name',
        'name' => __('Name'),
        'datatype' => 'itemlink',
        'massiveaction' => false,
        'forcegroupby' => true,
        'autocomplete' => true,
    ];
    
    $tab[] = [
        'id' => '2',
        'table' => $this->getTable(),
        'field' => 'id',
        'name' => __('ID'),
        'massiveaction' => false,
        'datatype' => 'number',
        'forcegroupby' => true
    ];
    
    // ... adicione os demais campos conforme o documento CORRECAO_ERROS_SEARCHOPTIONS.md
    
    return $tab;
}
```

### SQL Manual:

```sql
USE glpi;

-- Verificar estrutura atual
DESCRIBE glpi_plugin_newbase_companydata;

-- Se o campo 'id' não existir ou estiver errado:
DROP TABLE IF EXISTS glpi_plugin_newbase_companydata;

CREATE TABLE `glpi_plugin_newbase_companydata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entities_id` int(11) NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corporate_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fantasy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `federal_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_registration` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entities_id` (`entities_id`),
  KEY `is_recursive` (`is_recursive`),
  KEY `cnpj` (`cnpj`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🐛 TROUBLESHOOTING

### Erro persiste após correção:

**1. Verificar encoding do arquivo:**
```bash
# Deve ser UTF-8 sem BOM
file plugins/newbase/src/CompanyData.php
```

**2. Reiniciar Apache:**
```bash
# No Laragon: Stop All → Start All
```

**3. Verificar permissões:**
```bash
# Windows
icacls "D:\laragon\www\glpi\plugins\newbase" /grant Everyone:F /T
```

**4. Verificar logs:**
```bash
# Log do PHP
tail -f D:\laragon\www\glpi\files\_log\php-errors.log

# Log do SQL
tail -f D:\laragon\www\glpi\files\_log\sql-errors.log
```

### "Table doesn't exist":

```sql
-- Verificar se tabela existe
SHOW TABLES LIKE 'glpi_plugin_newbase%';

-- Se não existir, execute:
source D:/laragon/www/glpi/plugins/newbase/install/mysql/2.0.0.sql
```

### "Permission denied":

```bash
# Dar permissões completas (Windows)
takeown /f "D:\laragon\www\glpi\plugins\newbase" /r /d y
icacls "D:\laragon\www\glpi\plugins\newbase" /grant Everyone:F /T
```

---

## 📞 SUPORTE

Se os erros persistirem, forneça:

1. **Versão do GLPI:** 10.0.20
2. **Versão do PHP:** 8.3.26
3. **Sistema Operacional:** Windows + Laragon
4. **Saída do comando:**
   ```sql
   DESCRIBE glpi_plugin_newbase_companydata;
   ```
5. **Log completo:**
   ```bash
   cat files/_log/php-errors.log | tail -50
   ```

---

## 📚 ARQUIVOS CRIADOS

```
fix_searchoptions.php          # Script de correção automática PHP
fix_database.sql               # Script de correção do banco de dados
CORRECAO_ERROS_SEARCHOPTIONS.md # Documentação completa
GUIA_RAPIDO.md                 # Este arquivo
```

---

## ⏱️ TEMPO TOTAL ESTIMADO

- **Backup:** 30 segundos
- **Correção PHP:** 1 minuto
- **Correção SQL:** 1 minuto
- **Limpar cache:** 30 segundos
- **Reinstalar plugin:** 2 minutos
- **Teste:** 1 minuto

**TOTAL: ~6 minutos**

---

## ✨ RESULTADO ESPERADO

Após seguir todos os passos:

✅ Sem erros "Unknown column"
✅ Sem warnings "Array to string conversion"
✅ Pesquisa funcionando corretamente
✅ Criação de empresas funcionando
✅ Todas as variáveis definidas

---

**Data:** 07/01/2026
**Versão:** 1.0
**Plugin:** Newbase 2.0.0
**GLPI:** 10.0.20
