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
     * COM CACHE e DEBOUNCE para evitar múltiplas requisições
     */
    
    // Cache de requisições (evitar buscar mesmo CNPJ repetidamente)
    const cnpjCache = {};
    let isSearching = false; // Debounce global
    
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
                
                // Debounce: bloquear se já estiver buscando
                if (isSearching) {
                    console.log('[NEWBASE] Já está buscando, aguarde...');
                    return;
                }
                
                // Cache: verificar se já buscou esse CNPJ recentemente (5 min)
                const cnpjClean = cnpj.replace(/[^0-9]/g, '');
                const now = Date.now();
                if (cnpjCache[cnpjClean] && (now - cnpjCache[cnpjClean].timestamp) < 300000) {
                    console.log('[NEWBASE] Usando dados do cache para CNPJ:', cnpj);
                    fillFormWithCachedData(cnpjCache[cnpjClean].data);
                    showNotification('Dados carregados do cache', 'success');
                    return;
                }

                console.log('[NEWBASE] Buscando CNPJ:', cnpj);
                isSearching = true;
                showLoading($cnpjButton, true, 'Buscando...'); // Desabilita botão visualmente

                const csrfToken = getCSRFToken();
                if (!csrfToken) {
                    console.error('[NEWBASE] CSRF Token não encontrado!');
                    showNotification('Erro de segurança: CSRF Token ausente. Recarregue a página.', 'error');
                    isSearching = false;
                    showLoading($cnpjButton, false);
                    return;
                }

                // Configuração da requisição com Retry
                let attempts = 0;
                const maxAttempts = 3;
                // Delay progressivo entre tentativas: 1.5s, 3s
                const retryDelays = [0, 1500, 3000];

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

                                // Nomes de campos conforme CompanyData.php
                                $('[name="name"]').val(data.legal_name || data.corporate_name || '');
                                $('[name="corporate_name"]').val(data.legal_name || '');
                                $('[name="fantasy_name"]').val(data.fantasy_name || '');
                                $('[name="email"]').val(data.email || '');
                                $('[name="phone"]').val(data.phone || '');
                                $('[name="street"]').val(data.street || '');
                                $('[name="number"]').val(data.number || '');
                                $('[name="complement"]').val(data.complement || '');
                                $('[name="neighborhood"]').val(data.neighborhood || data.district || '');
                                $('[name="city"]').val(data.city || '');
                                $('[name="state"]').val(data.state || '');
                                $('[name="cep"]').val(data.postcode || data.zip_code || '');
                                $('[name="latitude"]').val(data.latitude || '');
                                $('[name="longitude"]').val(data.longitude || '');

                                // Salvar no cache
                                cnpjCache[cnpjClean] = {
                                    timestamp: Date.now(),
                                    data: data
                                };
                                
                                console.log('[NEWBASE] Campos preenchidos com sucesso');
                                showNotification('Dados da empresa preenchidos com sucesso!', 'success');
                                isSearching = false; // Liberar debounce
                                showLoading($cnpjButton, false); // Sucesso final: reabilita botão
                            } else {
                                // Erro de negócio (API respondeu, mas com erro lógico - ex: CNPJ inválido)
                                // NÃO faz retry aqui, pois a resposta é definitiva
                                console.error('[NEWBASE] Erro na resposta:', response.message);
                                showNotification(response.message || 'Erro ao buscar CNPJ', 'error');
                                isSearching = false; // Liberar debounce
                                showLoading($cnpjButton, false);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(`[NEWBASE] AJAX Error (Tentativa ${attempts}/${maxAttempts}): status=${status}, httpStatus=${xhr.status}`);

                            // Erros NÃO recuperáveis: autenticação/autorização/dado inválido
                            const isFatalError = (xhr.status === 400 || xhr.status === 403 || xhr.status === 404);

                            // Erros recuperáveis: qualquer coisa que não seja fatal e ainda há tentativas
                            // Inclui: timeout, erro de rede (status==='error', xhr.status===0), 5xx
                            const isRetryable = !isFatalError && attempts < maxAttempts;

                            if (isRetryable) {
                                const delay = retryDelays[attempts] || 2000;
                                console.log(`[NEWBASE] Retentando em ${delay}ms... (tentativa ${attempts + 1}/${maxAttempts})`);
                                showLoading($cnpjButton, true, `Tentativa ${attempts + 1}/${maxAttempts}...`);
                                setTimeout(performSearch, delay);
                            } else {
                                // Esgotou tentativas ou é erro fatal
                                let errorMsg;

                                if (xhr.status === 400) {
                                    errorMsg = 'CNPJ inválido. Verifique os dígitos digitados.';
                                } else if (xhr.status === 403) {
                                    errorMsg = 'Sem permissão. Faça login novamente.';
                                } else if (xhr.status === 404) {
                                    errorMsg = 'CNPJ não encontrado na base de dados.';
                                } else {
                                    errorMsg = `Falha na conexão após ${attempts} tentativas. Verifique sua rede e tente novamente.`;
                                }

                                showNotification(errorMsg, 'error');
                                isSearching = false;
                                showLoading($cnpjButton, false);
                            }
                        }
                    });
                }

                // Inicia a primeira tentativa
                performSearch();
            });
    }
    
    /**
     * Preencher formulário com dados do cache
     */
    function fillFormWithCachedData(data) {
        $('[name="name"]').val(data.legal_name || data.corporate_name || '');
        $('[name="corporate_name"]').val(data.legal_name || '');
        $('[name="fantasy_name"]').val(data.fantasy_name || '');
        $('[name="email"]').val(data.email || '');
        $('[name="phone"]').val(data.phone || '');
        $('[name="street"]').val(data.street || '');
        $('[name="number"]').val(data.number || '');
        $('[name="complement"]').val(data.complement || '');
        $('[name="neighborhood"]').val(data.neighborhood || data.district || '');
        $('[name="city"]').val(data.city || '');
        $('[name="state"]').val(data.state || '');
        $('[name="cep"]').val(data.postcode || data.zip_code || '');
        $('[name="latitude"]').val(data.latitude || '');
        $('[name="longitude"]').val(data.longitude || '');
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
                            $('[name="street"]').val(data.logradouro || '');
                            $('[name="neighborhood"]').val(data.bairro || '');
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
     * Mostrar/Ocultar loading com mensagem
     */
    function showLoading($button, show, message) {
        if (show) {
            $button
                .prop('disabled', true)
                .addClass('loading')
                .css('opacity', '0.6')
                .html('<i class="fas fa-spinner fa-spin"></i> ' + (message || 'Buscando...'));
        } else {
            $button
                .prop('disabled', false)
                .removeClass('loading')
                .css('opacity', '1')
                .html('<i class="ti ti-search"></i>');
        }
    }

    /**
     * Mostrar notificação
     * Prioridade: SweetAlert2 > GLPI toast > alert nativo
     */
    function showNotification(message, type) {
        // 1º) SweetAlert2 (carregado via CDN no template Twig)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'success' ? 'success' : 'error',
                title: type === 'success' ? 'Sucesso!' : 'Atenção',
                text: message,
                timer: type === 'success' ? 2500 : 0,
                showConfirmButton: type !== 'success',
                toast: type === 'success',
                position: type === 'success' ? 'top-end' : 'center',
                confirmButtonColor: '#206bc4'
            });
            return;
        }

        // 2º) Notificação nativa do GLPI
        if (type === 'success' && typeof glpi_toast_info !== 'undefined') {
            glpi_toast_info(message);
            return;
        }
        if (type === 'error' && typeof glpi_toast_error !== 'undefined') {
            glpi_toast_error(message);
            return;
        }

        // 3º) Fallback
        alert(message);
    }

    // ════════════════════════════════════════════════════════════
    //  MAPA LEAFLET — Integração com campos lat/lng no formulário
    // ════════════════════════════════════════════════════════════

    /**
     * Inicializa mapa Leaflet no container #nb-task-map
     * Ao clicar no mapa, atualiza campos [name=latitude] e [name=longitude]
     * Lê coordenadas iniciais de data-lat / data-lng no container
     */
    function initTaskMap() {
        var $container = $('#nb-task-map');
        if (!$container.length) return;

        // Função interna que cria o mapa (chamada após Leaflet estar disponível)
        function criarMapa() {
            // Prioridade: data-attributes do container > campos de formulário > padrão ES
            var initLat = parseFloat($container.data('lat'))
                       || parseFloat($('[name="latitude"]').val())
                       || -20.3222;
            var initLng = parseFloat($container.data('lng'))
                       || parseFloat($('[name="longitude"]').val())
                       || -40.3381;

            var map = L.map('nb-task-map').setView([initLat, initLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '\u00a9 <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Marcador arrastável
            var marker = null;

            function placeMarker(lat, lng) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function (e) {
                    var pos = e.target.getLatLng();
                    setCoords(pos.lat, pos.lng);
                });
                setCoords(lat, lng);
            }

            function setCoords(lat, lng) {
                $('[name="latitude"]').val(lat.toFixed(8));
                $('[name="longitude"]').val(lng.toFixed(8));
            }

            // Posicionar marcador se já houver coordenadas
            if ($('[name="latitude"]').val() || $container.data('lat')) {
                placeMarker(initLat, initLng);
            }

            // Clique no mapa coloca/move marcador
            map.on('click', function (e) {
                placeMarker(e.latlng.lat, e.latlng.lng);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon              : 'info',
                        title             : 'Localização definida',
                        text              : 'Lat: ' + e.latlng.lat.toFixed(6) + ' | Lng: ' + e.latlng.lng.toFixed(6),
                        toast             : true,
                        position          : 'top-end',
                        timer             : 2000,
                        showConfirmButton : false
                    });
                }
            });

            // Botão "Minha Localização" (injetado por Task::showForm)
            $(document).off('click.nb-location').on('click.nb-location', '#nb-btn-my-location', function () {
                if (!navigator.geolocation) {
                    showNotification('Geolocalização não suportada neste navegador.', 'error');
                    return;
                }
                var $btn = $(this);
                $btn.prop('disabled', true).html('<i class="ti ti-loader nb-spin"></i> Localizando...');

                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        $btn.prop('disabled', false).html('<i class="ti ti-current-location me-1"></i> Minha Localização');
                        var lat = pos.coords.latitude;
                        var lng = pos.coords.longitude;
                        map.setView([lat, lng], 15);
                        placeMarker(lat, lng);
                        showNotification('Posição atual definida no mapa!', 'success');
                    },
                    function () {
                        $btn.prop('disabled', false).html('<i class="ti ti-current-location me-1"></i> Minha Localização');
                        showNotification('Não foi possível obter sua localização.', 'error');
                    },
                    { timeout: 10000, maximumAge: 60000 }
                );
            });

            // Campos lat/lng alterados manualmente → atualiza mapa
            $('[name="latitude"], [name="longitude"]').on('change.nb-map', function () {
                var lat = parseFloat($('[name="latitude"]').val());
                var lng = parseFloat($('[name="longitude"]').val());
                if (!isNaN(lat) && !isNaN(lng)) {
                    map.setView([lat, lng], 15);
                    placeMarker(lat, lng);
                }
            });

            // Garantir renderização correta após exibição da tab
            setTimeout(function () { map.invalidateSize(); }, 200);
        }

        // Leaflet já carregado?
        if (typeof L !== 'undefined') {
            criarMapa();
        } else if (typeof Newbase !== 'undefined' && typeof Newbase.Map !== 'undefined') {
            // Usar loader de map.js (carrega CSS + JS do CDN)
            Newbase.Map.loadLeaflet(criarMapa);
        } else {
            // Fallback: polling simples
            var waitL = setInterval(function () {
                if (typeof L === 'undefined') return;
                clearInterval(waitL);
                criarMapa();
            }, 250);
        }
    }

    $(document).ready(function () {
        initTaskMap();
    });

})(jQuery);
