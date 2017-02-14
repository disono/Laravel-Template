/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    WBMap.init('map_user_locator');

    jQ('#locator_role_selector').on('change', function () {
        fetchActiveUsers(jQ(this).val());
    });

    /**
     * Fetch active users
     *
     * @param role
     */
    function fetchActiveUsers(role) {
        var filters =  (!role) ? null : {
                role: role
            };

        // clear markers
        WBMap.deleteMarker();

        WBHelper.ajax({
            url: WBSocket.uri() + 'user/all',
            type: 'get',
            data: filters,
            success: function (response) {
                if (response.success) {
                    var data = response.data;

                    for (var i = 0; i < data.length; i++) {
                        WBMap.addMarker(data[i].geo[0], data[i].geo[1], data[i], function (lat, lng, marker_data, marker) {
                            console.log(lat + ' ' + lng);

                            window.open('/user/' + marker_data.user_id + '?type=id', "_blank");
                        });
                    }
                }
            }
        });
    }

    // fetch all
    fetchActiveUsers('');
});