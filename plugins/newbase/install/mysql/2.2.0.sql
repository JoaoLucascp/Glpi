-- ============================================================
-- Newbase Plugin - Migration
-- Version : 2.2.0
-- Author  : João Lucas
-- License : GPLv2+
--
-- O que faz:
--   1. Adiciona colunas faltantes em glpi_plugin_newbase_companydatas
--   2. Cria tabelas novas:
--      - glpi_plugin_newbase_ipbx
--      - glpi_plugin_newbase_ipbx_cloud
--      - glpi_plugin_newbase_dispositivos
--      - glpi_plugin_newbase_rede
--      - glpi_plugin_newbase_chatbot
--      - glpi_plugin_newbase_linha_telefonica
--
--   NÃO altera:
--      - glpi_plugin_newbase_tasks
--      - glpi_plugin_newbase_task_signatures
--      - glpi_plugin_newbase_config
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";

-- ============================================================
-- SEÇÃO 1 — glpi_plugin_newbase_companydatas
--           Adicionar colunas se ainda não existirem
-- ============================================================

-- fantasy_name
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `fantasy_name` VARCHAR(255) DEFAULT NULL AFTER `id`;

-- corporate_name
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `corporate_name` VARCHAR(255) DEFAULT NULL AFTER `fantasy_name`;

-- cnpj
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `cnpj` VARCHAR(18) DEFAULT NULL AFTER `corporate_name`;

-- phone
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `phone` VARCHAR(20) DEFAULT NULL AFTER `cnpj`;

-- email
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `email` VARCHAR(255) DEFAULT NULL AFTER `phone`;

-- cep
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `cep` VARCHAR(10) DEFAULT NULL AFTER `email`;

-- street
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `street` VARCHAR(255) DEFAULT NULL AFTER `cep`;

-- number
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `number` VARCHAR(20) DEFAULT NULL AFTER `street`;

-- complement
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `complement` VARCHAR(255) DEFAULT NULL AFTER `number`;

-- neighborhood
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `neighborhood` VARCHAR(255) DEFAULT NULL AFTER `complement`;

-- city
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `city` VARCHAR(255) DEFAULT NULL AFTER `neighborhood`;

-- state
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `state` VARCHAR(2) DEFAULT NULL AFTER `city`;

-- country
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `country` VARCHAR(100) NOT NULL DEFAULT 'Brasil' AFTER `state`;

-- latitude
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `latitude` DECIMAL(10,8) DEFAULT NULL AFTER `country`;

-- longitude
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `longitude` DECIMAL(11,8) DEFAULT NULL AFTER `latitude`;

-- contract_status
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `contract_status` VARCHAR(50) NOT NULL DEFAULT 'active' AFTER `longitude`;

-- date_end
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `date_end` DATE NULL DEFAULT NULL AFTER `contract_status`;

-- notes
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `notes` LONGTEXT DEFAULT NULL AFTER `date_end`;

-- contact_person
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `contact_person` VARCHAR(255) DEFAULT NULL AFTER `notes`;

-- website
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD COLUMN IF NOT EXISTS `website` VARCHAR(255) DEFAULT NULL AFTER `contact_person`;

-- Índices úteis (criados apenas se não existirem via procedimento seguro)
ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD INDEX IF NOT EXISTS `cnpj` (`cnpj`);

ALTER TABLE `glpi_plugin_newbase_companydatas`
    ADD INDEX IF NOT EXISTS `contract_status` (`contract_status`);

-- ============================================================
-- SEÇÃO 2 — glpi_plugin_newbase_ipbx  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_ipbx` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `modelo`                               VARCHAR(255)    DEFAULT NULL,
    `versao`                               VARCHAR(100)    DEFAULT NULL,
    `ip_interno`                           VARCHAR(45)     DEFAULT NULL,
    `ip_externo`                           VARCHAR(45)     DEFAULT NULL,
    `porta_web`                            VARCHAR(10)     DEFAULT NULL,
    `senha_web`                            VARCHAR(255)    DEFAULT NULL,
    `porta_ssh`                            VARCHAR(10)     DEFAULT NULL,
    `senha_ssh`                            VARCHAR(255)    DEFAULT NULL,
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `systems_config`                       LONGTEXT        DEFAULT NULL COMMENT 'JSON: {ramais:[], operadoras:[]}',
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Dados de IPBX / PABX por empresa';

-- ============================================================
-- SEÇÃO 3 — glpi_plugin_newbase_ipbx_cloud  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_ipbx_cloud` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `modelo`                               VARCHAR(255)    DEFAULT NULL,
    `versao`                               VARCHAR(100)    DEFAULT NULL,
    `ip_interno`                           VARCHAR(45)     DEFAULT NULL,
    `ip_externo`                           VARCHAR(45)     DEFAULT NULL,
    `porta_web`                            VARCHAR(10)     DEFAULT NULL,
    `senha_web`                            VARCHAR(255)    DEFAULT NULL,
    `porta_ssh`                            VARCHAR(10)     DEFAULT NULL,
    `senha_ssh`                            VARCHAR(255)    DEFAULT NULL,
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `systems_config`                       LONGTEXT        DEFAULT NULL COMMENT 'JSON: {ramais:[], operadoras:[]}',
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Dados de IPBX Cloud por empresa';

