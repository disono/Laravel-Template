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
});