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

/**
* TaskSignature Class - Digital signature management for tasks
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;
use Html;
use Toolbox;

/**
* TaskSignature - Manages digital signatures for field tasks
* Stores signature as base64 data or file reference
*/
class TaskSignature extends CommonDBTM
{
/**
* Rights management
* @var string
*/
    public static $rightname = 'plugin_newbase';

/**
* Enable history tracking
* @var bool
*/
    public $dohistory = true;

/**
* Maximum signature size in bytes (500KB)
* @var int
*/
    const MAX_SIGNATURE_SIZE = 512000;

/**
* Get type name
* @param int $nb Number of items
* @return string Type name
*/
    public static function getTypeName($nb = 0): string
    {
        return $nb > 1 ? __('Signatures', 'newbase') : __('Signature', 'newbase');
    }

/**
* Get table name
* @param string|null $classname Class name
* @return string Table name
*/
    public static function getTable($classname = null): string
    {
        return 'glpi_plugin_newbase_task_signatures';
    }

/**
* Get icon for menus
* @return string Icon class
*/
    public static function getIcon(): string
    {
        return 'ti ti-signature';
    }

/**
* Get signature for a task
* @param int $task_id Task ID
* @return array|null Signature data or null
*/
    public static function getForTask(int $task_id): ?array
    {
        global $DB;

        if ($task_id <= 0) {
            return null;
        }

        $iterator = $DB->request([
            'FROM' => self::getTable(),
            'WHERE' => [
                'plugin_newbase_tasks_id' => $task_id,
                'is_deleted' => 0,
            ],
            'LIMIT' => 1,
        ]);

        if (count($iterator)) {
            return $iterator->current();
        }

        return null;
    }

/**
* Save signature for a task
* @param int $task_id Task ID
* @param string $signature_data Base64 signature data
* @param string $signer_name Name of the person who signed
* @return int|false Signature ID or false on error
*/
    public static function saveSignature(int $task_id, string $signature_data, string $signer_name = ''): int|false
    {
        global $DB;

        // VALIDATIONS
        if ($task_id <= 0) {
            Toolbox::logInFile('newbase_plugin', "Invalid task_id for signature: {$task_id}\n");
            return false;
        }

        // Verify task exists
        $task = new Task();
        if (!$task->getFromDB($task_id)) {
            Toolbox::logInFile('newbase_plugin', "Task {$task_id} not found for signature\n");
            return false;
        }

        // Validate signature data format
        if (!self::validateSignatureData($signature_data)) {
            Session::addMessageAfterRedirect(
                __('Invalid signature format', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        // Check signature size
        $size = strlen($signature_data);
        if ($size > self::MAX_SIGNATURE_SIZE) {
            Session::addMessageAfterRedirect(
                sprintf(
                    __('Signature too large: %s KB (max: %s KB)', 'newbase'),
                    round($size / 1024, 2),
                    round(self::MAX_SIGNATURE_SIZE / 1024, 2)
                ),
                false,
                ERROR
            );
            return false;
        }

        // SAVE OR UPDATE
        $existing = self::getForTask($task_id);
        $timestamp = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');

        $data = [
            'plugin_newbase_tasks_id' => $task_id,
            'signature_data' => $signature_data,
            'signer_name' => $signer_name,
            'users_id' => Session::getLoginUserID(),
            'date_mod' => $timestamp,
        ];

        if ($existing) {
            // Update existing signature
            $result = $DB->update(
                self::getTable(),
                $data,
                ['id' => $existing['id']]
            );

            if ($result === false) {
                Toolbox::logInFile('newbase_plugin', "Failed to update signature for task {$task_id}\n");
                return false;
            }

            Toolbox::logInFile('newbase_plugin', "Signature updated for task {$task_id}\n");
            return $existing['id'];
        } else {
            // Insert new signature
            $data['date_creation'] = $timestamp;
            $data['is_deleted'] = 0;

            $result = $DB->insert(self::getTable(), $data);

            if ($result === false) {
                Toolbox::logInFile('newbase_plugin', "Failed to insert signature for task {$task_id}\n");
                return false;
            }

            Toolbox::logInFile('newbase_plugin', "Signature created for task {$task_id}\n");
            return $result;
        }
    }

/**
* Delete signature for a task
* @param int $task_id Task ID
* @return bool Success
*/
    public static function deleteSignature(int $task_id): bool
    {
        global $DB;

        if ($task_id <= 0) {
            return false;
        }

        $signature = self::getForTask($task_id);
        if (!$signature) {
            return true; // Already deleted
        }

        $result = $DB->update(
            self::getTable(),
            [
                'is_deleted' => 1,
                'date_mod' => $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s'),
            ],
            ['id' => $signature['id']]
        );

        if ($result === false) {
            Toolbox::logInFile('newbase_plugin', "Failed to delete signature for task {$task_id}\n");
            return false;
        }

        Toolbox::logInFile('newbase_plugin', "Signature deleted for task {$task_id}\n");
        return true;
    }

/**
* Validate signature data format
* @param string $signature_data Base64 signature data
* @return bool Valid format
*/
    private static function validateSignatureData(string $signature_data): bool
    {
        // Check if empty
        if (empty($signature_data)) {
            return false;
        }

        // Check if starts with data URI scheme
        if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $signature_data)) {
            Toolbox::logInFile('newbase_plugin', "Invalid signature format (not a data URI)\n");
            return false;
        }

        // Extract base64 data
        $base64_data = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $signature_data);

        // Validate base64
        if (!base64_decode($base64_data, true)) {
            Toolbox::logInFile('newbase_plugin', "Invalid base64 data in signature\n");
            return false;
        }

        return true;
    }

/**
* Display signature for a task
* @param Task $task Task item
* @return void
*/
    public static function showForTask(Task $task): void
    {
        $task_id = $task->getID();
        $signature = self::getForTask($task_id);
        $canedit = $task->canUpdate();

        echo "<div class='signature-container' style='margin-top: 20px;'>";

        if ($signature) {
            // SHOW EXISTING SIGNATURE
            echo "<div class='signature-display'>";
            echo "<h3>" . __('Digital Signature', 'newbase') . "</h3>";

            // Signature image
            echo "<div class='signature-image' style='border: 1px solid #ccc; padding: 10px; background: #fff; margin: 10px 0;'>";
            echo "<img src='" . htmlspecialchars($signature['signature_data']) . "'
                    alt='" . __('Signature', 'newbase') . "'
                    style='max-width: 100%; height: auto; max-height: 200px;'>";
            echo "</div>";

            // Signature metadata
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr>";
            echo "<th>" . __('Signer name', 'newbase') . "</th>";
            echo "<td>" . htmlspecialchars($signature['signer_name'] ?? '-') . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>" . __('Signed at', 'newbase') . "</th>";
            echo "<td>" . Html::convDateTime($signature['date_creation']) . "</td>";
            echo "</tr>";
            echo "</table>";

            // Delete button
            if ($canedit) {
                echo "<div style='margin-top: 10px;'>";
                echo "<button type='button' class='btn btn-danger' onclick='deleteSignature(" . $task_id . ")'>";
                echo "<i class='ti ti-trash'></i> " . __('Delete signature', 'newbase');
                echo "</button>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            // SHOW SIGNATURE CANVAS (UPLOAD)
            if ($canedit) {
                echo "<div class='signature-upload'>";
                echo "<h3>" . __('Digital Signature', 'newbase') . "</h3>";
                echo "<p>" . __('Draw signature below:', 'newbase') . "</p>";

                // Canvas for signature
                echo "<div style='border: 2px solid #000; margin: 10px 0; background: #fff;'>";
                echo "<canvas id='signature-canvas' width='500' height='200'
                        style='display: block; cursor: crosshair; touch-action: none;'></canvas>";
                echo "</div>";

                // Signer name input
                echo "<div style='margin: 10px 0;'>";
                echo "<label>" . __('Signer name', 'newbase') . ": </label>";
                echo Html::input('signer_name', [
                    'id' => 'signer-name',
                    'size' => 50,
                    'placeholder' => __('Full name of signer', 'newbase'),
                ]);
                echo "</div>";

                // Buttons
                echo "<div>";
                echo "<button type='button' class='btn btn-secondary' onclick='clearSignature()'>";
                echo "<i class='ti ti-eraser'></i> " . __('Clear', 'newbase');
                echo "</button> ";
                echo "<button type='button' class='btn btn-primary' onclick='saveSignature(" . $task_id . ")'>";
                echo "<i class='ti ti-check'></i> " . __('Save signature', 'newbase');
                echo "</button>";
                echo "</div>";

                echo "</div>";

                // JavaScript for signature canvas
                self::includeSignatureScript();
            } else {
                echo "<p class='text-muted'>" . __('No signature uploaded', 'newbase') . "</p>";
            }
        }

        echo "</div>";
    }

/**
* Include JavaScript for signature canvas
* @return void
*/
    private static function includeSignatureScript(): void
    {
        $js_path = PLUGIN_NEWBASE_DIR . '/js/signature.js';

        if (file_exists($js_path)) {
            echo "<script src='" . PLUGIN_NEWBASE_WEB_DIR . "/js/signature.js'></script>";
        } else {
            // Inline minimal signature script if file not found
            echo "<script>
                const canvas = document.getElementById('signature-canvas');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    let drawing = false;

                    canvas.addEventListener('mousedown', () => drawing = true);
                    canvas.addEventListener('mouseup', () => drawing = false);
                    canvas.addEventListener('mousemove', (e) => {
                        if (!drawing) return;
                        const rect = canvas.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        ctx.lineTo(x, y);
                        ctx.stroke();
                    });
                }

                function clearSignature() {
                    const canvas = document.getElementById('signature-canvas');
                    if (canvas) {
                        const ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                    }
                }

                function saveSignature(taskId) {
                    const canvas = document.getElementById('signature-canvas');
                    const signerName = document.getElementById('signer-name').value;

                    if (!canvas) return;

                    const signatureData = canvas.toDataURL('image/png');

                    // Send AJAX request
                    fetch('" . PLUGIN_NEWBASE_WEB_DIR . "/ajax/signatureUpload.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            task_id: taskId,
                            signature_data: signatureData,
                            signer_name: signerName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('" . __('Signature saved successfully', 'newbase') . "');
                            location.reload();
                        } else {
                            alert(data.message || '" . __('Error saving signature', 'newbase') . "');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('" . __('Error saving signature', 'newbase') . "');
                    });
                }

                function deleteSignature(taskId) {
                    if (!confirm('" . __('Delete signature?', 'newbase') . "')) return;

                    fetch('" . PLUGIN_NEWBASE_WEB_DIR . "/ajax/signatureUpload.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ task_id: taskId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('" . __('Signature deleted successfully', 'newbase') . "');
                            location.reload();
                        } else {
                            alert(data.message || '" . __('Error deleting signature', 'newbase') . "');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('" . __('Error deleting signature', 'newbase') . "');
                    });
                }
            </script>";
        }
    }

/**
* Check if signature is required for task completion
* @param Task $task Task
* @return bool Signature required
*/
    public static function isRequiredForCompletion(Task $task): bool
    {
        // Get plugin configuration
        $require_signature = Config::getConfigValue('require_signature', 0);

        // Check if signature is required globally
        if (!$require_signature) {
            return false;
        }

        // Check task status
        if ($task->fields['status'] === 'completed') {
            return true;
        }

        return false;
    }

/**
* Validate task can be completed (has signature if required)
* @param Task $task Task
* @return bool Can complete
*/
    public static function canCompleteTask(Task $task): bool
    {
        if (!self::isRequiredForCompletion($task)) {
            return true; // Signature not required
        }

        $signature = self::getForTask($task->getID());
        return $signature !== null;
    }
}
