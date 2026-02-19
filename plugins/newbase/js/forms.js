/**
 * ╔═══════════════════════════════════════════════════════════════╗
 * ║ NEWBASE PLUGIN - FORMS.JS - AUTO-PREENCHIMENTO               ║
 * ║ Compatível: GLPI 10.0.20+ | jQuery nativo                    ║
 * ╚═══════════════════════════════════════════════════════════════╝
 */

(function ($) {
    'use strict';

    /**
     * Função auxiliar para obter token CSRF de múltiplas fontes (GLPI 10.0.20+)
     * @returns {string} Token CSRF ou string vazia
     */
    function getCSRFToken() {
        let token = '';

        // 1: Campo hidden do formulário (mais confiável)
        token = $('[name="_glpi_csrf_token"]').first().val();
        if (token) {
            console.log('[NEWBASE] CSRF Token (hidden): ' + token.substring(0, 10) + '...');
            return token;
        }

        // 2: Meta tag do GLPI
        token = $('meta[name="glpi:csrf_token"]').attr('content');
        if (token) {
            console.log('[NEWBASE] CSRF Token (meta): ' + token.substring(0, 10) + '...');
            return token;
        }

        // 3: Data attribute
        token = $('body').data('csrf-token') || $('html').data('csrf-token');
        if (token) {
            console.log('[NEWBASE] CSRF Token (data-*): ' + token.substring(0, 10) + '...');
            return token;
        }

        // 4: Qualquer input hidden com “token” no nome
        token = $('input[type="hidden"][name*="token"]').first().val();
        if (token) {
            console.log('[NEWBASE] CSRF Token (genérico): ' + token.substring(0, 10) + '...');
            return token;
        }

        // 5: Função global do GLPI (se existir)
        if (typeof getGlPICsrfToken === 'function') {
            try {
                token = getGlPICsrfToken();
                if (token) {
                    console.log('[NEWBASE] CSRF Token (getGlPICsrfToken): ' + token.substring(0, 10) + '...');
                    return token;
                }
            } catch (e) {
                console.warn('[NEWBASE] Erro em getGlPICsrfToken():', e);
            }
        }

        console.warn('[NEWBASE] CSRF Token NÃO encontrado! Requisições POST podem falhar.');
        return '';
    }

    $(document).ready(function () {
        console.log('[NEWBASE] Inicializando forms.js');

        // Não precisamos mais “achar” os botões na carga;
        // usamos delegação de eventos.
        initCNPJSearch();
        initCEPSearch();
    });

    /**
     * Busca CNPJ com auto-preenchimento via searchCompany.php
     */
    /**
     * Busca CNPJ com auto-preenchimento via searchCompany.php
     * COM RETRY AUTOMÁTICO (3 tentativas)
     */
    function initCNPJSearch() {
        // Delegação: qualquer botão com data-action="search-cnpj"
        $(document)
            .off('click.newbase-cnpj', 'button[data-action="search-cnpj"]')
            .on('click.newbase-cnpj', 'button[data-action="search-cnpj"]', function (e) {
                e.preventDefault();

                const $cnpjButton = $(this);
                const $input = $('[name="cnpj"]');
                const cnpj = $input.val();

                if (!cnpj) {
                    alert('Digite um CNPJ');
                    return;
                }

                console.log('[NEWBASE] Buscando CNPJ:', cnpj);
                showLoading($cnpjButton, true); // Desabilita botão visualmente

                const csrfToken = getCSRFToken();
                if (!csrfToken) {
                    console.error('[NEWBASE] CSRF Token não encontrado!');
                    showNotification('Erro de segurança: CSRF Token ausente. Recarregue a página.', 'error');
                    showLoading($cnpjButton, false);
                    return;
                }

                // Configuração da requisição com Retry
                let attempts = 0;
                const maxAttempts = 3;

                function performSearch() {
                    attempts++;

                    $.ajax({
                        type: 'POST',
                        url: CFG_GLPI.root_doc + '/plugins/newbase/ajax/searchCompany.php',
                        data: {
                            cnpj: cnpj,
                            _glpi_csrf_token: csrfToken
                        },
                        dataType: 'json',
                        timeout: 15000,
                        headers: {
                            'X-Glpi-Csrf-Token': csrfToken
                        },
                        success: function (response) {
                            console.log('[NEWBASE] Resposta CNPJ:', response);

                            if (response.success && response.data) {
                                const data = response.data;

                                // Nomes de campos conforme searchCompany.php
                                $('[name="name"]').val(data.legal_name || data.corporate_name || '');
                                $('[name="corporate_name"]').val(data.legal_name || '');
                                $('[name="fantasy_name"]').val(data.fantasy_name || '');
                                $('[name="email"]').val(data.email || '');
                                $('[name="phone"]').val(data.phone || '');

                                let endereco = data.street || '';
                                if (data.number) {
                                    endereco += (endereco ? ', ' : '') + data.number;
                                }
                                if (data.complement) {
                                    endereco += ' ' + data.complement;
                                }

                                $('[name="address"]').val(endereco.trim());
                                $('[name="city"]').val(data.city || '');
                                $('[name="state"]').val(data.state || '');
                                $('[name="cep"]').val(data.postcode || '');

                                console.log('[NEWBASE] Campos preenchidos com sucesso');
                                showNotification('Dados da empresa preenchidos com sucesso!', 'success');
                                showLoading($cnpjButton, false); // Sucesso final: reabilita botão
                            } else {
                                // Erro de negócio (API respondeu, mas com erro lógico - ex: CNPJ inválido)
                                // NÃO faz retry aqui, pois a resposta é definitiva
                                console.error('[NEWBASE] Erro na resposta:', response.message);
                                showNotification(response.message || 'Erro ao buscar CNPJ', 'error');
                                showLoading($cnpjButton, false);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(`[NEWBASE] AJAX Error (Tentativa ${attempts}/${maxAttempts}):`, status);

                            // Se for erro recuperável (timeout ou erro de servidor 5xx) e ainda tiver tentativas
                            if ((status === 'timeout' || xhr.status >= 500) && attempts < maxAttempts) {
                                console.log(`[NEWBASE] Retentando em 2 segundos...`);
                                setTimeout(performSearch, 2000); // Tenta de novo após 2s
                            } else {
                                // Esgotou tentativas ou é erro fatal (400/403/404)
                                let errorMsg = 'Erro ao conectar com o servidor após ' + attempts + ' tentativas.';

                                if (xhr.status === 400) {
                                    errorMsg = 'Dados inválidos. Verifique o CNPJ digitado.';
                                } else if (xhr.status === 403) {
                                    errorMsg = 'Sem permissão. Faça login novamente.';
                                } else if (xhr.status === 404) {
                                    errorMsg = 'CNPJ não encontrado na base de dados.';
                                }

                                showNotification(errorMsg, 'error');
                                showLoading($cnpjButton, false); // Erro final: reabilita botão
                            }
                        }
                    });
                }

                // Inicia a primeira tentativa
                performSearch();
            });
    }

    /**
     * Busca CEP com auto-preenchimento (ViaCEP direto)
     */
    function initCEPSearch() {
        $(document)
            .off('click.newbase-cep', 'button[data-action="search-cep"]')
            .on('click.newbase-cep', 'button[data-action="search-cep"]', function (e) {
                e.preventDefault();

                const $cepButton = $(this);
                const $input = $('[name="cep"]');
                const cep = $input.val();

                if (!cep) {
                    alert('Digite um CEP');
                    return;
                }

                console.log('[NEWBASE] Buscando CEP:', cep);

                showLoading($cepButton, true);

                const cepLimpo = cep.replace(/\D/g, '');

                fetch('https://viacep.com.br/ws/' + cepLimpo + '/json/')
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('CEP não encontrado');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        console.log('[NEWBASE] Resposta CEP:', data);

                        if (data && !data.erro) {
                            $('[name="address"]').val(data.logradouro || '');
                            $('[name="city"]').val(data.localidade || '');
                            $('[name="state"]').val(data.uf || '');

                            console.log('[NEWBASE] CEP preenchido com sucesso');
                            showNotification('Endereço preenchido com sucesso!', 'success');
                        } else {
                            showNotification('CEP não encontrado', 'error');
                        }
                    })
                    .catch(function (error) {
                        console.error('[NEWBASE] Erro CEP:', error);
                        showNotification('Erro ao buscar CEP. Verifique sua conexão.', 'error');
                    })
                    .finally(function () {
                        showLoading($cepButton, false);
                    });
            });
    }

    /**
     * Mostrar/Ocultar loading
     */
    function showLoading($button, show) {
        if (show) {
            $button
                .prop('disabled', true)
                .addClass('loading')
                .html('<i class="fas fa-spinner fa-spin"></i>');
        } else {
            $button
                .prop('disabled', false)
                .removeClass('loading')
                .html('<i class="ti ti-search"></i>');
        }
    }

    /**
     * Mostrar notificação
     */
    function showNotification(message, type) {
        // Usar notificação nativa do GLPI
        if (typeof glpi_toast_info !== 'undefined') {
            if (type === 'success') {
                glpi_toast_info(message);
            } else {
                glpi_toast_error(message);
            }
        } else if (typeof displayAjaxMessageAfterRedirect !== 'undefined') {
            displayAjaxMessageAfterRedirect();
        } else {
            alert(message);
        }
    }

})(jQuery);
