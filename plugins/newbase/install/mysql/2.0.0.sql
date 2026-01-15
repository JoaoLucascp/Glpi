-- Newbase Plugin - Database Installation
-- Version: 2.0.0
-- Compatible with GLPI 10.0.20+

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-----------------------------------------------------------------------------------------------
-- ┌──────────────────────────────────────────────────────────────────────────────────────────┐
-- | Tipo               | O Que Armazena           | Exemplo                                  |
-- | ------------------ | ------------------------ | ---------------------------------------- |
-- | INT                | Números inteiros         | ID (1, 2, 3, 100)                        |
-- | VARCHAR(255)       | Texto com tamanho máximo | Nome da empresa                          |
-- | TEXT               | Texto longo sem limite   | Descrição, comentários                   |
-- | DECIMAL(10,2)      | Número com decimais      | Preço (1.234,56) ou Quilometragem (5.34) |
-- | TIMESTAMP          | Data e hora              | 2026-01-12 12:05:00                      |
-- | BOOLEAN ou TINYINT | Verdadeiro/Falso         | Ativo: 0 ou 1                            |
-- | LONGBLOB           | Arquivo binário          | Assinatura digital, foto                 |
-- └──────────────────────────────────────────────────────────────────────────────────────────┘

-- Tabela 1: glpi_plugin_newbase_companydata (Empresas)
-- Esta tabela armazena dados sobre as empresas cadastradas no sistema.

-- Estrutura (simplificada):
-- ┌───────────────────────────────────────────────────────────────┐
-- │ glpi_plugin_newbase_companydata                               │
-- ├───────────────────────────────────────────────────────────────┤
-- │ id            | INT          | Número único da empresa        │
-- │ name          | VARCHAR(255) | Nome da empresa (Razão Social) │
-- │ cnpj          | VARCHAR(20)  | CNPJ (14 dígitos)              │
-- │ phone         | VARCHAR(20)  | Telefone                       │
-- │ email         | VARCHAR(255) | E-mail                         │
-- │ website       | VARCHAR(255) | Site da empresa                │
-- │ responsible   | VARCHAR(255) | Responsável                    │
-- │ date_creation | TIMESTAMP    | Data de criação                │
-- │ date_mod      | TIMESTAMP    | Data de modificação            │
-- └───────────────────────────────────────────────────────────────┘
-- Exemplo de DADOS nesta tabela:
-- ┌────┬─────────────────┬────────────────────┬────────────────┐
-- │ id │ name            │ cnpj               │ phone          │
-- ├────┼─────────────────┼────────────────────┼────────────────┤
-- │  1 │ Newtel Soluções │ 12.345.678/0001-90 │ (27) 3000-1111 │
-- └────┴─────────────────┴────────────────────┴────────────────┘
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_companydata`;

CREATE TABLE `glpi_plugin_newbase_companydata` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) DEFAULT NULL,
    `cnpj` VARCHAR(18) DEFAULT NULL,
    `corporate_name` VARCHAR(255) DEFAULT NULL,
    `fantasy_name` VARCHAR(255) DEFAULT NULL,
    `branch` VARCHAR(100) DEFAULT NULL,
    `entities_id` INT unsigned NOT NULL DEFAULT '0',
    `is_recursive` TINYINT NOT NULL DEFAULT '0',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY (`id`),
    KEY `name` (`name`),
    KEY `cnpj` (`cnpj`),
    KEY `entities_id` (`entities_id`),
    KEY `is_deleted` (`is_deleted`),
    KEY `date_mod` (`date_mod`)
    CONSTRAINT `fk_company_entities`
    FOREIGN KEY (`entities_id`)
    REFERENCES `glpi_entities` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Tabela 2: glpi_plugin_newbase_addresses (Endereços)
-- Esta tabela armazena endereços das empresas.

-- Estrutura: (simplificada)
-- ┌───────────────────────────────────────────────┐
-- │ glpi_plugin_newbase_addresses                 │
-- ├───────────────────────────────────────────────┤
-- │ id             | INT | Número único           │
-- │ companydata_id | INT | ID da empresa          │
-- │ street         | VARCHAR(255) | Rua           │
-- │ number         | VARCHAR(20) | Número         │
-- │ neighborhood   | VARCHAR(100) | Bairro        │
-- │ city           | VARCHAR(100) | Cidade        │
-- │ state          | VARCHAR(2) | Estado (ES, RJ) │
-- │ cep            | VARCHAR(10) | CEP            │
-- │ latitude       | DECIMAL(10,8) | Latitude     │
-- │ longitude      | DECIMAL(11,8) | Longitude    │
-- └───────────────────────────────────────────────┘
-- Exemplo de DADOS:
-- ┌────┬────────────────┬───────────────────┬───────────┐
-- │ id │ companydata_id │ street │ city     │ cep       │
-- ├────┼────────────────┼────────┼──────────┼───────────┤
-- │ 1  │ 1              │ Rua A  │ Linhares │ 29900-000 │
-- └────┴────────────────┴────────┴──────────┴───────────┘
-- --------------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_addresses`;

