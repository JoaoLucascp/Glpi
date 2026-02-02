<?php

/**
* Setup for Newbase Plugin
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

// Prevenir acesso direto
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// CONSTANTES DO PLUGIN
define('PLUGIN_NEWBASE_VERSION', '2.1.0');
define('PLUGIN_NEWBASE_NAME', 'Newbase');
define('PLUGIN_NEWBASE_AUTHOR', 'João Lucas');
define('PLUGIN_NEWBASE_LICENSE', 'GPLv2+');
define('PLUGIN_NEWBASE_HOMEPAGE', 'https://github.com/joaolucas/glpi-newbase');
define('PLUGIN_NEWBASE_MIN_GLPI', '10.0.20');
define('PLUGIN_NEWBASE_MAX_GLPI', '10.0.99');

// Caminhos do plugin
define('PLUGIN_NEWBASE_DIR', Plugin::getPhysicalDir('newbase'));
define('PLUGIN_NEWBASE_WEB_DIR', Plugin::getWebDir('newbase'));

// CARREGAR AUTOLOADER
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// INICIALIZAÇÃO DO PLUGIN

/**
* Plugin init function
* @global array $PLUGIN_HOOKS
*/
function plugin_init_newbase(): void
{
    global $PLUGIN_HOOKS;

    $plugin = new Plugin();

    // Verificar se plugin está instalado e ativado
    if (!$plugin->isInstalled('newbase') || !$plugin->isActivated('newbase')) {
        return;
    }

    // REGISTRAR CLASSES PRINCIPAIS
    Plugin::registerClass('GlpiPlugin\\Newbase\\Address');
    Plugin::registerClass('GlpiPlugin\\Newbase\\CompanyData');
    Plugin::registerClass('GlpiPlugin\\Newbase\\System');
    Plugin::registerClass('GlpiPlugin\\Newbase\\Task');
    Plugin::registerClass('GlpiPlugin\\Newbase\\TaskSignature');
    Plugin::registerClass('GlpiPlugin\\Newbase\\Config');

    // MENU PRINCIPAL
    $PLUGIN_HOOKS['menu_entry']['newbase'] = true;

    // PÁGINA DE CONFIGURAÇÃO
    $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

    // CSS DO PLUGIN
    $PLUGIN_HOOKS['add_css']['newbase'] = [
        'css/newbase.css',
        'css/forms.css',
        'css/responsive.css',
    ];

    // JAVASCRIPT DO PLUGIN
    $PLUGIN_HOOKS['add_javascript']['newbase'] = [
        'js/newbase.js',
        'js/forms.js',
        'js/map.js',
        'js/signature.js',
        'js/mileage.js',
        'js/mobile.js',
        'js/jquery.mask.min.js',
    ];

    // CSRF COMPLIANCE (GLPI 10+)
    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    // HOOKS DE ITENS (DISPLAY TABS)
    $PLUGIN_HOOKS['item_get_tabs']['newbase'] = [
        'Entity' => 'GlpiPlugin\\Newbase\\System',
        'Entity' => 'GlpiPlugin\\Newbase\\Task',
    ];
}

// INFORMAÇÕES DO PLUGIN

/**
* Get plugin version and information for GLPI
* @return array Plugin information
*/
function plugin_version_newbase(): array
{
    return [
        'name' => PLUGIN_NEWBASE_NAME,
        'version' => PLUGIN_NEWBASE_VERSION,
        'author' => PLUGIN_NEWBASE_AUTHOR,
        'license' => PLUGIN_NEWBASE_LICENSE,
        'homepage' => PLUGIN_NEWBASE_HOMEPAGE,
        'requirements' => [
            'glpi' => [
                'min' => PLUGIN_NEWBASE_MIN_GLPI,
                'max' => PLUGIN_NEWBASE_MAX_GLPI,
            ],
            'php' => [
                'min' => '8.1',
            ],
        ],
    ];
}

// VERIFICAÇÃO DE PRÉ-REQUISITOS

/**
* Check plugin prerequisites before installation
* @return bool True if compatible
*/
function plugin_newbase_check_prerequisites(): bool
{
    // VERIFICAR VERSÃO DO GLPI
    if (version_compare(GLPI_VERSION, PLUGIN_NEWBASE_MIN_GLPI, '<')) {
        echo sprintf(
            '<p class="red">Este plugin requer GLPI %s ou superior. Sua versão é %s.</p>',
            PLUGIN_NEWBASE_MIN_GLPI,
            GLPI_VERSION
        );
        return false;
    }

    if (version_compare(GLPI_VERSION, PLUGIN_NEWBASE_MAX_GLPI, '>')) {
        echo sprintf(
            '<p class="red">Este plugin não é compatível com GLPI %s. Versão máxima suportada: %s.</p>',
            GLPI_VERSION,
            PLUGIN_NEWBASE_MAX_GLPI
        );
        return false;
    }

    // VERIFICAR VERSÃO DO PHP
    if (version_compare(PHP_VERSION, '8.1', '<')) {
        echo '<p class="red">Este plugin requer PHP 8.1 ou superior.</p>';
        return false;
    }

    // VERIFICAR EXTENSÕES PHP OBRIGATÓRIAS
    $required_extensions = ['json', 'mysqli', 'mbstring', 'curl', 'gd'];
    $missing = [];

    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }

    if (!empty($missing)) {
        echo sprintf(
            '<p class="red">Extensões PHP faltando: <strong>%s</strong></p>',
            implode(', ', $missing)
        );
        return false;
    }

    // VERIFICAR AUTOLOADER
    $autoload = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        echo '<p class="red">Autoloader não encontrado. Execute: <code>composer install</code></p>';
        return false;
    }

    return true;
}

/**
* Check plugin configuration
* @param bool $verbose Display messages
* @return bool True if configured
*/
function plugin_newbase_check_config(bool $verbose = false): bool
{
    if ($verbose) {
        echo '<p class="green">Plugin Newbase instalado e configurado corretamente</p>';
    }

    return true;
}

// MENU DO PLUGIN

/**
* Define menu entries for plugin
* @return array Menu structure
*/
function plugin_newbase_getMenuContent(): array
{
    return GlpiPlugin\Newbase\Menu::getMenuContent();
}
