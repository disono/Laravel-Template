/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // setup token for ajax request
    jQ.ajaxSetup({
        headers: {
            'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
        }
    });

    // delete modal confirmation box
    WBHelper.delAjax({
        selector: '.delete-data',
        reload: true
    });

    // light-box
    jQ(document).delegate('*[data-toggle="light-box"]', 'click', function (event) {
        event.preventDefault();
        jQ(this).ekkoLightbox();
    });

    // container of multiple upload images
    var image_container = jQ('#image_container').html();
    jQ('#addImage').off().on('click', function (e) {
        e.preventDefault();

        jQ('#images').append(image_container);
    });

    // rating read only
    jQ('.rating-read-only').each(function (i, obj) {
        var init_val = jQ(this).attr('data-value');

        jQ(this).awesomeRating({
            valueInitial: parseInt(init_val),
            readonly: true
        });
    });

    // rating
    jQ('.rating').each(function (i, obj) {
        var data_input = jQ(this).attr('data-input');

        jQ(this).awesomeRating({
            valueInitial: 5,
            values: [1, 2, 3, 4, 5],
            targetSelector: data_input,
            readonly: false
        });
    });

    // select picker initialize
    jQ('select').selectpicker({
        liveSearch: true
    });

    // date picker
    // class="date-picker"
    WBDate.datePicker();
    // class="date-picker-min"
    WBDate.datePickerMin();
    // class="date-picker-none"
    WBDate.none();

    // time picker
    // class="time-picker"
    WBDate.timePicker();

    // form ajax call
    // class="ajax-form"
    WBHelper.form();

    // confirm modal
    WBHelper.confirm();

    // confirm modal for form
    WBHelper.confirmForm();

    // socket
    WBSocket.connect(function () {
        // on connect

        // messaging
        WBSocket.on('message_session_' + jQ('meta[name="_authenticated_id"]').attr('content'), function (data) {
            if (data.from_id != jQ('#inbox_from_id').val()) {
                WBErrors.toastMessage({
                    title: 'New Message',
                    message: data.message
                });
            } else {
                WBSocket.emitter().emitEvent('msg_received', [data]);
            }
        });
    }, function () {
        // event
    }, function () {
        // disconnect
    });
});

