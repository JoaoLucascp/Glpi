<?php
/**
 * Diagnostic script for Newbase plugin
 * 
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

// Include GLPI
include('../../../../inc/includes.php');

// Check if user is logged in
Session::checkLoginUser();

global $DB, $CFG_GLPI;

echo "<!DOCTYPE html>";
echo "<html><head><title>Newbase - Diagnostic</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { background: white; padding: 20px; border-radius: 8px; max-width: 1200px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h1 { color: #2196F3; }
h2 { color: #555; margin-top: 30px; border-bottom: 2px solid #2196F3; padding-bottom: 10px; }
.success { color: #4CAF50; }
.error { color: #F44336; }
.warning { color: #FF9800; }
.info { color: #2196F3; }
.check { margin: 10px 0; padding: 10px; background: #f9f9f9; border-left: 4px solid #ddd; }
.check.ok { border-left-color: #4CAF50; background: #E8F5E9; }
.check.fail { border-left-color: #F44336; background: #FFEBEE; }
.check.warn { border-left-color: #FF9800; background: #FFF3E0; }
.table { width: 100%; border-collapse: collapse; margin: 20px 0; }
.table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f5f5f5; font-weight: bold; }
.btn { display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
.btn:hover { background: #1976D2; }
.code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; overflow-x: auto; }
</style>";
echo "</head><body>";
echo "<div class='container'>";

echo "<h1>🔍 Newbase Plugin - Diagnostic Report</h1>";
echo "<p><strong>Generated:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>User:</strong> " . $_SESSION['glpiname'] . " (ID: " . $_SESSION['glpiID'] . ")</p>";

// Check 1: GLPI Version
echo "<h2>1. GLPI Version</h2>";
$glpi_version = GLPI_VERSION;
$required_min = '10.0.20';
$required_max = '10.0.99';

echo "<div class='check " . (version_compare($glpi_version, $required_min, '>=') && version_compare($glpi_version, $required_max, '<') ? 'ok' : 'fail') . "'>";
echo "<strong>Current Version:</strong> $glpi_version<br>";
echo "<strong>Required:</strong> >= $required_min and < $required_max<br>";
if (version_compare($glpi_version, $required_min, '>=') && version_compare($glpi_version, $required_max, '<')) {
    echo "<span class='success'>✓ Version OK</span>";
} else {
    echo "<span class='error'>✗ Version not compatible</span>";
}
echo "</div>";

// Check 2: PHP Version
echo "<h2>2. PHP Version</h2>";
$php_version = PHP_VERSION;
$php_required = '8.1';

echo "<div class='check " . (version_compare($php_version, $php_required, '>=') ? 'ok' : 'fail') . "'>";
echo "<strong>Current Version:</strong> $php_version<br>";
echo "<strong>Required:</strong> >= $php_required<br>";
if (version_compare($php_version, $php_required, '>=')) {
    echo "<span class='success'>✓ Version OK</span>";
} else {
    echo "<span class='error'>✗ Version too old</span>";
}
echo "</div>";

// Check 3: Plugin Installation
echo "<h2>3. Plugin Installation</h2>";
$plugin = new Plugin();
$is_installed = $plugin->isInstalled('newbase');
$is_activated = $plugin->isActivated('newbase');

echo "<div class='check " . ($is_installed && $is_activated ? 'ok' : 'fail') . "'>";
echo "<strong>Installed:</strong> " . ($is_installed ? '<span class="success">Yes</span>' : '<span class="error">No</span>') . "<br>";
echo "<strong>Activated:</strong> " . ($is_activated ? '<span class="success">Yes</span>' : '<span class="error">No</span>') . "<br>";
if ($is_installed && $is_activated) {
    echo "<span class='success'>✓ Plugin is installed and activated</span>";
} else {
    echo "<span class='error'>✗ Plugin is not properly installed/activated</span>";
}
echo "</div>";

// Check 4: Database Tables
echo "<h2>4. Database Tables</h2>";
$tables = [
    'glpi_plugin_newbase_companydata',
    'glpi_plugin_newbase_address',
    'glpi_plugin_newbase_system',
    'glpi_plugin_newbase_task',
    'glpi_plugin_newbase_tasksignature',
    'glpi_plugin_newbase_config'
];

$all_tables_ok = true;
echo "<table class='table'>";
echo "<tr><th>Table Name</th><th>Exists</th><th>Row Count</th></tr>";

foreach ($tables as $table) {
    $exists = $DB->tableExists($table);
    $count = $exists ? countElementsInTable($table) : 0;
    $all_tables_ok = $all_tables_ok && $exists;
    
    echo "<tr>";
    echo "<td>$table</td>";
    echo "<td>" . ($exists ? '<span class="success">✓ Yes</span>' : '<span class="error">✗ No</span>') . "</td>";
    echo "<td>$count</td>";
    echo "</tr>";
}

echo "</table>";

echo "<div class='check " . ($all_tables_ok ? 'ok' : 'fail') . "'>";
if ($all_tables_ok) {
    echo "<span class='success'>✓ All tables exist</span>";
} else {
    echo "<span class='error'>✗ Some tables are missing - reinstall plugin</span>";
}
echo "</div>";

// Check 5: Permissions
echo "<h2>5. User Permissions</h2>";
$user_id = $_SESSION['glpiID'];
$profile_id = $_SESSION['glpiactiveprofile']['id'];
$profile_name = $_SESSION['glpiactiveprofile']['name'];

echo "<div class='info'>";
echo "<strong>Current Profile:</strong> $profile_name (ID: $profile_id)<br>";
echo "</div>";

$rights_to_check = [
    'plugin_newbase_companydata',
    'plugin_newbase_task',
    'plugin_newbase_system',
    'plugin_newbase_config'
];

echo "<table class='table'>";
echo "<tr><th>Right Name</th><th>Value</th><th>Can Read</th><th>Can Create</th><th>Can Update</th><th>Can Delete</th></tr>";

$permissions_ok = true;
foreach ($rights_to_check as $right) {
    $iterator = $DB->request([
        'FROM' => 'glpi_profilerights',
        'WHERE' => [
            'profiles_id' => $profile_id,
            'name' => $right
        ],
        'LIMIT' => 1
    ]);
    
    if (count($iterator)) {
        $row = $iterator->current();
        $value = $row['rights'];
        
        echo "<tr>";
        echo "<td>$right</td>";
        echo "<td>$value</td>";
        echo "<td>" . (($value & READ) ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
        echo "<td>" . (($value & CREATE) ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
        echo "<td>" . (($value & UPDATE) ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
        echo "<td>" . (($value & DELETE) ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
        echo "</tr>";
        
        if ($value == 0) {
            $permissions_ok = false;
        }
    } else {
        echo "<tr>";
        echo "<td>$right</td>";
        echo "<td colspan='5'><span class='error'>✗ Permission not found</span></td>";
        echo "</tr>";
        $permissions_ok = false;
    }
}

echo "</table>";

echo "<div class='check " . ($permissions_ok ? 'ok' : 'fail') . "'>";
if ($permissions_ok) {
    echo "<span class='success'>✓ All permissions configured</span>";
} else {
    echo "<span class='error'>✗ Some permissions are missing or incorrect</span><br>";
    echo "<a href='fix_permissions.php' class='btn'>Fix Permissions Now</a>";
}
echo "</div>";

// Check 6: Plugin Files
echo "<h2>6. Plugin Files</h2>";
$plugin_dir = GLPI_ROOT . '/plugins/newbase';
$required_files = [
    'setup.php',
    'hook.php',
    'front/index.php',
    'front/config.php',
    'front/companydata.php',
    'front/companydata.form.php',
    'src/CompanyData.php',
    'src/Config.php',
    'src/Task.php',
    'src/System.php',
    'src/Address.php'
];

$all_files_ok = true;
echo "<table class='table'>";
echo "<tr><th>File</th><th>Exists</th><th>Readable</th></tr>";

foreach ($required_files as $file) {
    $path = $plugin_dir . '/' . $file;
    $exists = file_exists($path);
    $readable = $exists && is_readable($path);
    $all_files_ok = $all_files_ok && $exists && $readable;
    
    echo "<tr>";
    echo "<td>$file</td>";
    echo "<td>" . ($exists ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
    echo "<td>" . ($readable ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<div class='check " . ($all_files_ok ? 'ok' : 'fail') . "'>";
if ($all_files_ok) {
    echo "<span class='success'>✓ All required files present</span>";
} else {
    echo "<span class='error'>✗ Some files are missing</span>";
}
echo "</div>";

// Check 7: Menu Registration
echo "<h2>7. Menu Registration</h2>";
$menu_registered = false;

if (isset($PLUGIN_HOOKS['menu_toadd']['newbase'])) {
    $menu_hook = $PLUGIN_HOOKS['menu_toadd']['newbase'];
    $menu_registered = true;
    
    echo "<div class='check ok'>";
    echo "<strong>Menu Hook:</strong> " . var_export($menu_hook, true) . "<br>";
    echo "<span class='success'>✓ Menu hook is registered</span>";
    echo "</div>";
} else {
    echo "<div class='check fail'>";
    echo "<span class='error'>✗ Menu hook is NOT registered</span>";
    echo "</div>";
}

// Check 8: Config Values
echo "<h2>8. Configuration</h2>";
$configs = $DB->request([
    'FROM' => 'glpi_plugin_newbase_config',
    'ORDER' => 'config_key'
]);

if (count($configs)) {
    echo "<table class='table'>";
    echo "<tr><th>Config Key</th><th>Value</th></tr>";
    
    foreach ($configs as $config) {
        echo "<tr>";
        echo "<td>" . $config['config_key'] . "</td>";
        echo "<td>" . $config['config_value'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<div class='check ok'>";
    echo "<span class='success'>✓ Configuration loaded (" . count($configs) . " settings)</span>";
    echo "</div>";
} else {
    echo "<div class='check warn'>";
    echo "<span class='warning'>⚠ No configuration found - using defaults</span>";
    echo "</div>";
}

// Summary
echo "<h2>9. Summary</h2>";
$all_ok = $all_tables_ok && $permissions_ok && $all_files_ok && $is_installed && $is_activated;

if ($all_ok) {
    echo "<div class='check ok'>";
    echo "<h3 class='success'>✓ Plugin is working correctly!</h3>";
    echo "<p>All checks passed. You can now use the plugin.</p>";
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/index.php' class='btn'>Go to Dashboard</a>";
    echo "</div>";
} else {
    echo "<div class='check fail'>";
    echo "<h3 class='error'>✗ Plugin has issues</h3>";
    echo "<p>Please fix the issues highlighted above.</p>";
    
    if (!$permissions_ok) {
        echo "<a href='fix_permissions.php' class='btn'>Fix Permissions</a>";
    }
    
    if (!$is_installed || !$is_activated) {
        echo "<a href='" . $CFG_GLPI['root_doc'] . "/front/plugin.php' class='btn'>Go to Plugins Page</a>";
    }
    
    echo "</div>";
}

// Actions
echo "<div style='margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd;'>";
echo "<h3>Quick Actions</h3>";
echo "<a href='" . $_SERVER['PHP_SELF'] . "' class='btn'>Refresh Diagnostic</a>";
echo "<a href='fix_permissions.php' class='btn'>Fix Permissions</a>";
echo "<a href='" . $CFG_GLPI['root_doc'] . "/front/plugin.php' class='btn'>Manage Plugins</a>";
echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/index.php' class='btn'>Newbase Dashboard</a>";
echo "</div>";

echo "</div>";
echo "</body></html>";
