/**
 * Mobile-specific JavaScript for Newbase Plugin
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

(function($) {
    'use strict';

    // Mobile namespace
    window.Newbase = window.Newbase || {};
    Newbase.Mobile = Newbase.Mobile || {};

    /**
     * Check if device is mobile
     */
    Newbase.Mobile.isMobile = function() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    };

    /**
     * Check if device is tablet
     */
    Newbase.Mobile.isTablet = function() {
        return /iPad|Android/i.test(navigator.userAgent) && window.innerWidth >= 768;
    };

    /**
     * Check if device is touch enabled
     */
    Newbase.Mobile.isTouchDevice = function() {
        return ('ontouchstart' in window) ||
               (navigator.maxTouchPoints > 0) ||
               (navigator.msMaxTouchPoints > 0);
    };

    /**
     * Initialize mobile features
     */
    Newbase.Mobile.init = function() {
        console.log('Newbase Mobile initialized');

        if (!Newbase.Mobile.isMobile()) {
            return;
        }

        // Add mobile class to body
        $('body').addClass('newbase-mobile');

        // Initialize mobile-specific features
        Newbase.Mobile.initSwipeGestures();
        Newbase.Mobile.initPullToRefresh();
        Newbase.Mobile.initMobileNavigation();
        Newbase.Mobile.initGeolocation();
        Newbase.Mobile.preventZoom();
    };

    /**
     * Initialize swipe gestures
     */
    Newbase.Mobile.initSwipeGestures = function() {
        var touchStartX = 0;
        var touchEndX = 0;
        var touchStartY = 0;
        var touchEndY = 0;

        $(document).on('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        });

        $(document).on('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;

            Newbase.Mobile.handleSwipe(touchStartX, touchEndX, touchStartY, touchEndY);
        });
    };

    /**
     * Handle swipe gesture
     */
    Newbase.Mobile.handleSwipe = function(startX, endX, startY, endY) {
        var diffX = startX - endX;
        var diffY = startY - endY;

        // Minimum swipe distance (50px)
        var minSwipeDistance = 50;

        // Swipe left
        if (diffX > minSwipeDistance && Math.abs(diffY) < minSwipeDistance) {
            $(document).trigger('newbase:swipe:left');
        }

        // Swipe right
        if (diffX < -minSwipeDistance && Math.abs(diffY) < minSwipeDistance) {
            $(document).trigger('newbase:swipe:right');
        }

        // Swipe up
        if (diffY > minSwipeDistance && Math.abs(diffX) < minSwipeDistance) {
            $(document).trigger('newbase:swipe:up');
        }

        // Swipe down
        if (diffY < -minSwipeDistance && Math.abs(diffX) < minSwipeDistance) {
            $(document).trigger('newbase:swipe:down');
        }
    };

    /**
     * Initialize pull to refresh
     */
    Newbase.Mobile.initPullToRefresh = function() {
        var pullThreshold = 100;
        var startY = 0;
        var pulling = false;

        $('body').on('touchstart', function(e) {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pulling = true;
            }
        });

        $('body').on('touchmove', function(e) {
            if (!pulling) return;

            var currentY = e.touches[0].clientY;
            var diff = currentY - startY;

            if (diff > pullThreshold) {
                $(document).trigger('newbase:pull:refresh');
                pulling = false;
            }
        });

        $('body').on('touchend', function() {
            pulling = false;
        });

        // Handle refresh event
        $(document).on('newbase:pull:refresh', function() {
            Newbase.notify('Refreshing...', 'info');
            location.reload();
        });
    };

    /**
     * Initialize mobile navigation
     */
    Newbase.Mobile.initMobileNavigation = function() {
        // Toggle mobile menu
        $(document).on('click', '.mobile-menu-toggle', function(e) {
            e.preventDefault();
            $('body').toggleClass('mobile-menu-open');
        });

        // Close menu on overlay click
        $(document).on('click', '.mobile-menu-overlay', function() {
            $('body').removeClass('mobile-menu-open');
        });

        // Close menu on navigation
        $(document).on('click', '.mobile-menu a', function() {
            $('body').removeClass('mobile-menu-open');
        });
    };

    /**
     * Initialize geolocation
     */
    Newbase.Mobile.initGeolocation = function() {
        // Add geolocation button to forms with coordinates
        $('input[name="latitude"], input[name="longitude"]').each(function() {
            var $field = $(this);

            if (!$field.siblings('.geolocation-btn').length) {
                var $btn = $('<button type="button" class="geolocation-btn newbase-btn newbase-btn-secondary">');
                $btn.html('<i class="fas fa-crosshairs"></i>');
                $field.after($btn);
            }
        });

        // Handle geolocation button click
        $(document).on('click', '.geolocation-btn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var $latField = $btn.closest('form').find('input[name*="latitude"]').first();
            var $lngField = $btn.closest('form').find('input[name*="longitude"]').first();

            Newbase.Mobile.getCurrentLocation(
                function(lat, lng) {
                    $latField.val(lat);
                    $lngField.val(lng);
                    Newbase.notify('Location detected successfully', 'success');
                },
                function(error) {
                    Newbase.notify('Error detecting location: ' + error.message, 'error');
                }
            );
        });
    };

    /**
     * Get current location
     */
    Newbase.Mobile.getCurrentLocation = function(successCallback, errorCallback) {
        if (!navigator.geolocation) {
            if (typeof errorCallback === 'function') {
                errorCallback({message: 'Geolocation not supported'});
            }
            return;
        }

        Newbase.notify('Detecting location...', 'info');

        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                if (typeof successCallback === 'function') {
                    successCallback(lat, lng);
                }
            },
            function(error) {
                var message = '';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Location permission denied';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Location unavailable';
                        break;
                    case error.TIMEOUT:
                        message = 'Location request timed out';
                        break;
                    default:
                        message = 'Unknown error';
                }

                if (typeof errorCallback === 'function') {
                    errorCallback({message: message});
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    };

    /**
     * Prevent accidental zoom
     */
    Newbase.Mobile.preventZoom = function() {
        // Prevent double-tap zoom
        var lastTouchEnd = 0;

        $(document).on('touchend', function(e) {
            var now = Date.now();
            if (now - lastTouchEnd <= 300) {
                e.preventDefault();
            }
            lastTouchEnd = now;
        });

        // Add viewport meta tag if not present
        if (!$('meta[name="viewport"]').length) {
            $('head').append('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">');
        }
    };

    /**
     * Vibrate device
     */
    Newbase.Mobile.vibrate = function(duration) {
        duration = duration || 200;

        if ('vibrate' in navigator) {
            navigator.vibrate(duration);
        }
    };

    /**
     * Show install prompt (PWA)
     */
    Newbase.Mobile.showInstallPrompt = function() {
        var deferredPrompt;

        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;

            // Show install button
            var $installBtn = $('<button class="newbase-btn newbase-btn-primary newbase-install-btn">');
            $installBtn.html('<i class="fas fa-download"></i> Install App');
            $('body').append($installBtn);

            $installBtn.on('click', function() {
                if (deferredPrompt) {
                    deferredPrompt.prompt();

                    deferredPrompt.userChoice.then(function(choiceResult) {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('App installed');
                        }
                        deferredPrompt = null;
                        $installBtn.remove();
                    });
                }
            });
        });
    };

    /**
     * Check online status
     */
    Newbase.Mobile.isOnline = function() {
        return navigator.onLine;
    };

    /**
     * Handle online/offline events
     */
    Newbase.Mobile.initOfflineDetection = function() {
        window.addEventListener('online', function() {
            Newbase.notify('Connection restored', 'success');
            $('body').removeClass('offline');
        });

        window.addEventListener('offline', function() {
            Newbase.notify('No internet connection', 'warning');
            $('body').addClass('offline');
        });
    };

    /**
     * Request notification permission
     */
    Newbase.Mobile.requestNotificationPermission = function(callback) {
        if (!('Notification' in window)) {
            console.log('Notifications not supported');
            return;
        }

        if (Notification.permission === 'granted') {
            if (typeof callback === 'function') {
                callback(true);
            }
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(function(permission) {
                if (typeof callback === 'function') {
                    callback(permission === 'granted');
                }
            });
        }
    };

    /**
     * Show notification
     */
    Newbase.Mobile.showNotification = function(title, options) {
        if (!('Notification' in window)) {
            return;
        }

        if (Notification.permission === 'granted') {
            new Notification(title, options);
        }
    };

    /**
     * Share content (Web Share API)
     */
    Newbase.Mobile.share = function(data) {
        if (!navigator.share) {
            // Fallback: copy to clipboard
            var text = data.text || data.url || '';
            Newbase.copyToClipboard(text);
            return;
        }

        navigator.share(data)
            .then(function() {
                console.log('Share successful');
            })
            .catch(function(error) {
                console.error('Share failed:', error);
            });
    };

    // Initialize on document ready
    $(document).ready(function() {
        if (Newbase.Mobile.isMobile() || Newbase.Mobile.isTouchDevice()) {
            Newbase.Mobile.init();
            Newbase.Mobile.initOfflineDetection();
        }
    });

})(jQuery);
