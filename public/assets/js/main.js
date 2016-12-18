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

    // date picker
    WBDate.datePicker();
    WBDate.datePickerMin();
    WBDate.none();

    // time picker
    WBDate.timePicker();

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
});