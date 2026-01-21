<?php

/**
* Company Data List
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\CompanyData;

include('../../../inc/includes.php');

Session::checkRight('plugin_newbase', READ);

Html::header(
    CompanyData::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class
);

Search::show(CompanyData::class);

Html::footer();
