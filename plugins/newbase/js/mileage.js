/**
* Mileage calculation JavaScript for Newbase Plugin
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright (c) 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*/


(function($) {
    'use strict';

    // Mileage namespace
    window.Newbase = window.Newbase || {};
    Newbase.Mileage = Newbase.Mileage || {};

    /**
     * Calculate distance using Haversine formula
     * Returns distance in kilometers
     */
    Newbase.Mileage.calculate = function(lat1, lng1, lat2, lng2) {
        // Convert to radians
        var toRad = function(deg) {
            return deg * (Math.PI / 180);
        };

        var R = 6371; // Earth radius in kilometers

        var dLat = toRad(lat2 - lat1);
        var dLng = toRad(lng2 - lng1);

        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);

        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        var distance = R * c;

        return Math.round(distance * 100) / 100; // Round to 2 decimal places
    };

    /**
     * Calculate distance via AJAX
     */
    Newbase.Mileage.calculateAjax = function(lat1, lng1, lat2, lng2, url) {
        return Newbase.ajax({
            url: url,
            type: 'POST',
            data: {
                lat1: lat1,
                lng1: lng1,
                lat2: lat2,
                lng2: lng2
            }
        });
    };

    /**
     * Format distance for display
     */
    Newbase.Mileage.format = function(distance, unit, locale) {
        unit = unit || 'km';
        locale = locale || 'pt-BR';

        var formatted = distance.toLocaleString(locale, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        return formatted + ' ' + unit;
    };

    /**
     * Convert kilometers to miles
     */
    Newbase.Mileage.kmToMiles = function(km) {
        return km * 0.621371;
    };

    /**
     * Convert miles to kilometers
     */
    Newbase.Mileage.milesToKm = function(miles) {
        return miles * 1.60934;
    };

    /**
     * Calculate total mileage for multiple points
     */
    Newbase.Mileage.calculateTotal = function(points) {
        if (!points || points.length < 2) {
            return 0;
        }

        var total = 0;

        for (var i = 0; i < points.length - 1; i++) {
            var distance = Newbase.Mileage.calculate(
                points[i].lat,
                points[i].lng,
                points[i + 1].lat,
                points[i + 1].lng
            );
            total += distance;
        }

        return Math.round(total * 100) / 100;
    };

    /**
     * Estimate travel time based on distance and speed
     * Distance in km, speed in km/h
     * Returns time in minutes
     */
    Newbase.Mileage.estimateTime = function(distance, speed) {
        speed = speed || 50; // Default speed: 50 km/h
        var hours = distance / speed;
        var minutes = hours * 60;
        return Math.round(minutes);
    };

    /**
     * Format time duration
     */
    Newbase.Mileage.formatTime = function(minutes) {
        if (minutes < 60) {
            return minutes + ' min';
        }

        var hours = Math.floor(minutes / 60);
        var mins = minutes % 60;

        return hours + 'h ' + mins + 'min';
    };

    /**
     * Calculate cost based on distance and price per km
     */
    Newbase.Mileage.calculateCost = function(distance, pricePerKm) {
        pricePerKm = pricePerKm || 0.5; // Default: R$ 0,50 per km
        return Math.round(distance * pricePerKm * 100) / 100;
    };

    /**
     * Format currency
     */
    Newbase.Mileage.formatCurrency = function(value, currency, locale) {
        currency = currency || 'BRL';
        locale = locale || 'pt-BR';

        return value.toLocaleString(locale, {
            style: 'currency',
            currency: currency
        });
    };

    /**
     * Get route statistics
     */
    Newbase.Mileage.getStats = function(points, speed, pricePerKm) {
        var distance = Newbase.Mileage.calculateTotal(points);
        var time = Newbase.Mileage.estimateTime(distance, speed);
        var cost = Newbase.Mileage.calculateCost(distance, pricePerKm);

        return {
            distance: distance,
            distanceFormatted: Newbase.Mileage.format(distance),
            time: time,
            timeFormatted: Newbase.Mileage.formatTime(time),
            cost: cost,
            costFormatted: Newbase.Mileage.formatCurrency(cost),
            points: points.length
        };
    };

    /**
     * Initialize mileage calculator for form
     */
    Newbase.Mileage.initFormCalculator = function(options) {
        var defaults = {
            lat1Field: '#latitude_start',
            lng1Field: '#longitude_start',
            lat2Field: '#latitude_end',
            lng2Field: '#longitude_end',
            resultField: '#mileage',
            calculateBtn: '#calculate_mileage',
            url: null
        };

        options = $.extend({}, defaults, options);

        $(options.calculateBtn).on('click', function(e) {
            e.preventDefault();

            var lat1 = parseFloat($(options.lat1Field).val());
            var lng1 = parseFloat($(options.lng1Field).val());
            var lat2 = parseFloat($(options.lat2Field).val());
            var lng2 = parseFloat($(options.lng2Field).val());

            // Validate coordinates
            if (isNaN(lat1) || isNaN(lng1) || isNaN(lat2) || isNaN(lng2)) {
                Newbase.notify('Please fill all coordinates', 'warning');
                return;
            }

            if (lat1 < -90 || lat1 > 90 || lat2 < -90 || lat2 > 90) {
                Newbase.notify('Invalid latitude (must be between -90 and 90)', 'error');
                return;
            }

            if (lng1 < -180 || lng1 > 180 || lng2 < -180 || lng2 > 180) {
                Newbase.notify('Invalid longitude (must be between -180 and 180)', 'error');
                return;
            }

            // Calculate distance
            if (options.url) {
                // Calculate via AJAX
                Newbase.showLoading(options.calculateBtn);

                Newbase.Mileage.calculateAjax(lat1, lng1, lat2, lng2, options.url)
                    .done(function(response) {
                        if (response.success) {
                            $(options.resultField).val(response.mileage);
                            Newbase.notify('Mileage calculated: ' + response.formatted_mileage, 'success');
                        } else {
                            Newbase.notify(response.message || 'Error calculating mileage', 'error');
                        }
                    })
                    .fail(function() {
                        Newbase.notify('Error connecting to server', 'error');
                    })
                    .always(function() {
                        Newbase.hideLoading(options.calculateBtn);
                    });
            } else {
                // Calculate locally
                var distance = Newbase.Mileage.calculate(lat1, lng1, lat2, lng2);
                $(options.resultField).val(distance.toFixed(2));
                Newbase.notify('Mileage calculated: ' + Newbase.Mileage.format(distance), 'success');
            }
        });

        // Auto-calculate on coordinate change (debounced)
        var autoCalculate = Newbase.debounce(function() {
            $(options.calculateBtn).trigger('click');
        }, 1000);

        $(options.lat1Field + ', ' + options.lng1Field + ', ' + options.lat2Field + ', ' + options.lng2Field)
            .on('input', function() {
                var lat1 = $(options.lat1Field).val();
                var lng1 = $(options.lng1Field).val();
                var lat2 = $(options.lat2Field).val();
                var lng2 = $(options.lng2Field).val();

                if (lat1 && lng1 && lat2 && lng2) {
                    autoCalculate();
                }
            });
    };

    /**
     * Display mileage statistics
     */
    Newbase.Mileage.displayStats = function(containerId, points, options) {
        var defaults = {
            speed: 50,
            pricePerKm: 0.5
        };

        options = $.extend({}, defaults, options);

        var stats = Newbase.Mileage.getStats(points, options.speed, options.pricePerKm);

        var html = `
            <div class="newbase-mileage-stats">
                <div class="stat-item">
                    <span class="label">Distance:</span>
                    <span class="value">${stats.distanceFormatted}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Estimated Time:</span>
                    <span class="value">${stats.timeFormatted}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Estimated Cost:</span>
                    <span class="value">${stats.costFormatted}</span>
                </div>
                <div class="stat-item">
                    <span class="label">Points:</span>
                    <span class="value">${stats.points}</span>
                </div>
            </div>
        `;

        $('#' + containerId).html(html);
    };

})(jQuery);
