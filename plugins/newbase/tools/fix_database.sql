-- =====================================================
-- CORREÇÃO COM REMOÇÃO DE FOREIGN KEYS - VERSÃO 2
-- =====================================================

USE glpi;

-- -----------------------------------------------------
-- 1. REMOVER FOREIGN KEYS (sem IF EXISTS)
-- -----------------------------------------------------

-- Remover FK da tabela address
SET @sql = (
    SELECT CONCAT('ALTER TABLE `glpi_plugin_newbase_address` DROP FOREIGN KEY `',CONSTRAINT_NAME,'`;')
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'glpi'
    AND TABLE_NAME = 'glpi_plugin_newbase_address'
    AND CONSTRAINT_NAME = 'fk_address_company'
    LIMIT 1
);

SET @sql = IFNULL(@sql,'SELECT "FK address não existe" AS status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Remover FK da tabela systems (se existir)
SET @sql = (
    SELECT CONCAT('ALTER TABLE `glpi_plugin_newbase_systems` DROP FOREIGN KEY `',CONSTRAINT_NAME,'`;')
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'glpi'
    AND TABLE_NAME = 'glpi_plugin_newbase_systems'
    AND CONSTRAINT_NAME = 'fk_systems_companydata'
    LIMIT 1
);

SET @sql = IFNULL(@sql,'SELECT "FK systems não existe" AS status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Remover FK da tabela tasks (se existir)
SET @sql = (
    SELECT CONCAT('ALTER TABLE `glpi_plugin_newbase_tasks` DROP FOREIGN KEY `',CONSTRAINT_NAME,'`;')
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'glpi'
    AND TABLE_NAME = 'glpi_plugin_newbase_tasks'
    AND CONSTRAINT_NAME = 'fk_tasks_companydata'
    LIMIT 1
);

SET @sql = IFNULL(@sql,'SELECT "FK tasks não existe" AS status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT '✓ Foreign keys removidas' AS status;

-- -----------------------------------------------------
-- 2. BACKUP DA TABELA EXISTENTE
-- -----------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_companydata_backup`;

CREATE TABLE `glpi_plugin_newbase_companydata_backup` AS
SELECT * FROM `glpi_plugin_newbase_companydata`;

SELECT CONCAT('✓ Backup criado com ',COUNT(*),' registros') AS status
FROM `glpi_plugin_newbase_companydata_backup`;

-- -----------------------------------------------------
-- 3. REMOVER TABELA ANTIGA
-- -----------------------------------------------------

DROP TABLE `glpi_plugin_newbase_companydata`;

SELECT '✓ Tabela antiga removida' AS status;

-- -----------------------------------------------------
-- 4. CRIAR TABELA COM ESTRUTURA CORRETA
-- -----------------------------------------------------

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
KEY `name` (`name`),
KEY `date_mod` (`date_mod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT '✓ Tabela criada com estrutura correta' AS status;

-- -----------------------------------------------------
-- 5. RESTAURAR DADOS DO BACKUP
-- -----------------------------------------------------

INSERT INTO `glpi_plugin_newbase_companydata`
    (id,entities_id,is_recursive,name,cnpj,corporate_name,fantasy_name,
    branch,federal_registration,state_registration,city_registration,
    contract_status,date_creation,date_mod)
SELECT
    id,
    COALESCE(entities_id,0),
    COALESCE(is_recursive,0),
    name,
    cnpj,
    corporate_name,
    fantasy_name,
    branch,
    federal_registration,
    state_registration,
    city_registration,
    contract_status,
    date_creation,
    date_mod
FROM `glpi_plugin_newbase_companydata_backup`;

SELECT CONCAT('✓ Restaurados ',COUNT(*),' registros') AS status
FROM `glpi_plugin_newbase_companydata`;

-- -----------------------------------------------------
-- 6. RECRIAR FOREIGN KEY NA TABELA ADDRESS
-- -----------------------------------------------------

ALTER TABLE `glpi_plugin_newbase_address`
ADD CONSTRAINT `fk_address_company`
FOREIGN KEY (`companydata_id`)
REFERENCES `glpi_plugin_newbase_companydata` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

SELECT '✓ Foreign key address recriada' AS status;

-- -----------------------------------------------------
-- 7. VERIFICAÇÃO FINAL
-- -----------------------------------------------------

DESCRIBE `glpi_plugin_newbase_companydata`;

SELECT
    'glpi_plugin_newbase_companydata' AS Tabela,
    COUNT(*) AS Total_Registros
FROM `glpi_plugin_newbase_companydata`;

SELECT '✓ CORREÇÃO CONCLUÍDA COM SUCESSO!' AS resultado;