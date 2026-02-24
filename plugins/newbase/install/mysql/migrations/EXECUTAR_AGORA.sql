-- ========================================
-- MIGRATION 2.1.1 - NEWBASE PLUGIN
-- Versão Compatível MySQL 8.x
-- ========================================

USE glpi;

-- Desabilitar verificação de chaves estrangeiras temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Adicionar campos de inscrições
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN `inscricao_estadual` VARCHAR(50) DEFAULT NULL AFTER `fantasy_name`,
ADD COLUMN `inscricao_municipal` VARCHAR(50) DEFAULT NULL AFTER `inscricao_estadual`;

-- Adicionar campos de endereço
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN `cep` VARCHAR(10) DEFAULT NULL AFTER `email`,
ADD COLUMN `street` VARCHAR(255) DEFAULT NULL AFTER `cep`,
ADD COLUMN `number` VARCHAR(20) DEFAULT NULL AFTER `street`,
ADD COLUMN `complement` VARCHAR(255) DEFAULT NULL AFTER `number`,
ADD COLUMN `neighborhood` VARCHAR(255) DEFAULT NULL AFTER `complement`,
ADD COLUMN `city` VARCHAR(255) DEFAULT NULL AFTER `neighborhood`,
ADD COLUMN `state` VARCHAR(2) DEFAULT NULL AFTER `city`,
ADD COLUMN `country` VARCHAR(100) DEFAULT 'Brasil' AFTER `state`,
ADD COLUMN `latitude` DECIMAL(10, 8) DEFAULT NULL AFTER `country`,
ADD COLUMN `longitude` DECIMAL(11, 8) DEFAULT NULL AFTER `latitude`;

-- Adicionar campo de status do contrato
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN `contract_status` VARCHAR(50) DEFAULT 'active' AFTER `longitude`;

-- Adicionar campo JSON para configurações de sistemas
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD COLUMN `systems_config` LONGTEXT DEFAULT NULL COMMENT 'JSON com configurações de IPBX/PABX, IPBX Cloud, Chatbot, Linha' AFTER `contract_status`;

-- Adicionar índices para performance
ALTER TABLE `glpi_plugin_newbase_company_extras`
ADD INDEX `idx_cep` (`cep`),
ADD INDEX `idx_state` (`state`),
ADD INDEX `idx_contract_status` (`contract_status`);

-- Inicializar systems_config como JSON vazio
UPDATE `glpi_plugin_newbase_company_extras` 
SET `systems_config` = '{}' 
WHERE `systems_config` IS NULL;

-- Reabilitar verificação de chaves estrangeiras
SET FOREIGN_KEY_CHECKS = 1;

-- Confirmar
SELECT 'Migration 2.1.1 concluída com sucesso!' AS status;
