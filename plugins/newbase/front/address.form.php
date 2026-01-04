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

require_once __DIR__ . '/../../../inc/includes.php';

use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\CompanyData;
use Session;
use Html;

global $CFG_GLPI, $DB;

// Check authentication
Session::checkLoginUser();

$address = new Address();

// Handle form submissions
if (isset($_POST['add'])) {
    // Validate company exists and is valid
    $company_id = (int)($_POST['plugin_newbase_companydata_id'] ?? 0);

    if ($company_id <= 0) {
        Session::addMessageAfterRedirect(
            __('A valid company must be selected', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Verify company exists in database
    $company = new CompanyData();
    if (!$company->getFromDB($company_id)) {
        Session::addMessageAfterRedirect(
            __('The selected company does not exist', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Check right for company
    if (!$company->canUpdate()) {
        Session::addMessageAfterRedirect(
            __('You do not have permission to manage this company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

    // Now safe to add address
    $address->check(-1, CREATE, $_POST);

    if ($newID = $address->add($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address added successfully', 'newbase'),
            false,
            SUCCESS
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($address->getLinkURL());
        }
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['update'])) {
    $address->check($_POST['id'], UPDATE);

    if ($address->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address updated successfully', 'newbase'),
            false,
            SUCCESS
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['delete'])) {
    $address->check($_POST['id'], DELETE);

    if ($address->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Address deleted successfully', 'newbase'),
            false,
            SUCCESS
        );
        $company_id = (int)($_POST['plugin_newbase_companydata_id'] ?? 0);
        if ($company_id > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $company_id);
        }
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['purge'])) {
    $address->check($_POST['id'], PURGE);

    if ($address->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Address purged successfully', 'newbase'),
            false,
            SUCCESS
        );
        $company_id = (int)($_POST['plugin_newbase_companydata_id'] ?? 0);
        if ($company_id > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $company_id);
        }
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging address', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
}

// Display form
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$company_id = (int)($_GET['plugin_newbase_companydata_id'] ?? $_POST['plugin_newbase_companydata_id'] ?? 0);

// Start page
Html::header(
    Address::getTypeName(1),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'address'
);

// Load address data or initialize new
if ($id > 0) {
    $address->getFromDB($id);
} elseif ($company_id > 0) {
    $address->fields['plugin_newbase_companydata_id'] = $company_id;
}

// Show form
$address->showForm($id);

Html::footer();
