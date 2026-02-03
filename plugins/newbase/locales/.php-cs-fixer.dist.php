<?php

/**
* PHP-CS-Fixer Configuration for Newbase Plugin
*
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// ==================== CONFIGURAÇÃO DO FINDER ====================

/** @var Finder*/
$finder = (new Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/front',
        __DIR__ . '/ajax',
    ])
    ->append([
        __DIR__ . '/setup.php',
        __DIR__ . '/hook.php',
    ])
    ->exclude('vendor')
    ->exclude('tools')
    ->exclude('locales')
    ->notPath('*.min.js')
    ->notPath('*.min.css')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

// ==================== CONFIGURAÇÃO DO PHP-CS-FIXER ====================

/** @var Config*/
$config = (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
        // ... resto das rules
    ])
    ->setFinder($finder)
    ->setLineEnding("\n");

return $config;
