/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

// jquery no conflict
var jQ = jQuery.noConflict();

/**
 * Application helpers
 *
 * @type {{initDefaults, enableFormInputs, disableFormInputs, ajax, delAjax, upload, form, modal, confirm, confirmForm, imageChooser, clearForm, preLoadImg, goTo}}
 */
var WBHelper = (function () {
    var _private = {
        defaultAjax: function () {
            return {
                formId: null,       // form id
                url: '',            // url
                type: 'post',       // method (get, post)
                async: true,        // asynchronous request
                data: {},           // data to send
                dataType: 'json',   // data type expected to received from server default json
                beforeSend: function (jqXHR, settings) {
                    // do stuff before sending
                },
                complete: function (jqXHR, textStatus) {
                    // do stuff if ajax is complete
                },
                success: function (data, textStatus, jqXHR) {
                    // do stuff if ajax is success
                },
                error: function (xhr, status, error) {
                    console.log('AJAX HTTP Error'); // do stuff if critical error occurred
                },
                errorType: 'bottomMessage',         // error message type
                statusContainer: '#responseMessage',// status message type
                showErrors: true,                   // show error messages
                removeMessage: true,                // remove status messages (responseMessage)
                disableForm: true,                  // disable form
                clearMessage: true,                 // clear messages
                enableForm: true,                   // enable form after ajax complete
                enableFormSuccess: true,            // enable form on success
                submitLoadingText: 'Loading...',    // submit button text
                clearForm: true                     // clear form use for uploads
            }
        }
    };

    return {
        /**
         * Initialize options with default values
         *
         * @param defaults
         * @param options
         * @returns {*}
         */
        initDefaults: function (defaults, options) {
            // make sure all options is on default values
            for (var prop in defaults) {
                // Note: if options would contain some undefined or unnecessary values, you should check for undefined instead.
                options[prop] = (typeof options[prop] !== 'undefined') ? options[prop] : defaults[prop];
            }

            return options;
        },

        /**
         * Enable form if disabled
         *
         * @param formId
         */
        enableFormInputs: function (formId) {
            // do not enable if attributes of input is data-disabled="yes"
            jQ(formId).find('input, textarea, button, select').not('[data-disabled="yes"]').removeAttr("disabled");
        },

        /**
         * Disable form if enabled
         *
         * @param formId
         */
        disableFormInputs: function (formId) {
            jQ(formId).find('input, textarea, button, select').attr("disabled", "disabled");
        },

        /**
         * AJAX
         *
         * @param options
         */
        ajax: function (options) {
            var btnSubmitLoading = null;
            var frmSubmitButton = null;

            // initialize defaults
            options = WBHelper.initDefaults(_private.defaultAjax(), options);

            jQ.ajax({
                url: options.url,
                type: options.type,
                data: options.data,
                async: options.async,
                beforeSend: function (jqXHR, settings) {
                    // disable forms
                    if (options.disableForm && jQ(options.formId).length) {
                        WBHelper.disableFormInputs(options.formId);
                    }

                    // clear messages
                    if (options.clearMessage) {
                        WBErrors.clearMessages(false);
                    }

                    // change button submit with (Loading...)
                    if (jQ(options.formId).length) {
                        frmSubmitButton = jQ(options.formId).find('button[type="submit"]');
                        btnSubmitLoading = frmSubmitButton.text();

                        frmSubmitButton.text(options.submitLoadingText);
                    }

                    // before send
                    options.beforeSend(jqXHR, settings);
                },
                success: function (data, textStatus, jqXHR) {
                    // clear messages
                    if (options.clearMessage) {
                        WBErrors.clearMessages(false);
                        jQ('[data-error-block]').remove();
                    }

                    options.enableForm = options.enableFormSuccess;

                    // success
                    options.success(data, textStatus, jqXHR);
                },
                error: function (xhr, status, error) {
                    // clear messages
                    if (options.clearMessage) {
                        WBErrors.clearMessages(false);
                        jQ('[data-error-block]').remove();
                    }

                    if (xhr.status == 422) {
                        var response = (typeof xhr.responseText == 'string') ? xhr.responseText : "";
                        WBErrors.run(JSON.parse(response), options);
                    }

                    options.error(xhr, status, error);

                    // log the errors
                    // shows error message
                    WBErrors.log(xhr.status + ' ' + error);
                    if (typeof xhr === 'object') {
                        console.error(JSON.stringify(xhr));
                    } else {
                        console.error(xhr);
                    }
                },
                complete: function (jqXHR, textStatus) {
                    // enable form
                    if (options.disableForm && jQ(options.formId).length) {
                        if (options.enableForm) {
                            WBHelper.enableFormInputs(options.formId);
                        }
                    }

                    // change button submit
                    if (jQ(options.formId).length) {
                        frmSubmitButton.text(btnSubmitLoading);
                    }

                    // status code
                    WBHeaders.get(jqXHR.status);

                    // complete
                    options.complete(jqXHR, textStatus);
                }
            });
        },

        /**
         * Delete data from server
         *
         * @param options
         */
        delAjax: function (options) {
            // options default values
            var _defaults = {
                selector: null,     // selector item
                url: null,          // url
                reload: false,      // reload page
                redirectTo: false,  // redirect to url
                type: 'post',       // is get or post
                removeContainerId: false,   // container of data to be delete
                removeItem: true,           // remove container
                showErrors: true,           // show errors
                errorType: 'toastMessage',  // error type to show
                beforeSend: function (jqXHR, settings) {
                    // before sending data do other stuff
                },
                success: function (data, textStatus, jqXHR) {
                    // custom success
                },
                complete: function (jqXHR, textStatus) {
                    // ajax complete
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // critical error
                },
                isModal: true // show modal before deletion
            };

            // initialize defaults
            options = WBHelper.initDefaults(_defaults, options);

            // delete on click
            jQ(options.selector).off().on('click', function (e) {
                e.preventDefault();
                var me = jQ(this);
                var itemId = me.attr('data-id');
                var url = me.attr('href');

                // default url
                if (url && (url != '#') && (url != '/') && (url != '')) {
                    options.url = url;
                }

                if (options.isModal) {
                    // show modal before deletion
                    jQ('.modal-delete').modal({
                        show: true
                    });

                    // if yes delete item
                    jQ('#delYes').off().on('click', function (e) {
                        e.preventDefault();

                        delMethod(me, itemId);
                    });
                } else {
                    delMethod(me, itemId);
                }
            });

            // delete data
            function delMethod(me, itemId) {
                // hide delete modal
                if (options.isModal) {
                    jQ('.modal-delete').modal('hide');
                }

                jQ.ajax({
                    url: options.url,
                    type: options.type,
                    dataType: 'json',
                    data: {id: itemId},
                    beforeSend: function (xhr, opts) {
                        // disable button before sending
                        me.attr("disabled", "disabled");

                        // clear all error messages
                        WBErrors.clearMessages(false);

                        // before sending data do your stuffs
                        options.beforeSend(xhr, opts);
                    },
                    success: function (data, textStatus, jqXHR) {
                        swal("Deleted", "Successfully Deleted.", "success");

                        if (options.reload === true) {
                            location.reload();
                        } else if (options.removeItem === true) {
                            // remove container after 800 milli seconds
                            setTimeout(function () {
                                var removeContainerId = itemId;
                                if (options.removeContainerId === true) {
                                    // get the container of data
                                    removeContainerId = me.attr('data-delete-container');
                                }

                                var container = jQ('[data-container=' + removeContainerId + ']');
                                container.fadeOut(800, function () {
                                    container.remove();
                                    // do your custom stuff
                                    options.success(data, textStatus, jqXHR);
                                });
                            }, 300);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // validation errors
                        if (jqXHR.status == 422) {
                            var response = (typeof jqXHR.responseText == 'string') ? jqXHR.responseText : "";
                            WBErrors.run(JSON.parse(response), options);
                        }

                        // callback error
                        options.error(jqXHR, textStatus, errorThrown);

                        // log the errors
                        // shows error message
                        WBErrors.log(jqXHR.status + ' ' + textStatus);
                        if (typeof jqXHR === 'object') {
                            console.error(JSON.stringify(jqXHR));
                        } else {
                            console.error(jqXHR);
                        }
                    },
                    complete: function (jqXHR, textStatus) {
                        // remove disable button
                        me.removeAttr("disabled");

                        // status code
                        WBHeaders.get(jqXHR.status);

                        // complete
                        options.complete(jqXHR, textStatus);
                    }
                });
            }
        },

        /**
         * Upload
         *
         * Single file: {name: value}
         * Multiple file: {name_1: [value_1, value_2], name_2: [value_1, value_2]}
         *
         * @param url
         * @param options
         * @param beforeSend
         * @param successCallback
         * @param errorCallback
         */
        upload: function (url, options, beforeSend, successCallback, errorCallback) {
            // options default values
            var _defaults = {
                formId: null,       // form id
                inputs: {},         // form inputs
                files: {},          // files
                reload: false,      // reload page
                redirectTo: null,   // redirect to url
                showErrors: true,           // show errors
                errorType: 'toastMessage',  // error type to show

                statusContainer: '#responseMessage',// status message type
                removeMessage: true,                // remove status messages (responseMessage)
                disableForm: true,                  // disable form
                clearMessage: true,                 // clear messages
                enableForm: true,                   // enable form after ajax complete
                enableFormSuccess: true,            // enable form on success
                submitLoadingText: 'Loading...',    // submit button text
                clearForm: true                     // clear form use for uploads
            };

            // initialize defaults
            options = WBHelper.initDefaults(_defaults, options);

            // HTTP Request
            var xhr = new XMLHttpRequest();

            // form inputs
            var formData = new FormData();

            // open the connection.
            xhr.open('POST', url, true);

            // files to upload
            if (options.files) {
                jQ.each(options.files, function (i, val) {
                    if (Array.isArray(val)) {
                        // multiple files upload
                        for (var num = 0; num < val.length; num++) {
                            formData.append(i + '[]', val[num]);
                        }
                    } else {
                        // single upload
                        formData.append(i, val);
                    }
                });
            }

            // inputs
            if (options.inputs) {
                jQ.each(options.inputs, function (i, val) {
                    formData.append(i, val);
                });
            }

            // before sending data
            beforeSend();

            // load to server
            // set up a handler for when the request finishes.
            xhr.onload = function () {
                var response = this.response;

                if (response) {
                    var data = (response) ? response : '{"success": false, "errors": []}';
                    try {
                        var res = JSON.parse(data);
                    } catch (e) {
                        WBErrors.run('Error: ' + e, options.errorType);
                        console.error(e);

                        errorCallback(e);
                        return;
                    }

                    if (res.success) {
                        successCallback(res);
                    } else {
                        errorCallback(res);
                        if (res) {
                            console.error(res);
                        }

                        if (options.showErrors) {
                            // error_type: bottomMessage, toastMessage, clearMessages
                            WBErrors.run(res, options);
                        }
                    }
                } else {
                    errorCallback(null);
                    console.error('Unknown error response.');
                }
            };

            // CSRF token
            xhr.setRequestHeader("X-CSRF-Token", jQ('meta[name="_token"]').attr('content'));
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

            // send the data.
            xhr.send(formData);
        },

        /**
         * Form AJAX call
         */
        form: function () {
            // ajax form submission
            jQ('.ajax-form').off().on('submit', function (e) {
                e.preventDefault();
                var me = jQ(this);

                var id = me.attr('id');
                var action = me.attr('action');
                var method = me.attr('method');
                var enctype = me.attr('enctype');
                var data = me.serialize();
                var dataArray = me.serializeArray();

                // view
                var frmSubmitButton = null;
                var btnSubmitLoading = null;

                // if id is not present make random id
                if (!id) {
                    var formId = Math.random().toString(36).substring(7);
                    me.attr('id', formId);
                    id = me.attr('id');
                }

                if (enctype) {
                    var inputs = {};
                    var files = {};

                    // clean inputs
                    jQ.each(dataArray, function (i, val) {
                        inputs[val.name] = val.value;
                    });

                    // clean files
                    var found_array_files = [];
                    jQ('#' + id + ' [type="file"]').each(function (i, obj) {
                        // this file is array
                        var array_cache_found = false;
                        for (var search_index = 0; search_index < found_array_files.length; search_index++) {
                            if (found_array_files[search_index] == jQ(obj).attr('name')) {
                                array_cache_found = true;
                                break;
                            }
                        }

                        if (jQ('#' + id + ' [name="' + jQ(obj).attr('name') + '"]').length > 1 && !array_cache_found) {
                            // add to found array files
                            found_array_files.push(jQ(obj).attr('name'));

                            files[jQ(obj).attr('name')] = [];
                            jQ('#' + id + ' [name="' + jQ(obj).attr('name') + '"]').each(function (iFileArray, objFileArray) {
                                var filesObj = jQ(objFileArray).prop('files');

                                if (filesObj && filesObj[0]) {
                                    files[jQ(obj).attr('name')].push(filesObj[0]);
                                }
                            });

                            if (!files[jQ(obj).attr('name')].length) {
                                delete files[jQ(obj).attr('name')];
                            }
                        } else if (jQ('#' + id + ' [name="' + jQ(obj).attr('name') + '"]').length <= 1) {
                            var filesObj = jQ(obj).prop('files');

                            if (filesObj && filesObj[0]) {
                                files[jQ(obj).attr('name')] = filesObj[0];
                            }
                        }
                    });

                    // upload file
                    WBHelper.upload(action, {
                        formId: '#' + id,
                        inputs: inputs,
                        files: files,
                        errorType: 'bottomMessage'
                    }, function (beforeSend) {
                        // disable forms
                        if (jQ('#' + id).length) {
                            WBHelper.disableFormInputs('#' + id);
                        }

                        // clear messages
                        WBErrors.clearMessages(false);

                        // change button submit with (Loading...)
                        if (jQ('#' + id).length) {
                            frmSubmitButton = jQ('#' + id).find('button[type="submit"]');
                            btnSubmitLoading = frmSubmitButton.text();

                            frmSubmitButton.text('Loading...');
                        }
                    }, function (response) {
                        _completeResponse();

                        if (response.success) {
                            _responseFormatter(response);
                        }
                    }, function (errorCallback) {
                        _completeResponse();
                    });
                } else {
                    // ordinary post call (no upload)
                    WBHelper.ajax({
                        formId: '#' + id,
                        url: action,
                        type: method,
                        data: data,
                        success: function (response, textStatus, jqXHR) {
                            if (response.success) {
                                _responseFormatter(response);
                            }
                        }
                    });
                }

                function _completeResponse() {
                    // enable form
                    if (jQ('#' + id).length) {
                        WBHelper.enableFormInputs('#' + id);
                    }

                    // change button submit
                    if (jQ('#' + id).length) {
                        frmSubmitButton.text(btnSubmitLoading);
                    }
                }

                // format the response
                function _responseFormatter(response) {
                    var data = WBHelper.initDefaults({
                        success: false,
                        data: {},
                        extra: {
                            redirect: null,
                            message: {
                                title: null,
                                message: null
                            }
                        }
                    }, response);
                    var extra = data.extra;

                    // pop a message
                    if (extra.message) {
                        swal({
                            title: extra.message.title,
                            text: extra.message.message,
                            timer: 3000,
                            showConfirmButton: true
                        });
                    }

                    // redirect to
                    if (extra.redirect) {
                        window.location.href = extra.redirect;
                    }
                }
            });
        },

        /**
         * Modal dynamically create
         *
         * @param header
         * @param content
         * @param successCallback
         * @param btnSuccess
         * @param cancelCallback
         * @param btnCancel
         */
        modal: function (header, content, successCallback, btnSuccess, cancelCallback, btnCancel) {
            var id = 'modal_' + Math.floor((Math.random() * 1000) + 1);
            var moda_id = "#" + id;

            var html = '<div id="' + id + '" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-modal" aria-hidden="true">';
            html += '<div class="modal-dialog">';
            html += '<div class="modal-content">';
            html += '<div class="modal-header">';
            html += '<a class="close" data-dismiss="modal">×</a>';
            html += '<h4>' + header + '</h4>'
            html += '</div>';
            html += '<div class="modal-body">';
            html += content;
            html += '</div>';
            html += '<div class="modal-footer">';

            if (btnSuccess) {
                html += '<span class="btn btn-primary"';
                html += ' id="success_' + id + '">' + btnSuccess;
                html += '</span>';
            }

            if (btnCancel) {
                html += '<span class="btn"';
                html += ' id="cancel_' + id + '">' + btnCancel;
                html += '</span>'; // close button
            } else {
                html += '<span class="btn"';
                html += ' id="cancel_' + id + '"> Cancel';
                html += '</span>'; // close button
            }

            html += '</div>';  // footer
            html += '</div>';  // content
            html += '</div>';  // dialog
            html += '</div>';  // modalWindow
            jQ("#dynamic_container").html(html);

            jQ(moda_id).modal();
            jQ(moda_id).modal('show');

            // on click success
            jQ(document).on("click", '#success_' + id, function () {
                successCallback();

                jQ(moda_id).modal('hide');
            });

            // on click cancel
            jQ(document).on("click", '#cancel_' + id, function () {
                if (cancelCallback) {
                    cancelCallback();
                }

                jQ(moda_id).modal('hide');
            });

            // remove the modal
            jQ(moda_id).on('hidden.bs.modal', function (e) {
                jQ(this).remove();
            });
        },

        /**
         * Confirm
         */
        confirm: function () {
            // confirm
            jQ('.confirm-ajax').off().on('click', function (e) {
                e.preventDefault();

                var me = jQ(this);
                var title = me.attr('data-modal-title');
                title = (title) ? title : 'Confirm';

                var content = me.attr('data-modal-content');
                content = (content) ? content : 'Are you sure to continue?';

                var callback = me.attr('data-modal-callback');
                callback = (callback) ? new Function(callback) : function () {
                        console.log('Callback Confirm.');
                    };

                WBHelper.modal(title, '<h4 class="text-danger">' + content + '</h4>', callback, 'Continue');
            });
        },

        /**
         * Confirm form
         */
        confirmForm: function () {
            // confirm
            jQ('.confirm-form').off().on('submit', function (e) {
                e.preventDefault();

                var me = jQ(this);
                var title = me.attr('data-modal-title');
                title = (title) ? title : 'Confirm';

                var content = me.attr('data-modal-content');
                content = (content) ? content : 'Are you sure to continue?';

                var callback = me.attr('data-modal-callback');
                callback = (callback) ? new Function(callback) : function () {
                        console.log('Callback Confirm.');
                        me.off('submit').submit();
                    };

                WBHelper.modal(title, '<h4 class="text-danger">' + content + '</h4>', callback, 'Continue');

                return false;
            });
        },

        /**
         * Image modal chooser
         */
        imageChooser: function (callback) {
            // reset the input
            jQ('#chooserImageFile').val('');

            jQ('.modal-image-chooser').modal({
                show: true
            });

            // this is the data of the selected image
            var selected_image = null;

            // view initializer for list images
            function view_images_list(response) {
                var view = '<div class="row">';

                for (var i = 0; i < response.length; i++) {
                    view += '<div class="col-xs-6 col-md-3">' +
                        '<a href="#" class="thumbnail image_chooser_data" data-index="' + i + '">' +
                        '<img src="' + response[i].path + '" alt="' + response[i].title + '" class="image_chooser">' +
                        '</a>' +
                        '</div>';
                }

                view += '</div>';
                jQ('#imageChooserList').html(view);

                jQ('.image_chooser_data').off().on('click', function (e) {
                    e.preventDefault();
                    console.log(response[parseInt(jQ(this).attr('data-index'))]);

                    selected_image = response[parseInt(jQ(this).attr('data-index'))];
                });
            }

            // response
            function fetch_images() {
                WBHelper.ajax({
                    url: '/images',
                    type: 'GET',
                    success: function (data, textStatus, jqXHR) {
                        console.log('Response' + JSON.stringify(data));

                        if (data.success) {
                            var response = data.data;
                            view_images_list(response);
                        }
                    }
                });
            }

            // fetch the images
            fetch_images();

            // images have been selected
            jQ('#selectImageYes').off().on('click', function (e) {
                e.preventDefault();
                console.log('Selected Image');

                // callback returns the image selected url
                callback(selected_image);
                jQ('.modal-image-chooser').modal('hide');
            });

            // upload image
            jQ('#frmImageChooser').off().on('submit', function (e) {
                e.preventDefault();
                console.log('Submitting Form');

                var file = jQ('#chooserImageFile').prop('files');
                if (!file || !file[0]) {
                    console.debug('No file selected.');
                    return;
                }

                console.log(file[0]);

                WBHelper.upload('/image/upload', {
                    files: {image: file[0]}
                }, function () {
                    // before sending
                    WBHelper.disableFormInputs('#frmImageChooser');
                }, function (res) {
                    // success
                    WBHelper.enableFormInputs('#frmImageChooser');

                    // refresh image list
                    var response = res.data;
                    view_images_list(response);

                    // reset the input
                    jQ('#chooserImageFile').val('');
                }, function (res) {
                    // error
                    WBHelper.enableFormInputs('#frmImageChooser');
                });
            });
        },

        /**
         * Clear form fields
         *
         * @param formId
         */
        clearForm: function (formId) {
            jQ(formId).find("input[type=text], input[type=password], input[type=tel], input[type=time], input[type=date], input[type=email], input[type=number], input[type=file], textarea").val('');
        },

        /**
         * Preload images
         */
        preLoadImg: function () {
            setTimeout(function () {
                jQ('.preload-img').each(function () {
                    var me = jQ(this);
                    me.attr('src', me.attr('data-img'));

                    me.load(function () {
                        me.removeClass('event-coverLoader-mnl').addClass('event-cover-mnl');
                    });
                });
            }, 3000);
        },

        /**
         * Go to a page
         *
         * @param url
         */
        goTo: function (url) {
            window.location.href = url;
        }
    };
}());

/**
 * Date helpers
 */
var WBDate = (function () {
    return {
        /**
         * No future dates good for birthdays
         */
        datePicker: function () {
            jQ('.date-picker').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                max: true
            });
        },

        /**
         * No past dates good for events
         */
        datePickerMin: function () {
            jQ('.date-picker-min').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                min: true
            });
        },

        /**
         * Limit to 80 years selection no limit on past and future dates
         */
        none: function () {
            jQ('.date-picker-none').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                min: false,
                max: false
            });
        },

        timePicker: function () {
            jQ('.time-picker').pickatime({
                format: 'hh:i A'
            });
        }
    };
}());

