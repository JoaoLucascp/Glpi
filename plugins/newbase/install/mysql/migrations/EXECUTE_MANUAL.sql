-- ========================================
-- MIGRATION MANUAL 2.1.1
-- Execute este SQL diretamente no MySQL
-- ========================================

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

-- Inicializar campo systems_config como objeto vazio JSON
UPDATE `glpi_plugin_newbase_company_extras` 
SET `systems_config` = '{}' 
WHERE `systems_config` IS NULL;

-- Verificar resultado
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE, 
    COLUMN_DEFAULT
FROM 
    INFORMATION_SCHEMA.COLUMNS
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'glpi_plugin_newbase_company_extras'
ORDER BY 
    ORDINAL_POSITION;
