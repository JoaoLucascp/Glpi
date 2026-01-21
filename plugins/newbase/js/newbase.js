/**
* Main JavaScript file for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/


(function($) {
    'use strict';

    // Newbase namespace
    window.Newbase = window.Newbase || {};

    /**
     * Initialize plugin
     */
    Newbase.init = function() {
        console.log('Newbase Plugin initialized');

        // Initialize components
        Newbase.initTooltips();
        Newbase.initAlerts();
        Newbase.initModals();
        Newbase.initTables();
    };

    /**
     * Initialize tooltips
     */
    Newbase.initTooltips = function() {
        $('[data-toggle="tooltip"]').each(function() {
            $(this).tooltip();
        });
    };

    /**
     * Initialize alerts
     */
    Newbase.initAlerts = function() {
        $('.newbase-alert').each(function() {
            var $alert = $(this);

            // Auto-dismiss after 5 seconds
            if ($alert.data('auto-dismiss')) {
                setTimeout(function() {
                    $alert.fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }

            // Close button
            $alert.find('.close').on('click', function() {
                $alert.fadeOut(function() {
                    $(this).remove();
                });
            });
        });
    };

    /**
     * Initialize modals
     */
    Newbase.initModals = function() {
        $('[data-modal-trigger]').on('click', function(e) {
            e.preventDefault();
            var modalId = $(this).data('modal-trigger');
            $('#' + modalId).fadeIn();
        });

        $('.modal-close, .modal-backdrop').on('click', function() {
            $(this).closest('.modal').fadeOut();
        });

        // Prevent modal content clicks from closing
        $('.modal-content').on('click', function(e) {
            e.stopPropagation();
        });

        // ESC key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal:visible').fadeOut();
            }
        });
    };

    /**
     * Initialize tables
     */
    Newbase.initTables = function() {
        // Responsive tables wrapper
        $('.newbase-table').each(function() {
            if (!$(this).parent().hasClass('newbase-table-wrapper')) {
                $(this).wrap('<div class="newbase-table-wrapper"></div>');
            }
        });

        // Table sorting
        $('.newbase-table th[data-sortable]').on('click', function() {
            var $th = $(this);
            var $table = $th.closest('table');
            var column = $th.index();
            var direction = $th.hasClass('sort-asc') ? 'desc' : 'asc';

            // Remove sort classes from other columns
            $table.find('th').removeClass('sort-asc sort-desc');

            // Add sort class to current column
            $th.addClass('sort-' + direction);

            // Sort rows
            var $tbody = $table.find('tbody');
            var $rows = $tbody.find('tr').toArray();

            $rows.sort(function(a, b) {
                var aValue = $(a).find('td').eq(column).text();
                var bValue = $(b).find('td').eq(column).text();

                if (direction === 'asc') {
                    return aValue.localeCompare(bValue, undefined, {numeric: true});
                } else {
                    return bValue.localeCompare(aValue, undefined, {numeric: true});
                }
            });

            $tbody.html($rows);
        });
    };

    /**
     * Show loading indicator
     */
    Newbase.showLoading = function(target) {
        var $target = $(target);
        $target.addClass('newbase-form-loading');
        $target.append('<div class="newbase-loading-overlay"><div class="newbase-loading"></div></div>');
    };

    /**
     * Hide loading indicator
     */
    Newbase.hideLoading = function(target) {
        var $target = $(target);
        $target.removeClass('newbase-form-loading');
        $target.find('.newbase-loading-overlay').remove();
    };

    /**
     * Show notification
     */
    Newbase.notify = function(message, type) {
        type = type || 'info';

        var alertClass = 'newbase-alert-' + type;
        var $alert = $('<div class="newbase-alert ' + alertClass + '" data-auto-dismiss="true">' + message + '</div>');

        $('body').append($alert);

        // Position at top center
        $alert.css({
            position: 'fixed',
            top: '20px',
            left: '50%',
            transform: 'translateX(-50%)',
            zIndex: 9999,
            minWidth: '300px',
            maxWidth: '600px'
        });

        // Auto dismiss
        setTimeout(function() {
            $alert.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    };

    /**
     * AJAX request wrapper
     */
    Newbase.ajax = function(options) {
        var defaults = {
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                if (options.loadingTarget) {
                    Newbase.showLoading(options.loadingTarget);
                }
            },
            complete: function() {
                if (options.loadingTarget) {
                    Newbase.hideLoading(options.loadingTarget);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Newbase.notify('An error occurred. Please try again.', 'error');
            }
        };

        return $.ajax($.extend({}, defaults, options));
    };

    /**
     * Confirm dialog
     */
    Newbase.confirm = function(message, callback) {
        if (confirm(message)) {
            if (typeof callback === 'function') {
                callback();
            }
        }
    };

    /**
     * Format CNPJ
     */
    Newbase.formatCNPJ = function(value) {
        value = value.replace(/\D/g, '');
        if (value.length <= 14) {
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }
        return value;
    };

    /**
     * Format CEP
     */
    Newbase.formatCEP = function(value) {
        value = value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/^(\d{5})(\d)/, '$1-$2');
        }
        return value;
    };

    /**
     * Format phone
     */
    Newbase.formatPhone = function(value) {
        value = value.replace(/\D/g, '');
        if (value.length === 11) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length === 10) {
            value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }
        return value;
    };

    /**
     * Validate CNPJ
     */
    Newbase.validateCNPJ = function(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');

        if (cnpj.length !== 14) return false;
        if (/^(\d)\1+$/.test(cnpj)) return false;

        // Validate first check digit
        var sum = 0;
        var weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for (var i = 0; i < 12; i++) {
            sum += parseInt(cnpj.charAt(i)) * weights[i];
        }
        var remainder = sum % 11;
        var digit1 = remainder < 2 ? 0 : 11 - remainder;
        if (parseInt(cnpj.charAt(12)) !== digit1) return false;

        // Validate second check digit
        sum = 0;
        weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for (i = 0; i < 13; i++) {
            sum += parseInt(cnpj.charAt(i)) * weights[i];
        }
        remainder = sum % 11;
        var digit2 = remainder < 2 ? 0 : 11 - remainder;

        return parseInt(cnpj.charAt(13)) === digit2;
    };

    /**
     * Validate email
     */
    Newbase.validateEmail = function(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    };

    /**
     * Debounce function
     */
    Newbase.debounce = function(func, wait) {
        var timeout;
        return function() {
            var context = this;
            var args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    };

    /**
     * Throttle function
     */
    Newbase.throttle = function(func, limit) {
        var inThrottle;
        return function() {
            var args = arguments;
            var context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(function() {
                    inThrottle = false;
                }, limit);
            }
        };
    };

    /**
     * Get URL parameter
     */
    Newbase.getUrlParameter = function(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    /**
     * Copy to clipboard
     */
    Newbase.copyToClipboard = function(text) {
        var $temp = $('<textarea>');
        $('body').append($temp);
        $temp.val(text).select();
        document.execCommand('copy');
        $temp.remove();
        Newbase.notify('Copied to clipboard!', 'success');
    };

    // Initialize on document ready
    $(document).ready(function() {
        Newbase.init();
    });

})(jQuery);