-- ============================================================
-- SEÇÃO 4 — glpi_plugin_newbase_dispositivos  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_dispositivos` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `tipo_dispositivo`                     VARCHAR(100)    DEFAULT NULL,
    `ip_dispositivo`                       VARCHAR(45)     DEFAULT NULL,
    `senha_dispositivo`                    VARCHAR(255)    DEFAULT NULL,
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Dispositivos de rede por empresa (switches, roteadores, etc.)';

-- ============================================================
-- SEÇÃO 5 — glpi_plugin_newbase_rede  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_rede` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `ip`                                   VARCHAR(45)     DEFAULT NULL,
    `mascara`                              VARCHAR(45)     DEFAULT NULL,
    `gateway`                              VARCHAR(45)     DEFAULT NULL,
    `dns_primario`                         VARCHAR(45)     DEFAULT NULL,
    `dns_secundario`                       VARCHAR(45)     DEFAULT NULL,
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Configurações de rede por empresa';

-- ============================================================
-- SEÇÃO 6 — glpi_plugin_newbase_chatbot  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_chatbot` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `modelo`                               VARCHAR(255)    DEFAULT NULL,
    `chatbot_id`                           VARCHAR(100)    DEFAULT NULL,
    `data_ativacao`                        DATE            NULL DEFAULT NULL,
    `numero_telefone`                      VARCHAR(20)     DEFAULT NULL,
    `link_acesso`                          VARCHAR(500)    DEFAULT NULL,
    `plano`                                VARCHAR(255)    DEFAULT NULL,
    `qtd_usuarios`                         INT UNSIGNED    NOT NULL DEFAULT 0,
    `qtd_supervisores`                     INT UNSIGNED    NOT NULL DEFAULT 0,
    `qtd_administradores`                  INT UNSIGNED    NOT NULL DEFAULT 0,
    `login_admin`                          VARCHAR(255)    DEFAULT NULL,
    `senha_admin`                          VARCHAR(255)    DEFAULT NULL,
    `login_superadmin`                     VARCHAR(255)    DEFAULT NULL,
    `senha_superadmin`                     VARCHAR(255)    DEFAULT NULL,
    `nome_responsavel`                     VARCHAR(255)    DEFAULT NULL,
    `numero_responsavel`                   VARCHAR(20)     DEFAULT NULL,
    `email_responsavel`                    VARCHAR(255)    DEFAULT NULL,
    `redes_sociais`                        TEXT            DEFAULT NULL,
    `systems_config`                       LONGTEXT        DEFAULT NULL COMMENT 'JSON: {comunicacao_massa:[], restricoes:[], usuarios:[]}',
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Configurações de chatbot por empresa';

-- ============================================================
-- SEÇÃO 7 — glpi_plugin_newbase_linha_telefonica  (NOVA)
-- ============================================================

CREATE TABLE IF NOT EXISTS `glpi_plugin_newbase_linha_telefonica` (
    `id`                                   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `entities_id`                          INT UNSIGNED    NOT NULL DEFAULT 0,
    `plugin_newbase_companydatas_id`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `numero_piloto`                        VARCHAR(20)     DEFAULT NULL,
    `tipo_linha`                           VARCHAR(100)    DEFAULT NULL,
    `operadora`                            VARCHAR(255)    DEFAULT NULL,
    `qtd_canais`                           INT UNSIGNED    NOT NULL DEFAULT 0,
    `qtd_ddr`                              INT UNSIGNED    NOT NULL DEFAULT 0,
    `portabilidade`                        TINYINT         NOT NULL DEFAULT 0,
    `data_portabilidade`                   DATE            NULL DEFAULT NULL,
    `operadora_anterior`                   VARCHAR(255)    DEFAULT NULL,
    `data_ativacao`                        DATE            NULL DEFAULT NULL,
    `data_vencimento`                      DATE            NULL DEFAULT NULL,
    `status_linha`                         VARCHAR(50)     NOT NULL DEFAULT 'ativo',
    `ip_proxy`                             VARCHAR(45)     DEFAULT NULL,
    `porta_proxy`                          VARCHAR(10)     DEFAULT NULL,
    `ip_audio`                             VARCHAR(45)     DEFAULT NULL,
    `observacoes`                          LONGTEXT        DEFAULT NULL,
    `is_deleted`                           TINYINT         NOT NULL DEFAULT 0,
    `date_creation`                        TIMESTAMP       NULL DEFAULT NULL,
    `date_mod`                             TIMESTAMP       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `entities_id`                      (`entities_id`),
    KEY `companydatas_id`                  (`plugin_newbase_companydatas_id`),
    KEY `status_linha`                     (`status_linha`),
    KEY `is_deleted`                       (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Linhas telefônicas por empresa';

-- ============================================================
-- FIM DA MIGRATION 2.2.0
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;