CREATE TABLE `glpi_plugin_newbase_addresses` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) DEFAULT NULL,
    `companydata_id` INT unsigned NOT NULL DEFAULT '0',
    `cep` VARCHAR(10) DEFAULT NULL,
    `street` VARCHAR(255) DEFAULT NULL,
    `number` VARCHAR(20) DEFAULT NULL,
    `complement` VARCHAR(255) DEFAULT NULL,
    `neighborhood` VARCHAR(100) DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT NULL,
    `state` VARCHAR(2) DEFAULT NULL,
    `latitude` DECIMAL(10,8) DEFAULT NULL,
    `longitude` DECIMAL(11,8) DEFAULT NULL,
    `entities_id` INT unsigned NOT NULL DEFAULT '0',
    `is_recursive` TINYINT NOT NULL DEFAULT '0',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY (`id`),
    KEY `name` (`name`),
    KEY `companydata_id` (`companydata_id`),
    KEY `cep` (`cep`),
    KEY `entities_id` (`entities_id`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_address_company`
    FOREIGN KEY (`companydata_id`)
    REFERENCES `glpi_plugin_newbase_companydata` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Tabela 3: glpi_plugin_newbase_systems (Sistemas Telefônicos)
-- Armazena sistemas telefônicos como PABX, IPBX, Chatbot, etc.

-- Estrutura: (simplificada)
-- ┌────────────────────────────────────────────────┐
-- │ glpi_plugin_newbase_systems                    │
-- ├────────────────────────────────────────────────┤
-- │ id       INT   | INT | Número único            │
-- │ companydata_id | INT | Qual empresa tem este   │
-- │ name           | VARCHAR(255) | Nome do sistema│
-- │ system_type    | VARCHAR(100) | PABX/IPBX/etc  │
-- │ ip_address     | VARCHAR(15) | IP do servidor  │
-- │ port           | INT | Porta (padrão: 80)      │
-- │ description    | VARCHAR(255) | Descrição              │
-- └────────────────────────────────────────────────┘
-- Exemplo:
-- A Newtel (ID=1) tem:
-- - PABX da INTelbrDECIMAL (ID sistema = 1)
-- - IPBX Cloud da Asterisk (ID sistema = 2)
-- - Chatbot no WhatsApp    (ID sistema = 3)
-- --------------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_systems`;

CREATE TABLE `glpi_plugin_newbase_systems` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `companydata_id` INT unsigned NOT NULL DEFAULT '0',
    `name` VARCHAR(255) DEFAULT NULL,
    `system_type` VARCHAR(100) DEFAULT NULL,
    `description` VARCHAR(255)DEFAULT NULL,
    `ip_address` VARCHAR(15) DEFAULT NULL,
    `port` INT DEFAULT NULL,
    `username` VARCHAR(255) DEFAULT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `entities_id` INT unsigned NOT NULL DEFAULT '0',
    `is_recursive` TINYINT NOT NULL DEFAULT '0',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY (`id`),
    KEY `name` (`name`),
    KEY `companydata_id` (`companydata_id`),
    KEY `entities_id` (`entities_id`),
    KEY `is_deleted` (`is_deleted`)
    CONSTRAINT `fk_system_company`
    FOREIGN KEY (`companydata_id`)
    REFERENCES `glpi_plugin_newbase_companydata` (`id`)
    ON DELETE CASCADE
    CONSTRAINT `fk_system_entity`
    FOREIGN KEY (`entities_id`)
    REFERENCES `glpi_entities` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Tabela 4: glpi_plugin_newbase_tasks (Tarefas)
-- Armazena tarefas com informações de localização, status, etc.

-- Estrutura: (INTplificada)
-- ┌───────────────────────────────────────────┐
-- │ glpi_plugin_newbase_tasks                 │
-- ├───────────────────────────────────────────┤
-- │ id              | INT | Número único      │
-- │ title           | VARCHAR(255) | Título   │
-- │ description     | VARCHAR(255) | Descrição│
-- │ status          | VARCHAR(50) | Aberta/...│
-- │ start_latitude  | DECIMAL(10,8) | Início  │
-- │ start_longitude | DECIMAL(11,8) | Início  │
-- │ end_latitude    | DECIMAL(10,8) | Fim     │
-- │ end_longitude   | DECIMAL(11,8) | Fim     │
-- │ mileage         | DECIMAL(10,2) | Quilom. │
-- │ date_creation   | TIMESTAMP | Data criação│
-- └───────────────────────────────────────────┘
-- Exemplo: Uma tarefa de visita técnica com coordenadas GPS de saída e chegada.
---------------------------------------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_tasks`;

CREATE TABLE `glpi_plugin_newbase_tasks` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) DEFAULT NULL,
    `description` VARCHAR(255),
    `status` VARCHAR(50) DEFAULT 'pending',
    `start_latitude` DECIMAL(10,8) DEFAULT NULL,
    `start_longitude` DECIMAL(10,8) DEFAULT NULL,
    `end_latitude` DECIMAL(10,8) DEFAULT NULL,
    `end_longitude` DECIMAL(10,8) DEFAULT NULL,
    `mileage` DECIMAL(10,2) DEFAULT NULL,
    `entities_id` INT unsigned NOT NULL DEFAULT '0',
    `companydata_id` INT unsigned NOT NULL DEFAULT '0',
    `address_id` INT unsigned NOT NULL DEFAULT '0',
    `users_id` INT unsigned NOT NULL DEFAULT '0',
    `priority` VARCHAR(50) DEFAULT 'normal',
    `start_date` TIMESTAMP NULL DEFAULT NULL,
    `end_date` TIMESTAMP NULL DEFAULT NULL,
    `is_recursive` TINYINT NOT NULL DEFAULT '0',
    `is_deleted` TINYINT NOT NULL DEFAULT '0',
    `date_creation` TIMESTAMP NULL DEFAULT NULL,
    `date_mod` TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY (`id`),
    KEY `name` (`name`),
    KEY `companydata_id` (`companydata_id`),
    KEY `address_id` (`address_id`),
    KEY `users_id` (`users_id`),
    KEY `status` (`status`),
    KEY `entities_id` (`entities_id`),
    KEY `is_deleted` (`is_deleted`),
    CONSTRAINT `fk_task_company`
    FOREIGN KEY (`companydata_id`)
    REFERENCES `glpi_plugin_newbase_companydata` (`id`)
    ON DELETE CASCADE
    CONSTRAINT `fk_task_entity`
    FOREIGN KEY (`entities_id`)
    REFERENCES `glpi_entities` (`id`)
    ON DELETE CASCADE
    CONSTRAINT `fk_task_address`
    FOREIGN KEY (`address_id`)
    REFERENCES `glpi_plugin_newbase_addresses` (`id`)
    ON DELETE CASCADE
    CONSTRAINT `fk_task_user`
    FOREIGN KEY (`users_id`)
    REFERENCES `glpi_users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

---------------------------------------------------------
-- Tabela 5: glpi_newbase_tasksignatures (Assinaturas)
-- Armazena assinaturas digitais das tarefas concluídas.

-- Estrutura: (simplificada)
-- ┌─────────────────────────────────────────────┐
-- │ glpi_newbase_tasksignatures                 │
-- ├─────────────────────────────────────────────┤
-- │ id          | INT | Número único            │
-- │ task_id     | INT | Qual tarefa (ligação)   │
-- │ signature   | LONGBLOB | Dados da assinatura│
-- │ date_signed | TIMESTAMP | Data da assinatura│
-- └─────────────────────────────────────────────┘
---------------------------------------------------------

DROP TABLE IF EXISTS `glpi_plugin_newbase_tasksignatures`;

CREATE TABLE `glpi_plugin_newbase_tasksignatures` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `task_id` INT unsigned NOT NULL DEFAULT '0',
    `signature` longblob,
    `date_signed` TIMESTAMP NULL DEFAULT NULL,
PRIMARY KEY (`id`),
    KEY `task_id` (`task_id`)
    CONSTRAINT `fk_signature_task`
    FOREIGN KEY (`task_id`)
    REFERENCES `glpi_plugin_newbase_tasks` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Tabela: glpi_plugin_newbase_configs
-- INT-----------------------------------------------INT---

DROP TABLE IF EXISTS `glpi_plugin_newbase_configs`;

CREATE TABLE `glpi_plugin_newbase_configs` (
    `id` INT unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `value` VARCHAR(255),
PRIMARY KEY (`id`),
UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Inserir configurações padrão
INSERT INTO `glpi_plugin_newbase_configs` (`name`, `value`) VALUES
    ('version', '2.0.0'),
    ('api_timeout', '30'),
    ('enable_geolocation', '1'),
    ('enable_signature', '1');