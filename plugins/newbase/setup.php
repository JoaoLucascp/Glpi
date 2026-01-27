<?php

/**
* Setup File - Plugin Newbase
*
* @package   Plugin Newbase
* @author    João Lucas
* @license   GPLv2+
* @since     2.1.0
*/

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// CONSTANTES DO PLUGIN
define('PLUGIN_NEWBASE_VERSION', '2.1.0');
define('PLUGIN_NEWBASE_MIN_GLPI', '10.0.0');
define('PLUGIN_NEWBASE_MAX_GLPI', '10.1.0');
define('PLUGIN_NEWBASE_NAME', 'Newbase - Company Management');
define('PLUGIN_NEWBASE_AUTHOR', 'João Lucas');
define('PLUGIN_NEWBASE_LICENSE', 'GPLv2+');
define('PLUGIN_NEWBASE_HOMEPAGE', 'https://github.com/joaolucas/glpi-newbase');
define('PLUGIN_NEWBASE_DESCRIPTION', 'Sistema completo de gestão empresarial para GLPI');

// AUTOLOADER DO COMPOSER (CRÍTICO!)
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    error_log('NEWBASE PLUGIN ERROR: Autoloader not found! Run: composer install');
}

// INICIALIZAÇÃO DO PLUGIN

/**
* Inicializa o plugin Newbase
* Define hooks, menus, assets CSS/JS
* @return void
*/
function plugin_init_newbase(): void
{
    global $PLUGIN_HOOKS;

    // Compatibilidade CSRF (obrigatório GLPI 10+)
    $PLUGIN_HOOKS['csrf_compliant']['newbase'] = true;

    // Verificar se plugin está instalado e ativado
    if (!class_exists('Plugin')) {
        return;
    }

    $plugin = new Plugin();
    if (!$plugin->isInstalled('newbase') || !$plugin->isActivated('newbase')) {
        return;
    }

    // Registrar classes do plugin (formato legado)
    Plugin::registerClass('PluginNewbaseSystem');
    Plugin::registerClass('PluginNewbaseTask');
    Plugin::registerClass('PluginNewbaseTaskSignature');

    // Menu principal
    $PLUGIN_HOOKS['menu_toadd']['newbase'] = [
        'admin' => 'GlpiPlugin\Newbase\Menu',
    ];

    // Página de configuração
    $PLUGIN_HOOKS['config_page']['newbase'] = 'front/config.php';

    // CSS do plugin
    $PLUGIN_HOOKS['add_css']['newbase'] = [
        'css/newbase.css',
        'css/forms.css',
        'css/responsive.css',
    ];

    // JavaScript do plugin
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

/**
* Retorna a versão e informações do plugin para o GLPI
* @return array Informações do plugin
*/
function plugin_version_newbase(): array
{
    return [
        'name'           => PLUGIN_NEWBASE_NAME,
        'version'        => PLUGIN_NEWBASE_VERSION,
        'author'         => PLUGIN_NEWBASE_AUTHOR,
        'license'        => PLUGIN_NEWBASE_LICENSE,
        'homepage'       => PLUGIN_NEWBASE_HOMEPAGE,
        'requirements'   => [
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

/**
* Verificar pré-requisitos antes da instalação
* @return bool True se compatível
*/
function plugin_newbase_check_prerequisites(): bool
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
* Verificar configuração do plugin
* @param bool $verbose Exibir mensagens
* @return bool True se configurado
*/
function plugin_newbase_check_config(bool $verbose = false): bool
{
    if ($verbose) {
        echo 'Plugin Newbase instalado e configurado corretamente';
    }
    return true;
}