/**
 * Error messages
 *
 * @type {{run, responseMessage, inputHighlight, bottomMessage, toastMessage, clearMessages, log}}
 */
var WBErrors = (function () {
    var _private = {
        errorTimeout: null,   // timeout clear
        errorTimer: 3000,   // timer 3 seconds
        errorAnimation: 800     // animation timer
    };

    return {
        /**
         * Run the error message handler
         *
         * @param options
         * @param data
         */
        run: function (data, options) {
            if (typeof data == 'object') {
                // initialize defaults
                data = WBHelper.initDefaults({
                    success: false,
                    errors: []
                }, data);

                if (!data.success) {
                    // show error messages
                    if (options.showErrors) {
                        var errorType = options.errorType;

                        // type of error messages
                        if (errorType == 'bottomMessage') {
                            WBErrors.errorType(data.errors, options.formId, options.statusContainer, null, 'bottomMessage');
                        } else if (errorType == 'responseMessage') {
                            WBErrors.errorType(data.errors, options.formId, options.statusContainer, options.removeMessage, 'responseMessage');
                        } else if (errorType == 'toastMessage') {
                            WBErrors.errorType(data.errors, null, null, null, 'toastMessage');
                        }
                    }
                }
            }
        },

        /**
         * Error type
         *
         * @param errors
         * @param formId
         * @param container
         * @param removeMessage
         * @param errorType
         */
        errorType: function (errors, formId, container, removeMessage, errorType) {
            // type of error messages
            if (errorType == 'bottomMessage') {
                WBErrors.bottomMessage({
                    errors: errors,
                    formId: formId,
                    container: container
                });
            } else if (errorType == 'responseMessage') {
                WBErrors.responseMessage({
                    errors: errors,
                    formId: formId,
                    container: container,
                    removeMessage: removeMessage
                });
            } else if (errorType == 'toastMessage') {
                WBErrors.toastMessage({
                    title: 'Oops error occurred',
                    message: errors,
                    type: 'error'
                });
            }
        },

        /**
         * Show response message
         *
         * @param options
         */
        responseMessage: function (options) {
            // clear all error messages
            WBErrors.clearMessages(false);

            var _defaults = {
                errors: null,
                container: '#responseMessage',
                removeMessage: true,
                formId: null
            };

            // initialize defaults
            options = WBHelper.initDefaults(_defaults, options);
            var container = jQ(options.container);

            if (container.length) {
                // add no margin
                container.addClass('no-margin');

                // show all error messages
                if (typeof options.errors === 'object') {
                    var view = '';

                    jQ.each(options.errors, function (inputName, error) {
                        view += '<div class="alert alert-danger errorResponse" role="alert">' +
                            '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ' + error + '</div>';

                        // add highlight on input form
                        WBErrors.inputHighlight(options.formId, inputName);
                    });

                    container.append(view);
                } else {
                    container.html('<div class="alert alert-danger errorResponse" role="alert">' +
                        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> ' + options.errors + '</div>');
                }

                // remove messages
                if (options.removeMessage) {
                    setTimeout(function () {
                        jQ('.errorResponse').fadeOut(_private.errorAnimation, function () {
                            jQ(this).remove();
                        });

                        if (_private.errorTimeout != null) {
                            _private.errorTimeout = null;
                        }
                    }, _private.errorTimer);
                }
            } else {
                WBErrors.toastMessage({message: options.errors, type: 'error'});
            }
        },

        /**
         * Add highlight on input forms
         *
         * @param formId
         * @param inputName
         */
        inputHighlight: function (formId, inputName) {
            if (formId) {
                var inputField = jQ(formId + ' [name="' + inputName + '"]');

                if (inputField.length) {
                    // add error markings on input fields in form
                    inputField.not('[type="hidden"]').addClass('input-error');
                }
            }
        },

        /**
         * Show message bottom of input form
         *
         * @param options
         */
        bottomMessage: function (options) {
            // clear all error messages
            WBErrors.clearMessages(false);

            var defaults = {
                formId: '',     // form id
                errors: {},     // errors
                container: null // alternative message container
            };

            // initialize defaults
            options = WBHelper.initDefaults(defaults, options);

            if (typeof options.errors === 'object') {
                jQ.each(options.errors, function (inputName, error) {
                    var inputField = jQ(options.formId + ' [name="' + inputName + '"]');

                    // add highlight on input form
                    WBErrors.inputHighlight(options.formId, inputName);

                    if (inputField.length) {
                        // clear old help block messages
                        jQ('[data-error-block="' + inputName + '"]').remove();

                        // add error markings on input fields in form
                        inputField.parent().addClass('has-error');
                        inputField.after('<span class="help-block" data-error-block="' + inputName + '">' +
                            '<strong>' + error + '</strong></span>');
                    } else {
                        // show toast instead
                        if (jQ(options.formId + ' [name="' + inputName + '"]').length) {
                            // if input field is hidden show error as toast
                            if (jQ(options.formId + ' [name="' + inputName + '"]').attr('type') == 'hidden') {
                                WBErrors.toastMessage({message: error, type: 'error'});
                            }
                        } else {
                            if (jQ(options.container).length) {
                                WBErrors.responseMessage({
                                    errors: options.errors,
                                    container: options.container
                                });
                            } else {
                                WBErrors.toastMessage({message: error, type: 'error'});
                            }
                        }
                    }
                });
            } else {
                WBErrors.toastMessage({message: options.errors, type: 'error'});
            }
        },

        /**
         * Show toast message
         *
         * @param options
         */
        toastMessage: function (options) {
            var defaults = {
                title: '',
                message: '',
                type: 'success'
            };

            // initialize defaults
            options = WBHelper.initDefaults(defaults, options);

            var len = jQ('.toast').length;
            if (len > 7) {
                // clears the current list of toasts
                jQ('div.toast:lt(3)').fadeOut('slow', function () {
                    jQ(this).remove();
                });
            }

            toastr.options.timeOut = 3000;              // how long the toast will display without user interaction
            toastr.options.extendedTimeOut = 600;       // how long the toast will display after a user hovers over it
            toastr.options.showMethod = 'slideDown';    // slide down animation when showing toast
            toastr.options.closeButton = true;          // show close or ex button on right

            if (typeof options.message === 'object') {
                jQ.each(options.message, function (inputName, error) {
                    showToast(error);
                });
            } else {
                showToast(options.message);
            }

            function showToast(message) {
                switch (options.type) {
                    case 'success':
                        toastr.success(message, options.title);
                        break;
                    case 'warning':
                        toastr.warning(message, options.title);
                        break;
                    case 'error':
                        toastr.error(message, options.title);
                        break;
                    default:
                        WBErrors.log('Unknown Toast Function.');
                }
            }
        },

        /**
         * Clear all messages
         */
        clearMessages: function (clearInput) {
            var body = jQ('body');

            // clear all input fields
            if (clearInput === true) {
                body.find('input:not([type="hidden"])').val('');
                body.find('textarea').val('');
            }

            // clears the current list of toasts
            toastr.clear();

            // remove errors or highlight
            body.find('div').removeClass('has-error');

            // remove all messages
            jQ('.errorResponse').remove();

            // rest html bottom errors
            jQ('.error-block').remove();

            // clear all running messages
            if (_private.errorTimeout != null) {
                _private.errorTimeout = null;
            }
        },

        /**
         * Log errors
         *
         * @param message
         */
        log: function (message) {
            console.log('Application Log: ' + message);
        }
    };
}());

/**
 * Response headers
 *
 * @type {{get}}
 */
var WBHeaders = (function () {
    return {
        get: function (code) {
            switch (code) {
                case 422:
                    // server errors

                    break;
                case 498:
                    WBErrors.toastMessage({message: 'Invalid token form.', type: 'error'});

                    setTimeout(function () {
                        // invalid token reload page
                        location.reload();
                    }, 800);

                    break;
                case 401:
                    // authentication required show login modal
                    WBErrors.clearMessages(false);

                    // bad request
                    WBErrors.toastMessage({message: 'Access is denied due to invalid credentials.', type: 'error'});

                    // hide or close all current modals
                    jQ('.modal').modal('hide');

                    // show login modal
                    jQ('#modalLogin').modal({
                        show: true
                    });

                    break;
                case 400:
                    WBErrors.clearMessages(false);

                    // bad request
                    WBErrors.toastMessage({message: '400 Bad Request', type: 'error'});

                    setTimeout(function () {
                        location.reload();
                    }, 800);

                    break;
                case 405:
                    WBErrors.clearMessages(false);

                    // method is not allowed
                    WBErrors.toastMessage({message: '405 (Method Not Allowed)', type: 'error'});

                    break;
                case 500:
                    // server reponded with errors
                    WBErrors.toastMessage({message: '500 Server Error, Please try again later.', type: 'error'});

                    break;
                default:
                    WBErrors.log('Unknown header code. (' + code + ')');
            }
        }
    };
}());

/**
 * Geo location
 *
 * @type {{location, lat, long}}
 */
var WBGeo = (function () {
    return {
        location: function (callbackSuccess, callbackError) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    WBGeo.lat = position.coords.latitude;
                    WBGeo.long = position.coords.longitude;

                    callbackSuccess(position);
                }, function (error) {
                    WBErrors.toastMessage({
                        title: 'Geo location Error',
                        message: 'Geo location has errors: ' + error.message,
                        type: 'error'
                    });
                }, {timeout: 20000});
            } else {
                WBErrors.toastMessage({
                    title: 'Geo location Error',
                    message: 'Geo location is not supported by this browser.',
                    type: 'error'
                });

                callbackError();
            }
        },

        lat: null,
        long: null
    };
}());

