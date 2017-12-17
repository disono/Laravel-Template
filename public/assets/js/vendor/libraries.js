/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 *
 * Description: all of this libraries is independent
 */

var WBLibraries = function () {
    // Date and Time pickers
    WBDatePicker.init();

    // tiny mce
    _tinyMCE();
};

/**
 * Tiny MCE library
 *
 * @private
 */
var _tinyMCE = function () {
    // initialize tiny-mce
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: 'textarea',
            menubar: false,
            theme: 'modern',

            height: "520",

            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,

            plugins: [
                'advlist autolink lists link image charmap preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'template paste textcolor colorpicker textpattern imagetools'
            ],

            // list of menu
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | preview media | forecolor backcolor | file_upload',
            image_advtab: true,

            // added styles
            content_css: [
                '/assets/css/vendor.css',
                '/assets/css/theme.css'
            ],

            // Media images, videos
            // images selector from server
            file_browser_callback_types: 'image media',
            file_browser_callback: function (field_name, url, type, win) {
                _fileSelector(type, function (data) {
                    win.document.getElementById(field_name).value = data.src;
                }, function (data) {

                });
            },

            // File uploading
            // add button for file upload
            setup: function (editor) {
                editor.addButton('file_upload', {
                    icon: 'fa fa-cloud-upload',
                    onclick: function () {
                        _fileSelector(null, function (data) {
                            editor.insertContent('<table class="table-vertical"><tbody><tr>' +
                                '<td data-th="Filename:">' + data.text + '</td><td><a class="btn btn-default btn-xs" ' +
                                'href="' + data.src + '" target="_blank" rel="noopener noreferrer">Download</a>' +
                                '</td></tr></tbody></table>');
                        }, function (data) {

                        });
                    }
                });
            }
        });

        /**
         * File selector
         *
         * @param type
         * @param confirmCallback
         * @param dismissCallback
         * @private
         */
        function _fileSelector(type, confirmCallback, dismissCallback) {
            _appData.mediaType = type;
            _appMethods.dialogs('media-chooser', function (m) {
                jQ(document).off('click', '.selected_media').on('click', '.selected_media', function (e) {
                    m.data = {
                        id: jQ(this).attr('data-id'),
                        src: jQ(this).attr('data-src'),
                        text: jQ(this).attr('data-title')
                    };
                });

                m.buttons();
            }, function (data) {
                confirmCallback(data);
            }, function (data) {
                dismissCallback(data);
            });
        }
    }

};

/**
 * Date and Time pickers
 *
 * @type {{init, datePicker, datePickerMin, limit, timePicker}}
 */
var WBDatePicker = (function () {
    return {
        /**
         * Initialize all date and time picker options
         */
        init: function () {
            WBDatePicker.datePicker();
            WBDatePicker.datePickerMin();
            WBDatePicker.limit();
            WBDatePicker.timePicker();
        },

        /**
         * No future dates good for birthdays
         */
        datePicker: function () {
            jQ('.date-picker').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                max: true
            });
        },

        /**
         * No past dates good for events
         */
        datePickerMin: function () {
            jQ('.date-picker-min').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                min: true
            });
        },

        /**
         * Limit to 80 years selection no limit on past and future dates
         */
        limit: function () {
            jQ('.date-picker-limit').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 90,
                min: false,
                max: false
            });
        },

        /**
         * Time
         */
        timePicker: function () {
            jQ('.time-picker').pickatime({
                format: 'hh:i A'
            });
        }
    };
}());

/**
 * Google Map V3
 *
 * @type {{map, infoWindow, lat, lng, init, search, route, addMarker, deleteMarker, centerMap, style}}
 */
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

/**
 * LeafLetJS
 *
 * @type {{map, lat, lng, zoom, init, search, route, addMarker, onClick, deleteMarker, centerMap, calculateDistance, toRadian}}
 */
