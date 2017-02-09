/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // applied type
    jQ('#applied_type').on('change', function () {
        var value = jQ(this).val();

        if (value == 'product') {
            WBHelper.ajax({
                url: '/admin/product',
                type: 'get',
                dataType: 'html',
                data: {
                    reponse_type: 'checkbox_list'
                },
                success: function (data, textStatus, jqXHR) {
                    jQ('#applied_type_container').html(data);

                    // replace all input with product_id[] to applied_on[]
                    jQ('[name="product_id[]"]').attr('name', 'applied_on[]');
                }
            });
        } else if (value == 'category') {
            WBHelper.ajax({
                url: '/admin/product/category',
                type: 'get',
                dataType: 'html',
                data: {
                    reponse_type: 'checkbox_list'
                },
                success: function (data, textStatus, jqXHR) {
                    jQ('#applied_type_container').html(data);

                    // replace all input with product_category_id[] to applied_on[]
                    jQ('[name="product_category_id[]"]').attr('name', 'applied_on[]');
                }
            });
        } else {
            jQ('#applied_type_container').html('');
        }
    });

    // expiration
    isExpirationCheck();
    jQ('#is_can_expired').on('change', function () {
        isExpirationCheck();
    });

    function isExpirationCheck() {
        if (jQ('#is_can_expired').is(':checked')) {
            jQ('#expired_at_container').show();
        } else {
            jQ('#expired_at_container').hide();
            jQ('#expired_at').val('');
        }
    }
});