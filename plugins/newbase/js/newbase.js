/**
 * Main JavaScript file for Newbase Plugin
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @copyright Copyright (c) 2026 João Lucas
 * @license   GPLv2+
 * @since     2.1.0
 */

(function ($) {
    'use strict';

    // SECURITY: Namespace isolado
    window.Newbase = window.Newbase || {};

    // CONFIGURATION
    const CONFIG = {
        NOTIFICATION_TIMEOUT: 5000,
        AJAX_TIMEOUT: 30000,
        MAX_TABLE_ROWS: 10000,
        DEBOUNCE_DELAY: 300,
        THROTTLE_DELAY: 100
    };

    // CACHE: Performance optimization
    let $cache = {
        body: null,
        document: null,
        window: null
    };

    // STATE: Track initialized components
    const state = {
        initialized: false,
        activeModals: [],
        eventListeners: []
    };

    /**
     * Get CSRF Token from multiple sources
     * @returns {string} CSRF token or empty string
     */
    Newbase.getCSRFToken = function () {
        // Método 1: Meta tag padrão do GLPI 10
        let token = $('meta[name="glpi:csrf_token"]').attr('content');
        if (token) return token;

        // Método 2: Campo hidden do formulário
        token = $('input[name="_glpi_csrf_token"]').first().val();
        if (token) return token;

        // Método 3: Atributo data do body
        token = $('body').data('csrf-token');
        if (token) return token;

        // Método 4: Variável global (se existir)
        if (typeof window._glpi_csrf_token !== 'undefined') {
            return window._glpi_csrf_token;
        }

        console.warn('[Newbase] CSRF token not found');
        return '';
    };

    /**
     * Setup CSRF Token for all AJAX requests
     */
    function setupCSRFToken() {
        const token = Newbase.getCSRFToken();

        if (!token) {
            console.warn('[Newbase] CSRF token not found - AJAX requests may fail');
            return false;
        }

        // Configurar jQuery AJAX global
        $.ajaxSetup({
            beforeSend: function (xhr, settings) {
                // Adicionar token apenas em requisições POST
                if (settings.type === 'POST') {
                    xhr.setRequestHeader('X-Glpi-Csrf-Token', token);

                    // Adicionar token nos dados se não existir
                    if (settings.data && typeof settings.data === 'object') {
                        if (!settings.data._glpi_csrf_token) {
                            settings.data._glpi_csrf_token = token;
                        }
                    } else if (typeof settings.data === 'string') {
                        if (!settings.data.includes('_glpi_csrf_token')) {
                            settings.data += (settings.data ? '&' : '') + '_glpi_csrf_token=' + encodeURIComponent(token);
                        }
                    }
                }
            }
        });

        return true;
    }

    /**
     * Initialize plugin with performance tracking
     */
    Newbase.init = function () {
        if (state.initialized) {
            console.warn('[Newbase] Already initialized');
            return;
        }

        const startTime = performance.now();

        // Cache elementos comuns
        $cache.body = $('body');
        $cache.document = $(document);
        $cache.window = $(window);

        // Setup CSRF Token
        setupCSRFToken();

        // Inicializar componentes
        Newbase.initTooltips();
        Newbase.initAlerts();
        Newbase.initModals();
        Newbase.initTables();
        Newbase.initFormMasks();
        Newbase.initSearchButtons();

        state.initialized = true;

        const endTime = performance.now();
        console.log(`[Newbase] Initialized in ${(endTime - startTime).toFixed(2)}ms`);
    };

    /**
     * Initialize input masks for Brazilian formats
     */
    Newbase.initFormMasks = function () {
        // Auto-aplicar máscaras em campos específicos
        $cache.body.on('focus', 'input[name="cnpj"]', function () {
            const $input = $(this);
            if (!$input.data('mask-applied')) {
                $input.on('input', function () {
                    this.value = Newbase.formatCNPJ(this.value);
                });
                $input.data('mask-applied', true);
            }
        });

        $cache.body.on('focus', 'input[name="cpf"]', function () {
            const $input = $(this);
            if (!$input.data('mask-applied')) {
                $input.on('input', function () {
                    this.value = Newbase.formatCPF(this.value);
                });
                $input.data('mask-applied', true);
            }
        });

        $cache.body.on('focus', 'input[name="cep"]', function () {
            const $input = $(this);
            if (!$input.data('mask-applied')) {
                $input.on('input', function () {
                    this.value = Newbase.formatCEP(this.value);
                });
                $input.data('mask-applied', true);
            }
        });

        $cache.body.on('focus', 'input[name="phone"], input[name="telefone"]', function () {
            const $input = $(this);
            if (!$input.data('mask-applied')) {
                $input.on('input', function () {
                    this.value = Newbase.formatPhone(this.value);
                });
                $input.data('mask-applied', true);
            }
        });
    };

    /**
     * Initialize search buttons (CNPJ and CEP)
     */
    Newbase.initSearchButtons = function () {
        // Buscar CNPJ (usando data-action)
        $cache.body.on('click', 'button[data-action="search-cnpj"]', function (e) {
            e.preventDefault();

            const $button = $(this);
            const $cnpjInput = $('input[name="cnpj"]');
            const cnpj = $cnpjInput.val();

            if (!cnpj) {
                Newbase.notify('Por favor, insira um CNPJ', 'warning');
                return;
            }

            // Validar CNPJ antes de buscar
            if (!Newbase.validateCNPJ(cnpj)) {
                Newbase.notify('CNPJ inválido', 'error');
                return;
            }

            const token = Newbase.getCSRFToken();
            if (!token) {
                Newbase.notify('Erro de segurança. Recarregue a página.', 'error');
                return;
            }

            Newbase.ajax({
                url: Newbase.getAjaxUrl('searchCompany.php'),
                data: {
                    cnpj: cnpj.replace(/\D/g, ''),
                    _glpi_csrf_token: token
                },
                loadingTarget: $button.parent(),
                success: function (response) {
                    if (response.success && response.data) {
                        const data = response.data;

                        // Preencher campos do formulário
                        $('input[name="name"]').val(data.legal_name || data.corporate_name || '');
                        $('input[name="corporate_name"]').val(data.legal_name || '');
                        $('input[name="fantasy_name"]').val(data.fantasy_name || '');
                        $('input[name="email"]').val(data.email || '');
                        $('input[name="phone"]').val(data.phone || '');

                        Newbase.notify(response.message || 'Dados carregados com sucesso', 'success');
                    } else {
                        Newbase.notify(response.message || 'CNPJ não encontrado', 'warning');
                    }
                },
                error: function () {
                    Newbase.notify('Erro ao buscar CNPJ. Tente novamente.', 'error');
                }
            });
        });

        // Buscar CEP (usando data-action)
        $cache.body.on('click', 'button[data-action="search-cep"]', function (e) {
            e.preventDefault();

            const $button = $(this);
            const $cepInput = $('input[name="cep"]');
            const cep = $cepInput.val();

            if (!cep) {
                Newbase.notify('Por favor, insira um CEP', 'warning');
                return;
            }

            const token = Newbase.getCSRFToken();
            if (!token) {
                Newbase.notify('Erro de segurança. Recarregue a página.', 'error');
                return;
            }

            Newbase.ajax({
                url: Newbase.getAjaxUrl('searchAddress.php'),
                data: {
                    cep: cep.replace(/\D/g, ''),
                    _glpi_csrf_token: token
                },
                loadingTarget: $button.parent(),
                success: function (response) {
                    if (response.success && response.data) {
                        const data = response.data;

                        // Preencher campos do formulário
                        $('input[name="street"]').val(data.street || data.logradouro || '');
                        $('input[name="neighborhood"]').val(data.neighborhood || data.bairro || '');
                        $('input[name="city"]').val(data.city || data.localidade || '');
                        $('input[name="state"]').val(data.state || data.uf || '');

                        Newbase.notify(response.message || 'Endereço carregado com sucesso', 'success');
                    } else {
                        Newbase.notify(response.message || 'CEP não encontrado', 'warning');
                    }
                },
                error: function () {
                    Newbase.notify('Erro ao buscar CEP. Tente novamente.', 'error');
                }
            });
        });
    };

    /**
     * Initialize tooltips with security
     */
    Newbase.initTooltips = function () {
        $cache.body.on('mouseenter', '[data-toggle="tooltip"]', function () {
            const $element = $(this);
            if ($element.data('tooltip-initialized')) return;

            const title = Newbase.escapeHtml($element.attr('title') || $element.data('original-title') || '');

            if (title && typeof $.fn.tooltip === 'function') {
                $element.attr('data-original-title', title).removeAttr('title');
                $element.tooltip({
                    html: false,
                    sanitize: true,
                    container: 'body'
                });
                $element.data('tooltip-initialized', true);
            }
        });
    };

    /**
     * Initialize alert dismissal
     */
    Newbase.initAlerts = function () {
        $cache.body.on('click', '.newbase-alert .close', function (e) {
            e.preventDefault();
            $(this).closest('.newbase-alert').fadeOut(300, function () {
                $(this).remove();
            });
        });

        $('.newbase-alert[data-auto-dismiss="true"]').each(function () {
            const $alert = $(this);
            const timeout = parseInt($alert.data('timeout'), 10) || CONFIG.NOTIFICATION_TIMEOUT;
            setTimeout(function () {
                if ($alert.is(':visible')) {
                    $alert.fadeOut(300, function () {
                        $(this).remove();
                    });
                }
            }, timeout);
        });
    };

    /**
     * Initialize modal system
     */
    Newbase.initModals = function () {
        $cache.body.on('click', '[data-modal-trigger]', function (e) {
            e.preventDefault();
            const modalId = $(this).data('modal-trigger');
            if (!/^[a-zA-Z0-9_-]+$/.test(modalId)) {
                console.error('[Newbase] Invalid modal ID:', modalId);
                return;
            }
            const $modal = $('#' + modalId);
            if ($modal.length) {
                Newbase.openModal($modal);
            }
        });

        $cache.body.on('click', '.modal-backdrop, .modal-close', function () {
            Newbase.closeModal($(this).closest('.modal'));
        });

        $cache.body.on('click', '.modal-content', function (e) {
            e.stopPropagation();
        });

        $cache.document.on('keydown.modal', function (e) {
            if (e.key === 'Escape' && state.activeModals.length > 0) {
                Newbase.closeModal($(state.activeModals[state.activeModals.length - 1]));
            }
        });
    };

    /**
     * Open modal
     */
    Newbase.openModal = function ($modal) {
        if (!$modal.length) return;

        $modal.fadeIn(200);
        state.activeModals.push($modal[0]);

        const $firstFocusable = $modal.find('input, button, select, textarea, a[href]').first();
        if ($firstFocusable.length) {
            setTimeout(() => $firstFocusable.focus(), 250);
        }

        $cache.body.addClass('modal-open');
    };

    /**
     * Close modal
     */
    Newbase.closeModal = function ($modal) {
        if (!$modal.length) return;

        $modal.fadeOut(200);

        const index = state.activeModals.indexOf($modal[0]);
        if (index > -1) {
            state.activeModals.splice(index, 1);
        }

        if (state.activeModals.length === 0) {
            $cache.body.removeClass('modal-open');
        }
    };

    /**
     * Initialize sortable tables
     */
    Newbase.initTables = function () {
        $('.newbase-table').each(function () {
            const $table = $(this);
            if (!$table.parent().hasClass('newbase-table-wrapper')) {
                $table.wrap('<div class="newbase-table-wrapper"></div>');
            }
        });

        $cache.body.on('click', '.newbase-table th[data-sortable]', function () {
            const $th = $(this);
            const $table = $th.closest('table');
            const $tbody = $table.find('tbody');
            const $rows = $tbody.find('tr');

            if ($rows.length > CONFIG.MAX_TABLE_ROWS) {
                Newbase.notify('Tabela muito grande para ordenar. Use filtros.', 'warning');
                return;
            }

            const column = $th.index();
            const currentDirection = $th.hasClass('sort-asc') ? 'asc' : 'desc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

            $table.find('th').removeClass('sort-asc sort-desc');
            $th.addClass('sort-' + newDirection);

            const fragment = document.createDocumentFragment();
            const rowsArray = Array.from($rows);

            rowsArray.sort(function (a, b) {
                const aValue = $(a).find('td').eq(column).text().trim();
                const bValue = $(b).find('td').eq(column).text().trim();
                const aNum = parseFloat(aValue);
                const bNum = parseFloat(bValue);

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return newDirection === 'asc' ? aNum - bNum : bNum - aNum;
                }

                return newDirection === 'asc'
                    ? aValue.localeCompare(bValue, undefined, { numeric: true, sensitivity: 'base' })
                    : bValue.localeCompare(aValue, undefined, { numeric: true, sensitivity: 'base' });
            });

            rowsArray.forEach(row => fragment.appendChild(row));
            $tbody[0].appendChild(fragment);
        });
    };

    /**
     * Get AJAX URL for plugin endpoints
     */
    Newbase.getAjaxUrl = function (endpoint) {
        const root = (typeof CFG_GLPI !== 'undefined' && CFG_GLPI.root_doc)
            ? CFG_GLPI.root_doc
            : '';
        return root + '/plugins/newbase/ajax/' + endpoint;
    };

    /**
     * Show loading overlay
     */
    Newbase.showLoading = function (target) {
        const $target = $(target);
        if ($target.hasClass('newbase-form-loading')) return;

        $target.addClass('newbase-form-loading');
        const $overlay = $('<div>').addClass('newbase-loading-overlay');
        const $spinner = $('<div>').addClass('newbase-loading');
        $overlay.append($spinner);
        $target.append($overlay);
    };

    /**
     * Hide loading overlay
     */
    Newbase.hideLoading = function (target) {
        const $target = $(target);
        $target.removeClass('newbase-form-loading');
        $target.find('.newbase-loading-overlay').remove();
    };

    /**
     * Display notification
     * Integra com o sistema de notificações do GLPI quando disponível
     */
    Newbase.notify = function (message, type) {
        type = type || 'info';
        const safeMessage = Newbase.escapeHtml(String(message));

        // Tentar usar o sistema de notificação do GLPI 10
        if (typeof glpi_toast_info === 'function') {
            switch (type) {
                case 'success':
                    if (typeof glpi_toast_success === 'function') {
                        glpi_toast_success(safeMessage);
                        return;
                    }
                    break;
                case 'error':
                    if (typeof glpi_toast_error === 'function') {
                        glpi_toast_error(safeMessage);
                        return;
                    }
                    break;
                case 'warning':
                    if (typeof glpi_toast_warning === 'function') {
                        glpi_toast_warning(safeMessage);
                        return;
                    }
                    break;
                default:
                    glpi_toast_info(safeMessage);
                    return;
            }
        }

        // Fallback: Sistema de notificação customizado
        const validTypes = ['info', 'success', 'warning', 'error'];
        if (!validTypes.includes(type)) type = 'info';

        const alertClass = 'newbase-alert-' + type;
        const $alert = $('<div>')
            .addClass('newbase-alert')
            .addClass(alertClass)
            .attr('role', 'alert')
            .attr('aria-live', 'polite');

        const $message = $('<span>').text(safeMessage);
        const $closeBtn = $('<button>')
            .addClass('close')
            .attr('type', 'button')
            .attr('aria-label', 'Close')
            .html('&times;');

        $alert.append($message).append($closeBtn);
        $cache.body.append($alert);

        $alert.css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: 9999,
            minWidth: '300px',
            maxWidth: '500px',
            padding: '15px 20px',
            borderRadius: '4px',
            boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
            animation: 'slideInRight 0.3s ease-out'
        });

        setTimeout(function () {
            if ($alert.is(':visible')) {
                $alert.fadeOut(300, function () {
                    $(this).remove();
                });
            }
        }, CONFIG.NOTIFICATION_TIMEOUT);

        $closeBtn.on('click', function () {
            $alert.fadeOut(300, function () {
                $(this).remove();
            });
        });
    };

    /**
     * AJAX wrapper with CSRF and error handling
     */
    Newbase.ajax = function (options) {
        const token = Newbase.getCSRFToken();

        const defaults = {
            type: 'POST',
            dataType: 'json',
            timeout: CONFIG.AJAX_TIMEOUT,
            data: {},
            beforeSend: function (xhr) {
                if (token) {
                    xhr.setRequestHeader('X-Glpi-Csrf-Token', token);
                }
                if (options.loadingTarget) {
                    Newbase.showLoading(options.loadingTarget);
                }
                if (typeof options.beforeSendCallback === 'function') {
                    options.beforeSendCallback(xhr);
                }
            },
            complete: function (xhr, status) {
                if (options.loadingTarget) {
                    Newbase.hideLoading(options.loadingTarget);
                }
                if (typeof options.completeCallback === 'function') {
                    options.completeCallback(xhr, status);
                }
            },
            error: function (xhr, status, error) {
                console.error('[Newbase] AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });

                let errorMessage = 'Ocorreu um erro. Tente novamente.';

                if (xhr.status === 403) {
                    errorMessage = 'Acesso negado. Atualize a página e tente novamente.';
                } else if (xhr.status === 401) {
                    errorMessage = 'Sessão expirada. Faça login novamente.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Erro no servidor. Contate o suporte.';
                } else if (status === 'timeout') {
                    errorMessage = 'Tempo esgotado. Tente novamente.';
                }

                Newbase.notify(errorMessage, 'error');

                if (typeof options.errorCallback === 'function') {
                    options.errorCallback(xhr, status, error);
                }
            }
        };

        const merged = $.extend(true, {}, defaults, options);

        // Garantir que o token está nos dados POST
        if (merged.type === 'POST' && token && !merged.data._glpi_csrf_token) {
            merged.data._glpi_csrf_token = token;
        }

        return $.ajax(merged);
    };

    /**
     * Confirm dialog
     */
    Newbase.confirm = function (message, callback) {
        if (confirm(String(message))) {
            if (typeof callback === 'function') {
                try {
                    callback();
                } catch (e) {
                    console.error('[Newbase] Confirm callback error:', e);
                }
            }
        }
    };

    // ========== FORMATTING FUNCTIONS ==========

    Newbase.formatCNPJ = function (value) {
        value = String(value || '').replace(/\D/g, '').substring(0, 14);
        if (value.length <= 14) {
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }
        return value;
    };

    Newbase.formatCPF = function (value) {
        value = String(value || '').replace(/\D/g, '').substring(0, 11);
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        return value;
    };

    Newbase.formatCEP = function (value) {
        value = String(value || '').replace(/\D/g, '').substring(0, 8);
        if (value.length <= 8) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }
        return value;
    };

    Newbase.formatPhone = function (value) {
        value = String(value || '').replace(/\D/g, '').substring(0, 11);
        if (value.length === 11) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length === 10) {
            value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else if (value.length === 9) {
            value = value.replace(/^(\d{5})(\d{4})/, '$1-$2');
        } else if (value.length === 8) {
            value = value.replace(/^(\d{4})(\d{4})/, '$1-$2');
        }
        return value;
    };

    // ========== VALIDATION FUNCTIONS ==========

    Newbase.validateCNPJ = function (cnpj) {
        cnpj = String(cnpj || '').replace(/\D/g, '');
        if (cnpj.length !== 14) return false;
        if (/^(\d)\1+$/.test(cnpj)) return false;

        try {
            let sum = 0;
            const weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for (let i = 0; i < 12; i++) {
                sum += parseInt(cnpj.charAt(i), 10) * weights1[i];
            }
            let remainder = sum % 11;
            const digit1 = remainder < 2 ? 0 : 11 - remainder;
            if (parseInt(cnpj.charAt(12), 10) !== digit1) return false;

            sum = 0;
            const weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for (let i = 0; i < 13; i++) {
                sum += parseInt(cnpj.charAt(i), 10) * weights2[i];
            }
            remainder = sum % 11;
            const digit2 = remainder < 2 ? 0 : 11 - remainder;
            return parseInt(cnpj.charAt(13), 10) === digit2;
        } catch (e) {
            return false;
        }
    };

    Newbase.validateCPF = function (cpf) {
        cpf = String(cpf || '').replace(/\D/g, '');
        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false;

        try {
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(cpf.charAt(i), 10) * (10 - i);
            }
            let remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(9), 10)) return false;

            sum = 0;
            for (let i = 0; i < 10; i++) {
                sum += parseInt(cpf.charAt(i), 10) * (11 - i);
            }
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(10), 10)) return false;

            return true;
        } catch (e) {
            return false;
        }
    };

    Newbase.validateEmail = function (email) {
        if (typeof email !== 'string' || email.length > 254) return false;
        const regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        return regex.test(email);
    };

    // ========== UTILITY FUNCTIONS ==========

    Newbase.escapeHtml = function (text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#x27;',
            '/': '&#x2F;'
        };
        return String(text).replace(/[&<>"'/]/g, function (char) {
            return map[char];
        });
    };

    Newbase.debounce = function (func, wait) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                func.apply(context, args);
            }, wait || CONFIG.DEBOUNCE_DELAY);
        };
    };

    Newbase.throttle = function (func, limit) {
        let inThrottle;
        return function () {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function () {
                    inThrottle = false;
                }, limit || CONFIG.THROTTLE_DELAY);
            }
        };
    };

    Newbase.getUrlParameter = function (name) {
        if (typeof name !== 'string') return '';
        name = name.replace(/[[\]]/g, '\\$&');
        try {
            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
            const results = regex.exec(window.location.href);
            if (!results || !results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        } catch (e) {
            return '';
        }
    };

    Newbase.copyToClipboard = function (text) {
        text = String(text);
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text)
                .then(() => Newbase.notify('Copiado!', 'success'))
                .catch(() => Newbase.copyToClipboardFallback(text));
        } else {
            Newbase.copyToClipboardFallback(text);
        }
    };

    Newbase.copyToClipboardFallback = function (text) {
        const $temp = $('<textarea>')
            .val(text)
            .css({ position: 'absolute', left: '-9999px', top: '0' })
            .appendTo('body')
            .select();

        try {
            if (document.execCommand('copy')) {
                Newbase.notify('Copiado!', 'success');
            } else {
                Newbase.notify('Erro ao copiar', 'error');
            }
        } catch (err) {
            Newbase.notify('Erro ao copiar', 'error');
        }

        $temp.remove();
    };

    Newbase.generateUUID = function () {
        if (typeof crypto !== 'undefined' && crypto.randomUUID) {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    };

    /**
     * Cleanup and destroy
     */
    Newbase.destroy = function () {
        if (!state.initialized) return;

        console.log('[Newbase] Destroying...');

        $cache.document.off('.modal');
        $cache.body.off();

        state.activeModals.forEach(modal => $(modal).hide());
        state.initialized = false;
        state.activeModals = [];
        $cache = {};

        console.log('[Newbase] Destroyed');
    };

    // ========== INITIALIZATION ==========

    $(document).ready(function () {
        Newbase.init();
    });

    $(window).on('beforeunload', function () {
        state.activeModals.forEach(modal => $(modal).hide());
    });

})(jQuery);
