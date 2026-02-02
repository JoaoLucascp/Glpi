<?php

/**
* Configuração do PHP-CS-Fixer para o Plugin Newbase
*
* Este arquivo define as regras de estilo de código que serão aplicadas
* automaticamente ao executar: composer cs:fix
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1️ Importar classes necessárias
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// 2️ Criar o objeto Finder (busca arquivos PHP)
$finder = Finder::create()
    // Define a pasta raiz a ser verificada
    ->in(__DIR__)

    // 3️ PASTAS A EXCLUIR (não serão verificadas)
    ->exclude('vendor')      // Composer
    ->exclude('node_modules') // NPM (se houver)
    ->exclude('locales')      // Traduções
    ->exclude('tools')        // Scripts auxiliares

    // 4️ Busca apenas arquivos PHP
    ->name('*.php')

    // 5️ Ignora arquivos ocultos (.htaccess, etc)
    ->ignoreDotFiles(true)

    // 6️ Ignora controle de versão (.git, .svn)
    ->ignoreVCS(true);

// 7️ Criar e retornar a configuração
return (new Config())
    ->setRiskyAllowed(false) // Não permite regras "arriscadas"

    // 8️ REGRAS DE ESTILO (PSR-12 + extras)
    ->setRules([
        '@PSR12' => true,  // Seguir padrão PSR-12

        // Regras extras para melhorar código
        'array_syntax' => ['syntax' => 'short'],  // Usar [] ao invés de array()
        'no_unused_imports' => true,              // Remove imports não usados
        'ordered_imports' => ['sort_algorithm' => 'alpha'], // Organiza imports
        'single_quote' => true,                   // Usa aspas simples
        'trailing_comma_in_multiline' => true,    // Vírgula no final de arrays
        'no_extra_blank_lines' => true,           // Remove linhas em branco extras
        'blank_line_after_opening_tag' => true,   // Linha em branco após <?php
    ])

    // 9️ Aplicar o Finder criado acima
    ->setFinder($finder);
