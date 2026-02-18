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

// Include GLPI
include('../../../inc/includes.php');
global $CFG_GLPI;

// Check GLPI is loaded
if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

// Check user is authenticated
\Session::checkLoginUser();

// Check user rights
\Session::checkRight('plugin_newbase', READ);

// Page header
\Html::header(__('Newbase - Dashboard', 'newbase'), $_SERVER['PHP_SELF'], "plugins", "newbase");

// Botões de ação rápida no topo do dashboard
echo "<div class='mb-3'>";

if (\GlpiPlugin\Newbase\CompanyData::canCreate()) {
    echo "<a class='btn btn-secondary me-2' href='"
    . $CFG_GLPI['root_doc']
    . "/plugins/newbase/front/companydata.form.php'>"
    . __('Nova empresa', 'newbase') . "</a>";
}

if (\GlpiPlugin\Newbase\Task::canCreate()) {
    echo "<a class='btn btn-primary me-2' href='"
    . $CFG_GLPI['root_doc']
    . \GlpiPlugin\Newbase\Task::getFormURL(false) . "'>"
    . __('Nova Tarefa', 'newbase') . "</a>";
}

echo "</div>";

global $DB;

// Get statistics
$stats = [
    'total_tasks'       => 0,
    'new_tasks'         => 0,
    'in_progress_tasks' => 0,
    'completed_tasks'   => 0,
    'total_mileage'     => 0,
];

// Count tasks by status (use QueryExpression for aggregates to avoid quoting issues)
$iterator = $DB->request([
    'SELECT' => [
        'status',
        new \QueryExpression('COUNT(*) AS total'),
        new \QueryExpression('SUM(mileage) AS total_mileage'),
    ],
    'FROM'   => [Task::getTable()],
    'WHERE'  => ['is_deleted' => 0],
    'GROUP'  => ['status'],
]);

foreach ($iterator as $row) {
    $stats['total_tasks']   += $row['total'];
    $stats['total_mileage'] += (float) ($row['total_mileage'] ?? 0);

    switch ($row['status']) {
        case 'new':
            $stats['new_tasks'] += $row['total'];
            break;
        case 'inprogress':
            $stats['in_progress_tasks'] += $row['total'];
            break;
        case 'completed':
            $stats['completed_tasks'] += $row['total'];
            break;
    }
}

// Recent tasks
$recent_tasks = [];
$iterator = $DB->request([
    'SELECT'    => [
        't.id', 't.title', 't.status', 't.date_start', 't.date_end', 't.mileage',
        'u.realname AS user_realname', 'u.firstname AS user_firstname'
    ],
    'FROM'      => [Task::getTable() . ' AS t'],
    'LEFT JOIN' => [
        'glpi_users AS u' => [
            'ON' => ['t' => 'users_id', 'u' => 'id']
        ]
    ],
    'WHERE'     => ['t.is_deleted' => 0],
    'ORDER'     => ['t.date_start DESC'],
    'LIMIT'     => 10,
]);

foreach ($iterator as $task) {
    $user_name = trim(($task['user_firstname'] ?? '') . ' ' . ($task['user_realname'] ?? ''));
    if (empty($user_name)) {
        $user_name = $task['user_realname'] ?? $task['user_firstname'] ?? '-';
    }
    $task['user_name'] = $user_name;
    $recent_tasks[] = $task;
}

$statuses = Task::getStatuses();

// Display dashboard
echo "<div class='container-fluid'>";

// Statistics cards
$cards = [
    [
        'icon'  => 'ti-sum',
        'color' => 'primary',
        'value' => (int)$stats['total_tasks'],
        'label' => __('Total Tasks', 'newbase')
    ],
    [
        'icon'  => 'ti-plus',
        'color' => 'secondary',
        'value' => (int)$stats['new_tasks'],
        'label' => __('New Tasks', 'newbase')
    ],
    [
        'icon'  => 'ti-player-play',
        'color' => 'warning',
        'value' => (int)$stats['in_progress_tasks'],
        'label' => __('In Progress', 'newbase')
    ],
    [
        'icon'  => 'ti-check',
        'color' => 'success',
        'value' => (int)$stats['completed_tasks'],
        'label' => __('Completed', 'newbase')
    ],
];

echo "<div class='row mb-3'>";

foreach ($cards as $c) {
    // ERRO 14 CORRIGIDO: Escapar output nos cards
    $iconClass  = htmlspecialchars($c['icon'], ENT_QUOTES, 'UTF-8');
    $colorClass = htmlspecialchars($c['color'], ENT_QUOTES, 'UTF-8');
    $label      = htmlspecialchars($c['label'], ENT_QUOTES, 'UTF-8');
    // Value já foi castado para int no array acima, mas por segurança convertemos para string limpa
    $value      = (int)$c['value'];

    echo "
    <div class='col-md-3'>
        <div class='card'>
            <div class='card-body text-center'>
                <h5 class='card-title'>{$label}</h5>
                <p class='card-text display-4'>{$value}</p>
                <i class='ti {$iconClass} text-{$colorClass} fs-1'></i>
            </div>
        </div>
    </div>";
}
echo "</div>";

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
    echo "tbody";

    foreach ($recent_tasks as $task) {
        $status_label = $statuses[$task['status']] ?? $task['status'];

        echo "<tr>";
        // ID seguro (int)
        echo "<td><a href='" . Task::getFormURLWithID((int)$task['id']) . "'>" . (int)$task['id'] . "</a></td>";

        // ERRO 14 CORRIGIDO: Escapar strings do banco
        echo "<td>" . htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($task['user_name'], ENT_QUOTES, 'UTF-8') . "</td>";

        // Helpers do GLPI são seguros
        echo "<td>" . \Html::convDateTime($task['date_start']) . "</td>";
        echo "<td>" . number_format((float)$task['mileage'], 2, ',', '.') . "</td>";
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
\Html::footer();
