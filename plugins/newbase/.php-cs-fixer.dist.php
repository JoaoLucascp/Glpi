<?php

/**
* PHP-CS-Fixer Configuration for Newbase Plugin
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*
* Usage:
*   - Check: vendor/bin/php-cs-fixer fix --dry-run --diff
*   - Fix:   vendor/bin/php-cs-fixer fix
*/

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// CONFIGURAÇÃO DO FINDER (ONDE PROCURAR ARQUIVOS)

$finder = (new Finder())
    // Pastas principais com código PHP
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/front',
        __DIR__ . '/ajax',
    ])
    // Arquivos raiz importantes
    ->append([
        __DIR__ . '/setup.php',
        __DIR__ . '/hook.php',
    ])
    // Excluir pastas de terceiros
    ->exclude('vendor')
    ->exclude('tools')
    ->exclude('locales')
    // Excluir arquivos minificados
    ->notPath('*.min.js')
    ->notPath('*.min.css')
    // Apenas arquivos PHP
    ->name('*.php')
    // Ignorar arquivos ocultos
    ->ignoreDotFiles(true)
    // Ignorar VCS (Git)
    ->ignoreVCS(true);

// CONFIGURAÇÃO DO PHP-CS-FIXER

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        // REGRAS BASE: PSR-12 (padrão GLPI)
        '@PSR12' => true,

        // ARRAYS
        'array_syntax' => ['syntax' => 'short'], // [] ao invés de array()
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],
        ],
        'whitespace_after_comma_in_array' => true,
        'no_whitespace_before_comma_in_array' => true,

        // IMPORTS
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'single_import_per_statement' => true,
        'group_import' => false,

        // OPERADORES
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'concat_space' => ['spacing' => 'one'], // 'a' . 'b'
        'unary_operator_spaces' => true,

        // STRINGS
        'single_quote' => true, // 'string' ao invés de "string"

        // ESPAÇOS EM BRANCO
        'blank_line_after_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,

        // FUNÇÕES E MÉTODOS
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'no_spaces_after_function_name' => true,
        'return_type_declaration' => ['space_before' => 'none'],

        // CONTROLE DE FLUXO
        'no_alternative_syntax' => true, // Não usar endif, endwhile
        'no_trailing_comma_in_singleline' => true,

        // LIMPEZA DE CÓDIGO
        'no_empty_statement' => true,
        'no_leading_namespace_whitespace' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,

        // COMPATIBILIDADE
        'encoding' => true, // UTF-8 sem BOM
        'full_opening_tag' => true, // <?php ao invés de <?
    ])
    ->setFinder($finder)
    ->setLineEnding("\n"); // Unix line endings (LF)
