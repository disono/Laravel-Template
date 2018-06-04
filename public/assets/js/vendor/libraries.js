/**
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

var WBLibraries = function () {
    // Date and Time pickers
    WBDatePicker.init();
};

/**
 * Date and Time pickers
 *
 * @type {{init, datePicker, datePickerMin, limit, timePicker}}
 */
var WBDatePicker = (function () {
    /**
     * No future dates
     */
    function datePicker() {
        jQ('.date-picker').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 80,
            max: true
        });
    }

    /**
     * No past dates
     */
    function datePickerCurrent() {
        jQ('.date-picker-current').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 80,
            min: true
        });
    }

    /**
     * No limit for future and past dates
     */
    function datePickerLimit() {
        jQ('.date-picker-limit').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 90,
            min: false,
            max: false
        });
    }

    /**
     * Time picker
     */
    function timePicker() {
        jQ('.time-picker').pickatime({
            format: 'hh:i A'
        });
    }

    return {
        init: function () {
            datePicker();
            datePickerCurrent();
            datePickerLimit();
            timePicker();
        }
    }
}());

/**
 * Google Map V3
 *
 * @type {{map, infoWindow, lat, lng, init, search, route, addMarker, deleteMarker, centerMap, style}}
 */
if (typeof google !== 'undefined') {
    var WBGoogleMap = (function () {
        var _markers = [];
        var _directionsDisplay;
        var _directionsService;

        var _centerTimer;

        return {
            map: null,
            infoWindow: new google.maps.InfoWindow(),

            // Default lat and lng
            lat: 14.5995,
            lng: 120.9842,

            /**
             * Initialize map
             *
             * @param id
             * @returns {google.maps.Map}
             */
            init: function (id) {
                if (!document.getElementById(id)) {
                    return null;
                }

                _markers = [];
                _directionsService = new google.maps.DirectionsService();
                _directionsDisplay = new google.maps.DirectionsRenderer();

                WBGoogleMap.map = new google.maps.Map(document.getElementById(id), {
                    center: {lat: WBGoogleMap.lat, lng: WBGoogleMap.lng},
                    zoom: 14,
                    styles: WBGoogleMap.style
                });

                // direction display map
                _directionsDisplay.setMap(WBGoogleMap.map);

                return WBGoogleMap.map;
            },

            /**
             * Search
             *
             * Commonly returns are place.formatted_address, place.name
             *
             * @param id
             * @param callback
             */
            search: function (id, callback) {
                var thisApp = this;

                if (!thisApp.map) {
                    return;
                }

                var input = document.getElementById(id);
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', thisApp.map);

                // add the listener
                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        console.error("No details available for input: '" + place.name + "'");
                        return;
                    }

                    // reset the markers
                    thisApp.deleteMarker();

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        thisApp.map.fitBounds(place.geometry.viewport);
                    } else {
                        thisApp.map.setCenter(place.geometry.location);
                        thisApp.map.setZoom(17);
                    }

                    // callback data
                    callback(place);

                    // add marker
                    thisApp.addMarker(place.geometry.location.lat(), place.geometry.location.lng(), null, null);
                });
            },

            /**
             * Direction routing
             *
             * @param start_lat
             * @param start_lng
             * @param end_lat
             * @param end_lng
             */
            route: function (start_lat, start_lng, end_lat, end_lng) {
                var thisApp = this;
                var request = {
                    origin: new google.maps.LatLng(parseFloat(start_lat), parseFloat(start_lng)),
                    destination: new google.maps.LatLng(parseFloat(end_lat), parseFloat(end_lng)),
                    travelMode: 'DRIVING'
                };

                _directionsService.route(request, function (result, status) {
                    if (status === 'OK') {
                        _directionsDisplay.setDirections(result);

                        // center the map to destination
                        thisApp.centerMap(end_lat, end_lng);
                    }
                });
            },

            /**
             * Add marker
             *
             * @param lat
             * @param lng
             * @param data
             * @param clickCallback
             */
            addMarker: function (lat, lng, data, clickCallback) {
                var icon = null;
                if (data) {
                    if (data.icon) {
                        icon = data.icon;
                    }
                }

                var marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    },

                    map: this.map,
                    icon: ((icon) ? icon : null)
                });
                _markers.push(marker);

                // click
                if (clickCallback) {
                    google.maps.event.addListener(marker, 'click', function () {
                        clickCallback(lat, lng, data, marker);
                    });
                }

                this.centerMap(null, null);
            },

            /**
             * Delete markers
             */
            deleteMarker: function () {
                _markers.forEach(function (marker) {
                    marker.setMap(null);
                });
            },

            /**
             * Center map
             *
             * @param lat
             * @param lng
             */
            centerMap: function (lat, lng) {
                var thisApp = this;

                if (_centerTimer) {
                    clearTimeout(_centerTimer);
                }

                _centerTimer = setTimeout(function () {
                    if (lat === null || lng === null) {
                        var bounds = new google.maps.LatLngBounds();
                        for (var i = 0; i < _markers.length; i++) {
                            bounds.extend(_markers[i].getPosition());
                        }

                        thisApp.map.fitBounds(bounds);
                    } else {
                        thisApp.map.panTo(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
                    }
                }, 800);
            },

            /**
             * Map styles
             */
            style: [{
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
            }, {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
            }, {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
            }, {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
            }, {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
            }, {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{"color": "#dedede"}, {"lightness": 21}]
            }, {
                "elementType": "labels.text.stroke",
                "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
            }, {
                "elementType": "labels.text.fill",
                "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
            }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
            }, {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
            }, {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
            }]
        };
    }());
}