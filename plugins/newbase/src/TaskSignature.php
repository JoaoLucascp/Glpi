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

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;
use Html;
use Toolbox;
use Plugin;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * TaskSignature - Manages digital signatures for field tasks
 * 
 * Features:
 * - Canvas-based signature drawing (mouse and touch support)
 * - Base64 PNG storage
 * - Size validation (max 500KB)
 * - Signature metadata (signer name, timestamp, user)
 * - Integration with Task completion workflow
 * - AJAX endpoints for save/delete operations
 *
 * @package GlpiPlugin\Newbase
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
    public const MAX_SIGNATURE_SIZE = 512000;

    /**
     * Canvas width in pixels
     * @var int
     */
    public const CANVAS_WIDTH = 600;

    /**
     * Canvas height in pixels
     * @var int
     */
    public const CANVAS_HEIGHT = 200;

    /**
     * Get type name
     * @param int $nb Number of items
     * @return string Type name
     */
    public static function getTypeName($nb = 0): string
    {
        return _n('Signature', 'Signatures', $nb, 'newbase');
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
     * Get icon for menus (Tabler Icons)
     * @return string Icon class
     */
    public static function getIcon(): string
    {
        return 'ti ti-signature';
    }

    /**
     * Get signature for a task
     * @param int $task_id Task ID
     * @return array|null Signature data or null if not found
     */
    public static function getForTask(int $task_id): ?array
    {
        global $DB;

        if ($task_id <= 0) {
            return null;
        }

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'plugin_newbase_tasks_id' => $task_id,
                'is_deleted'              => 0,
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
     * @param int    $task_id        Task ID
     * @param string $signature_data Base64 signature data (data URI)
     * @param string $signer_name    Name of the person who signed
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

        // Sanitize signer name
        $signer_name = trim($signer_name);
        if (empty($signer_name)) {
            $signer_name = __('Unknown', 'newbase');
        }

        // SAVE OR UPDATE
        $existing = self::getForTask($task_id);
        $timestamp = $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s');

        $data = [
            'plugin_newbase_tasks_id' => $task_id,
            'signature_data'          => $signature_data,
            'signer_name'             => $signer_name,
            'users_id'                => Session::getLoginUserID(),
            'date_mod'                => $timestamp,
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

            // Update task signature_data field
            $task->update([
                'id'             => $task_id,
                'signature_data' => $signature_data,
            ]);

            Toolbox::logInFile('newbase_plugin', "Signature updated for task {$task_id} by user " . Session::getLoginUserID() . "\n");
            return $existing['id'];
        } else {
            // Insert new signature
            $data['date_creation'] = $timestamp;
            $data['is_deleted']    = 0;

            $result = $DB->insert(self::getTable(), $data);

            if ($result === false) {
                Toolbox::logInFile('newbase_plugin', "Failed to insert signature for task {$task_id}\n");
                return false;
            }

            // Update task signature_data field
            $task->update([
                'id'             => $task_id,
                'signature_data' => $signature_data,
            ]);

            Toolbox::logInFile('newbase_plugin', "Signature created for task {$task_id} by user " . Session::getLoginUserID() . "\n");
            return $result;
        }
    }

    /**
     * Delete signature for a task (soft delete)
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
                'date_mod'   => $_SESSION['glpi_currenttime'] ?? date('Y-m-d H:i:s'),
            ],
            ['id' => $signature['id']]
        );

        if ($result === false) {
            Toolbox::logInFile('newbase_plugin', "Failed to delete signature for task {$task_id}\n");
            return false;
        }

        // Clear task signature_data field
        $task = new Task();
        if ($task->getFromDB($task_id)) {
            $task->update([
                'id'             => $task_id,
                'signature_data' => null,
            ]);
        }

        Toolbox::logInFile('newbase_plugin', "Signature deleted for task {$task_id}\n");
        return true;
    }

    /**
     * Validate signature data format (must be a PNG data URI)
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

        echo "<div class='signature-container card mt-3'>";
        echo "<div class='card-header'>";
        echo "<h3><i class='" . self::getIcon() . "'></i> " . __('Digital Signature', 'newbase') . "</h3>";
        echo "</div>";
        echo "<div class='card-body'>";

        if ($signature) {
            // SHOW EXISTING SIGNATURE
            echo "<div class='signature-display'>";

            // Signature image
            echo "<div class='signature-image border p-3 mb-3 bg-white text-center'>";
            echo "<img src='" . htmlspecialchars($signature['signature_data'], ENT_QUOTES, 'UTF-8') . "' ";
            echo "alt='" . __('Signature', 'newbase') . "' ";
            echo "class='img-fluid' style='max-height: 200px;'>";
            echo "</div>";

            // Signature metadata
            echo "<table class='table table-bordered'>";
            echo "<tr>";
            echo "<th style='width: 30%;'>" . __('Signer name', 'newbase') . "</th>";
            echo "<td>" . htmlspecialchars($signature['signer_name'] ?? '-', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>" . __('Signed at', 'newbase') . "</th>";
            echo "<td>" . Html::convDateTime($signature['date_creation']) . "</td>";
            echo "</tr>";
            if (!empty($signature['users_id'])) {
                echo "<tr>";
                echo "<th>" . __('Signed by user', 'newbase') . "</th>";
                echo "<td>" . getUserName($signature['users_id']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Delete button
            if ($canedit) {
                echo "<div class='mt-3'>";
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
                echo "<p>" . __('Draw your signature below:', 'newbase') . "</p>";

                // Canvas for signature
                echo "<div class='border border-dark mb-3 bg-white'>";
                echo "<canvas id='signature-canvas' ";
                echo "width='" . self::CANVAS_WIDTH . "' ";
                echo "height='" . self::CANVAS_HEIGHT . "' ";
                echo "style='display: block; cursor: crosshair; touch-action: none;'>";
                echo "</canvas>";
                echo "</div>";

                // Signer name input
                echo "<div class='mb-3'>";
                echo "<label for='signer-name' class='form-label'>" . __('Signer name', 'newbase') . " <span class='red'>*</span></label>";
                echo Html::input('signer_name', [
                    'id'          => 'signer-name',
                    'class'       => 'form-control',
                    'size'        => 50,
                    'placeholder' => __('Full name of signer', 'newbase'),
                    'required'    => true,
                ]);
                echo "</div>";

                // Buttons
                echo "<div class='d-flex gap-2'>";
                echo "<button type='button' class='btn btn-secondary' onclick='clearSignature()'>";
                echo "<i class='ti ti-eraser'></i> " . __('Clear', 'newbase');
                echo "</button>";
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

        echo "</div>"; // .card-body
        echo "</div>"; // .signature-container
    }

    /**
     * Include JavaScript for signature canvas
     * @return void
     */
    private static function includeSignatureScript(): void
    {
        $plugin_web_dir = Plugin::getWebDir('newbase');

        echo "<script>
        (function() {
            const canvas = document.getElementById('signature-canvas');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let drawing = false;
            let lastX = 0;
            let lastY = 0;

            // Configure drawing style
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            // Mouse events
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Touch events (mobile support)
            canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
            canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(e) {
                drawing = true;
                const rect = canvas.getBoundingClientRect();
                lastX = e.clientX - rect.left;
                lastY = e.clientY - rect.top;
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
            }

            function draw(e) {
                if (!drawing) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ctx.lineTo(x, y);
                ctx.stroke();
                lastX = x;
                lastY = y;
            }

            function stopDrawing() {
                drawing = false;
            }

            function handleTouchStart(e) {
                e.preventDefault();
                const touch = e.touches[0];
                const rect = canvas.getBoundingClientRect();
                drawing = true;
                lastX = touch.clientX - rect.left;
                lastY = touch.clientY - rect.top;
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
            }

            function handleTouchMove(e) {
                e.preventDefault();
                if (!drawing) return;
                const touch = e.touches[0];
                const rect = canvas.getBoundingClientRect();
                const x = touch.clientX - rect.left;
                const y = touch.clientY - rect.top;
                ctx.lineTo(x, y);
                ctx.stroke();
                lastX = x;
                lastY = y;
            }

            // Global functions
            window.clearSignature = function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            };

            window.saveSignature = function(taskId) {
                const signerName = document.getElementById('signer-name').value.trim();

                if (!signerName) {
                    alert('" . __('Please enter the signer name', 'newbase') . "');
                    document.getElementById('signer-name').focus();
                    return;
                }

                const signatureData = canvas.toDataURL('image/png');

                // Check if canvas is empty
                const blankCanvas = document.createElement('canvas');
                blankCanvas.width = canvas.width;
                blankCanvas.height = canvas.height;
                if (signatureData === blankCanvas.toDataURL('image/png')) {
                    alert('" . __('Please draw a signature first', 'newbase') . "');
                    return;
                }

                // Get CSRF token
                const csrfToken = document.querySelector('input[name=\"_glpi_csrf_token\"]')?.value || '';

                // Send AJAX request
                fetch('{$plugin_web_dir}/ajax/signatureUpload.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Glpi-Csrf-Token': csrfToken
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        signature_data: signatureData,
                        signer_name: signerName,
                        _glpi_csrf_token: csrfToken
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
            };

            window.deleteSignature = function(taskId) {
                if (!confirm('" . __('Are you sure you want to delete this signature?', 'newbase') . "')) {
                    return;
                }

                // Get CSRF token
                const csrfToken = document.querySelector('input[name=\"_glpi_csrf_token\"]')?.value || '';

                fetch('{$plugin_web_dir}/ajax/signatureUpload.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Glpi-Csrf-Token': csrfToken
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        _glpi_csrf_token: csrfToken
                    })
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
            };
        })();
        </script>";
    }

    /**
     * Check if signature is required for task completion
     * @param Task $task Task
     * @return bool Signature required
     */
    public static function isRequiredForCompletion(Task $task): bool
    {
        // Get plugin configuration
        $config = Config::getConfig();
        $require_signature = $config['require_signature'] ?? 0;

        // Check if signature is required globally
        if (!$require_signature) {
            return false;
        }

        // Check task status - require signature for completed tasks
        return in_array($task->fields['status'], ['completed', 'cancelled'], true);
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

    /**
     * Get tab name for item
     * @param CommonGLPI $item Item
     * @param int $withtemplate Template mode
     * @return string|array Tab name
     */
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item instanceof Task) {
            return self::createTabEntry(self::getTypeName(1));
        }
        return '';
    }

    /**
     * Display tab content for item
     * @param CommonGLPI $item Item
     * @param int $tabnum Tab number
     * @param int $withtemplate Template mode
     * @return bool Success
     */
    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0): bool
    {
        if ($item instanceof Task) {
            self::showForTask($item);
            return true;
        }
        return false;
    }
}
