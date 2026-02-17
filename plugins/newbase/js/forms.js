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

        // Tentativa 1: Campo hidden do formulário (mais confiável)
        token = $('[name="_glpi_csrf_token"]').first().val();
        if (token) {
            console.log('[NEWBASE] CSRF Token obtido do campo hidden: ' + token.substring(0, 10) + '...');
            return token;
        }

        // Tentativa 2: Meta tag do GLPI
        token = $('meta[name="glpi:csrf_token"]').attr('content');
        if (token) {
            console.log('[NEWBASE] CSRF Token obtido da meta tag: ' + token.substring(0, 10) + '...');
            return token;
        }

        // Tentativa 3: Data attribute
        token = $('body').data('csrf-token') || $('html').data('csrf-token');
        if (token) {
            console.log('[NEWBASE] CSRF Token obtido de data attribute: ' + token.substring(0, 10) + '...');
            return token;
        }

        // Tentativa 4: Procurar em qualquer input hidden com pattern de token
        token = $('input[type="hidden"][name*="token"]').first().val();
        if (token) {
            console.log('[NEWBASE] CSRF Token obtido de input genérico: ' + token.substring(0, 10) + '...');
            return token;
        }

        // Tentativa 5: Buscar pela função getGlPICsrfToken() do GLPI (se disponível)
        if (typeof getGlPICsrfToken === 'function') {
            try {
                token = getGlPICsrfToken();
                if (token) {
                    console.log('[NEWBASE] CSRF Token obtido via getGlPICsrfToken(): ' + token.substring(0, 10) + '...');
                    return token;
                }
            } catch (e) {
                console.warn('[NEWBASE] Error calling getGlPICsrfToken():', e);
            }
        }

        console.warn('[NEWBASE] ⚠️ CSRF Token NÃO ENCONTRADO! Requisições POST podem falhar.');
        return '';
    }

    $(document).ready(function () {
        console.log('[NEWBASE] Inicializando forms.js');

        // Aguardar um pouco para garantir que todos os elementos foram carregados
        setTimeout(function () {
            // Inicializar busca CNPJ
            initCNPJSearch();

            // Inicializar busca CEP
            initCEPSearch();
        }, 100);
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

        console.log('[NEWBASE] Botão CNPJ encontrado e vinculado');

        $cnpjButton.off('click').on('click', function (e) {
            e.preventDefault();

            const $input = $('[name="cnpj"]');
            const cnpj = $input.val();

            if (!cnpj) {
                alert('Digite um CNPJ');
                return;
            }

            console.log('[NEWBASE] Buscando CNPJ:', cnpj);

            // Mostrar loading
            showLoading($cnpjButton, true);

            // Obter token CSRF
            const csrfToken = getCSRFToken();

            if (!csrfToken) {
                console.error('[NEWBASE] CSRF Token não encontrado!');
                showNotification('Erro de segurança: CSRF Token ausente. Recarregue a página.', 'error');
                showLoading($cnpjButton, false);
                return;
            }

            // Usar Newbase.ajax() se disponível (garante token CSRF)
            var ajaxCall = (typeof Newbase !== 'undefined' && Newbase.ajax)
                ? Newbase.ajax
                : $.ajax;

            ajaxCall({
                type: 'POST',
                url: CFG_GLPI.root_doc + '/plugins/newbase/ajax/cnpj_proxy.php',
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

                        // Preencher campos
                        $('[name="name"]').val(data.razao_social || '');
                        $('[name="corporate_name"]').val(data.razao_social || '');
                        $('[name="fantasy_name"]').val(data.nome_fantasia || '');
                        $('[name="email"]').val(data.email || '');
                        $('[name="phone"]').val(data.telefone || '');

                        // Montar endereço completo
                        let endereco = data.logradouro || '';
                        if (data.numero) {
                            endereco += (endereco ? ', ' : '') + data.numero;
                        }
                        if (data.complemento) {
                            endereco += ' ' + data.complemento;
                        }

                        $('[name="address"]').val(endereco.trim());
                        $('[name="city"]').val(data.municipio || '');
                        $('[name="state"]').val(data.uf || '');
                        $('[name="cep"]').val(data.cep || '');

                        console.log('[NEWBASE] ✅ Campos preenchidos com sucesso');
                        showNotification('Dados da empresa preenchidos com sucesso!', 'success');
                    } else {
                        console.error('[NEWBASE] ❌ Erro:', response.error);
                        showNotification(response.error || 'Erro ao buscar CNPJ', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('[NEWBASE] ❌ AJAX Error:', {
                        status: xhr.status,
                        statusText: status,
                        error: error,
                        responseText: xhr.responseText
                    });

                    let errorMsg = 'Erro ao conectar com o servidor';

                    if (xhr.status === 400) {
                        errorMsg = 'Dados inválidos. Verifique o CNPJ digitado.';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Sem permissão. Faça login novamente.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'CNPJ não encontrado na base de dados.';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Erro no servidor. Tente novamente.';
                    }

                    showNotification(errorMsg, 'error');
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

        console.log('[NEWBASE] Botão CEP encontrado e vinculado');

        $cepButton.off('click').on('click', function (e) {
            e.preventDefault();

            const $input = $('[name="cep"]');
            const cep = $input.val();

            if (!cep) {
                alert('Digite um CEP');
                return;
            }

            console.log('[NEWBASE] Buscando CEP:', cep);

            showLoading($cepButton, true);

            // Limpar CEP (remover pontos e traços)
            const cepLimpo = cep.replace(/\D/g, '');

            // Usar fetch puro para evitar CORS (jQuery adiciona headers que causam erro)
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

                        console.log('[NEWBASE] ✅ CEP preenchido com sucesso');
                        showNotification('Endereço preenchido com sucesso!', 'success');
                    } else {
                        showNotification('CEP não encontrado', 'error');
                    }
                })
                .catch(function (error) {
                    console.error('[NEWBASE] ❌ Erro CEP:', error);
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
            $button.prop('disabled', true)
                .addClass('loading')
                .html('<i class="fas fa-spinner fa-spin"></i>');
        } else {
            $button.prop('disabled', false)
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
            // Fallback simples
            alert(message);
        }
    }

})(jQuery);
