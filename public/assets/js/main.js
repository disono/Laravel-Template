/**
 * Author: Archie, Disono (disono.apd@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // setup token for ajax request
    jQ.ajaxSetup({
        headers: {
            'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
        }
    });

    // data picker
    WBDate.datePicker();
    WBDate.datePickerMin();
    WBDate.none();

    // delete modal confimation box
    WBHelper.delAjax({
        selector: '.delete-data',
        reload: true
    });
});