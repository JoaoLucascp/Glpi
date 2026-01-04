<?php

/**
 * Address Form
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../inc/includes.php';

use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\CompanyData;
use Session;

include('../../../inc/includes.php');

// Check authentication
Session::checkLoginUser();

$address = new Address();

// Handle form submissions
if (isset($_POST['add'])) {
    $address->check(-1, CREATE, $_POST);

    if ($newID = $address->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($address->getLinkURL());
        }
    }
    Html::back();

} elseif (isset($_POST['update'])) {
    $address->check($_POST['id'], UPDATE);
    $address->update($_POST);
    Html::back();

} elseif (isset($_POST['delete'])) {
    $address->check($_POST['id'], DELETE);
    $address->delete($_POST);

    $company_id = $_POST['plugin_newbase_companydata_id'] ?? 0;
    if ($company_id) {
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/companydata.form.php?id=" . $company_id);
    } else {
        Html::back();
    }

} elseif (isset($_POST['purge'])) {
    $address->check($_POST['id'], PURGE);
    $address->delete($_POST, 1);

    $company_id = $_POST['plugin_newbase_companydata_id'] ?? 0;
    if ($company_id) {
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/companydata.form.php?id=" . $company_id);
    } else {
        Html::back();
    }
}

// Display form
$id = $_GET['id'] ?? $_POST['id'] ?? 0;
$company_id = $_GET['plugin_newbase_companydata_id'] ?? $_POST['plugin_newbase_companydata_id'] ?? 0;

// Start page
Html::header(
    Address::getTypeName(1),
    $_SERVER['PHP_SELF'],
    "management",
    CompanyData::class,
    "address"
);

// Load address data
if ($id > 0) {
    $address->getFromDB($id);
} elseif ($company_id > 0) {
    $address->fields['plugin_newbase_companydata_id'] = $company_id;
}

// Show form
$address->showForm($id);

Html::footer();
