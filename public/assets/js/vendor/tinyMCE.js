/**
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

jQ(document).ready(function () {
    var _filePage = 1;

    tinymce.init({
        selector: '.tiny-editor-content',
        menubar: false,
        theme: 'modern',

        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        plugins: [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'template paste textcolor colorpicker textpattern imagetools',
            'code'
        ],

        // list of menu
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image media file_upload | forecolor backcolor | code preview',
        image_advtab: true,

        // added styles
        content_css: [
            '/assets/css/vendor.css',
            '/assets/css/theme.css'
        ],

        // images selector from server
        file_browser_callback_types: 'image media',
        file_browser_callback: function (field_name, url, type, win) {
            VueAppMethods.dialogs('fileSelector', function (views) {

                if (type === 'image') {
                    type = 'photo';
                } else if (type === 'media') {
                    type = 'video';
                }

                formUpload(views, type);

                _filePage = 1;
                fetchData(views, type);
            }, function (r) {
                if (r) {
                    win.document.getElementById(field_name).value = r.path;
                }
            }, function (r) {

            });
        },

        setup: function (editor) {
            // experimental (bootstrap components)
            editor.addButton('bootstrap', {
                text: 'Components',
                icon: false,
                onclick: function () {
                    editor.insertContent('');
                }
            });

            // file upload any file
            editor.addButton('file_upload', {
                icon: 'upload',
                onclick: function () {
                    VueAppMethods.dialogs('fileSelector', function (views) {
                        formUpload(views, null);

                        _filePage = 1;
                        fetchData(views, null);
                    }, function (r) {
                        if (r) {
                            editor.insertContent('<table class="table-vertical"><tbody><tr>' +
                                '<td data-th="Filename:">' + r.title + '</td><td><a class="btn btn-default btn-xs" ' +
                                'href="' + r.path + '" target="_blank" rel="noopener noreferrer">Download</a>' +
                                '</td></tr></tbody></table>');
                        }
                    }, function (r) {

                    });
                }
            });
        }
    });

    function parseView(data) {
        var _view = '';

        data.forEach(function (data) {
            _view += '<div class="col-lg-3 col-md-4 col-xs-6">';
            _view += '<a href="#" class="d-block mb-4 h-100 selectedFile" data-id="' + data.id + '" data-path="' + data.path + '" data-title="' + data.title + '">';
            _view += '<img class="img-fluid img-thumbnail col-12" src="' + data.cover + '" alt="' + data.title + '">';
            if (data.title !== '' && data.title !== null && data.title) {
                _view += '<p>' + data.title + '</p>';
            }
            _view += '</a>';
            _view += '</div>';
        });

        return _view;
    }

    function renderView(data, isTop) {
        if (data === '' || !data || data === null) {
            return;
        }

        if (isTop === 1) {
            jQ('#fileSelectList').prepend(data);
        } else {
            jQ('#fileSelectList').append(data);
        }
    }

    function viewEvents(views, type) {
        jQ('.selectedFile').off().on('click', function (e) {
            e.preventDefault();

            views.data = {
                id: jQ(this).attr('data-id'),
                path: jQ(this).attr('data-path'),
                title: jQ(this).attr('data-title')
            };

            jQ('.dialogConfirm').click();
        });

        jQ('.fileSelectorNav').off().on('click', function (e) {
            e.preventDefault();

            jQ('.fileSelectorNav').removeClass('active');
            jQ(this).addClass('active');
            var _dataType = jQ(this).attr('data-type');
            _dataType = (_dataType === 'all') ? null : _dataType;

            _filePage = 1;
            fetchData(views, _dataType);
        });

        jQ('#fileSelectorLoad').off().on('click', function (e) {
            e.preventDefault();
            fetchData(views, type);
        });

        var _dataType = (type === null) ? 'all' : type;
        jQ('.fileSelectorNav').removeClass('active');
        jQ('.fileSelectorNav[data-type="' + _dataType + '"]').addClass('active');
    }

    function formUpload(views, type) {
        // form
        jQ('#fileUploaderFrm').off().on('submit', function (e) {
            e.preventDefault();
            var _me = jQ(this);

            VueAppMethods.onUpload(e, function (response) {
                _me.find('[name="title"]').val('');
                _me.find('[name="description"]').val('');
                _me.find('[name="file_selected"]').val('');

                renderView(parseView([response.data]), 1);
                viewEvents(views, type);
            }, function (e) {

            });
        });
    }

    function fetchData(views, type) {
        if (_filePage === 1) {
            jQ('#fileSelectList').html('');
        }

        // list of files
        VueAppMethods.httpGet('/files', {
            page: _filePage,
            type: type
        }).then(function (response) {
            views.buttons();

            renderView(parseView(response.data), 0);
            viewEvents(views, type);

            if (response.data.length) {
                _filePage++;
            }
        }).catch(function (error) {
            views.buttons();
        });
    }
});