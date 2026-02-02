-- Newbase Plugin - Database Installation
-- Version: 2.1.0
-- Compatible with GLPI 10.0.20+
-- Author: João Lucas
-- License: GPLv2+

SET FOREIGN_KEY_CHECKS = 0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "-03:00";

-- TABELA 1: glpi_plugin_newbase_addresses (Endereços)
DROP TABLE IF EXISTS `glpi_plugin_newbase_addresses`;

CREATE TABLE `glpi_plugin_newbase_addresses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `name` VARCHAR(255) NOT NULL,
    `cep` VARCHAR(10) DEFAULT NULL,
    `street` VARCHAR(255) DEFAULT NULL,
    `number` VARCHAR(20) DEFAULT NULL,
    `complement` VARCHAR(255) DEFAULT NULL,
    `neighborhood` VARCHAR(255) DEFAULT NULL,
    `city` VARCHAR(255) DEFAULT NULL,
    `state` VARCHAR(2) DEFAULT NULL,
    `latitude` DECIMAL(10, 8) DEFAULT NULL,
    `longitude` DECIMAL(11, 8) DEFAULT NULL,
    `is_recursive` TINYINT NOT NULL DEFAULT 0,
    `is_deleted` TINYINT NOT NULL DEFAULT 0,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `cep` (`cep`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_addresses_entities` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Endereços cadastrados no Newbase';

-- TABELA 2: glpi_plugin_newbase_systems (Sistemas)
DROP TABLE IF EXISTS `glpi_plugin_newbase_systems`;

CREATE TABLE `glpi_plugin_newbase_systems` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `name` VARCHAR(255) NOT NULL,
    `system_type` VARCHAR(50) NOT NULL DEFAULT 'pabx',
    `status` VARCHAR(50) NOT NULL DEFAULT 'active',
    `description` TEXT,
    `configuration` LONGTEXT,
    `is_recursive` TINYINT NOT NULL DEFAULT 0,
    `is_deleted` TINYINT NOT NULL DEFAULT 0,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `system_type` (`system_type`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_systems_entities` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Documentação de sistemas (IPBX, Chatbot, etc)';

-- TABELA 3: glpi_plugin_newbase_tasks (Tarefas)
DROP TABLE IF EXISTS `glpi_plugin_newbase_tasks`;

CREATE TABLE `glpi_plugin_newbase_tasks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `entities_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `users_id` INT UNSIGNED DEFAULT 0,
    `plugin_newbase_addresses_id` INT UNSIGNED DEFAULT NULL,
    `plugin_newbase_systems_id` INT UNSIGNED DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `status` VARCHAR(50) NOT NULL DEFAULT 'new',
    `date_start` TIMESTAMP NULL DEFAULT NULL,
    `date_end` TIMESTAMP NULL DEFAULT NULL,
    `gps_start_lat` DECIMAL(10, 8) DEFAULT NULL,
    `gps_start_lng` DECIMAL(11, 8) DEFAULT NULL,
    `gps_end_lat` DECIMAL(10, 8) DEFAULT NULL,
    `gps_end_lng` DECIMAL(11, 8) DEFAULT NULL,
    `mileage` DECIMAL(10, 2) DEFAULT NULL,
    `is_recursive` TINYINT NOT NULL DEFAULT 0,
    `is_deleted` TINYINT NOT NULL DEFAULT 0,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `users_id` (`users_id`),
    KEY `addresses_id` (`plugin_newbase_addresses_id`),
    KEY `systems_id` (`plugin_newbase_systems_id`),
    KEY `status` (`status`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_tasks_entities` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tasks_users` FOREIGN KEY (`users_id`) REFERENCES `glpi_users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tasks_addresses` FOREIGN KEY (`plugin_newbase_addresses_id`) REFERENCES `glpi_plugin_newbase_addresses` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tasks_systems` FOREIGN KEY (`plugin_newbase_systems_id`) REFERENCES `glpi_plugin_newbase_systems` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Tarefas com geolocalização e quilometragem';

-- TABELA 4: glpi_plugin_newbase_task_signatures (Assinaturas)
DROP TABLE IF EXISTS `glpi_plugin_newbase_task_signatures`;

CREATE TABLE `glpi_plugin_newbase_task_signatures` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `plugin_newbase_tasks_id` INT UNSIGNED NOT NULL,
    `signature_data` LONGTEXT NOT NULL,
    `signer_name` VARCHAR(255) DEFAULT NULL,
    `users_id` INT UNSIGNED DEFAULT NULL,
    `is_deleted` TINYINT NOT NULL DEFAULT 0,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tasks_id` (`plugin_newbase_tasks_id`),
    KEY `users_id` (`users_id`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_signatures_tasks` FOREIGN KEY (`plugin_newbase_tasks_id`) REFERENCES `glpi_plugin_newbase_tasks` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Assinaturas digitais de tarefas';

-- TABELA 5: glpi_plugin_newbase_company_extras (Complementos de Empresa)
DROP TABLE IF EXISTS `glpi_plugin_newbase_company_extras`;

CREATE TABLE `glpi_plugin_newbase_company_extras` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `entities_id` INT UNSIGNED NOT NULL,
    `cnpj` VARCHAR(18) DEFAULT NULL,
    `corporate_name` VARCHAR(255) DEFAULT NULL,
    `fantasy_name` VARCHAR(255) DEFAULT NULL,
    `contact_person` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `notes` LONGTEXT,
    `is_deleted` TINYINT NOT NULL DEFAULT 0,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `cnpj` (`cnpj`),
    KEY `is_deleted` (`is_deleted`),
    UNIQUE KEY `unique_entities_id` (`entities_id`),
    CONSTRAINT `fk_company_extras_entities` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Dados complementares de empresas';

-- TABELA 6: glpi_plugin_newbase_config (Configuração)
DROP TABLE IF EXISTS `glpi_plugin_newbase_config`;

CREATE TABLE `glpi_plugin_newbase_config` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `config_key` VARCHAR(255) NOT NULL,
    `config_value` LONGTEXT,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_config_key` (`config_key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Configurações do plugin';

-- INSERIR CONFIGURAÇÕES PADRÃO
INSERT INTO
    `glpi_plugin_newbase_config` (`config_key`, `config_value`)
VALUES ('enable_signature', '1'),
    ('require_signature', '0'),
    ('enable_gps', '1'),
    ('calculate_mileage', '1'),
    ('default_map_zoom', '13');

SET FOREIGN_KEY_CHECKS = 1;