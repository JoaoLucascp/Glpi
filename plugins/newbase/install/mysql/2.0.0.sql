-- Newbase Database Schema
-- Version: 2.0.0
-- GLPI Version: 10.0.20+
-- Database: MySQL 8.4.6+

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table: glpi_plugin_newbase_companydatas (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_companydatas`;

CREATE TABLE `glpi_plugin_newbase_companydatas` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `entities_id` INT UNSIGNED NOT NULL DEFAULT '0',
    `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL,
    `cnpj` varchar(18) DEFAULT NULL,
    `corporate_name` varchar(255) DEFAULT NULL,
    `fantasy_name` varchar(255) DEFAULT NULL,
    `branch` varchar(100) DEFAULT NULL,
    `federal_registration` varchar(50) DEFAULT NULL,
    `state_registration` varchar(50) DEFAULT NULL,
    `city_registration` varchar(50) DEFAULT NULL,
    `contract_status` varchar(50) DEFAULT 'active',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `is_recursive` (`is_recursive`),
    KEY `cnpj` (`cnpj`),
    KEY `contract_status` (`contract_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_addresses (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_addresses`;

CREATE TABLE `glpi_plugin_newbase_addresses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `plugin_newbase_companydatas_id` INT UNSIGNED NOT NULL,
    `cep` varchar(9) DEFAULT NULL,
    `street` varchar(255) DEFAULT NULL,
    `number` varchar(10) DEFAULT NULL,
    `complement` varchar(255) DEFAULT NULL,
    `neighborhood` varchar(100) DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `state` varchar(2) DEFAULT NULL,
    `country` varchar(100) DEFAULT 'Brasil',
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plugin_newbase_companydatas_id` (`plugin_newbase_companydatas_id`),
    KEY `cep` (`cep`),
    KEY `city` (`city`),
    KEY `state` (`state`),
    CONSTRAINT `fk_address_company`
        FOREIGN KEY (`plugin_newbase_companydatas_id`)
        REFERENCES `glpi_plugin_newbase_companydatas` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_systems (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_systems`;

CREATE TABLE `glpi_plugin_newbase_systems` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `plugin_newbase_companydatas_id` INT UNSIGNED NOT NULL,
    `name` varchar(255) NOT NULL,
    `type` enum('ipbx','pabx','chatbot','ipbx_cloud','telephone_line') NOT NULL DEFAULT 'ipbx',
    `description` text DEFAULT NULL,
    `status` enum('active','inactive') NOT NULL DEFAULT 'active',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plugin_newbase_companydatas_id` (`plugin_newbase_companydatas_id`),
    KEY `type` (`type`),
    KEY `status` (`status`),
    CONSTRAINT `fk_system_company`
        FOREIGN KEY (`plugin_newbase_companydatas_id`)
        REFERENCES `glpi_plugin_newbase_companydatas` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_tasks (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_tasks`;

CREATE TABLE `glpi_plugin_newbase_tasks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `plugin_newbase_companydatas_id` INT UNSIGNED NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `status` enum('open','in_progress','paused','completed') NOT NULL DEFAULT 'open',
    `assigned_to` INT UNSIGNED DEFAULT NULL,
    `date_start` TIMESTAMP NULL DEFAULT NULL,
    `date_end` TIMESTAMP NULL DEFAULT NULL,
    `latitude_start` decimal(10,8) DEFAULT NULL,
    `longitude_start` decimal(11,8) DEFAULT NULL,
    `latitude_end` decimal(10,8) DEFAULT NULL,
    `longitude_end` decimal(11,8) DEFAULT NULL,
    `mileage` decimal(10,2) DEFAULT NULL,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plugin_newbase_companydatas_id` (`plugin_newbase_companydatas_id`),
    KEY `assigned_to` (`assigned_to`),
    KEY `status` (`status`),
    KEY `date_start` (`date_start`),
    KEY `date_end` (`date_end`),
    CONSTRAINT `fk_task_company`
        FOREIGN KEY (`plugin_newbase_companydatas_id`)
        REFERENCES `glpi_plugin_newbase_companydatas` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_task_user`
        FOREIGN KEY (`assigned_to`)
        REFERENCES `glpi_users` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_tasksignatures (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_tasksignatures`;

CREATE TABLE `glpi_plugin_newbase_tasksignatures` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `plugin_newbase_tasks_id` INT UNSIGNED NOT NULL,
    `signature_data` longblob DEFAULT NULL,
    `signature_filename` varchar(255) DEFAULT NULL,
    `signature_mime` varchar(100) DEFAULT NULL,
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `plugin_newbase_tasks_id` (`plugin_newbase_tasks_id`),
    CONSTRAINT `fk_signature_task`
        FOREIGN KEY (`plugin_newbase_tasks_id`)
        REFERENCES `glpi_plugin_newbase_tasks` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_configs (PLURAL)
--
DROP TABLE IF EXISTS `glpi_plugin_newbase_configs`;

CREATE TABLE `glpi_plugin_newbase_configs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `config_key` varchar(100) NOT NULL,
    `config_value` text DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Default configuration values
--
INSERT INTO `glpi_plugin_newbase_configs` (`config_key`, `config_value`, `date_mod`) VALUES
('enable_cnpj_api', '1', NOW()),
('enable_cep_api', '1', NOW()),
('enable_geolocation', '1', NOW()),
('enable_signature', '1', NOW()),
('cnpj_api_url', 'https://brasilapi.com.br/api/cnpj/v1/', NOW()),
('cep_api_url', 'https://viacep.com.br/ws/', NOW()),
('map_provider', 'leaflet', NOW()),
('map_default_zoom', '13', NOW()),
('map_default_lat', '-23.5505', NOW()),
('map_default_lng', '-46.6333', NOW()),
('auto_calculate_mileage', '1', NOW()),
('require_signature', '0', NOW());
