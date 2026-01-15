<?php
/**
* Company Data Form - VERSÃO CORRIGIDA
*
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2025 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

require_once __DIR__ . '/../../../inc/includes.php';

use GlpiPlugin\Newbase\Src\CompanyData;
use GlpiPlugin\Newbase\Src\Address;
use GlpiPlugin\Newbase\Src\System;
use GlpiPlugin\Newbase\Src\Task;
use Glpi\Event;

global $CFG_GLPI, $DB;

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_companydata', READ);

// Create object
$company = new CompanyData();

// HANDLE FORM SUBMISSIONS

if (isset($_POST['add'])) {
    // ADD NEW COMPANY
    $company->check(-1, CREATE, $_POST);

    if ($newID = $company->add($_POST)) {
        Event::log($newID, CompanyData::class, 4, 'newbase', sprintf(__('%1$s adds the item %2$s'), $_SESSION['glpiname'], $_POST['name'] ?? ''));

        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );

        if ($_SESSION['glpibackcreated']) {
            Html::redirect($company->getFormURLWithID($newID));
        } else {
            Html::back();
        }
    } else {
        Session::addMessageAfterRedirect(
            __('Error adding company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['update'])) {
    // UPDATE COMPANY
    $company->check($_POST['id'], UPDATE);

    if ($company->update($_POST)) {
        Event::log($_POST['id'], CompanyData::class, 4, 'newbase', sprintf(__('%s updates an item'), $_SESSION['glpiname']));

        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['delete'])) {
    // DELETE COMPANY (soft delete)
    $company->check($_POST['id'], DELETE);

    if ($company->delete($_POST)) {
        Event::log($_POST['id'], CompanyData::class, 4, 'newbase', sprintf(__('%s deletes an item'), $_SESSION['glpiname']));

        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['restore'])) {
    // RESTORE COMPANY
    $company->check($_POST['id'], DELETE);

    if ($company->restore($_POST)) {
        Event::log($_POST['id'], CompanyData::class, 4, 'newbase', sprintf(__('%s restores an item'), $_SESSION['glpiname']));

        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error restoring company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['purge'])) {
    // PURGE COMPANY (permanent delete)
    $company->check($_POST['id'], PURGE);

    if ($company->delete($_POST, 1)) {
        Event::log($_POST['id'], CompanyData::class, 4, 'newbase', sprintf(__('%s purges an item'), $_SESSION['glpiname']));

        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging company', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} else {
    // DISPLAY FORM

    // Get ID from URL
    $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

    // Display header
    Html::header(
        CompanyData::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class
    );

    // Check permissions and display form
    if ($id > 0) {
        $company->check($id, READ);
    } else {
        $company->check(-1, CREATE);
    }

    // Display the form using the showForm method
    $company->showForm($id);

    // Display related items (addresses, systems, tasks) if viewing
    if ($id > 0) {
        echo "<div class='spaced'>";

        // Show addresses tab
        if (class_exists('GlpiPlugin\Newbase\Address')) {
            Address::displayTabContentForItem($company, 1);
        }

        // Show systems tab
        if (class_exists('GlpiPlugin\Newbase\System')) {
            System::displayTabContentForItem($company, 2);
        }

        // Show tasks tab
        if (class_exists('GlpiPlugin\Newbase\Task')) {
            Task::displayTabContentForItem($company, 3);
        }

        echo "</div>";
    }

    Html::footer();
}
