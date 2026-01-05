<?php

declare(strict_types=1);
/**
 * Cleanup Script for Newbase Plugin
 * 
 * This script removes all Newbase plugin data from the database
 * Run this BEFORE attempting to reinstall the plugin
 * 
 * Usage: php cleanup_db.php
 */

// Bootstrap GLPI
define('GLPI_ROOT', realpath('../../../'));
include(GLPI_ROOT . '/inc/includes.php');

global $DB;

echo "====================================\n";
echo "Newbase Plugin - Database Cleanup\n";
echo "====================================\n\n";

// List of tables to drop (in correct order - children first!)
$tables = [
    'glpi_plugin_newbase_tasksignature',
    'glpi_plugin_newbase_task',
    'glpi_plugin_newbase_system',
    'glpi_plugin_newbase_address',
    'glpi_plugin_newbase_companydata',
    'glpi_plugin_newbase_config',
    // Old incorrect table names (just in case)
    'newbase_tasksignature',
    'newbase_task',
    'newbase_system',
    'newbase_address',
    'newbase_companydata',
    'newbase_config'
];

echo "Step 1: Disabling foreign key checks...\n";
$DB->query("SET FOREIGN_KEY_CHECKS = 0");

echo "Step 2: Dropping tables...\n";
foreach ($tables as $table) {
    if ($DB->tableExists($table)) {
        echo "  - Dropping table: $table\n";
        $result = $DB->query("DROP TABLE IF EXISTS `$table`");
        if (!$result) {
            echo "    ERROR: " . $DB->error() . "\n";
        } else {
            echo "    SUCCESS\n";
        }
    } else {
        echo "  - Table $table does not exist (skipping)\n";
    }
}

echo "\nStep 3: Re-enabling foreign key checks...\n";
$DB->query("SET FOREIGN_KEY_CHECKS = 1");

echo "\nStep 4: Removing display preferences...\n";
$result = $DB->delete('glpi_displaypreferences', [
    'itemtype' => ['LIKE', 'GlpiPlugin\\Newbase\\%']
]);
echo "  Removed: " . ($result ? "SUCCESS" : "NONE FOUND") . "\n";

echo "\nStep 5: Removing profile rights...\n";
$patterns = ['plugin_newbase_%', 'newbase_%'];
foreach ($patterns as $pattern) {
    $result = $DB->delete('glpi_profilerights', [
        'name' => ['LIKE', $pattern]
    ]);
    echo "  Pattern '$pattern': " . ($result ? "REMOVED" : "NONE FOUND") . "\n";
}

echo "\n====================================\n";
echo "Cleanup completed!\n";
echo "====================================\n";
echo "\nYou can now try to install the plugin again.\n";
echo "Go to: Setup > Plugins > Newbase > Install\n\n";

