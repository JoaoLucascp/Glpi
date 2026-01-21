/**
* Signature JavaScript for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/


(function($) {
    'use strict';

    // Signature namespace
    window.Newbase = window.Newbase || {};
    Newbase.Signature = Newbase.Signature || {};

    // Signature pads storage
    Newbase.Signature.pads = {};

    /**
     * Initialize signature pad
     */
    Newbase.Signature.init = function(canvasId, options) {
        var defaults = {
            width: 500,
            height: 200,
            strokeColor: '#000',
            strokeWidth: 2,
            backgroundColor: '#fff'
        };

        options = $.extend({}, defaults, options);

        var canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error('Canvas not found:', canvasId);
            return null;
        }

        // Set canvas size
        canvas.width = options.width;
        canvas.height = options.height;

        var ctx = canvas.getContext('2d');
        ctx.strokeStyle = options.strokeColor;
        ctx.lineWidth = options.strokeWidth;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        // Fill background
        ctx.fillStyle = options.backgroundColor;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        var pad = {
            canvas: canvas,
            ctx: ctx,
            isDrawing: false,
            lastX: 0,
            lastY: 0,
            options: options
        };

        // Mouse events
        canvas.addEventListener('mousedown', function(e) {
            Newbase.Signature.startDrawing(pad, e);
        });

        canvas.addEventListener('mousemove', function(e) {
            Newbase.Signature.draw(pad, e);
        });

        canvas.addEventListener('mouseup', function() {
            Newbase.Signature.stopDrawing(pad);
        });

        canvas.addEventListener('mouseout', function() {
            Newbase.Signature.stopDrawing(pad);
        });

        // Touch events for mobile
        canvas.addEventListener('touchstart', function(e) {
            e.preventDefault();
            Newbase.Signature.startDrawing(pad, e.touches[0]);
        });

        canvas.addEventListener('touchmove', function(e) {
            e.preventDefault();
            Newbase.Signature.draw(pad, e.touches[0]);
        });

        canvas.addEventListener('touchend', function(e) {
            e.preventDefault();
            Newbase.Signature.stopDrawing(pad);
        });

        // Store pad
        Newbase.Signature.pads[canvasId] = pad;

        return pad;
    };

    /**
     * Start drawing
     */
    Newbase.Signature.startDrawing = function(pad, e) {
        pad.isDrawing = true;
        var rect = pad.canvas.getBoundingClientRect();
        pad.lastX = e.clientX - rect.left;
        pad.lastY = e.clientY - rect.top;
    };

    /**
     * Draw on canvas
     */
    Newbase.Signature.draw = function(pad, e) {
        if (!pad.isDrawing) return;

        var rect = pad.canvas.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;

        pad.ctx.beginPath();
        pad.ctx.moveTo(pad.lastX, pad.lastY);
        pad.ctx.lineTo(x, y);
        pad.ctx.stroke();

        pad.lastX = x;
        pad.lastY = y;
    };

    /**
     * Stop drawing
     */
    Newbase.Signature.stopDrawing = function(pad) {
        pad.isDrawing = false;
    };

    /**
     * Clear signature
     */
    Newbase.Signature.clear = function(canvasId) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        pad.ctx.fillStyle = pad.options.backgroundColor;
        pad.ctx.fillRect(0, 0, pad.canvas.width, pad.canvas.height);
    };

    /**
     * Get signature as data URL
     */
    Newbase.Signature.getDataURL = function(canvasId, format) {
        format = format || 'image/png';

        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return null;

        return pad.canvas.toDataURL(format);
    };

    /**
     * Get signature as blob
     */
    Newbase.Signature.getBlob = function(canvasId, callback, format) {
        format = format || 'image/png';

        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        pad.canvas.toBlob(function(blob) {
            callback(blob);
        }, format);
    };

    /**
     * Check if signature is empty
     */
    Newbase.Signature.isEmpty = function(canvasId) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return true;

        var imageData = pad.ctx.getImageData(0, 0, pad.canvas.width, pad.canvas.height);
        var pixels = imageData.data;

        // Check if all pixels are the same (background color)
        var firstPixel = [pixels[0], pixels[1], pixels[2], pixels[3]];

        for (var i = 0; i < pixels.length; i += 4) {
            if (pixels[i] !== firstPixel[0] ||
                pixels[i + 1] !== firstPixel[1] ||
                pixels[i + 2] !== firstPixel[2] ||
                pixels[i + 3] !== firstPixel[3]) {
                return false;
            }
        }

        return true;
    };

    /**
     * Load signature from data URL
     */
    Newbase.Signature.loadFromDataURL = function(canvasId, dataURL) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        var img = new Image();
        img.onload = function() {
            pad.ctx.drawImage(img, 0, 0);
        };
        img.src = dataURL;
    };

    /**
     * Resize canvas
     */
    Newbase.Signature.resize = function(canvasId, width, height) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        // Save current signature
        var dataURL = Newbase.Signature.getDataURL(canvasId);

        // Resize canvas
        pad.canvas.width = width;
        pad.canvas.height = height;

        // Restore background
        pad.ctx.fillStyle = pad.options.backgroundColor;
        pad.ctx.fillRect(0, 0, width, height);

        // Restore signature
        if (dataURL) {
            Newbase.Signature.loadFromDataURL(canvasId, dataURL);
        }
    };

    /**
     * Set stroke color
     */
    Newbase.Signature.setStrokeColor = function(canvasId, color) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        pad.ctx.strokeStyle = color;
        pad.options.strokeColor = color;
    };

    /**
     * Set stroke width
     */
    Newbase.Signature.setStrokeWidth = function(canvasId, width) {
        var pad = Newbase.Signature.pads[canvasId];
        if (!pad) return;

        pad.ctx.lineWidth = width;
        pad.options.strokeWidth = width;
    };

    /**
     * Upload signature to server
     */
    Newbase.Signature.upload = function(canvasId, taskId, url) {
        // Check if signature is empty
        if (Newbase.Signature.isEmpty(canvasId)) {
            Newbase.notify('Please draw your signature first', 'warning');
            return;
        }

        // Get signature data
        var dataURL = Newbase.Signature.getDataURL(canvasId);

        // Upload via AJAX
        return Newbase.ajax({
            url: url,
            type: 'POST',
            data: {
                task_id: taskId,
                signature: dataURL
            },
            success: function(response) {
                if (response.success) {
                    Newbase.notify(response.message || 'Signature saved successfully', 'success');
                } else {
                    Newbase.notify(response.message || 'Error saving signature', 'error');
                }
            }
        });
    };

    /**
     * Download signature as image
     */
    Newbase.Signature.download = function(canvasId, filename) {
        filename = filename || 'signature.png';

        var dataURL = Newbase.Signature.getDataURL(canvasId);
        if (!dataURL) return;

        var link = document.createElement('a');
        link.download = filename;
        link.href = dataURL;
        link.click();
    };

    /**
     * Create signature modal
     */
    Newbase.Signature.createModal = function(options) {
        var defaults = {
            title: 'Draw your signature',
            canvasId: 'signature_canvas_' + Date.now(),
            width: 500,
            height: 200,
            onSave: null,
            onCancel: null
        };

        options = $.extend({}, defaults, options);

        // Create modal HTML
        var modalHtml = `
            <div id="${options.canvasId}_modal" class="newbase-signature-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999;">
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:20px; border-radius:8px; max-width:90%; max-height:90%; overflow:auto;">
                    <h3>${options.title}</h3>
                    <canvas id="${options.canvasId}" style="border:2px solid #333; cursor:crosshair;"></canvas>
                    <br><br>
                    <button type="button" class="newbase-btn newbase-btn-secondary" data-action="clear">
                        <i class="fas fa-eraser"></i> Clear
                    </button>
                    <button type="button" class="newbase-btn newbase-btn-success" data-action="save">
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button type="button" class="newbase-btn newbase-btn-danger" data-action="cancel">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        `;

        // Add modal to body
        $('body').append(modalHtml);

        var $modal = $('#' + options.canvasId + '_modal');

        // Initialize signature pad
        $modal.show();
        var pad = Newbase.Signature.init(options.canvasId, {
            width: options.width,
            height: options.height
        });
        $modal.hide();

        // Button handlers
        $modal.find('[data-action="clear"]').on('click', function() {
            Newbase.Signature.clear(options.canvasId);
        });

        $modal.find('[data-action="save"]').on('click', function() {
            if (typeof options.onSave === 'function') {
                options.onSave(Newbase.Signature.getDataURL(options.canvasId));
            }
            $modal.fadeOut();
        });

        $modal.find('[data-action="cancel"]').on('click', function() {
            if (typeof options.onCancel === 'function') {
                options.onCancel();
            }
            $modal.fadeOut();
        });

        return {
            modal: $modal,
            pad: pad,
            show: function() {
                $modal.fadeIn();
            },
            hide: function() {
                $modal.fadeOut();
            },
            destroy: function() {
                $modal.remove();
                delete Newbase.Signature.pads[options.canvasId];
            }
        };
    };

})(jQuery);
