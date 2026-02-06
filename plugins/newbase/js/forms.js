/**
 * ╔═══════════════════════════════════════════════════════════════╗
 * ║ NEWBASE PLUGIN - FORMS.JS - AUTO-PREENCHIMENTO               ║
 * ║ Compatível: GLPI 10.0.20+ | jQuery nativo                    ║
 * ╚═══════════════════════════════════════════════════════════════╝
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        console.warn('[NEWBASE] Inicializando forms.js');

        // Inicializar busca CNPJ
        initCNPJSearch();

        // Inicializar busca CEP
        initCEPSearch();
    });

    /**
     * Busca CNPJ com auto-preenchimento
     */
    function initCNPJSearch() {
        // Selecionar botão de busca (lupa)
        const $cnpjButton = $('[data-action="search-cnpj"]');

        if (!$cnpjButton.length) {
            console.warn('[NEWBASE] Botão CNPJ não encontrado');
            return;
        }

        $cnpjButton.off('click').on('click', function (e) {
            e.preventDefault();

            const $input = $('[name="cnpj"]');
            const cnpj = $input.val();

            if (!cnpj) {
                alert('Digite um CNPJ');
                return;
            }

            // Mostrar loading
            showLoading($cnpjButton, true);

            $.ajax({
                type: 'POST',
                url: CFG_GLPI.root_doc + '/plugins/newbase/ajax/cnpj_proxy.php',
                data: {
                    action: 'searchCNPJ',
                    cnpj: cnpj,
                    glpi_csrf_token: $('[name="glpi_csrf_token"]').val()
                },
                dataType: 'json',
                timeout: 10000,
                success: function (response) {
                    if (response.success) {
                        // Preencher campos
                        $('[name="company_name"]').val(response.company_name);
                        $('[name="phone"]').val(response.phone);
                        $('[name="address"]').val(response.address);
                        $('[name="city"]').val(response.city);
                        $('[name="state"]').val(response.state);
                        $('[name="zip_code"]').val(response.zip_code);

                        console.warn('[NEWBASE] CNPJ preenchido com sucesso');
                        showNotification('Dados preenchidos com sucesso!', 'success');
                    } else {
                        console.error('[NEWBASE] Erro:', response.error);
                        showNotification(response.error || 'Erro ao buscar CNPJ', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('[NEWBASE] AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    showNotification('Erro ao conectar com o servidor', 'error');
                },
                complete: function () {
                    showLoading($cnpjButton, false);
                }
            });
        });
    }

    /**
     * Busca CEP com auto-preenchimento
     */
    function initCEPSearch() {
        const $cepButton = $('[data-action="search-cep"]');

        if (!$cepButton.length) {
            console.warn('[NEWBASE] Botão CEP não encontrado');
            return;
        }

        $cepButton.off('click').on('click', function (e) {
            e.preventDefault();

            const $input = $('[name="zip_code"]');
            const cep = $input.val();

            if (!cep) {
                alert('Digite um CEP');
                return;
            }

            showLoading($cepButton, true);

            $.ajax({
                type: 'POST',
                url: CFG_GLPI.root_doc + '/plugins/newbase/ajax/cnpj_proxy.php',
                data: {
                    action: 'searchCEP',
                    cep: cep,
                    glpi_csrf_token: $('[name="glpi_csrf_token"]').val()
                },
                dataType: 'json',
                timeout: 10000,
                success: function (response) {
                    if (response.success) {
                        $('[name="address"]').val(response.address);
                        $('[name="district"]').val(response.district);
                        $('[name="city"]').val(response.city);
                        $('[name="state"]').val(response.state);

                        console.warn('[NEWBASE] CEP preenchido com sucesso');
                        showNotification('Endereço preenchido com sucesso!', 'success');
                    } else {
                        showNotification(response.error || 'CEP não encontrado', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('[NEWBASE] AJAX Error:', {
                        status: status,
                        error: error
                    });
                    showNotification('Erro ao buscar CEP', 'error');
                },
                complete: function () {
                    showLoading($cepButton, false);
                }
            });
        });
    }

    /**
     * Mostrar/Ocultar loading
     */
    function showLoading($button, show) {
        if (show) {
            $button.prop('disabled', true)
                .addClass('loading')
                .html('<i class="fas fa-spinner fa-spin"></i>');
        } else {
            $button.prop('disabled', false)
                .removeClass('loading')
                .html('<i class="fas fa-search"></i>');
        }
    }

    /**
     * Mostrar notificação
     */
    function showNotification(message, type) {
        // Usar GLPI native notification
        if (typeof glpi_html_dialog !== 'undefined') {
            glpi_html_dialog({
                title: type === 'success' ? 'Sucesso' : 'Erro',
                message: message,
                class: type === 'success' ? 'alert-success' : 'alert-danger'
            });
        } else {
            // Fallback
            alert(message);
        }
    }

})(jQuery);