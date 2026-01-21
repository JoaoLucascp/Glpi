<?php

/**
* Reports page for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/
declare(strict_types=1);

use GlpiPlugin\Newbase\Src\CompanyData;
use GlpiPlugin\Newbase\Src\Task;

include('../../../inc/includes.php');

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_companydata', READ);

// Start page
Html::header(
    __('Newbase Reports', 'newbase'),
    $_SERVER['PHP_SELF'],
    "management",
    "PluginNewbaseCompanyData"
);

global $DB;

echo "<div class='center'>";
echo "<h1>" . __('Reports', 'newbase') . "</h1>";
echo "</div>";

// Report filters
echo "<div style='margin: 20px;'>";
echo "<form method='GET' action='" . $_SERVER['PHP_SELF'] . "'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-filter'></i> " . __('Filters', 'newbase') . "</h3>";

echo "<table class='tab_cadre'>";
echo "<tr>";
echo "<td>" . __('Date from', 'newbase') . "</td>";
echo "<td>";
Html::showDateField('date_from', ['value' => $_GET['date_from'] ?? '']);
echo "</td>";

echo "<td>" . __('Date to', 'newbase') . "</td>";
echo "<td>";
Html::showDateField('date_to', ['value' => $_GET['date_to'] ?? '']);
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td>" . __('Company', 'newbase') . "</td>";
echo "<td>";
CompanyData::dropdown([
    'name' => 'company_id',
    'value' => $_GET['company_id'] ?? 0,
    'display_emptychoice' => true,
]);
echo "</td>";

echo "<td>" . __('User', 'newbase') . "</td>";
echo "<td>";
User::dropdown([
    'name' => 'user_id',
    'value' => $_GET['user_id'] ?? 0,
    'right' => 'all',
    'display_emptychoice' => true,
]);
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td colspan='4' class='center'>";
echo "<button type='submit' class='btn btn-primary'><i class='fas fa-search'></i> " . __('Generate Report', 'newbase') . "</button>";
echo "&nbsp;<a href='" . $_SERVER['PHP_SELF'] . "' class='btn btn-secondary'><i class='fas fa-eraser'></i> " . __('Clear Filters', 'newbase') . "</a>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "</div>";
echo "</form>";
echo "</div>";

// Build WHERE clause for filters
$where = [];
if (!empty($_GET['date_from'])) {
    $where[] = ['date_start' => ['>=', $_GET['date_from'] . ' 00:00:00']];
}
if (!empty($_GET['date_to'])) {
    $where[] = ['date_start' => ['<=', $_GET['date_to'] . ' 23:59:59']];
}
if (!empty($_GET['company_id'])) {
    $where['plugin_newbase_companydata_id'] = intval($_GET['company_id']);
}
if (!empty($_GET['user_id'])) {
    $where['assigned_to'] = intval($_GET['user_id']);
}

// Tasks Summary Report
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-chart-pie'></i> " . __('Tasks Summary', 'newbase') . "</h3>";

$tasks_by_status = [];
foreach (['open', 'in_progress', 'paused', 'completed'] as $status) {
    $status_where = $where;
    $status_where['status'] = $status;
    $count = countElementsInTable('glpi_plugin_newbase_task', $status_where);
    $tasks_by_status[$status] = $count;
}

echo "<table class='tab_cadre'>";
echo "<tr>";
echo "<th>" . __('Status', 'newbase') . "</th>";
echo "<th>" . __('Count', 'newbase') . "</th>";
echo "</tr>";

$statuses = Task::getTaskStatuses();
$total = 0;
foreach ($tasks_by_status as $status => $count) {
    $total += $count;
    echo "<tr class='tab_bg_1'>";
    echo "<td>" . ($statuses[$status] ?? $status) . "</td>";
    echo "<td><b>$count</b></td>";
    echo "</tr>";
}

echo "<tr class='tab_bg_2'>";
echo "<td><b>" . __('Total', 'newbase') . "</b></td>";
echo "<td><b>$total</b></td>";
echo "</tr>";
echo "</table>";

echo "</div>";
echo "</div>";

// Mileage Report
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-road'></i> " . __('Mileage Report', 'newbase') . "</h3>";

$mileage_where = $where;
$mileage_where['mileage'] = ['>', 0];

$iterator = $DB->request([
    'SELECT' => [
        'SUM' => 'mileage AS total_mileage',
        'AVG' => 'mileage AS avg_mileage',
        'COUNT' => 'id AS task_count',
    ],
    'FROM' => 'glpi_plugin_newbase_task',
    'WHERE' => $mileage_where,
]);

$mileage_data = $iterator->current();

echo "<table class='tab_cadre'>";
echo "<tr>";
echo "<th>" . __('Metric', 'newbase') . "</th>";
echo "<th>" . __('Value', 'newbase') . "</th>";
echo "</tr>";

echo "<tr class='tab_bg_1'>";
echo "<td>" . __('Total Mileage', 'newbase') . "</td>";
echo "<td><b>" . number_format((float) ($mileage_data['total_mileage'] ?? 0), 2, ',', '.') . " km</b></td>";
echo "</tr>";

echo "<tr class='tab_bg_1'>";
echo "<td>" . __('Average Mileage per Task', 'newbase') . "</td>";
echo "<td><b>" . number_format((float) ($mileage_data['avg_mileage'] ?? 0), 2, ',', '.') . " km</b></td>";
echo "</tr>";

echo "<tr class='tab_bg_1'>";
echo "<td>" . __('Tasks with Mileage', 'newbase') . "</td>";
echo "<td><b>" . ($mileage_data['task_count'] ?? 0) . "</b></td>";
echo "</tr>";

echo "</table>";

echo "</div>";
echo "</div>";

// Tasks by Company Report
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-building'></i> " . __('Tasks by Company', 'newbase') . "</h3>";

$iterator = $DB->request([
    'SELECT' => [
        'glpi_plugin_newbase_companydata.name AS company_name',
        'COUNT' => 'glpi_plugin_newbase_task.id AS task_count',
    ],
    'FROM' => 'glpi_plugin_newbase_task',
    'INNER JOIN' => [
        'glpi_plugin_newbase_companydata' => [
            'ON' => [
                'glpi_plugin_newbase_task' => 'plugin_newbase_companydata_id',
                'glpi_plugin_newbase_companydata' => 'id',
            ],
        ],
    ],
    'WHERE' => $where,
    'GROUP' => 'plugin_newbase_companydata_id',
    'ORDER' => 'task_count DESC',
    'LIMIT' => 10,
]);

if (count($iterator)) {
    echo "<table class='tab_cadre'>";
    echo "<tr>";
    echo "<th>" . __('Company', 'newbase') . "</th>";
    echo "<th>" . __('Tasks', 'newbase') . "</th>";
    echo "</tr>";

    foreach ($iterator as $row) {
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . $row['company_name'] . "</td>";
        echo "<td><b>" . $row['task_count'] . "</b></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p class='center'>" . __('No data available', 'newbase') . "</p>";
}

echo "</div>";
echo "</div>";

// Tasks by User Report
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-users'></i> " . __('Tasks by User', 'newbase') . "</h3>";

$iterator = $DB->request([
    'SELECT' => [
        'glpi_users.name AS user_name',
        'COUNT' => 'glpi_plugin_newbase_task.id AS task_count',
    ],
    'FROM' => 'glpi_plugin_newbase_task',
    'INNER JOIN' => [
        'glpi_users' => [
            'ON' => [
                'glpi_plugin_newbase_task' => 'assigned_to',
                'glpi_users' => 'id',
            ],
        ],
    ],
    'WHERE' => $where,
    'GROUP' => 'assigned_to',
    'ORDER' => 'task_count DESC',
    'LIMIT' => 10,
]);

if (count($iterator)) {
    echo "<table class='tab_cadre'>";
    echo "<tr>";
    echo "<th>" . __('User', 'newbase') . "</th>";
    echo "<th>" . __('Tasks', 'newbase') . "</th>";
    echo "</tr>";

    foreach ($iterator as $row) {
        echo "<tr class='tab_bg_1'>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td><b>" . $row['task_count'] . "</b></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p class='center'>" . __('No data available', 'newbase') . "</p>";
}

echo "</div>";
echo "</div>";

// Export buttons
echo "<div style='margin: 20px; text-align: center;'>";
echo "<button class='btn btn-primary' onclick='window.print();'><i class='fas fa-print'></i> " . __('Print Report', 'newbase') . "</button>";
echo "</div>";

Html::footer();
