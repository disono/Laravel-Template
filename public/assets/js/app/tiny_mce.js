/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

jQ(document).ready(function () {
    let _filePage = 1;

    if (typeof tinymce === 'undefined') {
        return;
    }

    tinymce.init({
        selector: 'textarea',
        menubar: false,

        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        plugins: [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table directionality',
            'template paste textpattern imagetools',
            'code'
        ],

        // list of menu
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | forecolor backcolor | link image media audio_upload document_upload file_upload | code preview',
        image_advtab: true,

        // added styles
        content_css: [
            '/assets/css/vendor.css',
            '/assets/css/theme.css'
        ],
        content_style: "body {padding: 10px}",

        // images selector from server
        file_picker_types: 'image media',
        file_picker_callback: function (callback, value, meta) {
            WBServices.view.dialogs('fileSelector', function (views) {
                let type = meta.filetype;

                if (type === 'image') {
                    type = 'photo';
                } else if (type === 'media') {
                    type = 'video';
                }

                _filePage = 1;
                formUpload(views, type);
                fetchData(views, type);
            }, function (r) {
                if (r) {
                    callback(r.path);
                }
            }, function (r) {

            });
        },

        setup: function (editor) {
            // file
            editor.ui.registry.addButton('file_upload', {
                icon: 'upload',
                tooltip: 'Insert/edit files',
                onAction: function () {
                    WBServices.view.dialogs('fileSelector', function (views) {
                        _filePage = 1;
                        formUpload(views, 'file');
                        fetchData(views, 'file');
                    }, function (r) {
                        if (r) {
                            editor.insertContent('<table class="table-vertical"><tbody><tr>' +
                                '<td data-th="Filename:">' + ((r.title && r.title !== 'null') ? r.title : '') + '</td><td><a class="btn btn-default btn-xs" ' +
                                'href="' + r.path + '" target="_blank" rel="noopener noreferrer">Download</a>' +
                                '</td></tr></tbody></table>');
                        }
                    }, function (r) {

                    });
                }
            });

            // document
            editor.ui.registry.addButton('document_upload', {
                icon: 'document-properties',
                tooltip: 'Insert/edit documents',
                onAction: function () {
                    WBServices.view.dialogs('fileSelector', function (views) {
                        _filePage = 1;
                        formUpload(views, 'doc');
                        fetchData(views, 'doc');
                    }, function (r) {
                        if (r) {
                            editor.insertContent('<table class="table-vertical"><tbody><tr>' +
                                '<td data-th="Filename:">' + ((r.title && r.title !== 'null') ? r.title : '') + '</td><td><a class="btn btn-default btn-xs" ' +
                                'href="' + r.path + '" target="_blank" rel="noopener noreferrer">View</a>' +
                                '</td></tr></tbody></table>');
                        }
                    }, function (r) {

                    });
                }
            });

            // audio
            editor.ui.registry.addButton('audio_upload', {
                icon: 'browse',
                tooltip: 'Insert/edit audio',
                onAction: function () {
                    WBServices.view.dialogs('fileSelector', function (views) {
                        _filePage = 1;
                        formUpload(views, 'audio');
                        fetchData(views, 'audio');
                    }, function (r) {
                        if (r) {
                            editor.insertContent('<audio controls style="width: 40%;"><source src="' + r.path +
                                '" type="audio/mpeg"></audio>');
                        }
                    }, function (r) {

                    });
                }
            });
        }
    });

    function parseView(data) {
        let _view = '';

        data.forEach(function (data) {
            _view += '<div class="col-lg-4 col-md-4 col-xs-6">';
            _view += '<a href="#" class="d-block mb-4 h-100 selectedFile" data-id="' + data.id + '" data-path="' + data.path + '" data-title="' + data.title + '">';

            if (data.type === 'video') {
                _view += '<video controls style="width: 100%;"><source src="' + data.path + '" type="video/mp4"></video>';
            } else if (data.type === 'audio') {
                _view += '<audio controls style="width: 100%;"><source src="' + data.path + '" type="audio/mpeg"></audio>';
            } else {
                _view += '<img class="img-fluid img-thumbnail col-12" src="' + data.cover + '" alt="' + data.title + '">';
            }

            if (data.title !== '' && data.title !== null && data.title) {
                _view += '<p class="mb-0">' + data.title + '</p>';
            } else {
                _view += '<p class="mb-0">' + data.created_at + '</p>';
            }

            _view += '<p class="mb-0 btn btn-primary btn-sm btn-block mt-3">Select</p>';
            _view += '</a>';
            _view += '</div>';
        });

        return _view;
    }

    function renderView(data, isTop) {
        if (data === '' || !data) {
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
            let _dataType = jQ(this).attr('data-type');
            _dataType = (_dataType === 'all') ? null : _dataType;

            _filePage = 1;
            fetchData(views, _dataType);
        });

        jQ('#fileSelectorLoad').off().on('click', function (e) {
            e.preventDefault();
            fetchData(views, type);
        });

        let _dataType = (type === null) ? 'all' : type;
        jQ('.fileSelectorNav').removeClass('active');
        jQ('.fileSelectorNav[data-type="' + _dataType + '"]').addClass('active');
    }

    function formUpload(views, type) {
        // form
        jQ('#fileUploaderFrm').off().on('submit', function (e) {
            e.preventDefault();
            let _me = jQ(this);

            WBServices.form.onUpload(e, function (response) {
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
        WBServices.raw.get('/files', {
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