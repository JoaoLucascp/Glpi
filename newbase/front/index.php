<?php
/**
 * Dashboard page for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Config;

include('../../../inc/includes.php');

// Check authentication
Session::checkLoginUser();

// Check rights
Session::checkRight('plugin_newbase_companydata', READ);

// Start page
Html::header(
    __('Newbase - Personal Data Management', 'newbase'),
    $_SERVER['PHP_SELF'],
    "management",
    "GlpiPlugin\\Newbase\\CompanyData"
);

echo "<div class='center'>";
echo "<h1>" . __('Newbase Dashboard', 'newbase') . "</h1>";
echo "</div>";

global $DB, $CFG_GLPI;

// Statistics cards
echo "<div class='dashboard-cards' style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px;'>";

// Companies count
$companies_count = countElementsInTable('glpi_plugin_newbase_companydata');
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3 style='margin: 0 0 10px 0;'><i class='fas fa-building'></i> " . __('Companies', 'newbase') . "</h3>";
echo "<div style='font-size: 32px; font-weight: bold; color: #2196F3;'>$companies_count</div>";
echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/companydata.php' class='btn btn-sm btn-primary' style='margin-top: 10px;'>";
echo __('View all', 'newbase') . " <i class='fas fa-arrow-right'></i></a>";
echo "</div>";

// Tasks count by status
$tasks_open = countElementsInTable('glpi_plugin_newbase_task', ['status' => 'open']);
$tasks_in_progress = countElementsInTable('glpi_plugin_newbase_task', ['status' => 'in_progress']);
$tasks_completed = countElementsInTable('glpi_plugin_newbase_task', ['status' => 'completed']);
$tasks_total = $tasks_open + $tasks_in_progress + $tasks_completed;

echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3 style='margin: 0 0 10px 0;'><i class='fas fa-tasks'></i> " . __('Tasks', 'newbase') . "</h3>";
echo "<div style='font-size: 32px; font-weight: bold; color: #4CAF50;'>$tasks_total</div>";
echo "<div style='margin: 10px 0; font-size: 14px;'>";
echo "<span style='color: #FF9800;'>" . __('Open', 'newbase') . ": $tasks_open</span> | ";
echo "<span style='color: #2196F3;'>" . __('In Progress', 'newbase') . ": $tasks_in_progress</span> | ";
echo "<span style='color: #4CAF50;'>" . __('Completed', 'newbase') . ": $tasks_completed</span>";
echo "</div>";
echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.php' class='btn btn-sm btn-primary' style='margin-top: 10px;'>";
echo __('View all', 'newbase') . " <i class='fas fa-arrow-right'></i></a>";
echo "</div>";

// Systems count
$systems_count = countElementsInTable('glpi_plugin_newbase_system');
$systems_active = countElementsInTable('glpi_plugin_newbase_system', ['status' => 'active']);

echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3 style='margin: 0 0 10px 0;'><i class='fas fa-server'></i> " . __('Systems', 'newbase') . "</h3>";
echo "<div style='font-size: 32px; font-weight: bold; color: #9C27B0;'>$systems_count</div>";
echo "<div style='margin: 10px 0; font-size: 14px;'>";
echo "<span style='color: #4CAF50;'>" . __('Active', 'newbase') . ": $systems_active</span>";
echo "</div>";
echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.php' class='btn btn-sm btn-primary' style='margin-top: 10px;'>";
echo __('View all', 'newbase') . " <i class='fas fa-arrow-right'></i></a>";
echo "</div>";

// Addresses count
$addresses_count = countElementsInTable('glpi_plugin_newbase_address');

echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3 style='margin: 0 0 10px 0;'><i class='fas fa-map-marker-alt'></i> " . __('Addresses', 'newbase') . "</h3>";
echo "<div style='font-size: 32px; font-weight: bold; color: #FF5722;'>$addresses_count</div>";
echo "</div>";

echo "</div>";

// Recent activities section
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-clock'></i> " . __('Recent Tasks', 'newbase') . "</h3>";

$iterator = $DB->request([
    'SELECT' => [
        'glpi_plugin_newbase_task.*',
        'glpi_plugin_newbase_companydata.name AS company_name',
        'glpi_users.name AS user_name'
    ],
    'FROM' => 'glpi_plugin_newbase_task',
    'LEFT JOIN' => [
        'glpi_plugin_newbase_companydata' => [
            'ON' => [
                'glpi_plugin_newbase_task' => 'plugin_newbase_companydata_id',
                'glpi_plugin_newbase_companydata' => 'id'
            ]
        ],
        'glpi_users' => [
            'ON' => [
                'glpi_plugin_newbase_task' => 'assigned_to',
                'glpi_users' => 'id'
            ]
        ]
    ],
    'ORDER' => 'date_creation DESC',
    'LIMIT' => 10
]);

if (count($iterator)) {
    echo "<table class='tab_cadrehov'>";
    echo "<tr>";
    echo "<th>" . __('Title', 'newbase') . "</th>";
    echo "<th>" . __('Company', 'newbase') . "</th>";
    echo "<th>" . __('Status', 'newbase') . "</th>";
    echo "<th>" . __('Assigned to', 'newbase') . "</th>";
    echo "<th>" . __('Created', 'newbase') . "</th>";
    echo "</tr>";

    $statuses = Task::getTaskStatuses();

    foreach ($iterator as $row) {
        $status_colors = [
            'open' => '#FF9800',
            'in_progress' => '#2196F3',
            'paused' => '#9E9E9E',
            'completed' => '#4CAF50'
        ];
        $status_color = $status_colors[$row['status']] ?? '#000';

        echo "<tr class='tab_bg_1'>";
        echo "<td><a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.form.php?id=" . $row['id'] . "'>";
        echo "<b>" . $row['title'] . "</b></a></td>";
        echo "<td>" . $row['company_name'] . "</td>";
        echo "<td><span style='color: $status_color; font-weight: bold;'>" . ($statuses[$row['status']] ?? $row['status']) . "</span></td>";
        echo "<td>" . ($row['user_name'] ?? '-') . "</td>";
        echo "<td>" . Html::convDateTime($row['date_creation']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p class='center'>" . __('No recent tasks', 'newbase') . "</p>";
}

echo "</div>";
echo "</div>";

// Quick actions
echo "<div style='margin: 20px;'>";
echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
echo "<h3><i class='fas fa-bolt'></i> " . __('Quick Actions', 'newbase') . "</h3>";
echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>";

if (Session::haveRight('plugin_newbase_companydata', CREATE)) {
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/companydata.form.php' class='btn btn-primary'>";
    echo "<i class='fas fa-plus'></i> " . __('New Company', 'newbase') . "</a>";
}

if (Session::haveRight('plugin_newbase_task', CREATE)) {
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/task.form.php' class='btn btn-primary'>";
    echo "<i class='fas fa-plus'></i> " . __('New Task', 'newbase') . "</a>";
}

if (Session::haveRight('plugin_newbase_system', CREATE)) {
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/system.form.php' class='btn btn-primary'>";
    echo "<i class='fas fa-plus'></i> " . __('New System', 'newbase') . "</a>";
}

echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/report.php' class='btn btn-secondary'>";
echo "<i class='fas fa-chart-bar'></i> " . __('Reports', 'newbase') . "</a>";

if (Session::haveRight('plugin_newbase_config', UPDATE)) {
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/config.php' class='btn btn-secondary'>";
    echo "<i class='fas fa-cog'></i> " . __('Configuration', 'newbase') . "</a>";
}

echo "</div>";
echo "</div>";
echo "</div>";

// Map preview (if geolocation is enabled)
if (Config::isGeolocationEnabled()) {
    $tasks_with_coords = countElementsInTable('glpi_plugin_newbase_task', [
        'OR' => [
            ['latitude_start' => ['<>', null]],
            ['latitude_end' => ['<>', null]]
        ]
    ]);

    if ($tasks_with_coords > 0) {
        echo "<div style='margin: 20px;'>";
        echo "<div class='card' style='background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
        echo "<h3><i class='fas fa-map'></i> " . __('Tasks Map', 'newbase') . " ($tasks_with_coords " . __('tasks with location', 'newbase') . ")</h3>";
        echo "<div id='dashboard_map' style='width: 100%; height: 400px;'></div>";
        echo "</div>";
        echo "</div>";

        // Initialize map
        echo Html::scriptBlock("
            $(document).ready(function() {
                // Load Leaflet if not loaded
                if (typeof L === 'undefined') {
                    $('<link>')
                        .attr('rel', 'stylesheet')
                        .attr('href', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')
                        .appendTo('head');

                    $.getScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', function() {
                        initDashboardMap();
                    });
                } else {
                    initDashboardMap();
                }
            });

            function initDashboardMap() {
                var map = L.map('dashboard_map').setView([-23.5505, -46.6333], 10);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Load task markers
                $.ajax({
                    url: '" . $CFG_GLPI['root_doc'] . "/plugins/newbase/ajax/mapData.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success && data.data.markers) {
                            var markers = data.data.markers;
                            var bounds = [];

                            markers.forEach(function(marker) {
                                var icon = L.icon({
                                    iconUrl: marker.type === 'start'
                                        ? 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png'
                                        : 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41]
                                });

                                var m = L.marker([marker.lat, marker.lng], {icon: icon}).addTo(map);
                                m.bindPopup('<b>' + marker.title + '</b><br>' +
                                           'Company: ' + marker.company + '<br>' +
                                           'Status: ' + marker.status);

                                bounds.push([marker.lat, marker.lng]);
                            });

                            if (bounds.length > 0) {
                                map.fitBounds(bounds);
                            }
                        }
                    }
                });
            }
        ");
    }
}

Html::footer();
