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
        menubar: false,
        theme: 'modern',

        height : "480",

        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        plugins: [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'template paste textcolor colorpicker textpattern imagetools'
        ],

        // list of menu
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | link image | preview media | forecolor backcolor | file_upload',
        image_advtab: true,

        // added styles
        content_css: [
            '/assets/css/vendor.css',
            '/assets/css/main.css'
        ],

        // images selector from server
        file_browser_callback_types: 'image media',
        file_browser_callback: function(field_name, url, type, win) {
            WBHelper.imageChooser(function (data) {
                if (!data) {
                    return;
                }

                win.document.getElementById(field_name).value = data.path;
            });
        },

        // add button for file upload
        setup: function (editor) {
            editor.addButton('file_upload', {
                icon: 'fa fa-cloud-upload',
                onclick: function () {
                    // modal to upload file
                    WBHelper.fileChooser(function (data) {
                        if (!data) {
                            return;
                        }

                        editor.insertContent('<a href="' + data.path + '" target="_blank" class="btn btn-primary">Download</a>');
                    });
                }
            });
        }
    });
});