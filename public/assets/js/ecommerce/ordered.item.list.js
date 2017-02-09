/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    jQ('.btn-confirm-status').off().on('click', function (e) {
        e.preventDefault();
        var me = jQ(this);
        var href = me.attr('href');

        var form = '<div class="form-group"> \
                    <label for="status_reason">Reason/Comment</label> \
                    <textarea name="status_reason" id="status_reason" cols="30" rows="4" class="form-control"></textarea> \
                    </div>';
        WBHelper.modal('Continue', form, function () {
            WBHelper.ajax({
                url: href,
                data: {
                    reason: jQ('#status_reason').val()
                },
                success: function (data, textStatus, jqXHR) {
                    location.reload();
                }
            });
        }, 'Continue');
    });
});