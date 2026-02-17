<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Newbase. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2024-2026 by João Lucas
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/JoaoLucascp/Glpi
 * -------------------------------------------------------------------------
 */

use GlpiPlugin\Newbase\Task;

// Check GLPI is loaded
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Include GLPI
include('../../../inc/includes.php');

// Check user is authenticated
Session::checkLoginUser();

// Check user rights
Session::checkRight('plugin_newbase', READ);

// Page header
Html::header(__('Newbase - Dashboard', 'newbase'), $_SERVER['PHP_SELF'], 'plugins', 'newbase');

global $DB;

// Get statistics
$stats = [
    'total_tasks' => 0,
    'new_tasks' => 0,
    'in_progress_tasks' => 0,
    'completed_tasks' => 0,
    'total_mileage' => 0,
];

// Count tasks by status
$iterator = $DB->request([
    'SELECT' => [
        'status',
        'COUNT(*) AS total',
        'SUM(mileage) AS total_mileage',
    ],
    'FROM' => Task::getTable(),
    'WHERE' => [
        'is_deleted' => 0,
    ],
    'GROUP' => 'status',
]);

foreach ($iterator as $row) {
    $stats['total_tasks'] += $row['total'];
    $stats['total_mileage'] += (float) ($row['total_mileage'] ?? 0);

    switch ($row['status']) {
        case 'new':
            $stats['new_tasks'] = $row['total'];
            break;
        case 'in_progress':
            $stats['in_progress_tasks'] = $row['total'];
            break;
        case 'completed':
            $stats['completed_tasks'] = $row['total'];
            break;
    }
}

// Recent tasks - SINTAXE CORRIGIDA
$recent_tasks = [];
$iterator = $DB->request([
    'SELECT' => [
        't.id',
        't.title',
        't.status',
        't.date_start',
        't.date_end',
        't.mileage',
        'u.realname AS user_realname',
        'u.firstname AS user_firstname',
    ],
    'FROM' => Task::getTable() . ' AS t',
    'LEFT JOIN' => [
        'glpi_users AS u' => [
            'ON' => [
                't' => 'users_id',
                'u' => 'id',
            ],
        ],
    ],
    'WHERE' => [
        't.is_deleted' => 0,
    ],
    'ORDER' => ['t.date_start DESC'],
    'LIMIT' => 10,
]);

foreach ($iterator as $row) {
    $recent_tasks[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'status' => $row['status'],
        'date_start' => $row['date_start'],
        'date_end' => $row['date_end'],
        'mileage' => (float) ($row['mileage'] ?? 0),
        'user_name' => trim(($row['user_firstname'] ?? '') . ' ' . ($row['user_realname'] ?? '')),
    ];
}

// Display dashboard
echo "<div class='container-fluid'>";

// Statistics cards
echo "<div class='row mb-3'>";

echo "<div class='col-md-3'>";
echo "<div class='card'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . __('Total Tasks', 'newbase') . "</h5>";
echo "<p class='card-text display-4'>" . $stats['total_tasks'] . "</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . __('New Tasks', 'newbase') . "</h5>";
echo "<p class='card-text display-4'>" . $stats['new_tasks'] . "</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . __('In Progress', 'newbase') . "</h5>";
echo "<p class='card-text display-4'>" . $stats['in_progress_tasks'] . "</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>" . __('Completed', 'newbase') . "</h5>";
echo "<p class='card-text display-4'>" . $stats['completed_tasks'] . "</p>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "</div>"; // .row

// Recent tasks table
echo "<div class='row'>";
echo "<div class='col-12'>";
echo "<div class='card'>";
echo "<div class='card-header'>";
echo "<h3>" . __('Recent Tasks', 'newbase') . "</h3>";
echo "</div>";
echo "<div class='card-body'>";

if (empty($recent_tasks)) {
    echo "<p>" . __('No tasks found', 'newbase') . "</p>";
} else {
    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>" . __('ID') . "</th>";
    echo "<th>" . __('Title', 'newbase') . "</th>";
    echo "<th>" . __('Status') . "</th>";
    echo "<th>" . __('Assigned to', 'newbase') . "</th>";
    echo "<th>" . __('Start date', 'newbase') . "</th>";
    echo "<th>" . __('Mileage (km)', 'newbase') . "</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $statuses = Task::getStatuses();

    foreach ($recent_tasks as $task) {
        echo "<tr>";
        echo "<td><a href='" . Task::getFormURLWithID($task['id']) . "'>{$task['id']}</a></td>";
        echo "<td>" . htmlspecialchars($task['title']) . "</td>";
        echo "<td>" . ($statuses[$task['status']] ?? $task['status']) . "</td>";
        echo "<td>" . htmlspecialchars($task['user_name'] ?: '-') . "</td>";
        echo "<td>" . Html::convDateTime($task['date_start']) . "</td>";
        echo "<td>" . number_format($task['mileage'], 2, ',', '.') . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

echo "</div>"; // .card-body
echo "</div>"; // .card
echo "</div>"; // .col-12
echo "</div>"; // .row

echo "</div>"; // .container-fluid

// Page footer
Html::footer();
