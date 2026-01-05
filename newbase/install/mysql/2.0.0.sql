-- Newbase Database Schema
-- Version: 2.0.0
-- GLPI Version: 10.0.20+
-- Database: MySQL 8.4.6+

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table: glpi_plugin_newbase_companydata
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_companydata`;
CREATE TABLE `glpi_plugin_newbase_companydata` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entities_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
  `cnpj` varchar(18) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `legal_name` varchar(255) DEFAULT NULL,
  `fantasy_name` varchar(255) DEFAULT NULL,
  `state_registration` varchar(30) DEFAULT NULL,
  `city_registration` varchar(30) DEFAULT NULL,
  `contract_status` enum('active','inactive','cancelled') NOT NULL DEFAULT 'active',
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `modified_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cnpj` (`cnpj`),
  KEY `entities_id` (`entities_id`),
  KEY `date_creation` (`date_creation`),
  KEY `date_mod` (`date_mod`),
  KEY `created_by` (`created_by`),
  KEY `modified_by` (`modified_by`),
  KEY `contract_status` (`contract_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_address
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_address`;
CREATE TABLE `glpi_plugin_newbase_address` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
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
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `modified_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
  KEY `cep` (`cep`),
  KEY `city` (`city`),
  KEY `state` (`state`),
  CONSTRAINT `fk_address_company` FOREIGN KEY (`plugin_newbase_companydata_id`) REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_system
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_system`;
CREATE TABLE `glpi_plugin_newbase_system` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('ipbx','pabx','chatbot','ipbx_cloud','telephone_line') NOT NULL DEFAULT 'ipbx',
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `modified_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  CONSTRAINT `fk_system_company` FOREIGN KEY (`plugin_newbase_companydata_id`) REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_task
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_task`;
CREATE TABLE `glpi_plugin_newbase_task` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_newbase_companydata_id` INT UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('open','in_progress','paused','completed') NOT NULL DEFAULT 'open',
  `assigned_to` INT UNSIGNED DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `latitude_start` decimal(10,8) DEFAULT NULL,
  `longitude_start` decimal(11,8) DEFAULT NULL,
  `latitude_end` decimal(10,8) DEFAULT NULL,
  `longitude_end` decimal(11,8) DEFAULT NULL,
  `mileage` decimal(10,2) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  `modified_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_newbase_companydata_id` (`plugin_newbase_companydata_id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `status` (`status`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`),
  CONSTRAINT `fk_task_company` FOREIGN KEY (`plugin_newbase_companydata_id`) REFERENCES `glpi_plugin_newbase_companydata` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_task_user` FOREIGN KEY (`assigned_to`) REFERENCES `glpi_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_tasksignature
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_tasksignature`;
CREATE TABLE `glpi_plugin_newbase_tasksignature` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_newbase_task_id` INT UNSIGNED NOT NULL,
  `signature_data` longblob DEFAULT NULL,
  `signature_filename` varchar(255) DEFAULT NULL,
  `signature_mime` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL,
  `created_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plugin_newbase_task_id` (`plugin_newbase_task_id`),
  CONSTRAINT `fk_signature_task` FOREIGN KEY (`plugin_newbase_task_id`) REFERENCES `glpi_plugin_newbase_task` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Table: glpi_plugin_newbase_config
--

DROP TABLE IF EXISTS `glpi_plugin_newbase_config`;
CREATE TABLE `glpi_plugin_newbase_config` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) NOT NULL,
  `config_value` text DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Default configuration values
--

INSERT INTO `glpi_plugin_newbase_config` (`config_key`, `config_value`, `date_mod`) VALUES
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
