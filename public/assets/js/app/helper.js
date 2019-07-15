/**
 * @author              Archie Disono (webmonsph@gmail.com)
 * @link                https://github.com/disono/Laravel-Template
 * @lincense            https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright           Webmons Development Studio
 */

let WBHelper = (function () {
    return {
        console: {
            log: function (message) {
                console.log('Debugging(Log): ' + message !== 'object' ? message : JSON.stringify(message));
            },

            error: function (message) {
                console.error('Debugging(Error): ' + message !== 'object' ? message : JSON.stringify(message));
            }
        },

        /**
         * Geo location
         * https://www.w3schools.com/html/html5_geolocation.asp
         */
        geo: {
            locate: function () {
                let self = this;
                return new Promise(function (resolve, reject) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (pos) {
                            resolve(pos.coords);
                        }, function (e) {
                            reject('Error(' + e.code + '): ' + self.geo.errors(e));
                        });

                        return;
                    }

                    reject('Geolocation is unavailable to your browser.');
                });
            },

            errors: function (e) {
                let message = 'An unknown error occurred.';
                switch (e.code) {
                    case e.PERMISSION_DENIED:
                        message = "User denied the request for Geo-location.";
                        break;
                    case e.POSITION_UNAVAILABLE:
                        message = "Location information is unavailable.";
                        break;
                    case e.TIMEOUT:
                        message = "The request to get user location timed out.";
                        break;
                    default:
                        message = "An unknown error occurred.";
                        break;
                }
                return message;
            }
        },

        cookie: {
            /**
             * Set cookie
             *
             * @param key
             * @param value
             * @param days
             */
            set: function (key, value, days) {
                days = (days) ? days : 3;
                let expireDays = parseInt(days);
                let d = new Date();
                d.setTime(d.getTime() + (expireDays * 24 * 60 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();

                document.cookie = key + "=" + value + "; " + expires;
            },

            /**
             * Get cookie
             *
             * @param key
             * @returns {string|null}
             */
            get: function (key) {
                let name = key + "=";
                let ca = document.cookie.split(';');

                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];

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
                let user = this.cookie.get(key);

                return !!(user);
            },

            /**
             * Remove cookie
             *
             * @param key
             */
            remove: function (key) {
                this.cookie.set(key, '', -1);
            },

            /**
             * Is cookie available
             *
             * @returns {boolean}
             */
            enabled: function () {
                this.cookie.set('WBCookie', 'true', 1);

                if (this.cookie.check('WBCookie')) {
                    this.cookie.remove('WBCookie');
                    return true;
                }

                return false;
            }
        },

        arrays: {
            chunk: function (collection, size) {
                let result = [];

                // default size to two item
                size = parseInt(size) || 2;

                // add each chunk to the result
                for (let x = 0; x < Math.ceil(collection.length / size); x++) {

                    let start = x * size;
                    let end = start + size;

                    result.push(collection.slice(start, end));

                }

                return result;
            }
        }
    };
}());