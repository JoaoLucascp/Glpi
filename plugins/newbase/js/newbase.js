/**
* Main JavaScript file for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
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
    * Initialize plugin with performance tracking
    * PERFORMANCE: Lazy loading de componentes
    */
    Newbase.init = function () {
        if (state.initialized) {
            console.warn('Newbase already initialized');
            return;
        }

        console.log('Newbase Plugin initializing...');
        const startTime = performance.now();

        // PERFORMANCE: Cache elementos comuns
        $cache.body = $('body');
        $cache.document = $(document);
        $cache.window = $(window);

        // Inicializar componentes
        Newbase.initTooltips();
        Newbase.initAlerts();
        Newbase.initModals();
        Newbase.initTables();

        state.initialized = true;

        const endTime = performance.now();
        console.log(`Newbase Plugin initialized in ${(endTime - startTime).toFixed(2)}ms`);
    };

    /**
    * Initialize tooltips with security
    * SECURITY: Sanitizar conteúdo dos tooltips
    */
    Newbase.initTooltips = function () {
        // PERFORMANCE: Event delegation ao invés de loop
        $cache.body.on('mouseenter', '[data-toggle="tooltip"]', function () {
            const $element = $(this);

            // Prevenir reinicialização
            if ($element.data('tooltip-initialized')) {
                return;
            }

            // SECURITY: Sanitizar título do tooltip
            const title = Newbase.escapeHtml($element.attr('title') || $element.data('original-title') || '');

            if (title) {
                $element.attr('data-original-title', title).removeAttr('title');

                // Usar tooltip nativo do GLPI se disponível
                if (typeof $.fn.tooltip === 'function') {
                    $element.tooltip({
                        html: false, // SECURITY: Nunca permitir HTML
                        sanitize: true,
                        container: 'body'
                    });
                }

                $element.data('tooltip-initialized', true);
            }
        });
    };

    /**
    * Initialize alerts with XSS protection
    * SECURITY: Prevenir XSS em mensagens de alerta
    */
    Newbase.initAlerts = function () {
        // PERFORMANCE: Event delegation
        $cache.body.on('click', '.newbase-alert .close', function (e) {
            e.preventDefault();
            const $alert = $(this).closest('.newbase-alert');

            $alert.fadeOut(300, function () {
                $(this).remove();
            });
        });

        // Auto-dismiss existentes
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
    * Initialize modals with security improvements
    * SECURITY: Validar IDs de modal e prevenir XSS
    */
    Newbase.initModals = function () {
        // SECURITY: Validar e sanitizar modal ID
        $cache.body.on('click', '[data-modal-trigger]', function (e) {
            e.preventDefault();
            const modalId = $(this).data('modal-trigger');

            // SECURITY: Validar formato de ID (apenas alfanumérico, underscore, hífen)
            if (!/^[a-zA-Z0-9_-]+$/.test(modalId)) {
                console.error('Invalid modal ID:', modalId);
                return;
            }

            const $modal = $('#' + modalId);

            if ($modal.length) {
                Newbase.openModal($modal);
            } else {
                console.error('Modal not found:', modalId);
            }
        });

        // Fechar modal ao clicar no backdrop
        $cache.body.on('click', '.modal-backdrop, .modal-close', function (e) {
            const $modal = $(this).closest('.modal');
            Newbase.closeModal($modal);
        });

        // Prevenir fechamento ao clicar no conteúdo
        $cache.body.on('click', '.modal-content', function (e) {
            e.stopPropagation();
        });

        // ACCESSIBILITY: ESC para fechar modal
        $cache.document.on('keydown.modal', function (e) {
            if (e.key === 'Escape' && state.activeModals.length > 0) {
                const $modal = $(state.activeModals[state.activeModals.length - 1]);
                Newbase.closeModal($modal);
            }
        });
    };

    /**
    * Open modal with state tracking
    */
    Newbase.openModal = function ($modal) {
        if (!$modal.length) return;

        $modal.fadeIn(200);
        state.activeModals.push($modal[0]);

        // ACCESSIBILITY: Focar primeiro elemento focável
        const $firstFocusable = $modal.find('input, button, select, textarea, a[href]').first();
        if ($firstFocusable.length) {
            setTimeout(() => $firstFocusable.focus(), 250);
        }

        // ACCESSIBILITY: Prevenir scroll da página
        $cache.body.addClass('modal-open');
    };

    /**
    * Close modal with state cleanup
    */
    Newbase.closeModal = function ($modal) {
        if (!$modal.length) return;

        $modal.fadeOut(200);

        // Remover do state
        const index = state.activeModals.indexOf($modal[0]);
        if (index > -1) {
            state.activeModals.splice(index, 1);
        }

        // Restaurar scroll se não há mais modais abertos
        if (state.activeModals.length === 0) {
            $cache.body.removeClass('modal-open');
        }
    };

    /**
    * Initialize tables with performance optimization
    * PERFORMANCE: Otimizar ordenação para tabelas grandes
    */
    Newbase.initTables = function () {
        // Responsive wrapper
        $('.newbase-table').each(function () {
            const $table = $(this);

            if (!$table.parent().hasClass('newbase-table-wrapper')) {
                $table.wrap('<div class="newbase-table-wrapper"></div>');
            }
        });

        // PERFORMANCE: Event delegation para ordenação
        $cache.body.on('click', '.newbase-table th[data-sortable]', function () {
            const $th = $(this);
            const $table = $th.closest('table');
            const $tbody = $table.find('tbody');
            const $rows = $tbody.find('tr');

            // PERFORMANCE: Limitar ordenação em tabelas muito grandes
            if ($rows.length > CONFIG.MAX_TABLE_ROWS) {
                Newbase.notify('Table too large to sort. Please use filters.', 'warning');
                return;
            }

            const column = $th.index();
            const currentDirection = $th.hasClass('sort-asc') ? 'asc' : 'desc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

            // Remover classes de ordenação
            $table.find('th').removeClass('sort-asc sort-desc');
            $th.addClass('sort-' + newDirection);

            // PERFORMANCE: Usar DocumentFragment para melhor performance
            const fragment = document.createDocumentFragment();
            const rowsArray = Array.from($rows);

            // Ordenar array
            rowsArray.sort(function (a, b) {
                const aValue = $(a).find('td').eq(column).text().trim();
                const bValue = $(b).find('td').eq(column).text().trim();

                // PERFORMANCE: Comparação numérica quando possível
                const aNum = parseFloat(aValue);
                const bNum = parseFloat(bValue);

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return newDirection === 'asc' ? aNum - bNum : bNum - aNum;
                }

                // Comparação alfabética
                const comparison = aValue.localeCompare(bValue, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });

                return newDirection === 'asc' ? comparison : -comparison;
            });

            // PERFORMANCE: Adicionar todas de uma vez usando fragment
            rowsArray.forEach(row => fragment.appendChild(row));
            $tbody[0].appendChild(fragment);
        });
    };

    /**
    * Show loading indicator
    */
    Newbase.showLoading = function (target) {
        const $target = $(target);

        if ($target.hasClass('newbase-form-loading')) {
            return; // Já está carregando
        }

        $target.addClass('newbase-form-loading');

        // SECURITY: Criar elementos de forma segura
        const $overlay = $('<div>').addClass('newbase-loading-overlay');
        const $spinner = $('<div>').addClass('newbase-loading');

        $overlay.append($spinner);
        $target.append($overlay);
    };

    /**
    * Hide loading indicator
    */
    Newbase.hideLoading = function (target) {
        const $target = $(target);
        $target.removeClass('newbase-form-loading');
        $target.find('.newbase-loading-overlay').remove();
    };

    /**
    * Show notification with XSS protection
    * SECURITY: Sanitizar mensagem antes de exibir
    */
    Newbase.notify = function (message, type) {
        type = type || 'info';

        // SECURITY: Sanitizar mensagem
        const safeMessage = Newbase.escapeHtml(String(message));

        // Validar tipo
        const validTypes = ['info', 'success', 'warning', 'error'];
        if (!validTypes.includes(type)) {
            type = 'info';
        }

        const alertClass = 'newbase-alert-' + type;

        // SECURITY: Criar elementos usando jQuery methods seguros
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

        // Adicionar ao body
        $cache.body.append($alert);

        // Posicionar
        $alert.css({
            position: 'fixed',
            top: '20px',
            left: '50%',
            transform: 'translateX(-50%)',
            zIndex: 9999,
            minWidth: '300px',
            maxWidth: '600px',
            padding: '15px 20px',
            borderRadius: '4px',
            boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
        });

        // Auto-dismiss
        const timeout = CONFIG.NOTIFICATION_TIMEOUT;
        setTimeout(function () {
            if ($alert.is(':visible')) {
                $alert.fadeOut(300, function () {
                    $(this).remove();
                });
            }
        }, timeout);

        // Fechar manualmente
        $closeBtn.on('click', function () {
            $alert.fadeOut(300, function () {
                $(this).remove();
            });
        });
    };

    /**
    * AJAX request wrapper with CSRF protection
    * SECURITY: Incluir token CSRF automaticamente
    */
    Newbase.ajax = function (options) {
        // SECURITY: Obter token CSRF do GLPI
        const csrfToken = $('meta[name="glpi-csrf-token"]').attr('content') ||
            $('input[name="_glpi_csrf_token"]').first().val() ||
            '';

        if (!csrfToken) {
            console.warn('CSRF token not found. Request may fail.');
        }

        const defaults = {
            type: 'POST',
            dataType: 'json',
            timeout: CONFIG.AJAX_TIMEOUT,
            data: {},
            beforeSend: function (xhr) {
                // SECURITY: Adicionar CSRF token no header
                if (csrfToken) {
                    xhr.setRequestHeader('X-Glpi-Csrf-Token', csrfToken);
                }

                if (options.loadingTarget) {
                    Newbase.showLoading(options.loadingTarget);
                }

                // Callback customizado
                if (typeof options.beforeSendCallback === 'function') {
                    options.beforeSendCallback(xhr);
                }
            },
            complete: function (xhr, status) {
                if (options.loadingTarget) {
                    Newbase.hideLoading(options.loadingTarget);
                }

                // Callback customizado
                if (typeof options.completeCallback === 'function') {
                    options.completeCallback(xhr, status);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });

                // Mensagem de erro amigável
                let errorMessage = 'An error occurred. Please try again.';

                if (xhr.status === 403) {
                    errorMessage = 'Access denied. Please refresh the page and try again.';
                } else if (xhr.status === 401) {
                    errorMessage = 'Session expired. Please log in again.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please contact support.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please try again.';
                }

                Newbase.notify(errorMessage, 'error');

                // Callback customizado
                if (typeof options.errorCallback === 'function') {
                    options.errorCallback(xhr, status, error);
                }
            }
        };

        // SECURITY: Adicionar CSRF token aos dados POST
        if (csrfToken && (options.type === 'POST' || options.type === 'PUT' || options.type === 'DELETE')) {
            if (!options.data) {
                options.data = {};
            }

            if (typeof options.data === 'object') {
                options.data._glpi_csrf_token = csrfToken;
            }
        }

        return $.ajax($.extend(true, {}, defaults, options));
    };

    /**
    * Confirm dialog with accessibility
    * ACCESSIBILITY: Usar confirm nativo com callback seguro
    */
    Newbase.confirm = function (message, callback) {
        // SECURITY: Sanitizar mensagem (confirm é seguro, mas melhor prevenir)
        const safeMessage = String(message);

        if (confirm(safeMessage)) {
            if (typeof callback === 'function') {
                try {
                    callback();
                } catch (e) {
                    console.error('Confirm callback error:', e);
                }
            }
        }
    };

    /**
    * Format CNPJ
    * Mantém lógica original (já está segura)
    */
    Newbase.formatCNPJ = function (value) {
        if (typeof value !== 'string') {
            value = String(value);
        }

        value = value.replace(/\D/g, '');
        value = value.substring(0, 14); // SECURITY: Limitar tamanho

        if (value.length <= 14) {
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }

        return value;
    };

    /**
    * Format CPF
    * NOVO: Adicionar formatação de CPF
    */
    Newbase.formatCPF = function (value) {
        if (typeof value !== 'string') {
            value = String(value);
        }

        value = value.replace(/\D/g, '');
        value = value.substring(0, 11); // SECURITY: Limitar tamanho

        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        return value;
    };

    /**
    * Format CEP
    * Mantém lógica original com melhorias
    */
    Newbase.formatCEP = function (value) {
        if (typeof value !== 'string') {
            value = String(value);
        }

        value = value.replace(/\D/g, '');
        value = value.substring(0, 8); // SECURITY: Limitar tamanho

        if (value.length <= 8) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }

        return value;
    };

    /**
    * Format phone
    * Mantém lógica original com melhorias
    */
    Newbase.formatPhone = function (value) {
        if (typeof value !== 'string') {
            value = String(value);
        }

        value = value.replace(/\D/g, '');
        value = value.substring(0, 11); // SECURITY: Limitar tamanho

        if (value.length === 11) {
            // Celular: (XX) XXXXX-XXXX
            value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length === 10) {
            // Fixo: (XX) XXXX-XXXX
            value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else if (value.length === 9) {
            // Sem DDD: XXXXX-XXXX
            value = value.replace(/^(\d{5})(\d{4})/, '$1-$2');
        } else if (value.length === 8) {
            // Sem DDD fixo: XXXX-XXXX
            value = value.replace(/^(\d{4})(\d{4})/, '$1-$2');
        }

        return value;
    };

    /**
    * Validate CNPJ with improved algorithm
    * SECURITY: Validação robusta contra ataques
    */
    Newbase.validateCNPJ = function (cnpj) {
        if (typeof cnpj !== 'string') {
            cnpj = String(cnpj);
        }

        cnpj = cnpj.replace(/\D/g, '');

        // Verificações básicas
        if (cnpj.length !== 14) return false;
        if (/^(\d)\1+$/.test(cnpj)) return false; // Todos dígitos iguais

        try {
            // Validar primeiro dígito verificador
            let sum = 0;
            const weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            for (let i = 0; i < 12; i++) {
                sum += parseInt(cnpj.charAt(i), 10) * weights1[i];
            }

            let remainder = sum % 11;
            const digit1 = remainder < 2 ? 0 : 11 - remainder;

            if (parseInt(cnpj.charAt(12), 10) !== digit1) {
                return false;
            }

            // Validar segundo dígito verificador
            sum = 0;
            const weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            for (let i = 0; i < 13; i++) {
                sum += parseInt(cnpj.charAt(i), 10) * weights2[i];
            }

            remainder = sum % 11;
            const digit2 = remainder < 2 ? 0 : 11 - remainder;

            return parseInt(cnpj.charAt(13), 10) === digit2;
        } catch (e) {
            console.error('CNPJ validation error:', e);
            return false;
        }
    };

    /**
    * Validate CPF
    * NOVO: Adicionar validação de CPF
    */
    Newbase.validateCPF = function (cpf) {
        if (typeof cpf !== 'string') {
            cpf = String(cpf);
        }

        cpf = cpf.replace(/\D/g, '');

        // Verificações básicas
        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false; // Todos dígitos iguais

        try {
            // Validar primeiro dígito
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(cpf.charAt(i), 10) * (10 - i);
            }

            let remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(9), 10)) return false;

            // Validar segundo dígito
            sum = 0;
            for (let i = 0; i < 10; i++) {
                sum += parseInt(cpf.charAt(i), 10) * (11 - i);
            }

            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(10), 10)) return false;

            return true;
        } catch (e) {
            console.error('CPF validation error:', e);
            return false;
        }
    };

    /**
    * Validate email with robust regex
    * SECURITY: Regex mais robusta contra bypass
    */
    Newbase.validateEmail = function (email) {
        if (typeof email !== 'string') {
            return false;
        }

        // SECURITY: Limitar tamanho do email
        if (email.length > 254) {
            return false;
        }

        // Regex robusta baseada em RFC 5322
        const regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

        return regex.test(email);
    };

    /**
    * Escape HTML to prevent XSS
    * SECURITY: Sanitizar strings antes de inserir no DOM
    */
    Newbase.escapeHtml = function (text) {
        if (typeof text !== 'string') {
            text = String(text);
        }

        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#x27;',
            '/': '&#x2F;'
        };

        return text.replace(/[&<>"'/]/g, function (char) {
            return map[char];
        });
    };

    /**
    * Debounce function
    * PERFORMANCE: Mantém implementação original (já está boa)
    */
    Newbase.debounce = function (func, wait) {
        let timeout;

        return function executedFunction() {
            const context = this;
            const args = arguments;

            const later = function () {
                timeout = null;
                func.apply(context, args);
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait || CONFIG.DEBOUNCE_DELAY);
        };
    };

    /**
    * Throttle function
    * PERFORMANCE: Mantém implementação original (já está boa)
    */
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

    /**
    * Get URL parameter with security
    * SECURITY: Sanitizar parâmetros de URL
    */
    Newbase.getUrlParameter = function (name) {
        if (typeof name !== 'string') {
            return '';
        }

        // SECURITY: Escapar caracteres especiais do regex
        name = name.replace(/[[\]]/g, '\\$&');

        try {
            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
            const results = regex.exec(window.location.href);

            if (!results) return '';
            if (!results[2]) return '';

            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        } catch (e) {
            console.error('URL parameter parsing error:', e);
            return '';
        }
    };

    /**
    * Copy to clipboard with modern API
    * SECURITY: Usar Clipboard API moderna ao invés de execCommand
    */
    Newbase.copyToClipboard = function (text) {
        if (typeof text !== 'string') {
            text = String(text);
        }

        // PERFORMANCE: Usar Clipboard API moderna
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text)
                .then(function () {
                    Newbase.notify('Copied to clipboard!', 'success');
                })
                .catch(function (err) {
                    console.error('Clipboard error:', err);
                    Newbase.copyToClipboardFallback(text);
                });
        } else {
            // Fallback para navegadores antigos
            Newbase.copyToClipboardFallback(text);
        }
    };

    /**
    * Fallback method for clipboard
    */
    Newbase.copyToClipboardFallback = function (text) {
        const $temp = $('<textarea>')
            .val(text)
            .css({
                position: 'absolute',
                left: '-9999px',
                top: '0'
            })
            .appendTo('body')
            .select();

        try {
            const successful = document.execCommand('copy');

            if (successful) {
                Newbase.notify('Copied to clipboard!', 'success');
            } else {
                Newbase.notify('Copy failed. Please copy manually.', 'error');
            }
        } catch (err) {
            console.error('Copy error:', err);
            Newbase.notify('Copy not supported. Please copy manually.', 'error');
        }

        $temp.remove();
    };

    /**
    * Generate UUID v4
    * SECURITY: UUID seguro para IDs únicos
    */
    Newbase.generateUUID = function () {
        if (typeof crypto !== 'undefined' && crypto.randomUUID) {
            return crypto.randomUUID();
        }

        // Fallback
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    };

    /**
    * Destroy Newbase - Cleanup for memory leaks
    * PERFORMANCE: Remover event listeners ao destruir
    */
    Newbase.destroy = function () {
        if (!state.initialized) {
            return;
        }

        console.log('Destroying Newbase...');

        // Remover event listeners
        $cache.document.off('.modal');
        $cache.body.off('click', '.newbase-alert .close');
        $cache.body.off('click', '[data-modal-trigger]');
        $cache.body.off('click', '.modal-backdrop, .modal-close');
        $cache.body.off('click', '.modal-content');
        $cache.body.off('mouseenter', '[data-toggle="tooltip"]');
        $cache.body.off('click', '.newbase-table th[data-sortable]');

        // Fechar modais abertos
        state.activeModals.forEach(function (modal) {
            $(modal).hide();
        });

        // Limpar state
        state.initialized = false;
        state.activeModals = [];
        $cache = {};

        console.log('Newbase destroyed');
    };

    // INITIALIZE
    $(document).ready(function () {
        Newbase.init();
    });

    // PERFORMANCE: Cleanup antes de sair da página
    $(window).on('beforeunload', function () {
        // Não destruir completamente, apenas limpar recursos pesados
        state.activeModals.forEach(function (modal) {
            $(modal).hide();
        });
    });

})(jQuery);
