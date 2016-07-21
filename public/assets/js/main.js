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

    // date picker
    WBDate.datePicker();
    WBDate.datePickerMin();
    WBDate.none();
    
    // time picker
    WBDate.timePicker();

    // delete modal confimation box
    WBHelper.delAjax({
        selector: '.delete-data',
        reload: true
    });
});