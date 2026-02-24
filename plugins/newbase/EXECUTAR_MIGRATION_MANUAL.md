# EXECUTAR MIGRATION MANUALMENTE

## Método 1: Via phpMyAdmin (Recomendado)

1. Acesse: http://localhost/phpmyadmin
2. Faça login (usuário: root, sem senha no Laragon)
3. Selecione o banco `glpi` no menu lateral
4. Clique na aba **SQL** no topo
5. Cole o SQL abaixo e clique em **Executar**

```sql
-- ========================================
-- MIGRATION 2.1.1 - NEWBASE PLUGIN
-- ========================================

USE glpi;

-- Adicionar campos de inscrições
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN IF NOT EXISTS `inscricao_estadual` VARCHAR(50) DEFAULT NULL AFTER `fantasy_name`,
ADD COLUMN IF NOT EXISTS `inscricao_municipal` VARCHAR(50) DEFAULT NULL AFTER `inscricao_estadual`;

-- Adicionar campos de endereço
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN IF NOT EXISTS `cep` VARCHAR(10) DEFAULT NULL AFTER `email`,
ADD COLUMN IF NOT EXISTS `street` VARCHAR(255) DEFAULT NULL AFTER `cep`,
ADD COLUMN IF NOT EXISTS `number` VARCHAR(20) DEFAULT NULL AFTER `street`,
ADD COLUMN IF NOT EXISTS `complement` VARCHAR(255) DEFAULT NULL AFTER `number`,
ADD COLUMN IF NOT EXISTS `neighborhood` VARCHAR(255) DEFAULT NULL AFTER `complement`,
ADD COLUMN IF NOT EXISTS `city` VARCHAR(255) DEFAULT NULL AFTER `neighborhood`,
ADD COLUMN IF NOT EXISTS `state` VARCHAR(2) DEFAULT NULL AFTER `city`,
ADD COLUMN IF NOT EXISTS `country` VARCHAR(100) DEFAULT 'Brasil' AFTER `state`,
ADD COLUMN IF NOT EXISTS `latitude` DECIMAL(10, 8) DEFAULT NULL AFTER `country`,
ADD COLUMN IF NOT EXISTS `longitude` DECIMAL(11, 8) DEFAULT NULL AFTER `latitude`;

-- Adicionar campo de status do contrato
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN IF NOT EXISTS `contract_status` VARCHAR(50) DEFAULT 'active' AFTER `longitude`;

-- Adicionar campo JSON para configurações de sistemas
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN IF NOT EXISTS `systems_config` LONGTEXT DEFAULT NULL COMMENT 'JSON com configurações de IPBX/PABX, IPBX Cloud, Chatbot, Linha' AFTER `contract_status`;

-- Adicionar índices para performance
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD INDEX IF NOT EXISTS `idx_cep` (`cep`),
ADD INDEX IF NOT EXISTS `idx_state` (`state`),
ADD INDEX IF NOT EXISTS `idx_contract_status` (`contract_status`);

-- Inicializar systems_config como JSON vazio
UPDATE `glpi_plugin_newbase_company_extras` 
SET `systems_config` = '{}' 
WHERE `systems_config` IS NULL;

-- Confirmar
SELECT 'Migration 2.1.1 concluída com sucesso!' AS status;
```

## Método 2: Via Linha de Comando (MySQL CLI)

1. Abra o **Terminal do Laragon**
2. Execute:

```bash
cd D:\laragon\www\glpi\plugins\newbase\install\mysql\migrations
mysql -u root glpi < 2.1.1-add_company_fields.sql
```

## Método 3: Via HeidiSQL (se instalado)

1. Abra o HeidiSQL
2. Conecte ao servidor MySQL local
3. Selecione o banco `glpi`
4. Abra a aba "Query"
5. Cole o SQL acima e execute

---

## ✅ Verificar se Funcionou

Após executar, rode este SQL para confirmar:

```sql
DESCRIBE glpi_plugin_newbase_company_extras;
```

Você deve ver os novos campos:
- inscricao_estadual
- inscricao_municipal
- cep, street, number, complement, neighborhood, city, state, country
- latitude, longitude
- contract_status
- systems_config

---

## 🔧 Remover arquivo migrate.php (opcional)

Após a migration, você pode deletar o arquivo problemático:

```
D:\laragon\www\glpi\plugins\newbase\front\tools\migrate.php
```

Ou apenas ignore-o.
