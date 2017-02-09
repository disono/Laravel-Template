/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // apply voucher code
    jQ('#btnApplyVoucher').off().on('click', function (e) {
        e.preventDefault();
        var code = jQ('#input_voucher_code').val();

        if (code) {
            WBHelper.ajax({
                url: '/cart/voucher/add/' + code,
                success: function (data, textStatus, jqXHR) {
                    location.reload();
                }
            });
        }
    });

    // remove voucher code
    jQ('#btnRemoveVoucher').off().on('click', function (e) {
        e.preventDefault();

        WBHelper.ajax({
            url: '/cart/voucher/remove',
            success: function (data, textStatus, jqXHR) {
                location.reload();
            }
        });
    });
});