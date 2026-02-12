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

    // AJAX SETUP: Configuração Global CSRF (GLPI 10.0.20+)
    $(function () {
        // Captura o token CSRF gerado pelo core do GLPI
        // Tenta pegar primeiro da meta tag padrão
        var glpi_csrf_token = $('meta[name="glpi:csrf_token"]').attr('content');

        // FALLBACK: Se não achar na meta tag, tenta achar no campo oculto do formulário
        if (!glpi_csrf_token) {
            glpi_csrf_token = $('input[name="_glpi_csrf_token"]').first().val();
        }

        if (!glpi_csrf_token) {
            console.warn('Newbase: CSRF token not found in meta tags or form input');
            // Não retornar aqui permite que o script continue, mas avisa no console
        } else {
            console.log('Newbase: CSRF Token encontrado e configurado.');

            // Configura o jQuery para enviar esse token em TODOS os AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-Glpi-Csrf-Token': glpi_csrf_token
                },
                data: {
                    '_glpi_csrf_token': glpi_csrf_token
                }
            });
        }
    });

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

        // INTERFACE: Correção para Select2 e Passive Listeners
        if ($.fn.select2) {
            $.fn.select2.defaults.set("scrollAfterSelect", false);
        }

        // Inicializar componentes
        Newbase.initTooltips();
        Newbase.initAlerts();
        Newbase.initModals();
        Newbase.initTables();
        Newbase.initSearchButtons();

        state.initialized = true;

        const endTime = performance.now();
        console.log(`Newbase Plugin initialized in ${(endTime - startTime).toFixed(2)}ms`);
    };

    // ... (Mantenha o restante das funções utilitárias: initTooltips, initAlerts, etc. inalteradas, elas estão corretas) ...

    /**
    * Initialize tooltips with security
    * SECURITY: Sanitizar conteúdo dos tooltips
    */
    Newbase.initTooltips = function () {
        $cache.body.on('mouseenter', '[data-toggle="tooltip"]', function () {
            const $element = $(this);
            if ($element.data('tooltip-initialized')) return;

            const title = Newbase.escapeHtml($element.attr('title') || $element.data('original-title') || '');

            if (title) {
                $element.attr('data-original-title', title).removeAttr('title');
                if (typeof $.fn.tooltip === 'function') {
                    $element.tooltip({
                        html: false,
                        sanitize: true,
                        container: 'body'
                    });
                }
                $element.data('tooltip-initialized', true);
            }
        });
    };

    Newbase.initAlerts = function () {
        $cache.body.on('click', '.newbase-alert .close', function (e) {
            e.preventDefault();
            $(this).closest('.newbase-alert').fadeOut(300, function () { $(this).remove(); });
        });

        $('.newbase-alert[data-auto-dismiss="true"]').each(function () {
            const $alert = $(this);
            const timeout = parseInt($alert.data('timeout'), 10) || CONFIG.NOTIFICATION_TIMEOUT;
            setTimeout(function () {
                if ($alert.is(':visible')) {
                    $alert.fadeOut(300, function () { $(this).remove(); });
                }
            }, timeout);
        });
    };

    Newbase.initModals = function () {
        $cache.body.on('click', '[data-modal-trigger]', function (e) {
            e.preventDefault();
            const modalId = $(this).data('modal-trigger');
            if (!/^[a-zA-Z0-9_-]+$/.test(modalId)) {
                console.error('Invalid modal ID:', modalId);
                return;
            }
            const $modal = $('#' + modalId);
            if ($modal.length) Newbase.openModal($modal);
        });

        $cache.body.on('click', '.modal-backdrop, .modal-close', function () {
            Newbase.closeModal($(this).closest('.modal'));
        });

        $cache.body.on('click', '.modal-content', function (e) { e.stopPropagation(); });

        $cache.document.on('keydown.modal', function (e) {
            if (e.key === 'Escape' && state.activeModals.length > 0) {
                Newbase.closeModal($(state.activeModals[state.activeModals.length - 1]));
            }
        });
    };

    Newbase.openModal = function ($modal) {
        if (!$modal.length) return;
        $modal.fadeIn(200);
        state.activeModals.push($modal[0]);
        const $firstFocusable = $modal.find('input, button, select, textarea, a[href]').first();
        if ($firstFocusable.length) setTimeout(() => $firstFocusable.focus(), 250);
        $cache.body.addClass('modal-open');
    };

    Newbase.closeModal = function ($modal) {
        if (!$modal.length) return;
        $modal.fadeOut(200);
        const index = state.activeModals.indexOf($modal[0]);
        if (index > -1) state.activeModals.splice(index, 1);
        if (state.activeModals.length === 0) $cache.body.removeClass('modal-open');
    };

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
                Newbase.notify('Table too large to sort. Please use filters.', 'warning');
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

    Newbase.getAjaxUrl = function (endpoint) {
        const root = (typeof CFG_GLPI !== 'undefined' && CFG_GLPI.root_doc) ? CFG_GLPI.root_doc : '';
        return root + '/plugins/newbase/ajax/' + endpoint;
    };

    Newbase.initSearchButtons = function () {
        $cache.body.on('click', '#btn-cnpj', function (e) {
            e.preventDefault();
            const cnpj = $('#cnpj').val();
            if (!cnpj) { Newbase.notify('Por favor, insira um CNPJ', 'warning'); return; }

            // Captura token CSRF atualizado (caso tenha mudado)
            let csrfToken = $('meta[name="glpi:csrf_token"]').attr('content');
            if (!csrfToken) csrfToken = $('input[name="_glpi_csrf_token"]').first().val();

            if (!csrfToken) {
                Newbase.notify('Erro de segurança (CSRF ausente). Recarregue a página.', 'error');
                return;
            }

            Newbase.ajax({
                url: Newbase.getAjaxUrl('searchCompany.php'),
                data: { cnpj: cnpj, _glpi_csrf_token: csrfToken },
                loadingTarget: $(this).closest('td'),
                success: function (response) {
                    if (response.success) {
                        const data = response.data;
                        $('#name').val(data.corporate_name || '');
                        $('#corporate_name').val(data.corporate_name || '');
                        $('#fantasy_name').val(data.fantasy_name || '');
                        $('#email').val(data.email || '');
                        $('#phone').val(data.phone || '');
                        $('#address').val(data.address || '');
                        $('#city').val(data.city || '');
                        $('#state').val(data.state || '');
                        $('#cep').val(data.postcode || '');
                        $('#country').val('BR');
                        Newbase.notify(response.message, 'success');
                    } else {
                        Newbase.notify(response.message || 'Erro ao buscar CNPJ', 'error');
                    }
                }
            });
        });

        $cache.body.on('click', '#btn-cep', function (e) {
            e.preventDefault();
            const cep = $('#cep').val();
            if (!cep) { Newbase.notify('Por favor, insira um CEP', 'warning'); return; }

            let csrfToken = $('meta[name="glpi:csrf_token"]').attr('content');
            if (!csrfToken) csrfToken = $('input[name="_glpi_csrf_token"]').first().val();

            if (!csrfToken) {
                Newbase.notify('Erro de segurança (CSRF ausente). Recarregue a página.', 'error');
                return;
            }

            Newbase.ajax({
                url: Newbase.getAjaxUrl('searchAddress.php'),
                data: { cep: cep, _glpi_csrf_token: csrfToken },
                loadingTarget: $(this).closest('td'),
                success: function (response) {
                    if (response.success) {
                        const data = response.data;
                        $('#address').val(data.logradouro || '');
                        $('#city').val(data.localidade || '');
                        $('#state').val(data.uf || '');
                        Newbase.notify(response.message, 'success');
                    } else {
                        Newbase.notify(response.message || 'Erro ao buscar CEP', 'error');
                    }
                }
            });
        });
    };

    Newbase.showLoading = function (target) {
        const $target = $(target);
        if ($target.hasClass('newbase-form-loading')) return;
        $target.addClass('newbase-form-loading');
        const $overlay = $('<div>').addClass('newbase-loading-overlay');
        const $spinner = $('<div>').addClass('newbase-loading');
        $overlay.append($spinner);
        $target.append($overlay);
    };

    Newbase.hideLoading = function (target) {
        const $target = $(target);
        $target.removeClass('newbase-form-loading');
        $target.find('.newbase-loading-overlay').remove();
    };

    Newbase.notify = function (message, type) {
        type = type || 'info';
        const safeMessage = Newbase.escapeHtml(String(message));
        const validTypes = ['info', 'success', 'warning', 'error'];
        if (!validTypes.includes(type)) type = 'info';
        const alertClass = 'newbase-alert-' + type;

        const $alert = $('<div>')
            .addClass('newbase-alert')
            .addClass(alertClass)
            .attr('role', 'alert')
            .attr('aria-live', 'polite');

        const $message = $('<span>').text(safeMessage);
        const $closeBtn = $('<button>').addClass('close').attr('type', 'button').attr('aria-label', 'Close').html('&times;');

        $alert.append($message).append($closeBtn);
        $cache.body.append($alert);

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

        setTimeout(function () {
            if ($alert.is(':visible')) $alert.fadeOut(300, function () { $(this).remove(); });
        }, CONFIG.NOTIFICATION_TIMEOUT);

        $closeBtn.on('click', function () {
            $alert.fadeOut(300, function () { $(this).remove(); });
        });
    };

    Newbase.ajax = function (options) {
        const defaults = {
            type: 'POST',
            dataType: 'json',
            timeout: CONFIG.AJAX_TIMEOUT,
            data: {},
            beforeSend: function (xhr) {
                if (options.loadingTarget) Newbase.showLoading(options.loadingTarget);
                if (typeof options.beforeSendCallback === 'function') options.beforeSendCallback(xhr);
            },
            complete: function (xhr, status) {
                if (options.loadingTarget) Newbase.hideLoading(options.loadingTarget);
                if (typeof options.completeCallback === 'function') options.completeCallback(xhr, status);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', { status: status, error: error, response: xhr.responseText });
                let errorMessage = 'An error occurred. Please try again.';
                if (xhr.status === 403) errorMessage = 'Access denied. Please refresh the page and try again.';
                else if (xhr.status === 401) errorMessage = 'Session expired. Please log in again.';
                else if (xhr.status === 500) errorMessage = 'Server error. Please contact support.';
                else if (status === 'timeout') errorMessage = 'Request timeout. Please try again.';

                Newbase.notify(errorMessage, 'error');
                if (typeof options.errorCallback === 'function') options.errorCallback(xhr, status, error);
            }
        };
        return $.ajax($.extend(true, {}, defaults, options));
    };

    Newbase.confirm = function (message, callback) {
        if (confirm(String(message))) {
            if (typeof callback === 'function') {
                try { callback(); } catch (e) { console.error('Confirm callback error:', e); }
            }
        }
    };

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
        if (value.length <= 8) value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        return value;
    };

    Newbase.formatPhone = function (value) {
        value = String(value || '').replace(/\D/g, '').substring(0, 11);
        if (value.length === 11) value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        else if (value.length === 10) value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        else if (value.length === 9) value = value.replace(/^(\d{5})(\d{4})/, '$1-$2');
        else if (value.length === 8) value = value.replace(/^(\d{4})(\d{4})/, '$1-$2');
        return value;
    };

    Newbase.validateCNPJ = function (cnpj) {
        cnpj = String(cnpj || '').replace(/\D/g, '');
        if (cnpj.length !== 14) return false;
        if (/^(\d)\1+$/.test(cnpj)) return false;
        try {
            let sum = 0;
            const weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for (let i = 0; i < 12; i++) sum += parseInt(cnpj.charAt(i), 10) * weights1[i];
            let remainder = sum % 11;
            const digit1 = remainder < 2 ? 0 : 11 - remainder;
            if (parseInt(cnpj.charAt(12), 10) !== digit1) return false;

            sum = 0;
            const weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for (let i = 0; i < 13; i++) sum += parseInt(cnpj.charAt(i), 10) * weights2[i];
            remainder = sum % 11;
            const digit2 = remainder < 2 ? 0 : 11 - remainder;
            return parseInt(cnpj.charAt(13), 10) === digit2;
        } catch (e) { return false; }
    };

    Newbase.validateCPF = function (cpf) {
        cpf = String(cpf || '').replace(/\D/g, '');
        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false;
        try {
            let sum = 0;
            for (let i = 0; i < 9; i++) sum += parseInt(cpf.charAt(i), 10) * (10 - i);
            let remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(9), 10)) return false;

            sum = 0;
            for (let i = 0; i < 10; i++) sum += parseInt(cpf.charAt(i), 10) * (11 - i);
            remainder = (sum * 10) % 11;
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(10), 10)) return false;
            return true;
        } catch (e) { return false; }
    };

    Newbase.validateEmail = function (email) {
        if (typeof email !== 'string' || email.length > 254) return false;
        const regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
        return regex.test(email);
    };

    Newbase.escapeHtml = function (text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#x27;', '/': '&#x2F;' };
        return String(text).replace(/[&<>"'/]/g, function (char) { return map[char]; });
    };

    Newbase.debounce = function (func, wait) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () { func.apply(context, args); }, wait || CONFIG.DEBOUNCE_DELAY);
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
                setTimeout(function () { inThrottle = false; }, limit || CONFIG.THROTTLE_DELAY);
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
        } catch (e) { return ''; }
    };

    Newbase.copyToClipboard = function (text) {
        text = String(text);
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text)
                .then(() => Newbase.notify('Copied to clipboard!', 'success'))
                .catch(() => Newbase.copyToClipboardFallback(text));
        } else {
            Newbase.copyToClipboardFallback(text);
        }
    };

    Newbase.copyToClipboardFallback = function (text) {
        const $temp = $('<textarea>').val(text).css({ position: 'absolute', left: '-9999px', top: '0' }).appendTo('body').select();
        try {
            if (document.execCommand('copy')) Newbase.notify('Copied to clipboard!', 'success');
            else Newbase.notify('Copy failed. Please copy manually.', 'error');
        } catch (err) {
            Newbase.notify('Copy not supported. Please copy manually.', 'error');
        }
        $temp.remove();
    };

    Newbase.generateUUID = function () {
        if (typeof crypto !== 'undefined' && crypto.randomUUID) return crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    };

    Newbase.destroy = function () {
        if (!state.initialized) return;
        console.log('Destroying Newbase...');
        $cache.document.off('.modal');
        $cache.body.off('click', '.newbase-alert .close');
        $cache.body.off('click', '[data-modal-trigger]');
        $cache.body.off('click', '.modal-backdrop, .modal-close');
        $cache.body.off('click', '.modal-content');
        $cache.body.off('mouseenter', '[data-toggle="tooltip"]');
        $cache.body.off('click', '.newbase-table th[data-sortable]');
        state.activeModals.forEach(modal => $(modal).hide());
        state.initialized = false;
        state.activeModals = [];
        $cache = {};
        console.log('Newbase destroyed');
    };

    $(document).ready(function () {
        Newbase.init();
    });

    $(window).on('beforeunload', function () {
        state.activeModals.forEach(modal => $(modal).hide());
    });

})(jQuery);
