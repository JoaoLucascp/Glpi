<?php

/**
* TaskSignature class
* Gerencia assinaturas digitais para tarefas com funcionalidade de upload e exibição
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.1.0
*/

namespace GlpiPlugin\Newbase;

use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\Common;
use Session;
use Html;
use Toolbox;

/**
* Undocumented class
*/
class TaskSignature extends Common
{
    // Nome correto para permissões
    public static $rightname = 'plugin_newbase_task';

    // Ativar histórico
    public $dohistory = true;

    /**
    * Obter nome da tabela
    * @param string|null $classname Nome da classe
    * @return string
    */
    public static function getTable($classname = null): string
    {
        if ($classname !== null && $classname !== self::class) {
            return parent::getTable($classname);
        }
        return 'glpi_plugin_newbase_tasksignature';
    }

    /**
    * Obter nome do tipo
    * @param int $nb Numero do item
    * @return string
    */
    public static function getTypeName($nb = 0): string
    {
        return _n('Digital Signature', 'Digital Signatures', $nb, 'newbase');
    }

    /**
    * Obter o nome do campo de chave estrangeira
    * @return string
    */
    public static function getForeignKeyField(): string
    {
        return 'plugin_newbase_tasksignature_id';
    }

    /**
    * Verificar se o usuário pode visualizar o item
    * @return bool
    */
    public static function canView(): bool
    {
        return (bool) Session::haveRight(self::$rightname, READ);
    }

