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

    WBLeafMap.init('leafMap');
    WBLeafMap.addMarker(14.5995, 120.9842, {
        popup: {
            content: 'This is a content.'
        },
        icon: 'http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Chartreuse-icon.png'
    }, function (e) {
        console.log(this.getLatLng());
    });

    // on click map
    WBLeafMap.onClick(function (e) {
        WBLeafMap.addMarker(e.latlng.lat, e.latlng.lng, {
            popup: {
                content: 'This is a content.'
            },
            icon: 'http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Chartreuse-icon.png'
        }, function (e) {
            console.log(this.getLatLng());
        });
    });

    // delete markers
    jQ('#deleteMarkers').off().on('click', function (e) {
        WBLeafMap.deleteMarker();
    });

    // search
    WBLeafMap.search('searchBox');

    // routing
    WBLeafMap.route([
        L.latLng(14.5547, 121.0244),
        L.latLng(14.6760, 121.0437)
    ], function (e) {

    });
});