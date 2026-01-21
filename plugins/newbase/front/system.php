<?php

/**
* Management page for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\System;

include('../../../inc/includes.php');

Session::checkRight('plugin_newbase', READ);

Html::header(
    System::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'management',
    System::class
);

Search::show(System::class);

Html::footer();