    /**
    * Verificar se o usuário pode criar um item
    * @return bool
    */
    public static function canCreate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, CREATE);
    }

    /**
    * Verificar se o usuário pode atualizar o item
    * @return bool
    */
    public static function canUpdate(): bool
    {
        return (bool) Session::haveRight(self::$rightname, UPDATE);
    }

    /**
    * Verificar se o usuário pode excluir o item
    * @return bool
    */
    public static function canDelete(): bool
    {
        return (bool) Session::haveRight(self::$rightname, DELETE);
    }

    /**
    * Obter assinatura para a tarefa
    * @param int $task_id ID da tarefa
    * @return array|null Dados de assinatura ou nulos
    */
    public static function getForTask(int $task_id): ?array
    {
        global $DB;

        $iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['plugin_newbase_task_id' => $task_id],
            'LIMIT' => 1,
        ]);

        if (count($iterator)) {
            return $iterator->current();
        }

        return null;
    }

    /**
    * Exibir assinatura da tarefa
    * @param Task $task Item tarefa
    * @return void
    */
    public static function showForTask(Task $task): void
    {
        global $CFG_GLPI;

        $task_id = (int) $task->getID();
        $signature = self::getForTask($task_id);

        echo "<div class='spaced'>";
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr class='tab_bg_2'>";
        echo "<th colspan='2'>" . self::getTypeName(1) . "</th>";
        echo "</tr>";

        if ($signature) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Signature', 'newbase') . "</td>";
            echo "<td>";

            // Exibir imagem de assinatura
            if (!empty($signature['signature_data'])) {
                echo "<img src='data:" . $signature['signature_mime'] . ";base64," . base64_encode($signature['signature_data']) . "' ";
                echo "alt='" . __('Digital Signature', 'newbase') . "' style='max-width: 400px; border: 1px solid #ccc; padding: 5px;' />";
            }

            echo "</td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Uploaded at', 'newbase') . "</td>";
            echo "<td>" . Html::convDateTime($signature['date_creation']) . "</td>";
            echo "</tr>";

            if ($task->canUpdate()) {
                echo "<tr class='tab_bg_1'>";
                echo "<td colspan='2' class='center'>";
                echo "<button type='button' id='update_signature' class='btn btn-primary'>";
                echo "<i class='fas fa-pen'></i> " . __('Update Signature', 'newbase');
                echo "</button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='2' class='center'>";
            echo __('No signature uploaded', 'newbase');
            echo "</td>";
            echo "</tr>";

            if ($task->canUpdate()) {
                echo "<tr class='tab_bg_1'>";
                echo "<td colspan='2' class='center'>";
                echo "<button type='button' id='add_signature' class='btn btn-primary'>";
                echo "<i class='fas fa-signature'></i> " . __('Add Signature', 'newbase');
                echo "</button>";
                echo "</td>";
                echo "</tr>";
            }
        }

        echo "</table>";

        // modal de tela de assinatura
        if ($task->canUpdate()) {
            echo "<div id='signature_modal' style='display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999;'>";
            echo "<div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:20px; border-radius:8px;'>";
            echo "<h3>" . __('Draw your signature', 'newbase') . "</h3>";
            echo "<canvas id='signature_canvas' width='500' height='200' style='border:2px solid #333; cursor:crosshair;'></canvas>";
            echo "<br><br>";
            echo "<button type='button' id='clear_signature' class='btn btn-secondary'>";
            echo "<i class='fas fa-eraser'></i> " . __('Clear', 'newbase');
            echo "</button>&nbsp;";
            echo "<button type='button' id='save_signature' class='btn btn-success'>";
            echo "<i class='fas fa-save'></i> " . __('Save', 'newbase');
            echo "</button>&nbsp;";
            echo "<button type='button' id='cancel_signature' class='btn btn-danger'>";
            echo "<i class='fas fa-times'></i> " . __('Cancel', 'newbase');
            echo "</button>";
            echo "</div>";
            echo "</div>";
        }

        echo "</div>";

        // Adicione JavaScript para lidar com assinaturas
        if ($task->canUpdate()) {
            echo Html::scriptBlock("
                var canvas, ctx, isDrawing = false;
                var lastX = 0, lastY = 0;

                function initSignatureCanvas() {
                    canvas = document.getElementById('signature_canvas');
                    ctx = canvas.getContext('2d');
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';

                    canvas.addEventListener('mousedown', startDrawing);
                    canvas.addEventListener('mousemove', draw);
                    canvas.addEventListener('mouseup', stopDrawing);
                    canvas.addEventListener('mouseout', stopDrawing);

                    // Touch events for mobile
                    canvas.addEventListener('touchstart', function(e) {
                        e.preventDefault();
                        var touch = e.touches[0];
                        var rect = canvas.getBoundingClientRect();
                        lastX = touch.clientX - rect.left;
                        lastY = touch.clientY - rect.top;
                        isDrawing = true;
                    });

                    canvas.addEventListener('touchmove', function(e) {
                        e.preventDefault();
                        if (!isDrawing) return;
                        var touch = e.touches[0];
                        var rect = canvas.getBoundingClientRect();
                        var x = touch.clientX - rect.left;
                        var y = touch.clientY - rect.top;
                        ctx.beginPath();
                        ctx.moveTo(lastX, lastY);
                        ctx.lineTo(x, y);
                        ctx.stroke();
                        lastX = x;
                        lastY = y;
                    });

                    canvas.addEventListener('touchend', function(e) {
                        e.preventDefault();
                        isDrawing = false;
                    });
                }

                function startDrawing(e) {
                    isDrawing = true;
                    var rect = canvas.getBoundingClientRect();
                    lastX = e.clientX - rect.left;
                    lastY = e.clientY - rect.top;
                }

                function draw(e) {
                    if (!isDrawing) return;
                    var rect = canvas.getBoundingClientRect();
                    var x = e.clientX - rect.left;
                    var y = e.clientY - rect.top;

                    ctx.beginPath();
                    ctx.moveTo(lastX, lastY);
                    ctx.lineTo(x, y);
                    ctx.stroke();

                    lastX = x;
                    lastY = y;
                }

                function stopDrawing() {
                    isDrawing = false;
                }

                function clearCanvas() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }

                $(document).ready(function() {
                    $('#add_signature, #update_signature').on('click', function() {
                        $('#signature_modal').show();
                        initSignatureCanvas();
                    });

                    $('#clear_signature').on('click', function() {
                        clearCanvas();
                    });

                    $('#cancel_signature').on('click', function() {
                        $('#signature_modal').hide();
                    });

                    $('#save_signature').on('click', function() {
                        var imageData = canvas.toDataURL('image/png');

                        $.ajax({
                            url: '" . $CFG_GLPI['root_doc'] . "/plugins/newbase/ajax/signatureUpload.php',
                            type: 'POST',
                            data: {
                                task_id: " . $task_id . ",
                                signature: imageData,
                                _glpi_csrf_token: $('input[name=_glpi_csrf_token]').val()
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.success) {
                                    alert('" . __('Signature saved successfully', 'newbase') . "');
                                    location.reload();
                                } else {
                                    alert(data.message || '" . __('Error saving signature', 'newbase') . "');
                                }
                            },
                            error: function() {
                                alert('" . __('Error connecting to server', 'newbase') . "');
                            }
                        });
                    });
                });
            ");
        }
    }

    /**
    * Prepare a entrada para adição
    * @param array $input Dados de entrada
    * @return array|false
    */
    public function prepareInputForAdd($input)
    {
        // Validate task ID
        if (empty($input['plugin_newbase_task_id'])) {
            Session::addMessageAfterRedirect(
                __('Task is required', 'newbase'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }

    /**
    * Ações realizadas após a adição
    * @return void
    */
    public function post_addItem(): void
    {
        Toolbox::logInFile(
            'newbase_plugin',
            "Signature added for task ID: " . $this->fields['plugin_newbase_task_id'] . "\n"
        );
    }

    /**
    * Ações realizadas após a atualização
    * @return void
    */
    public function post_updateItem($history = true): void
    {
        Toolbox::logInFile(
            'newbase_plugin',
            "Signature updated for task ID: " . $this->fields['plugin_newbase_task_id'] . "\n"
        );
    }
}
