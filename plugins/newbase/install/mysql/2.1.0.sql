-- Newbase Plugin - Database Installation
-- Version: 2.1.0
-- Compatible with GLPI 10.0.20+
--
-- NOTA: Este plugin AGORA utiliza apenas tabelas nativas do GLPI para gestão de empresas
-- Lê diretamente de glpi_entities, eliminando duplicação de dados e mantendo sincronização automática
--
-- Tabelas criadas APENAS para funcionalidades exclusivas do Newbase:
-- - Complementos de empresa
-- - Documentação de sistemas (Asterisk, CloudPBX, Chatbot)
-- - Tarefas com geolocalização
-- - Assinaturas digitais

SET FOREIGN_KEY_CHECKS = 0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "+00:00";

-- ============================================================================
-- Tabela 1: glpi_plugin_newbase_company_extras (Complementos de Empresa)
-- ============================================================================
-- Armazena dados COMPLEMENTARES de uma empresa (não duplica dados de glpi_entities)
-- Cada empresa está vinculada a uma Entity do GLPI via chave estrangeira

DROP TABLE IF EXISTS `glpi_plugin_newbase_company_extras`;

CREATE TABLE `glpi_plugin_newbase_company_extras` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `entities_id` INT unsigned NOT NULL COMMENT 'FK para glpi_entities',
    `cnpj` VARCHAR(18) DEFAULT NULL COMMENT 'CNPJ da empresa',
    `corporate_name` VARCHAR(255) DEFAULT NULL COMMENT 'Razão Social',
    `fantasy_name` VARCHAR(255) DEFAULT NULL COMMENT 'Nome Fantasia',
    `cep` VARCHAR(10) DEFAULT NULL COMMENT 'CEP Principal',
    `website` VARCHAR(255) DEFAULT NULL COMMENT 'Website',
    `contract_status` ENUM(
        'active',
        'inactive',
        'canceled'
    ) DEFAULT 'active' COMMENT 'Status do contrato',
    `notes` TEXT COMMENT 'Notas sobre a empresa',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `entities_id` (`entities_id`),
    KEY `cnpj` (`cnpj`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_company_extras_entity` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC COMMENT = 'Dados complementares de empresas (Newbase)';

-- ============================================================================
-- Tabela 2: glpi_plugin_newbase_systems (Documentação de Sistemas)
-- ============================================================================
-- Documentação de sistemas telefônicos e ferramentas relacionadas
-- IPBX, PABX, CloudPBX, Chatbot, Linhas telefônicas, etc.

DROP TABLE IF EXISTS `glpi_plugin_newbase_systems`;

CREATE TABLE `glpi_plugin_newbase_systems` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `entities_id` INT unsigned NOT NULL COMMENT 'FK para glpi_entities (empresa)',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nome do sistema',
    `system_type` ENUM(
        'ipbx',
        'pabx',
        'cloudpbx',
        'chatbot',
        'line',
        'other'
    ) DEFAULT 'other' COMMENT 'Tipo de sistema',
    `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'Endereço IP (suporta IPv4 e IPv6)',
    `port` INT DEFAULT NULL COMMENT 'Porta',
    `username` VARCHAR(255) DEFAULT NULL COMMENT 'Usuário',
    `password_encrypted` TEXT DEFAULT NULL COMMENT 'Senha criptografada',
    `status` ENUM(
        'active',
        'inactive',
        'maintenance'
    ) DEFAULT 'active' COMMENT 'Status',
    `description` TEXT COMMENT 'Descrição do sistema',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `system_type` (`system_type`),
    KEY `status` (`status`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_systems_entity` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC COMMENT = 'Documentação de sistemas telefônicos e ferramentas';

-- ============================================================================
-- Tabela 3: glpi_plugin_newbase_chatbot (Chatbot Omnichannel)
-- ============================================================================
-- Configuração e documentação de Chatbots Omnichannel

DROP TABLE IF EXISTS `glpi_plugin_newbase_chatbot`;

CREATE TABLE `glpi_plugin_newbase_chatbot` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `entities_id` INT unsigned NOT NULL COMMENT 'FK para glpi_entities',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nome do Chatbot',
    `provider` VARCHAR(100) DEFAULT NULL COMMENT 'Provedor (Zendesk, Landbot, etc)',
    `api_url` VARCHAR(255) DEFAULT NULL COMMENT 'URL da API',
    `api_key` TEXT DEFAULT NULL COMMENT 'Chave de API criptografada',
    `channels` JSON DEFAULT NULL COMMENT 'Canais suportados (WhatsApp, Telegram, etc)',
    `status` ENUM(
        'active',
        'inactive',
        'testing'
    ) DEFAULT 'testing' COMMENT 'Status',
    `description` TEXT COMMENT 'Descrição',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `status` (`status`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_chatbot_entity` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC COMMENT = 'Configuração de Chatbot Omnichannel';

-- ============================================================================
-- Tabela 4: glpi_plugin_newbase_tasks (Tarefas com Geolocalização)
-- ============================================================================
-- Gerenciamento de tarefas com rastreamento de localização e quilometragem

DROP TABLE IF EXISTS `glpi_plugin_newbase_tasks`;

CREATE TABLE `glpi_plugin_newbase_tasks` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `entities_id` INT unsigned NOT NULL COMMENT 'FK para glpi_entities',
    `users_id` INT unsigned DEFAULT NULL COMMENT 'Usuário atribuído',
    `title` VARCHAR(255) NOT NULL COMMENT 'Título da tarefa',
    `description` TEXT COMMENT 'Descrição',
    `status` ENUM(
        'open',
        'in_progress',
        'paused',
        'completed',
        'canceled'
    ) DEFAULT 'open' COMMENT 'Status da tarefa',
    `priority` ENUM(
        'low',
        'medium',
        'high',
        'urgent'
    ) DEFAULT 'medium' COMMENT 'Prioridade',
    `start_latitude` DECIMAL(10, 8) DEFAULT NULL COMMENT 'Latitude inicial',
    `start_longitude` DECIMAL(11, 8) DEFAULT NULL COMMENT 'Longitude inicial',
    `end_latitude` DECIMAL(10, 8) DEFAULT NULL COMMENT 'Latitude final',
    `end_longitude` DECIMAL(11, 8) DEFAULT NULL COMMENT 'Longitude final',
    `estimated_kilometers` DECIMAL(10, 2) DEFAULT NULL COMMENT 'Quilometragem estimada',
    `actual_kilometers` DECIMAL(10, 2) DEFAULT NULL COMMENT 'Quilometragem real',
    `start_date` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data/hora de início',
    `end_date` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data/hora de término',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `entities_id` (`entities_id`),
    KEY `users_id` (`users_id`),
    KEY `status` (`status`),
    KEY `is_deleted` (`is_deleted`),
    KEY `start_date` (`start_date`),
    CONSTRAINT `fk_tasks_entity` FOREIGN KEY (`entities_id`) REFERENCES `glpi_entities` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tasks_user` FOREIGN KEY (`users_id`) REFERENCES `glpi_users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC COMMENT = 'Tarefas com rastreamento de geolocalização';

-- ============================================================================
-- Tabela 5: glpi_plugin_newbase_signatures (Assinaturas Digitais)
-- ============================================================================
-- Armazenamento de assinaturas digitais capturadas em tarefas

DROP TABLE IF EXISTS `glpi_plugin_newbase_signatures`;

CREATE TABLE `glpi_plugin_newbase_signatures` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `tasks_id` INT unsigned NOT NULL COMMENT 'FK para tarefas',
    `users_id` INT unsigned NOT NULL COMMENT 'Usuário que assinou',
    `signature_data` LONGBLOB NOT NULL COMMENT 'Dados da assinatura (imagem)',
    `signature_format` VARCHAR(20) DEFAULT 'png' COMMENT 'Formato (png, jpg, etc)',
    `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'IP de origem',
    `user_agent` TEXT DEFAULT NULL COMMENT 'User Agent do navegador',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tasks_id` (`tasks_id`),
    KEY `users_id` (`users_id`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_signatures_task` FOREIGN KEY (`tasks_id`) REFERENCES `glpi_plugin_newbase_tasks` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_signatures_user` FOREIGN KEY (`users_id`) REFERENCES `glpi_users` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC COMMENT = 'Assinaturas digitais de tarefas';

SET FOREIGN_KEY_CHECKS = 1;