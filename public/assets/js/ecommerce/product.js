/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    var is_qty_required = '#is_qty_required';
    isQuantityRequired();

    jQ(is_qty_required).on('change', function (e) {
        isQuantityRequired();
    });

    function isQuantityRequired() {
        if(jQ(is_qty_required).is(':checked')) {
            jQ('#quantityContainer').show();
        } else {
            jQ('#quantityContainer').hide();
        }
    }
});