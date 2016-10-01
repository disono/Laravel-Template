/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // initialize tiny-mce
    tinymce.init({
        selector: 'textarea',
        menubar: true,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'preview media | forecolor backcolor',
        image_advtab: true,
        content_css: [
            '/assets/css/vendor.css',
            '/assets/css/main.css'
        ]
    });
});