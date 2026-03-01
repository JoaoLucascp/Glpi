/**
 * ╔═══════════════════════════════════════════════════════════════╗
 * ║ NEWBASE PLUGIN - newbase.js                                   ║
 * ║ UI global: SweetAlert2, duplicidade, utilidades               ║
 * ║ Compatível: GLPI 10.0.20+ | Bootstrap 5 | Tabler             ║
 * ╚═══════════════════════════════════════════════════════════════╝
 */

(function ($) {
    'use strict';

    window.Newbase = window.Newbase || {};

    // ────────────────────────────────────────────────────────────
    //  1. Carregar SweetAlert2 via CDN (se ainda não presente)
    // ────────────────────────────────────────────────────────────
    Newbase.loadSwal = function (callback) {
        if (typeof Swal !== 'undefined') {
            if (typeof callback === 'function') callback();
            return;
        }
        // CSS
        if (!$('link[href*="sweetalert2"]').length) {
            $('<link>', {
                rel: 'stylesheet',
                href: 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'
            }).appendTo('head');
        }
        // JS
        $.getScript(
            'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
            function () {
                if (typeof callback === 'function') callback();
            }
        ).fail(function () {
            console.warn('[NEWBASE] SweetAlert2 não pôde ser carregado.');
        });
    };

    // ────────────────────────────────────────────────────────────
    //  2. Notificação centralizada (SweetAlert2 > GLPI > alert)
    // ────────────────────────────────────────────────────────────
    Newbase.notify = function (msg, type, opts) {
        type = type || 'info';
        opts = opts || {};

        if (typeof Swal !== 'undefined') {
            Swal.fire($.extend({
                icon              : type,
                text              : msg,
                toast             : true,
                position          : 'top-end',
                timer             : type === 'success' ? 2500 : (type === 'info' ? 2000 : 0),
                showConfirmButton : type === 'error' || type === 'warning',
                confirmButtonColor: '#206bc4'
            }, opts));
            return;
        }

        if (type === 'success' && typeof glpi_toast_info !== 'undefined') {
            glpi_toast_info(msg);
        } else if ((type === 'error' || type === 'warning') && typeof glpi_toast_error !== 'undefined') {
            glpi_toast_error(msg);
        } else {
            alert(msg);
        }
    };

    // ────────────────────────────────────────────────────────────
    //  3. Confirmação via SweetAlert2
    // ────────────────────────────────────────────────────────────
    Newbase.confirm = function (title, text, confirmText, cancelText, onConfirm, onCancel) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title             : title || 'Confirmar?',
                text              : text  || '',
                icon              : 'question',
                showCancelButton  : true,
                confirmButtonText : confirmText || 'Confirmar',
                cancelButtonText  : cancelText  || 'Cancelar',
                confirmButtonColor: '#206bc4',
                cancelButtonColor : '#6c757d',
                reverseButtons    : true
            }).then(function (result) {
                if (result.isConfirmed && typeof onConfirm === 'function') {
                    onConfirm();
                } else if (!result.isConfirmed && typeof onCancel === 'function') {
                    onCancel();
                }
            });
        } else {
            if (confirm(title + '\n' + text)) {
                if (typeof onConfirm === 'function') onConfirm();
            } else {
                if (typeof onCancel === 'function') onCancel();
            }
        }
    };

    // ────────────────────────────────────────────────────────────
    //  4. Verificação de duplicidade (CNPJ já cadastrado)
    //     Executada antes do submit do formulário principal
    // ────────────────────────────────────────────────────────────
    Newbase.checkDuplicate = function ($form) {
        var cnpj      = $form.find('[name="cnpj"]').val();
        var currentId = $form.find('[name="id"]').val() || 0;

        if (!cnpj) return $.Deferred().resolve(false).promise();

        var dfd = $.Deferred();

        $.ajax({
            type    : 'POST',
            url     : (CFG_GLPI || {}).root_doc + '/plugins/newbase/ajax/searchCompany.php',
            data    : {
                check_duplicate   : 1,
                cnpj              : cnpj,
                current_id        : currentId,
                _glpi_csrf_token  : $('[name="_glpi_csrf_token"]').first().val()
            },
            dataType: 'json',
            timeout : 8000,
            success : function (res) {
                dfd.resolve(res.duplicate === true);
            },
            error   : function () {
                // Em caso de erro na verificação, deixa o submit continuar
                dfd.resolve(false);
            }
        });

        return dfd.promise();
    };

    // ────────────────────────────────────────────────────────────
    //  5. Interceptar submit do formulário principal de empresa
    //     para checar duplicidade antes de enviar
    // ────────────────────────────────────────────────────────────
    $(document).on('submit', 'form:has([name="cnpj"])', function (e) {
        var $form      = $(this);
        var $actionBtn = $form.find('[name="add"]:clicked, [name="update"]:clicked,'
                       + 'input[type="submit"][name="add"], input[type="submit"][name="update"],'
                       + 'button[type="submit"][name="add"], button[type="submit"][name="update"]');

        // Detectar qual botão de ação está presente no formulário
        var $addBtn    = $form.find('[name="add"]');
        var $updateBtn = $form.find('[name="update"]');

        // Só intercepta se há botão add ou update — ignora delete/purge
        if (!$addBtn.length && !$updateBtn.length) return;

        e.preventDefault();

        var $submitBtn = $form.find('[type="submit"]').first();
        $submitBtn.prop('disabled', true);

        // Função para realmente enviar o formulário,
        // garantindo que o campo add/update chegue no POST
        function doSubmit() {
            // Remover hidden temporário anterior se existir
            $form.find('#_nb_action_field').remove();

            // Determinar qual ação usar
            var actionName  = $addBtn.length ? 'add' : 'update';
            var actionValue = $addBtn.length
                ? ($addBtn.val() || '1')
                : ($updateBtn.val() || '1');

            // Injetar campo hidden com o valor do botão
            $('<input>').attr({
                type  : 'hidden',
                id    : '_nb_action_field',
                name  : actionName,
                value : actionValue
            }).appendTo($form);

            $form[0].submit();
        }

        Newbase.checkDuplicate($form).done(function (isDuplicate) {
            if (isDuplicate) {
                $submitBtn.prop('disabled', false);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon              : 'warning',
                        title             : 'CNPJ Duplicado',
                        html              : 'Este CNPJ já está cadastrado no sistema.<br>Deseja continuar assim mesmo?',
                        showCancelButton  : true,
                        confirmButtonText : 'Sim, salvar mesmo assim',
                        cancelButtonText  : 'Cancelar',
                        confirmButtonColor: '#f59f00',
                        cancelButtonColor : '#6c757d'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $submitBtn.prop('disabled', true);
                            doSubmit();
                        }
                    });
                } else {
                    if (confirm('CNPJ duplicado. Deseja continuar?')) {
                        doSubmit();
                    }
                }
            } else {
                doSubmit();
            }
        });
    });

    // ────────────────────────────────────────────────────────────
    //  6. Inicialização ao carregar o DOM
    // ────────────────────────────────────────────────────────────
    $(document).ready(function () {
        // Carrega SweetAlert2 em background para ter pronto quando precisar
        Newbase.loadSwal();

        // Tooltip Bootstrap 5 para elementos com data-bs-toggle="tooltip"
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        }

        console.log('[NEWBASE] newbase.js inicializado (v2.2.0)');
    });

})(jQuery);
