<?php

/**
* ---------------------------------------------------------------------
* PHP-CS-Fixer Configuration - Plugin Newbase (Pasta AJAX)
* ---------------------------------------------------------------------
*
* Este arquivo configura o PHP-CS-Fixer para manter padrão de código
* consistente em todos os arquivos AJAX do plugin.
*
* Padrões aplicados:
* - PSR-12 (PHP Standards Recommendation)
* - GLPI Coding Standards
* - Indentação: 4 espaços
* - Line ending: LF (Unix)
* - Encoding: UTF-8 without BOM
*
* Uso:
* $ vendor/bin/php-cs-fixer fix ajax/
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([

        // REGRAS BÁSICAS (PSR-12)

        '@PSR12' => true,

        // ARRAYS

        'array_syntax' => [
            'syntax' => 'short',  // [] ao invés de array()
        ],
        'array_indentation' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],  // Vírgula no último item
        ],

        // COMENTÁRIOS E DOCUMENTAÇÃO

        'single_line_comment_style' => [
            'comment_types' => ['hash'],  // # vira //
        ],
        'no_trailing_whitespace_in_comment' => true,
        'comment_to_phpdoc' => true,

        // IMPORTS (use statements)

        'ordered_imports' => [
            'sort_algorithm' => 'alpha',  // Ordem alfabética
            'imports_order' => ['class', 'function', 'const'],
        ],
        'no_unused_imports' => true,
        'single_line_after_imports' => true,
        'no_leading_import_slash' => true,

        // ESPAÇAMENTO E INDENTAÇÃO

        'indentation_type' => true,
        'no_trailing_whitespace' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
                'use_trait',
            ],
        ],
        'blank_line_before_statement' => [
            'statements' => ['return', 'try'],
        ],

        // CHAVES E PARÊNTESES

        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_functions_and_oop_constructs' => 'next',  // Chave na linha seguinte
        ],
        'no_spaces_inside_parenthesis' => true,
        'no_spaces_after_function_name' => true,

        // OPERADORES

        'binary_operator_spaces' => [
            'default' => 'single_space',  // Espaço ao redor de operadores
        ],
        'concat_space' => [
            'spacing' => 'one',  // $a . $b (com espaço)
        ],
        'unary_operator_spaces' => true,

        // STRINGS

        'single_quote' => true,  // 'string' ao invés de "string"
        'no_trailing_whitespace_in_string' => true,

        // CONTROLE DE FLUXO

        'switch_case_space' => true,
        'switch_case_semicolon_to_colon' => true,
        'no_break_comment' => true,

        // FUNÇÕES E MÉTODOS

        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'return_type_declaration' => [
            'space_before' => 'none',
        ],
        'function_typehint_space' => true,

        // LIMPEZA

        'no_empty_statement' => true,
        'no_closing_tag' => true,  // Remove ?> no final
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,  // Linha em branco no final

        // ENCODING

        'encoding' => true,  // UTF-8
        'line_ending' => true,  // LF (Unix)

        // ESPECÍFICO DO PROJETO

        'cast_spaces' => [
            'space' => 'single',  // (int) $var
        ],
        'lowercase_cast' => true,  // (int) ao invés de (INT)
        'native_function_casing' => true,  // strlen ao invés de STRLEN
        'no_short_bool_cast' => true,  // (bool) ao invés de !!
    ])
    ->setFinder(
        (new Finder())
            ->in(__DIR__)  // Pasta ajax/
            ->name('*.php')  // Apenas arquivos PHP
            ->exclude([
                'vendor',
                'node_modules',
            ])
            ->ignoreDotFiles(true)  // Ignora arquivos ocultos
            ->ignoreVCS(true)  // Ignora .git, .svn, etc
    )
    ->setIndent('    ')  // 4 espaços
    ->setLineEnding("\n");  // LF (Unix)
