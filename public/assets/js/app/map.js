/**
 * @author              Archie Disono (webmonsph@gmail.com)
 * @link                https://github.com/disono/Laravel-Template
 * @lincense            https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright           Webmons Development Studio
 */

let WBGoogleMap = (function () {
    return {
        map: null,
        markers: [],
        directionsDisplay: null,
        directionsService: null,

        // Default lat and lng
        lat: 14.5995,
        lng: 120.9842,

        // Map styles
        style: null,

        /**
         * Initialize map
         *
         * @param id
         * @returns {google.maps.Map}
         */
        builder: function (id) {
            if (!document.getElementById(id)) {
                return null;
            }

            this.markers = [];
            this.directionsService = new google.maps.DirectionsService();
            this.directionsDisplay = new google.maps.DirectionsRenderer();

            this.map = new google.maps.Map(document.getElementById(id), {
                center: {lat: this.lat, lng: this.lng},
                zoom: 14,
                styles: this.style
            });

            // direction display map
            this.directionsDisplay.setMap(this.map);

            return this;
        },

        /**
         * Search
         * https://developers.google.com/maps/documentation/javascript/places-autocomplete
         *
         * Commonly returns are place.formatted_address, place.name
         *
         * @param id
         * input for search bar
         */
        search: function (id) {
            let self = this;

            return new Promise((resolve, reject) => {
                if (!self.map) {
                    return reject('Map is not initialize.');
                }

                let input = document.getElementById(id);
                let autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', self.map);

                // add the listener
                autocomplete.addListener('place_changed', function () {
                    let place = autocomplete.getPlace();

                    if (!place.geometry) {
                        return reject("No details available for input: '" + place.name + "'");
                    }

                    // reset the markers
                    self.deleteMarker();

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        self.map.fitBounds(place.geometry.viewport);
                    } else {
                        self.map.setCenter(place.geometry.location);
                        self.map.setZoom(17);
                    }

                    // add marker
                    self.addMarker(place.geometry.location.lat(), place.geometry.location.lng(), null, null);

                    resolve(place);
                });
            });
        },

        /**
         * Direction routing
         * https://developers.google.com/maps/documentation/javascript/directions
         *
         * @param start_lat
         * @param start_lng
         * @param end_lat
         * @param end_lng
         */
        route: function (start_lat, start_lng, end_lat, end_lng) {
            let self = this;
            let request = {
                origin: new google.maps.LatLng(parseFloat(start_lat), parseFloat(start_lng)),
                destination: new google.maps.LatLng(parseFloat(end_lat), parseFloat(end_lng)),
                travelMode: 'DRIVING'
            };

            self.directionsService.route(request, function (result, status) {
                if (status === 'OK') {
                    self.directionsDisplay.setDirections(result);

                    // center the map to destination
                    self.centerMap(end_lat, end_lng);
                }
            });
        },

        /**
         * Add marker
         * https://developers.google.com/maps/documentation/javascript/markers
         * https://developers.google.com/maps/documentation/javascript/infowindows
         *
         * @param lat
         * @param lng
         * @param options
         */
        addMarker: function (lat, lng, options) {
            let marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                },

                map: this.map,
                icon: typeof options.icon !== "undefined" ? options.icon : null
            });

            // onClick
            if (typeof options.onClick !== "undefined") {
                google.maps.event.addListener(marker, 'click', options.onClick);
            }

            // infoWindow
            if (typeof options.infoWindow !== "undefined") {
                let infoWindow = new google.maps.InfoWindow({
                    content: options.infoWindow.content
                });

                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.open(map, marker);
                });
            }

            // center map
            if (typeof options.center !== "undefined") {
                this.centerMap(null, null);
            }

            // add markers
            this.markers.push({
                marker: marker,
                data: typeof options.data !== "undefined" ? options.data : null
            });
        },

        /**
         * Delete markers
         */
        deleteMarker: function (id) {
            this.markers.forEach(function (obj) {
                if (id === obj.data.id) {
                    obj.marker.setMap(null);
                }
            });
        },

        /**
         * Delete markers
         */
        deleteMarkers: function () {
            this.markers.forEach(function (obj) {
                obj.marker.setMap(null);
            });
        },

        /**
         * Center map
         *
         * @param lat
         * @param lng
         */
        centerMap: function (lat, lng) {
            let self = this;

            if (lat === null || lng === null) {
                let bounds = new google.maps.LatLngBounds();
                for (let i = 0; i < this.markers.length; i++) {
                    bounds.extend(this.markers[i].getPosition());
                }

                self.map.fitBounds(bounds);
            } else {
                self.map.panTo(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
            }
        }
    };
}());