/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    WBMap.init('mapBranch');

    WBMap.search('address', function (place) {
        jQ('#lat').val(place.geometry.location.lat());
        jQ('#lng').val(place.geometry.location.lng());
    });
});