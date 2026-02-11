<?php

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonGLPI;
use Config as CoreConfig;
use Session;
use Glpi\Application\View\TemplateRenderer;

/**
 * Config Class - Plugin configuration management
 *
 * @package   Plugin - Newbase
 * @author    João Lucas
 * @copyright 2026 João Lucas
 * @license   GPLv2+
 * @version   2.1.0
 */
class Config extends \Config
{
    // Define o direito necessário para acessar esta config (geralmente 'config')
    public static $rightname = 'config';

    /**
     * Retorna o nome da aba a ser exibida
     */
    public static function getTypeName($nb = 0)
    {
        return __('Newbase', 'newbase');
    }

    /**
     * Recupera as configurações do plugin salvas na tabela glpi_configs
     * Contexto: 'plugin:newbase'
     */
    public static function getConfig(): array
    {
        return CoreConfig::getConfigurationValues('plugin:newbase');
    }

    /**
     * Define o nome da aba no menu Configurar > Geral (CoreConfig)
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        // Verifica se o item é a configuração principal do GLPI
        if ($item->getType() === CoreConfig::class) {
            return self::createTabEntry(self::getTypeName());
        }
        return '';
    }

    /**
     * Exibe o conteúdo da aba
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        // Verifica se é a classe de configuração correta
        if ($item->getType() === CoreConfig::class) {
            return self::showForConfig($item, $withtemplate);
        }
        return true;
    }

    /**
     * Renderiza o formulário e processa o salvamento
     */
    public static function showForConfig(CoreConfig $config, $withtemplate = 0)
    {
        // Verifica permissões de leitura/escrita
        if (!self::canView()) {
            return false;
        }

        $canedit = Session::haveRight(self::$rightname, UPDATE);

        // --- LÓGICA DE SALVAMENTO (Processar POST) ---
        if ($canedit && isset($_POST['update_config'])) {
            // Valida token CSRF
            Session::checkCSRF($_POST);

            // Prepara os dados para salvar
            $new_configs = [
                'enable_signature'  => (int) ($_POST['enable_signature'] ?? 0),
                'require_signature' => (int) ($_POST['require_signature'] ?? 0),
                'enable_gps'        => (int) ($_POST['enable_gps'] ?? 0),
                'calculate_mileage' => (int) ($_POST['calculate_mileage'] ?? 0),
                'default_zoom'      => (int) ($_POST['default_zoom'] ?? 10),
            ];

            // Salva na tabela glpi_configs com o contexto 'plugin:newbase'
            CoreConfig::setConfigurationValues('plugin:newbase', $new_configs);

            // Mensagem de sucesso
            Session::addMessageAfterRedirect(
                __('Settings saved successfully', 'newbase'),
                true,
                INFO
            );

            // Redireciona para evitar reenvio do formulário
            // Html::back(); // Opcional, dependendo do fluxo desejado
        }

        // --- EXIBIÇÃO ---

        // Carrega configurações atuais (com valores padrão caso não existam)
        $defaults = [
            'enable_signature'  => 0,
            'require_signature' => 0,
            'enable_gps'        => 0,
            'calculate_mileage' => 0,
            'default_zoom'      => 10
        ];

        $stored_config = self::getConfig();
        $current_config = array_merge($defaults, $stored_config);

        // Renderiza o template Twig
        TemplateRenderer::getInstance()->display('@newbase/config.html.twig', [
            'config'   => $current_config,
            'can_edit' => $canedit,
        ]);

        return true;
    }
}