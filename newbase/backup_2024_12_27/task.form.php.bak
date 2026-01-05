<?php
/**
 * Task form page for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

include('../../../inc/includes.php');
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\TaskSignature;

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_task', READ);

// Create instance
$task = new Task();

// Handle actions
if (isset($_POST['add'])) {
    // Add new task
    $task->check(-1, CREATE, $_POST);

    // Auto-calculate mileage if coordinates provided and auto-calc is enabled
    if (Config ::('auto_calculate_mileage', '1') === '1') {
        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start']) &&
            !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
            $_POST['mileage'] = Common::calculateDistance(
                floatval($_POST['latitude_start']),
                floatval($_POST['longitude_start']),
                floatval($_POST['latitude_end']),
                floatval($_POST['longitude_end'])
            );
        }
    }

    $newID = $task->add($_POST);

    if ($newID) {
        Session::addMessageAfterRedirect(
            __('Task created successfully', 'newbase'),
            true,
            INFO
        );

        // Redirect to company if company_id provided
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/company_data.form.php?id=" . $_POST['plugin_newbase_companydata_id']);
        } else {
            Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.form.php?id=$newID");
        }
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} elseif (isset($_POST['update'])) {
    // Update existing task
    $task->check($_POST['id'], UPDATE);

    // Auto-calculate mileage if coordinates provided and auto-calc is enabled
    if (Config::getConfigValue('auto_calculate_mileage', '1') === '1') {
        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start']) &&
            !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
            $_POST['mileage'] = Common::calculateDistance(
                floatval($_POST['latitude_start']),
                floatval($_POST['longitude_start']),
                floatval($_POST['latitude_end']),
                floatval($_POST['longitude_end'])
            );
        }
    }

    if ($task->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Task updated successfully', 'newbase'),
            true,
            INFO
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} elseif (isset($_POST['delete'])) {
    // Delete task
    $task->check($_POST['id'], DELETE);

    if ($task->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Task deleted successfully', 'newbase'),
            true,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.php");
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} elseif (isset($_POST['purge'])) {
    // Purge task
    $task->check($_POST['id'], PURGE);

    if ($task->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Task purged successfully', 'newbase'),
            true,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.php");
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

} else {
    // Display form
    Html::header(
        __('Task', 'newbase'),
        $_SERVER['PHP_SELF'],
        "management",
        "Task"
    );

    $task_id = intval($_GET['id'] ?? 0);
    $task->display([
        'id' => $task_id,
        'plugin_newbase_companydata_id' => $_GET['plugin_newbase_companydata_id'] ?? 0
    ]);

    // Display signature section if task exists
    if ($task_id > 0 && Config::isSignatureEnabled()) {
        TaskSignature::showForTask($task);
    }

    Html::footer();
}
