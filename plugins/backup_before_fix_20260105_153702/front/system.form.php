<?php

/**
 * System Form - Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../inc/includes.php';

use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\CompanyData;

global $CFG_GLPI, $DB;

Session::checkLoginUser();
Session::checkRight('plugin_newbase_system', READ);

$system = new System();

if (isset($_POST['add'])) {
    $system->check(-1, CREATE, $_POST);

    $newID = $system->add($_POST);
    if ($newID) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );

        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $_POST['plugin_newbase_companydata_id']);
        } else {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.form.php?id=' . $newID);
        }
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['update'])) {
    $system->check($_POST['id'], UPDATE);

    if ($system->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['delete'])) {
    $system->check($_POST['id'], DELETE);

    if ($system->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['purge'])) {
    $system->check($_POST['id'], PURGE);

    if ($system->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/system.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} else {
    Html::header(
        System::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class,
        'system'
    );

    $id = (int)($_GET['id'] ?? 0);
    $company_id = (int)($_GET['plugin_newbase_companydata_id'] ?? 0);

    if ($id > 0) {
        $system->getFromDB($id);
    } elseif ($company_id > 0) {
        $system->fields['plugin_newbase_companydata_id'] = $company_id;
    }

    $system->showForm($id, ['plugin_newbase_companydata_id' => $company_id]);

    Html::footer();
}
