/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

let WBGoogleMap = (function () {
    let _markers = [];
    let _directionsDisplay;
    let _directionsService;

    let _centerTimer;

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
            let thisApp = this;

            if (!thisApp.map) {
                return;
            }

            let input = document.getElementById(id);
            let autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', thisApp.map);

            // add the listener
            autocomplete.addListener('place_changed', function () {
                let place = autocomplete.getPlace();

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
            let thisApp = this;
            let request = {
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
            let icon = null;
            if (data) {
                if (data.icon) {
                    icon = data.icon;
                }
            }

            let marker = new google.maps.Marker({
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
            let thisApp = this;

            if (_centerTimer) {
                clearTimeout(_centerTimer);
            }

            _centerTimer = setTimeout(function () {
                if (lat === null || lng === null) {
                    let bounds = new google.maps.LatLngBounds();
                    for (let i = 0; i < _markers.length; i++) {
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
        style: null
    };
}());