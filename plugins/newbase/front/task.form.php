<?php

/**
 * Task Form - Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../inc/includes.php';

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Config;
use Session;
use Html;
use CommonDBTM;

global $CFG_GLPI, $DB;

Session::checkLoginUser();
Session::checkRight('plugin_newbase_task', READ);

$task = new Task();

if (isset($_POST['add'])) {
    $task->check(-1, CREATE, $_POST);

    if (Config::getConfigValue('autocalculatemileage', 1) == 1) {
        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start']) &&
            !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
            $_POST['mileage'] = CommonDBTM::calculateDistance(
                (float)$_POST['latitude_start'],
                (float)$_POST['longitude_start'],
                (float)$_POST['latitude_end'],
                (float)$_POST['longitude_end']
            );
        }
    }

    $newID = $task->add($_POST);
    if ($newID) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );

        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $_POST['plugin_newbase_companydata_id']);
        } else {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.form.php?id=' . $newID);
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
    $task->check($_POST['id'], UPDATE);

    if (Config::getConfigValue('autocalculatemileage', 1) == 1) {
        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start']) &&
            !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {
            $_POST['mileage'] = CommonDBTM::calculateDistance(
                (float)$_POST['latitude_start'],
                (float)$_POST['longitude_start'],
                (float)$_POST['latitude_end'],
                (float)$_POST['longitude_end']
            );
        }
    }

    if ($task->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
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
    $task->check($_POST['id'], DELETE);

    if ($task->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} elseif (isset($_POST['purge'])) {
    $task->check($_POST['id'], PURGE);

    if ($task->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Company added successfully', 'newbase'),
            false,
            'success'
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
} else {
    Html::header(
        Task::getTypeName(1),
        $_SERVER['PHP_SELF'],
        'management',
        CompanyData::class,
        'task'
    );

    $id = (int)($_GET['id'] ?? 0);
    $company_id = (int)($_GET['plugin_newbase_companydata_id'] ?? 0);

    if ($id > 0) {
        $task->getFromDB($id);
    } elseif ($company_id > 0) {
        $task->fields['plugin_newbase_companydata_id'] = $company_id;
    }

    $task->showForm($id, ['plugin_newbase_companydata_id' => $company_id]);

    Html::footer();
}
