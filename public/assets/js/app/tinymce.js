/**
 * @author              Archie Disono (webmonsph@gmail.com)
 * @link                https://github.com/disono/Laravel-Template
 * @lincense            https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright           Webmons Development Studio
 */

jQ(document).ready(WBTinyMCE);

function WBTinyMCE() {
    if (typeof tinymce === 'undefined') {
        return;
    }

    let paginationNumber = 1;
    let onLoadingView = '<div class="w-100 d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';

    let MCEToolBars = 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | forecolor backcolor | link image media | insertBtn | code preview';
    let MCEContentStyle = "body {padding: 8px; background-color: #fff;}";

    let MCEContentCss = [
        '/assets/css/vendor.css',
        '/assets/css/theme.css'
    ];

    let MCEPlugins = [
        'advlist autolink lists link image charmap preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table directionality',
        'template paste textpattern imagetools',
        'code'
    ];

    function MCEOnFilePicker(callback, value, meta) {
        WBServices.view.dialogs('fileExplorer', function (views) {
            jQ('#fileExplorerLoading').hide();
            let type = meta.filetype;
            paginationNumber = 1;

            if (type === 'image') {
                type = 'photo';
            } else if (type === 'media') {
                type = 'video';
            }

            tinyFormUpload(views, type);
            tinyFetchData(views, type);
        }, function (r) {
            if (r) {
                callback(r.path);
            }
        }, function (r) {

        });
    }

    function MCESetup(editor) {
        editor.ui.registry.addMenuButton('insertBtn', {
            text: 'Insert',

            onAction: function () {

            },

            onItemAction: function (api, value) {
                editor.insertContent(value);
            },

            fetch: function (callback) {
                callback([
                    {
                        type: 'menuitem',
                        text: 'Audio',
                        onAction: function () {
                            WBServices.view.dialogs('fileExplorer', function (views) {
                                paginationNumber = 1;
                                tinyFormUpload(views, 'audio');
                                tinyFetchData(views, 'audio');
                            }, function (r) {
                                onInsertContent(r, editor);
                            }, function (r) {

                            });
                        }
                    },
                    {
                        type: 'menuitem',
                        text: 'Document',
                        onAction: function () {
                            WBServices.view.dialogs('fileExplorer', function (views) {
                                paginationNumber = 1;
                                tinyFormUpload(views, 'doc');
                                tinyFetchData(views, 'doc');
                            }, function (r) {
                                onInsertContent(r, editor);
                            }, function (r) {

                            });
                        }
                    },
                    {
                        type: 'menuitem',
                        text: 'File',
                        onAction: function () {
                            WBServices.view.dialogs('fileExplorer', function (views) {
                                paginationNumber = 1;
                                tinyFormUpload(views, 'file');
                                tinyFetchData(views, 'file');
                            }, function (r) {
                                onInsertContent(r, editor);
                            }, function (r) {

                            });
                        }
                    }
                ]);
            }
        });
    }

    function onInsertContent(r, editor) {
        if (!r) {
            return;
        }

        if (r.type === 'audio') {
            editor.insertContent(
                '<audio controls class="file-exp-insert-audio-con"><source src="' + r.path + '" type="audio/mpeg"></audio>'
            );
            return;
        }

        if (r.type === 'doc') {
            editor.insertContent(
                '<table class="table-vertical border-0">' +
                '   <tbody>' +
                '       <tr>' +
                '           <td data-th="Filename:" class="p-1 border-0">' + (r.title && r.title !== 'null' ? r.title : 'Document') + '</td>' +
                '           <td class="p-1 border-0"><a class="btn btn-primary btn-xs" href="' + r.path + '" target="_blank" rel="noopener noreferrer">View</a></td>' +
                '       </tr>' +
                '   </tbody>' +
                '</table>'
            );
            return;
        }

        if (r.type === 'file') {
            editor.insertContent(
                '<table class="table-vertical">' +
                '   <tbody>' +
                '       <tr>' +
                '           <td data-th="Filename:">' + (r.title && r.title !== 'null' ? r.title : 'File') + '</td><td><a class="btn btn-primary btn-xs" ' +
                '               href="' + r.path + '" target="_blank" rel="noopener noreferrer">Download</a>' +
                '           </td>' +
                '       </tr>' +
                '   </tbody>' +
                '</table>'
            );
            return;
        }

        if (r.type === 'photo') {
            editor.insertContent(
                '<img src="' + r.path + '">'
            );
            return;
        }

        if (r.type === 'video') {
            editor.insertContent(
                '<video width="320" height="240" controls><source src="' + r.path + '" type="video/mp4">Your browser does not support the video tag.</video>'
            );
        }
    }

    function parseView(data) {
        let view = '';

        data.forEach(function (data) {
            view += '<div class="col-lg-4 col-md-4 col-sm-6">';
            view += '<div class="d-block h-100 mb-4 text-center">';

            if (data.type === 'video') {
                view += '<video controls class="img-fluid" "><source src="' + data.path + '" type="video/mp4"></video>';
            } else if (data.type === 'audio') {
                view += '<audio controls class="file-exp-audio-list"><source src="' + data.path + '" type="audio/mpeg"></audio>';
            } else if (data.type === 'photo') {
                view += '<div style="background-image:url(' + data.cover + ') !important;" class="rounded file-exp-photo-list"></div>';
            } else {
                view += '<img class="img-fluid rounded" src="' + data.cover + '" alt="' + data.title + '">';
            }

            if (data.title !== '' && data.title !== null && data.title) {
                view += '<p class="mb-0 mt-1 text-center">' + data.title + '</p>';
            } else {
                view += '<p class="mb-0 mt-1 text-center">' + data.created_at + '</p>';
            }

            view += '<button type="button" class="mb-0 mt-1 btn btn-link btn-sm btn-block selected-file" ' +
                '   data-id="' + data.id + '" data-path="' + data.path + '" data-title="' + data.title + '" data-type="' + data.type + '">Select ' + data.type +
                '</button>';
            view += '</div>';
            view += '</div>';
        });

        return view;
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

    function onEvents(views, type) {
        // validate on change input values
        WBLibraries();

        jQ('.selected-file').off().on('click', function (e) {
            e.preventDefault();

            views.data = {
                id: jQ(this).attr('data-id'),
                path: jQ(this).attr('data-path'),
                title: jQ(this).attr('data-title'),
                type: jQ(this).attr('data-type')
            };

            jQ('.dialogConfirm').click();
        });

        jQ('.fileExplorerNav').off().on('click', function (e) {
            e.preventDefault();

            jQ('.fileExplorerNav').removeClass('active');
            jQ(this).addClass('active');
            let dataType = jQ(this).attr('data-type');
            dataType = (dataType === 'all') ? null : dataType;

            paginationNumber = 1;
            tinyFetchData(views, dataType);
        });

        jQ('#fileExplorerLoad').off().on('click', function (e) {
            e.preventDefault();
            jQ(this).hide();
            jQ('#fileExplorerLoading').show();
            tinyFetchData(views, type);
        });

        jQ('#fileExplorerSearchBar').off().keypress(function (e) {
            let key = e.which;
            if (key === 13) {
                paginationNumber = 1;
                tinyFetchData(views, type);
            }
        });

        let dataType = (type === null) ? 'all' : type;
        jQ('.fileExplorerNav').removeClass('active');
        jQ('.fileExplorerNav[data-type="' + dataType + '"]').addClass('active');
    }

    function tinyFormUpload(views, type) {
        // form
        jQ('#fileUploaderFrm').off().on('submit', function (e) {
            e.preventDefault();
            let self = jQ(this);

            WBServices.form.onUpload(e, function (response) {
                self.find('[name="title"]').val('');
                self.find('[name="description"]').val('');
                self.find('[name="file_selected"]').val('');

                if (response.data.type === type) {
                    renderView(parseView([response.data]), 1);
                    onEvents(views, type);
                }
            }, function (e) {

            });
        });
    }

    function tinyFetchData(views, type) {
        if (paginationNumber === 1) {
            jQ('#fileSelectList').html(onLoadingView);
        }

        // list of files
        WBServices.raw.get('/files', {
            page: paginationNumber,
            type: type,
            search: jQ('#fileExplorerSearchBar').val()
        }).then(function (response) {
            jQ('#fileExplorerLoad').show();
            jQ('#fileExplorerLoading').hide();

            if (paginationNumber === 1) {
                jQ('#fileSelectList').html('');
            }

            views.buttons();
            renderView(parseView(response.data), 0);
            onEvents(views, type);

            if (response.data.length) {
                paginationNumber++;
            } else if (paginationNumber === 1) {
                type = type ? type : 'All';
                jQ('#fileSelectList').html('<h4 class="text-center w-100">No files uploaded (' + type + ').</h4>');
            }
        }).catch(function (error) {
            jQ('#fileExplorerLoad').show();
            jQ('#fileExplorerLoading').hide();
            views.buttons();
        });
    }

    tinymce.init({
        selector: 'textarea.tiny',
        menubar: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        plugins: MCEPlugins,

        // list of menu
        toolbar: MCEToolBars,
        image_advtab: true,

        // added styles
        content_css: MCEContentCss,

        // inline styles
        content_style: MCEContentStyle,

        // images selector from server
        file_picker_types: 'image media',
        file_picker_callback: MCEOnFilePicker,
        setup: MCESetup
    });
}