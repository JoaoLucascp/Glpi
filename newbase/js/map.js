/**
 * Map JavaScript for Newbase Plugin (Leaflet integration)
 *
 * @package   PluginNewbase
 * @author    João Lucas
 * @copyright Copyright (c) 2025 João Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

(function($) {
    'use strict';

    // Map namespace
    window.Newbase = window.Newbase || {};
    Newbase.Map = Newbase.Map || {};

    // Map instances storage
    Newbase.Map.instances = {};

    /**
     * Initialize map
     */
    Newbase.Map.init = function(containerId, options) {
        var defaults = {
            center: [-23.5505, -46.6333], // São Paulo, Brazil
            zoom: 13,
            markers: [],
            routes: []
        };

        options = $.extend({}, defaults, options);

        // Load Leaflet if not loaded
        if (typeof L === 'undefined') {
            Newbase.Map.loadLeaflet(function() {
                Newbase.Map.create(containerId, options);
            });
        } else {
            Newbase.Map.create(containerId, options);
        }
    };

    /**
     * Load Leaflet library
     */
    Newbase.Map.loadLeaflet = function(callback) {
        // Load CSS
        if (!$('link[href*="leaflet.css"]').length) {
            $('<link>')
                .attr('rel', 'stylesheet')
                .attr('href', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')
                .appendTo('head');
        }

        // Load JS
        if (!$('script[src*="leaflet.js"]').length) {
            $.getScript('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', function() {
                console.log('Leaflet loaded');
                if (typeof callback === 'function') {
                    callback();
                }
            });
        }
    };

    /**
     * Create map instance
     */
    Newbase.Map.create = function(containerId, options) {
        var map = L.map(containerId).setView(options.center, options.zoom);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Store instance
        Newbase.Map.instances[containerId] = map;

        // Add markers
        if (options.markers && options.markers.length > 0) {
            Newbase.Map.addMarkers(containerId, options.markers);
        }

        // Add routes
        if (options.routes && options.routes.length > 0) {
            Newbase.Map.addRoutes(containerId, options.routes);
        }

        return map;
    };

    /**
     * Add markers to map
     */
    Newbase.Map.addMarkers = function(containerId, markers) {
        var map = Newbase.Map.instances[containerId];
        if (!map) return;

        markers.forEach(function(marker) {
            var icon = Newbase.Map.getMarkerIcon(marker.type, marker.status);

            var leafletMarker = L.marker([marker.lat, marker.lng], {
                icon: icon
            }).addTo(map);

            // Add popup if provided
            if (marker.popup) {
                leafletMarker.bindPopup(marker.popup);
            } else if (marker.title || marker.description) {
                var popupContent = '';
                if (marker.title) {
                    popupContent += '<h4>' + marker.title + '</h4>';
                }
                if (marker.description) {
                    popupContent += '<p>' + marker.description + '</p>';
                }
                leafletMarker.bindPopup(popupContent);
            }
        });

        // Fit bounds if multiple markers
        if (markers.length > 1) {
            var bounds = L.latLngBounds(markers.map(function(m) {
                return [m.lat, m.lng];
            }));
            map.fitBounds(bounds, {padding: [50, 50]});
        }
    };

    /**
     * Get marker icon based on type and status
     */
    Newbase.Map.getMarkerIcon = function(type, status) {
        var iconUrls = {
            'start': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            'end': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            'open': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
            'in_progress': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            'paused': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
            'completed': 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            'default': 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png'
        };

        var iconUrl = iconUrls[type] || iconUrls[status] || iconUrls['default'];

        return L.icon({
            iconUrl: iconUrl,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    };

    /**
     * Add routes to map
     */
    Newbase.Map.addRoutes = function(containerId, routes) {
        var map = Newbase.Map.instances[containerId];
        if (!map) return;

        routes.forEach(function(route) {
            var latlngs = [
                [route.start.lat, route.start.lng],
                [route.end.lat, route.end.lng]
            ];

            var polyline = L.polyline(latlngs, {
                color: route.color || '#2196F3',
                weight: route.weight || 3,
                opacity: route.opacity || 0.7
            }).addTo(map);

            // Add popup if provided
            if (route.popup) {
                polyline.bindPopup(route.popup);
            }
        });
    };

    /**
     * Add click handler to map
     */
    Newbase.Map.onClick = function(containerId, callback) {
        var map = Newbase.Map.instances[containerId];
        if (!map) return;

        map.on('click', function(e) {
            callback(e.latlng.lat, e.latlng.lng);
        });
    };

    /**
     * Get current location
     */
    Newbase.Map.getCurrentLocation = function(callback, errorCallback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    callback(position.coords.latitude, position.coords.longitude);
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    if (typeof errorCallback === 'function') {
                        errorCallback(error);
                    }
                }
            );
        } else {
            console.error('Geolocation not supported');
            if (typeof errorCallback === 'function') {
                errorCallback({message: 'Geolocation not supported'});
            }
        }
    };

    /**
     * Add current location marker
     */
    Newbase.Map.addCurrentLocationMarker = function(containerId) {
        Newbase.Map.getCurrentLocation(function(lat, lng) {
            var map = Newbase.Map.instances[containerId];
            if (!map) return;

            var icon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            L.marker([lat, lng], {icon: icon})
                .addTo(map)
                .bindPopup('Your current location')
                .openPopup();

            map.setView([lat, lng], 15);
        });
    };

    /**
     * Clear map
     */
    Newbase.Map.clear = function(containerId) {
        var map = Newbase.Map.instances[containerId];
        if (!map) return;

        map.eachLayer(function(layer) {
            if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                map.removeLayer(layer);
            }
        });
    };

    /**
     * Destroy map instance
     */
    Newbase.Map.destroy = function(containerId) {
        var map = Newbase.Map.instances[containerId];
        if (map) {
            map.remove();
            delete Newbase.Map.instances[containerId];
        }
    };

    /**
     * Geocode address (using Nominatim)
     */
    Newbase.Map.geocode = function(address, callback, errorCallback) {
        $.ajax({
            url: 'https://nominatim.openstreetmap.org/search',
            type: 'GET',
            data: {
                q: address,
                format: 'json',
                limit: 1
            },
            success: function(data) {
                if (data && data.length > 0) {
                    callback(parseFloat(data[0].lat), parseFloat(data[0].lon));
                } else {
                    if (typeof errorCallback === 'function') {
                        errorCallback({message: 'Address not found'});
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Geocoding error:', error);
                if (typeof errorCallback === 'function') {
                    errorCallback({message: error});
                }
            }
        });
    };

    /**
     * Reverse geocode (coordinates to address)
     */
    Newbase.Map.reverseGeocode = function(lat, lng, callback, errorCallback) {
        $.ajax({
            url: 'https://nominatim.openstreetmap.org/reverse',
            type: 'GET',
            data: {
                lat: lat,
                lon: lng,
                format: 'json'
            },
            success: function(data) {
                if (data && data.address) {
                    callback(data.address);
                } else {
                    if (typeof errorCallback === 'function') {
                        errorCallback({message: 'Address not found'});
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Reverse geocoding error:', error);
                if (typeof errorCallback === 'function') {
                    errorCallback({message: error});
                }
            }
        });
    };

})(jQuery);
