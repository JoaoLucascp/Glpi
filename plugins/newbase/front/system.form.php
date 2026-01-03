<?php
/**
 * System form page for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

include('../../../inc/includes.php');
use GlpiPlugin\Newbase\System;

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_system', READ);

// Create instance
$system = new System();

// Handle actions
if (isset($_POST['add'])) {
    // Add new system
    $system->check(-1, CREATE, $_POST);
    $newID = $system->add($_POST);

    if ($newID) {
        Session::addMessageAfterRedirect(
            __('System created successfully', 'newbase'),
            true,
            INFO
        );

        // Redirect to company if company_id provided
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/company_data.form.php?id=" . $_POST['plugin_newbase_companydata_id']);
        } else {
            Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php?id=$newID");
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
    // Update existing system
    $system->check($_POST['id'], UPDATE);

    if ($system->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('System updated successfully', 'newbase'),
            true,
            INFO
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
    // Delete system
    $system->check($_POST['id'], DELETE);

    if ($system->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('System deleted successfully', 'newbase'),
            true,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.php");
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} elseif (isset($_POST['purge'])) {
    // Purge system
    $system->check($_POST['id'], PURGE);

    if ($system->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('System purged successfully', 'newbase'),
            true,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.php");
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging system', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} else {
    // Display form
    Html::header(
        __('Communication System', 'newbase'),
        $_SERVER['PHP_SELF'],
        "management",
        "System"
    );

    $system->display([
        'id' => $_GET['id'] ?? 0,
        'plugin_newbase_companydata_id' => $_GET['plugin_newbase_companydata_id'] ?? 0
    ]);

    Html::footer();
}
