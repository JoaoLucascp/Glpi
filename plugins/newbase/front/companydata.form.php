<?php
/**
* Company Data Form - Handles create, update, and delete operations for CompanyData entities
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

$company = new CompanyData();

if (isset($_POST['add'])) {
    $company->check(-1, CREATE, $_POST);
    $newID = $company->add($_POST);
    Html::back();

} else if (isset($_POST['update'])) {
    $company->check($_POST['id'], UPDATE);
    $company->update($_POST);
    Html::back();

} else if (isset($_POST['purge'])) {
    $company->check($_POST['id'], PURGE);
    $company->delete($_POST, 1);
    $company->redirectToList();

} else {
    $id = $_GET['id'] ?? 0;

    Html::header(
        CompanyData::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class
    );

    $company->display(['id' => $id]);

    Html::footer();
}
