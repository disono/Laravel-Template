/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 *
 * JavaScript helpers
 */

// jquery no conflict
var jQ = jQuery.noConflict();

var _debugging = function (m) {
    console.log(m);
};

var _WBConfigs = {
    domain: null,

    // http://your-domain:3000/
    socket: null
};

/**
 * Geo location
 * https://www.w3schools.com/html/html5_geolocation.asp
 *
 * @type {{location, lat, long}}
 */
var WBGeo = (function () {
    return {
        locate: function (successCallback, errorCallback) {
            if (navigator.geolocation) {
                successCallback = (successCallback) ? successCallback : this.showPosition;
                errorCallback = (errorCallback) ? errorCallback : this.showError;

                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }
        },

        showPosition: function (position) {

        },

        showError: function (error) {
            var message = 'An unknown error occurred.';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    message = "User denied the request for Geo-location.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    message = "Location information is unavailable.";
                    break;
                case error.TIMEOUT:
                    message = "The request to get user location timed out.";
                    break;
                default:
                    message = "An unknown error occurred.";
                    break;
            }
        }
    };
}());

/**
 * Cookie helper
 *
 * @type {{set, get, check, remove, enabled}}
 */
var WBCookie = (function () {
    return {
        /**
         * Set cookie
         *
         * @param key
         * @param value
         * @param days
         */
        set: function (key, value, days) {
            days = (days) ? days : 3;
            var expireDays = parseInt(days);
            var d = new Date();
            d.setTime(d.getTime() + (expireDays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();

            document.cookie = key + "=" + value + "; " + expires;
        },

        /**
         * Get cookie
         *
         * @param key
         * @returns {string|null}
         */
        get: function (key) {
            var name = key + "=";
            var ca = document.cookie.split(';');

            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];

                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }

                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }

            return null;
        },

        /**
         * Check if cookie exist
         *
         * @param key
         * @returns {boolean}
         */
        check: function (key) {
            var user = WBCookie.get(key);

            return !!(user);
        },

        /**
         * Remove cookie
         *
         * @param key
         */
        remove: function (key) {
            WBCookie.set(key, '', -1);
        },

        /**
         * Is cookie available
         *
         * @returns {boolean}
         */
        enabled: function () {
            WBCookie.set('WBCookie', 'true', 1);

            if (WBCookie.check('WBCookie')) {
                WBCookie.remove('WBCookie');
                return true;
            }

            return false;
        }
    };
}());