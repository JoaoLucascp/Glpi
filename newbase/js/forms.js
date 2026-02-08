/**
 * Forms JavaScript for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

(function($) {
    'use strict';

    // Forms namespace
    window.Newbase = window.Newbase || {};
    Newbase.Forms = Newbase.Forms || {};

    /**
     * Initialize forms
     */
    Newbase.Forms.init = function() {
        console.log('Newbase Forms initialized');

        // Auto-format inputs
        Newbase.Forms.initFormatting();

        // Form validation
        Newbase.Forms.initValidation();

        // Auto-save forms
        Newbase.Forms.initAutoSave();
    };

    /**
     * Initialize input formatting
     */
    Newbase.Forms.initFormatting = function() {
        // CNPJ formatting
        $('input[data-format="cnpj"]').on('input', function() {
            var value = $(this).val();
            $(this).val(Newbase.formatCNPJ(value));
        });

        // CEP formatting
        $('input[data-format="cep"]').on('input', function() {
            var value = $(this).val();
            $(this).val(Newbase.formatCEP(value));
        });

        // Phone formatting
        $('input[data-format="phone"]').on('input', function() {
            var value = $(this).val();
            $(this).val(Newbase.formatPhone(value));
        });

        // Numbers only
        $('input[data-format="numbers"]').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });

        // Uppercase
        $('input[data-format="uppercase"]').on('input', function() {
            var value = $(this).val().toUpperCase();
            $(this).val(value);
        });

        // Lowercase
        $('input[data-format="lowercase"]').on('input', function() {
            var value = $(this).val().toLowerCase();
            $(this).val(value);
        });
    };

    /**
     * Initialize form validation
     */
    Newbase.Forms.initValidation = function() {
        // Validate on submit
        $('form[data-validate]').on('submit', function(e) {
            var $form = $(this);
            var isValid = true;

            // Clear previous errors
            $form.find('.newbase-form-error').removeClass('show');
            $form.find('.newbase-form-control').removeClass('error');

            // Validate required fields
            $form.find('[required]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();

                if (!value) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'This field is required');
                }
            });

            // Validate email fields
            $form.find('input[type="email"]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();

                if (value && !Newbase.validateEmail(value)) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Invalid email address');
                }
            });

            // Validate CNPJ fields
            $form.find('input[data-validate="cnpj"]').each(function() {
                var $field = $(this);
                var value = $field.val().replace(/\D/g, '');

                if (value && !Newbase.validateCNPJ(value)) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Invalid CNPJ');
                }
            });

            // Validate min length
            $form.find('[data-min-length]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();
                var minLength = parseInt($field.data('min-length'));

                if (value && value.length < minLength) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Minimum ' + minLength + ' characters required');
                }
            });

            // Validate max length
            $form.find('[data-max-length]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();
                var maxLength = parseInt($field.data('max-length'));

                if (value && value.length > maxLength) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Maximum ' + maxLength + ' characters allowed');
                }
            });

            // Validate pattern
            $form.find('[data-pattern]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();
                var pattern = new RegExp($field.data('pattern'));

                if (value && !pattern.test(value)) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Invalid format');
                }
            });

            // Validate matching fields (e.g., password confirmation)
            $form.find('[data-match]').each(function() {
                var $field = $(this);
                var matchFieldId = $field.data('match');
                var $matchField = $('#' + matchFieldId);
                var value = $field.val();
                var matchValue = $matchField.val();

                if (value !== matchValue) {
                    isValid = false;
                    Newbase.Forms.showFieldError($field, 'Fields do not match');
                }
            });

            if (!isValid) {
                e.preventDefault();
                Newbase.notify('Please correct the errors in the form', 'error');

                // Focus first error field
                var $firstError = $form.find('.newbase-form-control.error').first();
                if ($firstError.length) {
                    $firstError.focus();
                }
            }

            return isValid;
        });

        // Real-time validation
        $('input[data-validate-realtime]').on('blur', function() {
            var $field = $(this);
            Newbase.Forms.validateField($field);
        });
    };

    /**
     * Validate single field
     */
    Newbase.Forms.validateField = function($field) {
        var value = $field.val().trim();
        var isValid = true;
        var errorMessage = '';

        // Clear previous error
        Newbase.Forms.clearFieldError($field);

        // Required validation
        if ($field.prop('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        // Email validation
        if (isValid && $field.attr('type') === 'email' && value && !Newbase.validateEmail(value)) {
            isValid = false;
            errorMessage = 'Invalid email address';
        }

        // CNPJ validation
        if (isValid && $field.data('validate') === 'cnpj' && value) {
            var cnpj = value.replace(/\D/g, '');
            if (!Newbase.validateCNPJ(cnpj)) {
                isValid = false;
                errorMessage = 'Invalid CNPJ';
            }
        }

        // Min length validation
        if (isValid && $field.data('min-length')) {
            var minLength = parseInt($field.data('min-length'));
            if (value && value.length < minLength) {
                isValid = false;
                errorMessage = 'Minimum ' + minLength + ' characters required';
            }
        }

        // Max length validation
        if (isValid && $field.data('max-length')) {
            var maxLength = parseInt($field.data('max-length'));
            if (value && value.length > maxLength) {
                isValid = false;
                errorMessage = 'Maximum ' + maxLength + ' characters allowed';
            }
        }

        if (!isValid) {
            Newbase.Forms.showFieldError($field, errorMessage);
        } else {
            Newbase.Forms.showFieldSuccess($field);
        }

        return isValid;
    };

    /**
     * Show field error
     */
    Newbase.Forms.showFieldError = function($field, message) {
        $field.addClass('error');

        var $error = $field.siblings('.newbase-form-error');
        if (!$error.length) {
            $error = $('<div class="newbase-form-error"></div>');
            $field.after($error);
        }

        $error.text(message).addClass('show');
    };

    /**
     * Clear field error
     */
    Newbase.Forms.clearFieldError = function($field) {
        $field.removeClass('error success');
        $field.siblings('.newbase-form-error').removeClass('show');
        $field.siblings('.newbase-form-success').removeClass('show');
    };

    /**
     * Show field success
     */
    Newbase.Forms.showFieldSuccess = function($field) {
        $field.addClass('success');

        var $success = $field.siblings('.newbase-form-success');
        if (!$success.length) {
            $success = $('<div class="newbase-form-success"></div>');
            $field.after($success);
        }

        $success.text('✓').addClass('show');
    };

    /**
     * Initialize auto-save
     */
    Newbase.Forms.initAutoSave = function() {
        var autoSaveForms = $('form[data-autosave]');

        autoSaveForms.each(function() {
            var $form = $(this);
            var formId = $form.attr('id') || 'form_' + Math.random().toString(36).substr(2, 9);
            var delay = parseInt($form.data('autosave')) || 3000;

            // Load saved data
            Newbase.Forms.loadAutoSave(formId, $form);

            // Save on input change (debounced)
            var debouncedSave = Newbase.debounce(function() {
                Newbase.Forms.saveAutoSave(formId, $form);
            }, delay);

            $form.find('input, textarea, select').on('input change', debouncedSave);

            // Clear auto-save on successful submit
            $form.on('submit', function() {
                if ($(this).valid()) {
                    Newbase.Forms.clearAutoSave(formId);
                }
            });
        });
    };

    /**
     * Save form data to localStorage
     */
    Newbase.Forms.saveAutoSave = function(formId, $form) {
        var data = {};

        $form.find('input, textarea, select').each(function() {
            var $field = $(this);
            var name = $field.attr('name');
            var type = $field.attr('type');

            if (name) {
                if (type === 'checkbox') {
                    data[name] = $field.is(':checked');
                } else if (type === 'radio') {
                    if ($field.is(':checked')) {
                        data[name] = $field.val();
                    }
                } else {
                    data[name] = $field.val();
                }
            }
        });

        try {
            localStorage.setItem('newbase_autosave_' + formId, JSON.stringify(data));
            console.log('Form auto-saved:', formId);
        } catch (e) {
            console.error('Auto-save error:', e);
        }
    };

    /**
     * Load saved form data from localStorage
     */
    Newbase.Forms.loadAutoSave = function(formId, $form) {
        try {
            var savedData = localStorage.getItem('newbase_autosave_' + formId);

            if (savedData) {
                var data = JSON.parse(savedData);

                Object.keys(data).forEach(function(name) {
                    var $field = $form.find('[name="' + name + '"]');
                    var type = $field.attr('type');

                    if (type === 'checkbox') {
                        $field.prop('checked', data[name]);
                    } else if (type === 'radio') {
                        $form.find('[name="' + name + '"][value="' + data[name] + '"]').prop('checked', true);
                    } else {
                        $field.val(data[name]);
                    }
                });

                console.log('Form data restored:', formId);
            }
        } catch (e) {
            console.error('Auto-load error:', e);
        }
    };

    /**
     * Clear saved form data
     */
    Newbase.Forms.clearAutoSave = function(formId) {
        try {
            localStorage.removeItem('newbase_autosave_' + formId);
            console.log('Auto-save cleared:', formId);
        } catch (e) {
            console.error('Clear auto-save error:', e);
        }
    };

    /**
     * Character counter
     */
    Newbase.Forms.initCharCounter = function() {
        $('textarea[data-max-chars], input[data-max-chars]').each(function() {
            var $field = $(this);
            var maxChars = parseInt($field.data('max-chars'));

            var $counter = $('<div class="char-counter"></div>');
            $field.after($counter);

            var updateCounter = function() {
                var currentLength = $field.val().length;
                $counter.text(currentLength + ' / ' + maxChars);

                if (currentLength > maxChars) {
                    $counter.addClass('over-limit');
                } else {
                    $counter.removeClass('over-limit');
                }
            };

            $field.on('input', updateCounter);
            updateCounter();
        });
    };

    // Initialize on document ready
    $(document).ready(function() {
        Newbase.Forms.init();
    });

})(jQuery);