/**
 * Cookie helper
 *
 * @type {{set, get, check, remove, enabled}}
 */
var WBCookie = (function () {
    return {
        /**
         * Set cookie
         *
         * @param key
         * @param value
         * @param days
         */
        set: function (key, value, days) {
            var expireDays = parseInt((days) ? days : 3);
            var d = new Date();
            d.setTime(d.getTime() + (expireDays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toUTCString();

            document.cookie = key + "=" + value + "; " + expires;
        },

        /**
         * Get cookie
         *
         * @param key
         * @returns {string|null}
         */
        get: function (key) {
            var name = key + "=";
            var ca = document.cookie.split(';');

            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];

                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }

                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }

            return null;
        },

        /**
         * Check if cookie exist
         *
         * @param key
         * @returns {boolean}
         */
        check: function (key) {
            var user = WBCookie.get(key);

            return (user) ? true : false;
        },

        /**
         * Remove cookie
         *
         * @param key
         */
        remove: function (key) {
            WBCookie.set(key, '', -1);
        },

        /**
         * Is cookie available
         *
         * @returns {boolean}
         */
        enabled: function () {
            WBCookie.set('WBCookie', 'true', 1);

            if (WBCookie.check('WBCookie')) {
                WBCookie.remove('WBCookie');
                return true;
            }

            return false;
        }
    };
}());