var WBLeafMap = (function () {
    var _markers = [];
    var _mapBoxToken = '';

    return {
        map: null,

        lat: 14.5995,
        lng: 120.9842,
        zoom: 14,

        /**
         * Initialize map
         *
         * @param id
         * @returns
         */
        init: function (id) {
            if (!document.getElementById(id)) {
                return;
            }

            // reset markers
            _markers = [];

            // leaf map
            this.map = L.map(id).setView([this.lat, this.lng], this.zoom);

            // use map box for tiles
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + _mapBoxToken, {
                maxZoom: this.zoom,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(this.map);

            return this.map;
        },

        /**
         * Search
         *
         * @param id
         */
        search: function (id) {
            var input = document.getElementById(id);
            if (!input) {
                return;
            }

            var searchBox = new google.maps.places.SearchBox(input);

            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length === 0) {
                    return;
                }

                var group = L.featureGroup();

                places.forEach(function (place) {
                    group.addLayer(WBLeafMap.addMarker(place.geometry.location.lat(), place.geometry.location.lng(), {
                        popup: {
                            content: place.formatted_address
                        },
                        icon: 'http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Chartreuse-icon.png'
                    }, function (e) {
                        console.log(this.getLatLng());
                    }));
                });

                group.addTo(WBLeafMap.map);
                WBLeafMap.map.fitBounds(group.getBounds());
            });
        },

        /**
         * Direction routing
         *
         * @param wayPointsList
         * @param callback
         */
        route: function (wayPointsList, callback) {
            var routingControl = L.Routing.control({
                waypoints: wayPointsList,
                plan: L.Routing.plan(wayPointsList, {
                    createMarker: function (i, wp) {
                        var marker = L.marker(wp.latLng, {
                            draggable: false
                        });

                        marker.on('click', function (e) {
                            console.log(this.getLatLng());
                        });

                        return marker;
                    }
                }),
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(this.map);

            // instruction hide
            jQ('.leaflet-routing-container').remove();

            // list of routes found
            routingControl.on('routesfound', function (e) {
                // e.routes[0].summary.totalDistance;
                // e.routes[0].summary.totalTime;
                // e.routes[0].instructions

                callback(e);
            });
        },

        /**
         * Add marker
         *
         * @param lat
         * @param lng
         * @param data
         * @param clickCallback
         * event on click: this.getLatLng()
         */
        addMarker: function (lat, lng, data, clickCallback) {
            var options = {};
            if (data) {
                // icon marker
                if (data.icon) {
                    options.icon = new L.icon({
                        iconUrl: data.icon,
                        iconSize: [64, 64]
                    });
                }
            }

            // marker with options
            var marker = L.marker([lat, lng], options).addTo(this.map);

            if (data) {
                // popup marker
                if (data.popup) {
                    marker.bindPopup(data.popup.content);
                }
            }

            // on marker click
            if (clickCallback) {
                // event
                // this.getLatLng()
                marker.on('click', clickCallback);
            }

            _markers.push(marker);

            this.centerMap(null, null);
            return marker;
        },

        /**
         * On map click
         * event: e.latlng
         *
         * @param callback
         */
        onClick: function (callback) {
            this.map.on('click', callback);
        },

        /**
         * Delete markers
         */
        deleteMarker: function () {
            for (var i = 0; i < _markers.length; i++) {
                this.map.removeLayer(_markers[i]);
            }

            _markers = [];
        },

        /**
         * Center map
         *
         * @param lat
         * @param lng
         */
        centerMap: function (lat, lng) {
            var listLatLng = [];

            for (var i = 0; i < _markers.length; i++) {
                listLatLng.push([_markers[i]._latlng.lat, _markers[i]._latlng.lng]);
            }

            var bounds = new L.LatLngBounds(listLatLng);
            this.map.fitBounds(bounds);
        },

        /**
         * Distance between two points
         *
         * @param lat_from
         * @param lng_from
         * @param lat_des
         * @param lng_des
         * @returns {*}
         */
        calculateDistance: function (lat_from, lng_from, lat_des, lng_des) {
            // km
            var R = 6371;
            var dLat = WBLeafMap.toRadian(lat_des - lat_from);
            var dLon = WBLeafMap.toRadian(lng_des - lng_from);

            lat_from = WBLeafMap.toRadian(lat_from);
            lat_des = WBLeafMap.toRadian(lat_des);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat_from) * Math.cos(lat_des);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;

            if (d) {
                return d.toFixed(1);
            }

            return d;
        },

        /**
         * Converts numeric degrees to radians
         *
         * @param Value
         * @returns {number}
         */
        toRadian: function (Value) {
            return Value * Math.PI / 180;
        }
    }
}());