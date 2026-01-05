<?php
/**
 * Company Data list page
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;

// Include GLPI
include('../../../inc/includes.php');

// Check rights
Session::checkRight(CompanyData::$rightname, READ);

// Display header with proper search type
Html::header(
    CompanyData::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "management",
    CompanyData::class,
    "companydata"
);

// Manual add button if Search doesn't show it
if (CompanyData::canCreate()) {
    global $CFG_GLPI;
    echo "<div class='center' style='margin: 10px 0;'>";
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/companydata.form.php' class='btn btn-primary'>";
    echo "<i class='fas fa-plus'></i>&nbsp;";
    echo "<span>Adicionar</span>";
    echo "</a>";
    echo "</div>";
}

// Show search interface
Search::show(CompanyData::class);

// Display footer
Html::footer();