/**
 * Google Map V3 helper
 *
 * @type {{map, init, search, route, addMarker, deleteMarker, centerMap, lat, lng, style}}
 */
var WBMap = (function () {
    var _markers = [];
    var _directionsDisplay;
    var _directionsService;

    var _centerTimer;

    return {
        map: null,
        infoWindow: new google.maps.InfoWindow(),

        /**
         * Initialize map
         *
         * @param id
         * @returns {google.maps.Map}
         */
        init: function (id) {
            if (!document.getElementById(id)) {
                return null;
            }

            _markers = [];
            _directionsService = new google.maps.DirectionsService();
            _directionsDisplay = new google.maps.DirectionsRenderer();

            this.map = new google.maps.Map(document.getElementById(id), {
                center: {lat: this.lat, lng: this.lng},
                zoom: 14,
                styles: this.style
            });

            // direction display map
            _directionsDisplay.setMap(this.map);

            return this.map;
        },

        /**
         * Search
         *
         * Commonly returns are place.formatted_address, place.name
         *
         * @param id
         * @param callback
         */
        search: function (id, callback) {
            var thisApp = this;

            if (!thisApp.map) {
                return;
            }

            var input = document.getElementById(id);
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', thisApp.map);

            // add the listener
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.error("No details available for input: '" + place.name + "'");
                    return;
                }

                // reset the markers
                thisApp.deleteMarker();

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    thisApp.map.fitBounds(place.geometry.viewport);
                } else {
                    thisApp.map.setCenter(place.geometry.location);
                    thisApp.map.setZoom(17);
                }

                // callback data
                callback(place);

                // add marker
                thisApp.addMarker(place.geometry.location.lat(), place.geometry.location.lng(), null, null);
            });
        },

        /**
         * Direction routing
         *
         * @param start_lat
         * @param start_lng
         * @param end_lat
         * @param end_lng
         */
        route: function (start_lat, start_lng, end_lat, end_lng) {
            var thisApp = this;
            var request = {
                origin: new google.maps.LatLng(parseFloat(start_lat), parseFloat(start_lng)),
                destination: new google.maps.LatLng(parseFloat(end_lat), parseFloat(end_lng)),
                travelMode: 'DRIVING'
            };

            _directionsService.route(request, function (result, status) {
                if (status == 'OK') {
                    _directionsDisplay.setDirections(result);

                    // center the map to destination
                    thisApp.centerMap(end_lat, end_lng);
                }
            });
        },

        /**
         * Add marker
         *
         * @param lat
         * @param lng
         * @param data
         * @param clickCallback
         */
        addMarker: function (lat, lng, data, clickCallback) {
            var icon = null;
            if (data) {
                if (data.icon) {
                    icon = data.icon;
                }
            }

            var marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                },

                map: this.map,
                icon: ((icon) ? icon : null)
            });
            _markers.push(marker);

            // click
            if (clickCallback) {
                google.maps.event.addListener(marker, 'click', function () {
                    clickCallback(lat, lng, data, marker);
                });
            }

            this.centerMap(null, null);
        },

        /**
         * Delete markers
         */
        deleteMarker: function () {
            _markers.forEach(function (marker) {
                marker.setMap(null);
            });
        },

        /**
         * Center map
         *
         * @param lat
         * @param lng
         */
        centerMap: function (lat, lng) {
            var thisApp = this;

            if (_centerTimer) {
                clearTimeout(_centerTimer);
            }

            _centerTimer = setTimeout(function () {
                if (lat == null || lng == null) {
                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < _markers.length; i++) {
                        bounds.extend(_markers[i].getPosition());
                    }

                    thisApp.map.fitBounds(bounds);
                } else {
                    thisApp.map.panTo(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
                }
            }, 800);
        },

        /**
         * Default lat and lng
         */
        lat: 14.5995,
        lng: 120.9842,

        /**
         * Map styles
         */
        style: [{
            "featureType": "water",
            "elementType": "geometry",
            "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
        }, {
            "featureType": "landscape",
            "elementType": "geometry",
            "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
        }, {
            "featureType": "road.highway",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
        }, {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
        }, {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
        }, {
            "featureType": "road.local",
            "elementType": "geometry",
            "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
        }, {
            "featureType": "poi",
            "elementType": "geometry",
            "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
        }, {
            "featureType": "poi.park",
            "elementType": "geometry",
            "stylers": [{"color": "#dedede"}, {"lightness": 21}]
        }, {
            "elementType": "labels.text.stroke",
            "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
        }, {
            "elementType": "labels.text.fill",
            "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
        }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
            "featureType": "transit",
            "elementType": "geometry",
            "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
        }, {
            "featureType": "administrative",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
        }, {
            "featureType": "administrative",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
        }]
    };
}());

