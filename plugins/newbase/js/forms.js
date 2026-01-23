/**
* Forms JavaScript for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/

(function ($) {
    'use strict';

    // ====================
    // SECURITY: Namespace isolado para evitar poluição global
    // ====================
    window.Newbase = window.Newbase || {};
    Newbase.Forms = Newbase.Forms || {};

    // ====================
    // CONFIGURATION
    // ====================
    const CONFIG = {
        AUTO_SAVE_DELAY: 3000,
        AUTO_SAVE_PREFIX: 'newbase_autosave_',
        AUTO_SAVE_EXPIRY: 24 * 60 * 60 * 1000, // 24 horas
        DEBOUNCE_DELAY: 300,
        MAX_FIELD_LENGTH: 10000,
        STORAGE_QUOTA_CHECK: true
    };

    // ====================
    // SECURITY: Lista de campos sensíveis que NÃO devem ser salvos
    // ====================
    const SENSITIVE_FIELDS = [
        'password',
        'token',
        '_glpi_csrf_token',
        'csrf_token',
        'credit_card',
        'cvv',
        'ssn'
    ];

    // ====================
    // CACHE: Armazenar seletores para melhor performance
    // ====================
    let $cachedElements = {};

    /**
     * Initialize forms with performance optimization
     */
    Newbase.Forms.init = function () {
        console.log('Newbase Forms initialized');

        // PERFORMANCE: Cache elementos comuns
        $cachedElements = {
            body: $('body'),
            forms: $('form[data-newbase]'),
            inputs: $('input[data-format]')
        };

        // Inicializar componentes
        Newbase.Forms.initFormatting();
        Newbase.Forms.initValidation();
        Newbase.Forms.initAutoSave();
        Newbase.Forms.initCharCounter();

        // SECURITY: Limpar auto-saves expirados
        Newbase.Forms.cleanExpiredAutoSaves();
    };

    /**
     * Initialize input formatting with event delegation
     * PERFORMANCE: Usa event delegation para melhor performance
     */
    Newbase.Forms.initFormatting = function () {
        // PERFORMANCE: Event delegation no body para elementos dinâmicos
        $cachedElements.body.on('input', 'input[data-format]', function () {
            const $field = $(this);
            const format = $field.data('format');
            const value = $field.val();

            // SECURITY: Limitar tamanho do input
            if (value.length > CONFIG.MAX_FIELD_LENGTH) {
                $field.val(value.substring(0, CONFIG.MAX_FIELD_LENGTH));
                return;
            }

            // Aplicar formatação baseada no tipo
            switch (format) {
                case 'cnpj':
                    $field.val(Newbase.formatCNPJ(value));
                    break;
                case 'cpf':
                    $field.val(Newbase.formatCPF(value));
                    break;
                case 'cep':
                    $field.val(Newbase.formatCEP(value));
                    break;
                case 'phone':
                    $field.val(Newbase.formatPhone(value));
                    break;
                case 'numbers':
                    $field.val(value.replace(/\D/g, ''));
                    break;
                case 'uppercase':
                    $field.val(value.toUpperCase());
                    break;
                case 'lowercase':
                    $field.val(value.toLowerCase());
                    break;
                case 'alphanumeric':
                    $field.val(value.replace(/[^a-zA-Z0-9]/g, ''));
                    break;
                default:
                    console.warn('Unknown format:', format);
            }
        });

        // SECURITY: Prevenir paste de conteúdo malicioso em campos numéricos
        $cachedElements.body.on('paste', 'input[data-format="numbers"]', function (e) {
            e.preventDefault();
            const pasteData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            const cleanData = pasteData.replace(/\D/g, '');
            $(this).val(cleanData).trigger('input');
        });
    };

    /**
     * Initialize form validation with GLPI integration
     * SECURITY: Validações completas com feedback ao usuário
     */
    Newbase.Forms.initValidation = function () {
        // Validar no submit
        $cachedElements.body.on('submit', 'form[data-validate]', function (e) {
            const $form = $(this);

            // SECURITY: Verificar se CSRF token existe (GLPI padrão)
            if (!$form.find('input[name="_glpi_csrf_token"]').length) {
                console.error('CSRF token missing in form');
                Newbase.notify('Security token missing. Please refresh the page.', 'error');
                e.preventDefault();
                return false;
            }

            const isValid = Newbase.Forms.validateForm($form);

            if (!isValid) {
                e.preventDefault();
                Newbase.notify('Please correct the errors in the form', 'error');

                // ACCESSIBILITY: Focar primeiro campo com erro
                const $firstError = $form.find('.newbase-form-control.error').first();
                if ($firstError.length) {
                    $firstError.focus();
                    // PERFORMANCE: Scroll suave até o erro
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 300);
                }

                return false;
            }

            return true;
        });

        // PERFORMANCE: Validação em tempo real com debounce
        const debouncedValidation = Newbase.debounce(function () {
            Newbase.Forms.validateField($(this));
        }, CONFIG.DEBOUNCE_DELAY);

        $cachedElements.body.on('blur', 'input[data-validate-realtime], textarea[data-validate-realtime]', debouncedValidation);
    };

    /**
     * Validate entire form
     * @param {jQuery} $form - Form element
     * @returns {boolean} - Is form valid
     */
    Newbase.Forms.validateForm = function ($form) {
        let isValid = true;

        // Limpar erros anteriores
        $form.find('.newbase-form-error').removeClass('show');
        $form.find('.newbase-form-control').removeClass('error');

        // SECURITY: Validar campos obrigatórios
        $form.find('[required]').each(function () {
            const $field = $(this);
            const value = $.trim($field.val());

            if (!value) {
                isValid = false;
                Newbase.Forms.showFieldError($field, Newbase.i18n('This field is required'));
            }
        });

        // SECURITY: Validar email com regex forte
        $form.find('input[type="email"]').each(function () {
            const $field = $(this);
            const value = $.trim($field.val());

            if (value && !Newbase.validateEmail(value)) {
                isValid = false;
                Newbase.Forms.showFieldError($field, Newbase.i18n('Invalid email address'));
            }
        });

        // SECURITY: Validar CNPJ com algoritmo completo
        $form.find('input[data-validate="cnpj"]').each(function () {
            const $field = $(this);
            const value = $field.val().replace(/\D/g, '');

            if (value && !Newbase.validateCNPJ(value)) {
                isValid = false;
                Newbase.Forms.showFieldError($field, Newbase.i18n('Invalid CNPJ'));
            }
        });

        // SECURITY: Validar CPF
        $form.find('input[data-validate="cpf"]').each(function () {
            const $field = $(this);
            const value = $field.val().replace(/\D/g, '');

            if (value && !Newbase.validateCPF(value)) {
                isValid = false;
                Newbase.Forms.showFieldError($field, Newbase.i18n('Invalid CPF'));
            }
        });

        // Validar comprimento mínimo
        $form.find('[data-min-length]').each(function () {
            const $field = $(this);
            const value = $.trim($field.val());
            const minLength = parseInt($field.data('min-length'), 10);

            if (value && value.length < minLength) {
                isValid = false;
                Newbase.Forms.showFieldError(
                    $field,
                    Newbase.i18n('Minimum {0} characters required', minLength)
                );
            }
        });

        // Validar comprimento máximo
        $form.find('[data-max-length]').each(function () {
            const $field = $(this);
            const value = $.trim($field.val());
            const maxLength = parseInt($field.data('max-length'), 10);

            if (value && value.length > maxLength) {
                isValid = false;
                Newbase.Forms.showFieldError(
                    $field,
                    Newbase.i18n('Maximum {0} characters allowed', maxLength)
                );
            }
        });

        // SECURITY: Validar pattern com sanitização
        $form.find('[data-pattern]').each(function () {
            const $field = $(this);
            const value = $.trim($field.val());
            const patternString = $field.data('pattern');

            if (value && patternString) {
                try {
                    const pattern = new RegExp(patternString);
                    if (!pattern.test(value)) {
                        isValid = false;
                        Newbase.Forms.showFieldError($field, Newbase.i18n('Invalid format'));
                    }
                } catch (e) {
                    console.error('Invalid regex pattern:', patternString, e);
                }
            }
        });

        // SECURITY: Validar campos correspondentes (senha, confirmação)
        $form.find('[data-match]').each(function () {
            const $field = $(this);
            const matchFieldId = $field.data('match');
            const $matchField = $('#' + matchFieldId);

            if ($matchField.length) {
                const value = $field.val();
                const matchValue = $matchField.val();

                if (value !== matchValue) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, Newbase.i18n('Fields do not match'));
                }
            }
        });

        return isValid;
    };

    /**
     * Validate single field
     * PERFORMANCE: Validação otimizada para campos individuais
     */
    Newbase.Forms.validateField = function ($field) {
        const value = $.trim($field.val());
        let isValid = true;
        let errorMessage = '';

        // Limpar erro anterior
        Newbase.Forms.clearFieldError($field);

        // Validação de campo obrigatório
        if ($field.prop('required') && !value) {
            isValid = false;
            errorMessage = Newbase.i18n('This field is required');
        }

        // Validação de email
        if (isValid && $field.attr('type') === 'email' && value && !Newbase.validateEmail(value)) {
            isValid = false;
            errorMessage = Newbase.i18n('Invalid email address');
        }

        // Validação de CNPJ
        if (isValid && $field.data('validate') === 'cnpj' && value) {
            const cnpj = value.replace(/\D/g, '');
            if (!Newbase.validateCNPJ(cnpj)) {
                isValid = false;
                errorMessage = Newbase.i18n('Invalid CNPJ');
            }
        }

        // Validação de CPF
        if (isValid && $field.data('validate') === 'cpf' && value) {
            const cpf = value.replace(/\D/g, '');
            if (!Newbase.validateCPF(cpf)) {
                isValid = false;
                errorMessage = Newbase.i18n('Invalid CPF');
            }
        }

        // Validação de comprimento mínimo
        if (isValid && $field.data('min-length')) {
            const minLength = parseInt($field.data('min-length'), 10);
            if (value && value.length < minLength) {
                isValid = false;
                errorMessage = Newbase.i18n('Minimum {0} characters required', minLength);
            }
        }

        // Validação de comprimento máximo
        if (isValid && $field.data('max-length')) {
            const maxLength = parseInt($field.data('max-length'), 10);
            if (value && value.length > maxLength) {
                isValid = false;
                errorMessage = Newbase.i18n('Maximum {0} characters allowed', maxLength);
            }
        }

        if (!isValid) {
            Newbase.Forms.showFieldError($field, errorMessage);
        } else if (value) {
            Newbase.Forms.showFieldSuccess($field);
        }

        return isValid;
    };

    /**
     * Show field error with accessibility
     * ACCESSIBILITY: Adiciona aria-invalid para leitores de tela
     */
    Newbase.Forms.showFieldError = function ($field, message) {
        $field.addClass('error').attr('aria-invalid', 'true');

        let $error = $field.siblings('.newbase-form-error');
        if (!$error.length) {
            $error = $('<div class="newbase-form-error" role="alert" aria-live="polite"></div>');
            $field.after($error);
        }

        // SECURITY: Usar .text() para prevenir XSS
        $error.text(message).addClass('show');
    };

    /**
     * Clear field error
     */
    Newbase.Forms.clearFieldError = function ($field) {
        $field.removeClass('error success').removeAttr('aria-invalid');
        $field.siblings('.newbase-form-error').removeClass('show');
        $field.siblings('.newbase-form-success').removeClass('show');
    };

    /**
     * Show field success
     */
    Newbase.Forms.showFieldSuccess = function ($field) {
        $field.addClass('success').removeAttr('aria-invalid');

        let $success = $field.siblings('.newbase-form-success');
        if (!$success.length) {
            $success = $('<div class="newbase-form-success" aria-live="polite"></div>');
            $field.after($success);
        }

        $success.html('<i class="fas fa-check-circle"></i>').addClass('show');
    };

    /**
     * Initialize auto-save with security
     * SECURITY: Não salvar campos sensíveis
     */
    Newbase.Forms.initAutoSave = function () {
        const $autoSaveForms = $('form[data-autosave]');

        $autoSaveForms.each(function () {
            const $form = $(this);
            const formId = $form.attr('id') || 'form_' + Newbase.generateUUID();
            const delay = parseInt($form.data('autosave'), 10) || CONFIG.AUTO_SAVE_DELAY;

            // Carregar dados salvos
            Newbase.Forms.loadAutoSave(formId, $form);

            // PERFORMANCE: Salvar com debounce
            const debouncedSave = Newbase.debounce(function () {
                Newbase.Forms.saveAutoSave(formId, $form);
            }, delay);

            $form.on('input change', 'input, textarea, select', debouncedSave);

            // Limpar auto-save no submit bem-sucedido
            $form.on('submit', function (e) {
                // Aguardar resposta do servidor antes de limpar
                setTimeout(function () {
                    if (!$form.find('.error').length) {
                        Newbase.Forms.clearAutoSave(formId);
                    }
                }, 1000);
            });
        });
    };

    /**
     * Save form data to localStorage with security
     * SECURITY: Não salvar campos sensíveis + verificar quota
     */
    Newbase.Forms.saveAutoSave = function (formId, $form) {
        const data = {
            timestamp: Date.now(),
            values: {}
        };

        $form.find('input, textarea, select').each(function () {
            const $field = $(this);
            const name = $field.attr('name');
            const type = $field.attr('type');

            // SECURITY: Pular campos sensíveis
            if (!name || Newbase.Forms.isSensitiveField(name)) {
                return;
            }

            // SECURITY: Limitar tamanho do valor
            let value = $field.val();
            if (typeof value === 'string' && value.length > CONFIG.MAX_FIELD_LENGTH) {
                value = value.substring(0, CONFIG.MAX_FIELD_LENGTH);
            }

            if (type === 'checkbox') {
                data.values[name] = $field.is(':checked');
            } else if (type === 'radio') {
                if ($field.is(':checked')) {
                    data.values[name] = value;
                }
            } else {
                data.values[name] = value;
            }
        });

        try {
            // SECURITY: Verificar quota do localStorage
            if (CONFIG.STORAGE_QUOTA_CHECK) {
                const dataStr = JSON.stringify(data);
                const dataSize = new Blob([dataStr]).size;

                // Alertar se > 500KB
                if (dataSize > 500000) {
                    console.warn('Auto-save data is very large:', dataSize, 'bytes');
                }
            }

            const storageKey = CONFIG.AUTO_SAVE_PREFIX + formId;
            localStorage.setItem(storageKey, JSON.stringify(data));
            console.log('Form auto-saved:', formId);
        } catch (e) {
            if (e.name === 'QuotaExceededError') {
                console.error('LocalStorage quota exceeded. Clearing old auto-saves...');
                Newbase.Forms.cleanExpiredAutoSaves();
            } else {
                console.error('Auto-save error:', e);
            }
        }
    };

    /**
     * Load saved form data from localStorage
     */
    Newbase.Forms.loadAutoSave = function (formId, $form) {
        try {
            const storageKey = CONFIG.AUTO_SAVE_PREFIX + formId;
            const savedData = localStorage.getItem(storageKey);

            if (!savedData) return;

            const data = JSON.parse(savedData);

            // SECURITY: Verificar se não expirou
            if (data.timestamp && (Date.now() - data.timestamp > CONFIG.AUTO_SAVE_EXPIRY)) {
                Newbase.Forms.clearAutoSave(formId);
                return;
            }

            // Restaurar valores
            Object.keys(data.values).forEach(function (name) {
                const $field = $form.find('[name="' + name + '"]');
                const type = $field.attr('type');

                if (type === 'checkbox') {
                    $field.prop('checked', data.values[name]);
                } else if (type === 'radio') {
                    $form.find('[name="' + name + '"][value="' + data.values[name] + '"]').prop('checked', true);
                } else {
                    $field.val(data.values[name]);
                }
            });

            console.log('Form data restored:', formId);

            // ACCESSIBILITY: Notificar usuário
            Newbase.notify('Draft restored from auto-save', 'info');

        } catch (e) {
            console.error('Auto-load error:', e);
            Newbase.Forms.clearAutoSave(formId);
        }
    };

    /**
     * Clear saved form data
     */
    Newbase.Forms.clearAutoSave = function (formId) {
        try {
            const storageKey = CONFIG.AUTO_SAVE_PREFIX + formId;
            localStorage.removeItem(storageKey);
            console.log('Auto-save cleared:', formId);
        } catch (e) {
            console.error('Clear auto-save error:', e);
        }
    };

    /**
     * Clean expired auto-saves from localStorage
     * PERFORMANCE: Limpar dados antigos para liberar espaço
     */
    Newbase.Forms.cleanExpiredAutoSaves = function () {
        try {
            const now = Date.now();
            const keysToRemove = [];

            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);

                if (key && key.startsWith(CONFIG.AUTO_SAVE_PREFIX)) {
                    try {
                        const data = JSON.parse(localStorage.getItem(key));

                        if (data.timestamp && (now - data.timestamp > CONFIG.AUTO_SAVE_EXPIRY)) {
                            keysToRemove.push(key);
                        }
                    } catch (e) {
                        // Dados corrompidos, remover
                        keysToRemove.push(key);
                    }
                }
            }

            keysToRemove.forEach(key => localStorage.removeItem(key));

            if (keysToRemove.length > 0) {
                console.log('Cleaned expired auto-saves:', keysToRemove.length);
            }
        } catch (e) {
            console.error('Clean expired auto-saves error:', e);
        }
    };

    /**
     * Check if field is sensitive and should not be auto-saved
     * SECURITY: Prevenir salvamento de dados sensíveis
     */
    Newbase.Forms.isSensitiveField = function (fieldName) {
        const lowerName = fieldName.toLowerCase();

        return SENSITIVE_FIELDS.some(function (sensitive) {
            return lowerName.includes(sensitive);
        });
    };

    /**
     * Initialize character counter
     */
    Newbase.Forms.initCharCounter = function () {
        $('textarea[data-max-chars], input[data-max-chars]').each(function () {
            const $field = $(this);
            const maxChars = parseInt($field.data('max-chars'), 10);

            const $counter = $('<div class="char-counter" role="status" aria-live="polite"></div>');
            $field.after($counter);

            const updateCounter = function () {
                const currentLength = $field.val().length;
                const remaining = maxChars - currentLength;

                // SECURITY: Truncar se exceder
                if (currentLength > maxChars) {
                    $field.val($field.val().substring(0, maxChars));
                    return;
                }

                $counter.text(remaining + ' characters remaining');

                if (remaining < 50) {
                    $counter.addClass('warning');
                } else {
                    $counter.removeClass('warning');
                }

                if (remaining < 0) {
                    $counter.addClass('over-limit');
                } else {
                    $counter.removeClass('over-limit');
                }
            };

            $field.on('input', updateCounter);
            updateCounter();
        });
    };

    /**
     * Destroy form handlers (cleanup)
     * PERFORMANCE: Prevenir memory leaks
     */
    Newbase.Forms.destroy = function () {
        $cachedElements.body.off('input', 'input[data-format]');
        $cachedElements.body.off('paste', 'input[data-format="numbers"]');
        $cachedElements.body.off('submit', 'form[data-validate]');
        $cachedElements.body.off('blur', 'input[data-validate-realtime]');

        $cachedElements = {};
        console.log('Newbase Forms destroyed');
    };

    // ====================
    // INITIALIZE
    // ====================
    $(document).ready(function () {
        Newbase.Forms.init();
    });

    // PERFORMANCE: Cleanup ao descarregar página
    $(window).on('beforeunload', function () {
        // Salvar dados pendentes
        $('form[data-autosave]').each(function () {
            const formId = $(this).attr('id');
            if (formId) {
                Newbase.Forms.saveAutoSave(formId, $(this));
            }
        });
    });

})(jQuery);
