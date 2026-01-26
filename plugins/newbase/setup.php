<?php

/**
* Setup File - Plugin Newbase
* Arquivo de configuração e registro do plugin com GLPI
* Define classes wrapper para compatibilidade com GLPI 10.0.20
* @package   GlpiPlugin\Newbase
* @author    João Lucas
* @license   GPLv2+
* @since     2.1.0
*/

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// CARREGAR AUTOLOADER DO COMPOSER
require_once(__DIR__ . '/vendor/autoload.php');

// DEFINIÇÕES GLOBAIS DO PLUGIN

define('PLUGIN_NEWBASE_VERSION', '2.1.0');

define('PLUGIN_NEWBASE_MIN_GLPI', '10.0.20');

define('PLUGIN_NEWBASE_MAX_GLPI', '10.9.99');

define('PLUGIN_NEWBASE_NAME', 'Newbase');

define('PLUGIN_NEWBASE_AUTHOR', 'João Lucas');

define('PLUGIN_NEWBASE_LICENSE', 'GPLv2+');

define('PLUGIN_NEWBASE_HOMEPAGE', 'https://github.com/joao-lucas/glpi-newbase');

define(
    'PLUGIN_NEWBASE_DESCRIPTION',
    'Sistema completo de Gestão de documentação de empresas '
    . 'para GLPI com gerenciamento de empresas, documentação de servidor '
    . 'telefônico baseado em asterisk, documentação de servidor telefônico '
    . 'em nuvem baseado em asterisk, documentação de sistema Chatbot Omnichannel, '
    . 'documentação de linha fixa, Gestão de tarefas com geolocalização, '
    . 'assinatura digital e cálculo de quilometragem.'
);

// CLASSES WRAPPER PARA COMPATIBILIDADE

use GlpiPlugin\Newbase\Src\System;
use GlpiPlugin\Newbase\Src\Task;
use GlpiPlugin\Newbase\Src\TaskSignature;

/**
* PluginNewbaseSystem
* Wrapper para compatibilidade com GLPI
*/
class PluginNewbaseSystem extends System
{
    // Classe wrapper - herda tudo de System
}

/**
* PluginNewbaseTask
* Wrapper para compatibilidade com GLPI
*/
class PluginNewbaseTask extends Task
{
    // Classe wrapper - herda tudo de Task
}

/**
* PluginNewbaseTaskSignature
* Wrapper para compatibilidade com GLPI
*/
class PluginNewbaseTaskSignature extends TaskSignature
{
    // Classe wrapper - herda tudo de TaskSignature
}

// CONFIGURAÇÃO DO PLUGIN
/**
* Retorna as informações de configuração do plugin
*
* @return array Configurações do plugin
*/
function plugin_newbase_getConfig(): array
{
    $config = [
        'name'           => PLUGIN_NEWBASE_NAME,
        'version'        => PLUGIN_NEWBASE_VERSION,
        'author'         => PLUGIN_NEWBASE_AUTHOR,
        'license'        => PLUGIN_NEWBASE_LICENSE,
        'homepage'       => PLUGIN_NEWBASE_HOMEPAGE,
        'minGlpiVersion' => PLUGIN_NEWBASE_MIN_GLPI,
        'maxGlpiVersion' => PLUGIN_NEWBASE_MAX_GLPI,
        'description'    => PLUGIN_NEWBASE_DESCRIPTION,
    ];

    return $config;
}

/**
* Retorna as classes do banco de dados do plugin
*
* @return array Array com classes que usam CommonDBTM
*/
function plugin_newbase_getDatabase(): array
{
    return [
        'PluginNewbaseSystem',
        'PluginNewbaseTask',
        'PluginNewbaseTaskSignature',
    ];
}

// VERIFICAR COMPATIBILIDADE

/**
* Valida se o GLPI é compatível com o plugin
*
* @return bool True se compatível
*/
function plugin_newbase_checkCompat(): bool
{
    // Verificar versão do GLPI
    if (version_compare(GLPI_VERSION, PLUGIN_NEWBASE_MIN_GLPI, '<')) {
        echo sprintf(
            'Este plugin requer GLPI %s ou superior. Sua versão é %s.',
            PLUGIN_NEWBASE_MIN_GLPI,
            GLPI_VERSION
        );
        return false;
    }

    // Verificar versão do PHP
    if (version_compare(PHP_VERSION, '8.1', '<')) {
        echo 'Este plugin requer PHP 8.1 ou superior.';
        return false;
    }

    return true;
}

/**
* Retorna a versão e informações do plugin para o GLPI
*
* @return array Informações do plugin
*/
function plugin_version_newbase(): array
{
    return [
        'name'     => PLUGIN_NEWBASE_NAME,
        'version'  => PLUGIN_NEWBASE_VERSION,
        'author'   => PLUGIN_NEWBASE_AUTHOR,
        'license'  => PLUGIN_NEWBASE_LICENSE,
        'homepage' => PLUGIN_NEWBASE_HOMEPAGE,
    ];
}

// AUTOLOADER E INCLUSÕES

// Incluir arquivo de aliases para compatibilidade
require_once(__DIR__ . '/src/LegacyAliases.php');

// Incluir arquivo de menu
require_once(__DIR__ . '/inc/menu.class.php');

/**
 * Inicializa o plugin Newbase
 * Define hooks, menus, assets CSS/JS
 *
 * @return void
 */
function plugin_init_newbase(): void
{
    global $PLUGIN_HOOKS;

    // ========================================
    // DECLARAÇÃO DE COMPATIBILIDADE COM CSRF
    // ========================================
    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    // Verificar se plugin está instalado e ativado
    if (!class_exists('Plugin')) {
        return;
    }

    $plugin = new Plugin();
    if (!$plugin->isInstalled('newbase') || !$plugin->isActivated('newbase')) {
        return;
    }

    // ========================================
    // REGISTRAR CLASSES DO PLUGIN
    // ========================================
    Plugin::registerClass('PluginNewbaseSystem');
    Plugin::registerClass('PluginNewbaseTask');
    Plugin::registerClass('PluginNewbaseTaskSignature');

    // ========================================
    // MENU PRINCIPAL
    // ========================================
    $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
        'admin' => 'GlpiPlugin\Newbase\Menu',
    ];

    // ========================================
    // PÁGINA DE CONFIGURAÇÃO
    // ========================================
    $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

    // ========================================
    // CSS DO PLUGIN
    // ========================================
    $PLUGIN_HOOKS['add_css']['newbase'] = [
        'css/newbase.css',
        'css/forms.css',
        'css/responsive.css',
    ];

    // ========================================
    // JAVASCRIPT DO PLUGIN
    // ========================================
    $PLUGIN_HOOKS['add_javascript']['newbase'] = [
        'js/newbase.js',
        'js/forms.js',
        'js/map.js',
        'js/signature.js',
        'js/mileage.js',
        'js/mobile.js',
        'js/jquery.mask.min.js',
    ];
}