/**
 * LeafLetJS
 *
 * @type {{map, lat, lng, zoom, init, search, route, addMarker, onClick, deleteMarker, centerMap}}
 */
var WBLeafMap = (function () {
    var _markers = [];
    var _mapBoxToken = '';

    return {
        map: null,

        lat: 14.5995,
        lng: 120.9842,
        zoom: 14,

        /**
         * Initialize map
         *
         * @param id
         * @returns
         */
        init: function (id) {
            if (!document.getElementById(id)) {
                return;
            }

            // reset markers
            _markers = [];

            // leaf map
            this.map = L.map(id).setView([this.lat, this.lng], this.zoom);

            // use map box for tiles
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + _mapBoxToken, {
                maxZoom: this.zoom,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(this.map);

            return this.map;
        },

        /**
         * Search
         *
         * @param id
         */
        search: function (id) {
            var input = document.getElementById(id);
            if (!input) {
                return;
            }

            var searchBox = new google.maps.places.SearchBox(input);

            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var group = L.featureGroup();

                places.forEach(function (place) {
                    group.addLayer(WBLeafMap.addMarker(place.geometry.location.lat(), place.geometry.location.lng(), {
                        popup: {
                            content: place.formatted_address
                        },
                        icon: 'http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Chartreuse-icon.png'
                    }, function (e) {
                        console.log(this.getLatLng());
                    }));
                });

                group.addTo(WBLeafMap.map);
                WBLeafMap.map.fitBounds(group.getBounds());
            });
        },

        /**
         * Direction routing
         *
         * @param wayPointsList
         * @param callback
         */
        route: function (wayPointsList, callback) {
            var routingControl = L.Routing.control({
                waypoints: wayPointsList,
                plan: L.Routing.plan(wayPointsList, {
                    createMarker: function (i, wp) {
                        var marker = L.marker(wp.latLng, {
                            draggable: false
                        });

                        marker.on('click', function (e) {
                            console.log(this.getLatLng());
                        });

                        return marker;
                    }
                }),
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(this.map);

            // instruction hide
            jQ('.leaflet-routing-container').remove();

            // list of routes found
            routingControl.on('routesfound', function (e) {
                // e.routes[0].summary.totalDistance;
                // e.routes[0].summary.totalTime;
                // e.routes[0].instructions

                callback(e);
            });
        },

        /**
         * Add marker
         *
         * @param lat
         * @param lng
         * @param data
         * @param clickCallback
         * event on click: this.getLatLng()
         */
        addMarker: function (lat, lng, data, clickCallback) {
            var options = {};
            if (data) {
                // icon marker
                if (data.icon) {
                    options.icon = new L.icon({
                        iconUrl: data.icon,
                        iconSize: [64, 64]
                    });
                }
            }

            // marker with options
            var marker = L.marker([lat, lng], options).addTo(this.map);

            if (data) {
                // popup marker
                if (data.popup) {
                    marker.bindPopup(data.popup.content);
                }
            }

            // on marker click
            if (clickCallback) {
                // event
                // this.getLatLng()
                marker.on('click', clickCallback);
            }

            _markers.push(marker);

            this.centerMap(null, null);
            return marker;
        },

        /**
         * On map click
         * event: e.latlng
         *
         * @param callback
         */
        onClick: function (callback) {
            this.map.on('click', callback);
        },

        /**
         * Delete markers
         */
        deleteMarker: function () {
            for (var i = 0; i < _markers.length; i++) {
                this.map.removeLayer(_markers[i]);
            }

            _markers = [];
        },

        /**
         * Center map
         *
         * @param lat
         * @param lng
         */
        centerMap: function (lat, lng) {
            var listLatLng = [];

            for (var i = 0; i < _markers.length; i++) {
                listLatLng.push([_markers[i]._latlng.lat, _markers[i]._latlng.lng]);
            }

            var bounds = new L.LatLngBounds(listLatLng);
            this.map.fitBounds(bounds);
        },

        /**
         * Distance between two points
         *
         * @param lat_from
         * @param lng_from
         * @param lat_des
         * @param lng_des
         * @returns {*}
         */
        calDistance: function (lat_from, lng_from, lat_des, lng_des) {
            // km
            var R = 6371;
            var dLat = WBLeafMap.toRad(lat_des - lat_from);
            var dLon = WBLeafMap.toRad(lng_des - lng_from);

            lat_from = WBLeafMap.toRad(lat_from);
            lat_des = WBLeafMap.toRad(lat_des);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat_from) * Math.cos(lat_des);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;

            if (d) {
                return d.toFixed(1);
            }

            return d;
        },

        /**
         * Converts numeric degrees to radians
         *
         * @param Value
         * @returns {number}
         */
        toRad: function (Value) {
            return Value * Math.PI / 180;
        }
    }
}());