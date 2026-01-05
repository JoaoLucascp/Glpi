<?php
/**
 * Script to fix Newbase plugin permissions
 * Run this once after reinstalling the plugin
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

// Check if user is logged in and is admin
Session::checkLoginUser();
Session::checkRight('config', UPDATE);

global $DB, $CFG_GLPI;

echo "<!DOCTYPE html>";
echo "<html><head><title>Newbase - Fix Permissions</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { background: white; padding: 20px; border-radius: 8px; max-width: 800px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
h1 { color: #2196F3; }
.success { color: #4CAF50; background: #E8F5E9; padding: 10px; margin: 10px 0; border-radius: 4px; }
.error { color: #F44336; background: #FFEBEE; padding: 10px; margin: 10px 0; border-radius: 4px; }
.info { color: #2196F3; background: #E3F2FD; padding: 10px; margin: 10px 0; border-radius: 4px; }
.table { width: 100%; border-collapse: collapse; margin: 20px 0; }
.table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
.table th { background: #f5f5f5; font-weight: bold; }
.btn { display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
.btn:hover { background: #1976D2; }
</style>";
echo "</head><body>";
echo "<div class='container'>";

echo "<h1>Newbase Plugin - Fix Permissions</h1>";

try {
    // Define rights with proper values
    $rights = [
        'plugin_newbase_companydata' => ALLSTANDARDRIGHT,
        'plugin_newbase_task' => ALLSTANDARDRIGHT,
        'plugin_newbase_system' => ALLSTANDARDRIGHT,
        'plugin_newbase_config' => READ | UPDATE
    ];

    echo "<div class='info'>Starting permission fix...</div>";

    // Get all profiles
    $profiles = $DB->request(['FROM' => 'glpi_profiles']);
    $updated = 0;
    $created = 0;
    $errors = 0;

    echo "<table class='table'>";
    echo "<tr><th>Profile</th><th>Right</th><th>Value</th><th>Action</th><th>Status</th></tr>";

    foreach ($profiles as $profile) {
        foreach ($rights as $rightname => $rightvalue) {
            // Determine rights based on profile
            $value = 0;
            
            // Super-Admin (id 4) gets all rights
            if ($profile['id'] == 4) {
                $value = $rightvalue;
            }
            // Central interface gets all rights except purge
            elseif ($profile['interface'] == 'central') {
                if ($rightvalue == ALLSTANDARDRIGHT) {
                    $value = READ | CREATE | UPDATE | DELETE;
                } else {
                    $value = $rightvalue;
                }
            }
            // Helpdesk interface gets read only
            elseif ($profile['interface'] == 'helpdesk') {
                $value = READ;
            }

            // Check if right already exists
            $existing = $DB->request([
                'FROM' => 'glpi_profilerights',
                'WHERE' => [
                    'profiles_id' => $profile['id'],
                    'name' => $rightname
                ],
                'LIMIT' => 1
            ]);

            $action = '';
            $status = '';

            if (count($existing)) {
                // Update existing right
                $existing_row = $existing->current();
                if ($existing_row['rights'] != $value) {
                    if ($DB->update(
                        'glpi_profilerights',
                        ['rights' => $value],
                        [
                            'profiles_id' => $profile['id'],
                            'name' => $rightname
                        ]
                    )) {
                        $action = 'Updated';
                        $status = 'success';
                        $updated++;
                    } else {
                        $action = 'Failed to update';
                        $status = 'error';
                        $errors++;
                    }
                } else {
                    $action = 'Already correct';
                    $status = 'info';
                }
            } else {
                // Create new right
                if ($DB->insert('glpi_profilerights', [
                    'profiles_id' => $profile['id'],
                    'name' => $rightname,
                    'rights' => $value
                ])) {
                    $action = 'Created';
                    $status = 'success';
                    $created++;
                } else {
                    $action = 'Failed to create';
                    $status = 'error';
                    $errors++;
                }
            }

            echo "<tr>";
            echo "<td>{$profile['name']}</td>";
            echo "<td>$rightname</td>";
            echo "<td>$value</td>";
            echo "<td>$action</td>";
            echo "<td class='$status'>$status</td>";
            echo "</tr>";
        }
    }

    echo "</table>";

    // Summary
    echo "<div class='success'>";
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li><strong>Rights created:</strong> $created</li>";
    echo "<li><strong>Rights updated:</strong> $updated</li>";
    echo "<li><strong>Errors:</strong> $errors</li>";
    echo "</ul>";
    echo "</div>";

    if ($errors == 0) {
        echo "<div class='success'><strong>✓ All permissions fixed successfully!</strong></div>";
    } else {
        echo "<div class='error'><strong>⚠ Some errors occurred. Please check the table above.</strong></div>";
    }

    echo "<div style='margin-top: 20px;'>";
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/front/plugin.php' class='btn'>Back to Plugins</a>";
    echo "<a href='" . $CFG_GLPI['root_doc'] . "/plugins/newbase/front/index.php' class='btn'>Go to Newbase Dashboard</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='error'><strong>Error:</strong> " . $e->getMessage() . "</div>";
    echo "<div class='info'>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "</div>";
echo "</body></html>";
