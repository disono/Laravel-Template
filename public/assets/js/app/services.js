/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

let WBServices = (function () {
    let _raw = function () {
        return WBServices.raw;
    };

    let _view = function () {
        return WBServices.view;
    };

    let _form = function () {
        return WBServices.form;
    };

    let _error = function () {
        return WBServices.error;
    };

    let _helpers = {
        http: {
            params(params) {
                params = (typeof params !== 'undefined') ? params : {response: null};
                params.response = 'json';
                return params;
            },

            config(params, config, isUpload) {
                let _config = {};
                if (typeof config !== 'undefined') {
                    _config = config;
                    _config.headers = (!isUpload) ? WBAppConfig().httpHeaders() : WBAppConfig().httpUploadHeaders();
                } else {
                    _config = {
                        headers: (!isUpload) ? WBAppConfig().httpHeaders() : WBAppConfig().httpUploadHeaders()
                    };
                }

                if (typeof params !== 'undefined') {
                    if (params !== null) {
                        _config.params = params;
                    }
                }

                return _config;
            }
        },

        view: {
            removeDialogContainer(_dialogContainer) {
                jQ('#' + _dialogContainer).modal('toggle');
                jQ('#' + _dialogContainer).remove();
            },

            createModalContainer(content) {
                if (jQ('.modal').length) {
                    jQ('.modal').last().before(content);
                } else {
                    jQ('#WBApp').append(content);
                }
            },
        },

        form: {
            validation(formAction, options) {
                let self = this;

                // save content
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }

                options = (typeof options !== 'undefined') ? options : {};
                let inputData = {};
                let inputRules = {};
                let hasValidation = false;
                let _options = {
                    showErrors: options.showErrors || true
                };

                // get all validation rules on inputs
                jQ.each(formAction[0].elements, function () {
                    if (typeof jQ(this).attr('data-validate') !== 'undefined') {
                        inputData[jQ(this).attr('name')] = jQ(this).val();
                        inputRules[jQ(this).attr('name')] = jQ(this).attr('data-validate');
                        hasValidation = true;
                    }
                });

                // validate the form inputs
                let validation = new Validator(inputData, inputRules);
                let validated = (hasValidation === true) ? validation.passes() : true;

                // options
                if (_options.showErrors === true) {
                    for (let key in inputData) {
                        self.formInValid(formAction.find('[name=' + key + ']'), validation.errors.first(key));
                    }
                }

                return validated;
            },

            processResponse(formAction, response) {
                let self = this;
                console.table(response);
                if (response.success === true) {
                    // remove all error messages
                    self.formValid(formAction);

                    // data
                    if (typeof response.data !== 'undefined') {
                        // redirect
                        if (typeof response.data.redirect !== 'undefined') {
                            WBServices.http.redirect(response.data.redirect);
                            return;
                        }

                        if (typeof response.data === 'string') {
                            jQ.snackbar({content: response.data});
                        }
                    }
                } else if (response.success === false && typeof response.errors !== 'undefined') {
                    if (typeof response.errors === 'object') {
                        jQ.each(response.errors, function (name, error) {
                            WBHelper.console.error(error);
                            self.formInValid(formAction.find('[name=' + name + ']'), error);
                        });
                    } else if (typeof response.errors === 'string') {
                        swal("Oops!", response.errors, "error");
                    }
                }
            },

            formValid(formAction) {
                // remove all error messages
                formAction.find('.invalid-feedback').remove();
                formAction.find('.custom-control-checkbox-invalid').remove();

                // remove error classes
                jQ.each(formAction[0].elements, function (index, elem) {
                    let input = jQ(this);

                    // custom checkbox (switch)
                    let materialInputs = ['material-switch'];
                    for (let i = 0; i < materialInputs.length; i++) {
                        if (input.hasClass(materialInputs[i] + '-control-input')) {
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.custom-control-checkbox-invalid').remove();
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-indicator').removeClass('material-switch-danger');
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-description').removeClass('text-danger');
                            return;
                        }
                    }

                    // material input checkbox
                    if (input.hasClass('material-control-input')) {
                        input.parent().parent().find('.material-control-input').parent().find('.custom-control-checkbox-invalid').remove();
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-indicator').removeClass('material-control-indicator-danger');
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-description').removeClass('text-danger');
                        return;
                    }

                    // select picker
                    input.parent().parent().find('.select_picker').parent().find('.invalid-feedback').remove();
                    if (input.hasClass('select_picker')) {
                        input.removeClass('is-invalid');
                        input.parent().parent().find('.btn.dropdown-toggle').removeClass('btn-toggle-danger');
                        input.parent().parent().find('.select_picker').removeClass('is-invalid');
                        jQ('.select_picker').selectpicker('refresh');
                        return;
                    }

                    // tiny mce
                    input.parent().parent().find('.tiny').parent().find('.invalid-feedback').remove();
                    if (input.hasClass('tiny')) {
                        input.parent().parent().find('.tiny').removeClass('is-invalid');
                        return;
                    }

                    // group input append
                    if (input.parent().find('.input-group-append').length) {
                        input.parent().find('.input-group-append').find('.btn').removeClass('input-group-append-danger');
                        input.parent().find('.input-group-append').next().remove();
                    }

                    // group input prepend
                    if (input.parent().find('.input-group-prepend').length) {
                        input.parent().find('.input-group-prepend').find('.btn').removeClass('input-group-prepend-danger');
                        input.parent().find('.input-group-prepend').next().remove();
                    }

                    // selectize
                    if (input.parent().find('.selectize').length) {
                        input.parent().find('.selectize').find('.selectize-input').removeClass('input--danger');
                    }

                    // group input prepend
                    if (input.hasClass('custom-file-input')) {
                        input.parent().find('.custom-file-label').next().remove();
                    }

                    if (input.hasClass('custom-file-input')) {
                        input.parent().find('.custom-file-label').text('Choose file');
                        input.parent().find('.custom-file-label').removeClass('custom-file-label-danger');
                    }

                    if (input.hasClass('picker__input')) {
                        input.removeClass('input--danger');
                    }

                    input.removeClass('is-invalid');
                });
            },

            formInValid(input, msg) {
                // no input found
                if (!input.length) {
                    swal("Validation Error", msg, "error");
                    return;
                }

                // hidden fields
                if (input.attr('type') === 'hidden') {
                    swal("Validation Error", msg, "error");
                }

                // custom checkbox (switch)
                let materialInputs = ['material-switch'];
                for (let i = 0; i < materialInputs.length; i++) {
                    input.parent().parent().find('.' + materialInputs[i]).parent().find('.custom-control-checkbox-invalid').not('.form-text').remove();
                    if (input.hasClass(materialInputs[i] + '-control-input')) {
                        if (msg) {
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-description').addClass('text-danger');
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-indicator').addClass('material-switch-danger');
                            input.parent().parent().find('.' + materialInputs[i]).after('<div class="custom-control-checkbox-invalid">' + msg + '</div>');
                        } else {
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-indicator').removeClass('material-switch-danger');
                            input.parent().parent().find('.' + materialInputs[i]).parent().find('.' + materialInputs[i] + '-control-description').removeClass('text-danger');
                        }

                        return;
                    }
                }

                // material input checkbox
                input.parent().parent().find('.material-control-input').parent().find('.custom-control-checkbox-invalid').not('.form-text').remove();
                if (input.hasClass('material-control-input')) {
                    if (msg) {
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-indicator').addClass('material-control-indicator-danger');
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-description').addClass('text-danger');
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-description').after('<div class="custom-control-checkbox-invalid">' + msg + '</div>');
                    } else {
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-indicator').removeClass('material-control-indicator-danger');
                        input.parent().parent().find('.material-control-input').parent().find('.material-control-description').removeClass('text-danger');
                    }

                    return;
                }

                // select picker
                input.parent().parent().find('.select_picker').parent().find('.invalid-feedback').not('.form-text').remove();
                if (input.hasClass('select_picker')) {
                    if (msg) {
                        input.addClass('is-invalid');
                        input.parent().parent().find('.btn.dropdown-toggle').addClass('btn-toggle-danger');
                        input.parent().parent().find('.select_picker').parent().after('<div class="invalid-feedback">' + msg + '</div>');
                    } else {
                        input.removeClass('is-invalid');
                        input.parent().parent().find('.btn.dropdown-toggle').removeClass('btn-toggle-danger');
                        input.parent().parent().find('.select_picker').removeClass('is-invalid');
                    }

                    jQ('.select_picker').selectpicker('refresh');
                    return;
                }

                // tiny mce
                input.parent().parent().find('.tiny').parent().find('.invalid-feedback').not('.form-text').remove();
                if (input.hasClass('tiny')) {
                    if (msg) {
                        input.addClass('is-invalid');
                        input.parent().parent().find('.tiny').parent().find('.tox-tinymce').after('<div class="invalid-feedback">' + msg + '</div>');
                    } else {
                        input.parent().parent().find('.tiny').removeClass('is-invalid');
                    }
                    return;
                }

                // remove error messages (bottom)
                input.parent().parent()
                    .not('.form-text')
                    .not('.custom-control-label')
                    .not('.custom-file-input')
                    .not('.custom-file-label')
                    .not('.dropdown-toggle')
                    .not('.dropdown-menu')
                    .not('.select_picker')
                    .not('.picker')
                    .not('.input-group-append')
                    .not('.input-group-prepend')
                    .not('.selectize')
                    .find('.invalid-feedback')
                    .remove();

                // group input append
                if (input.parent().find('.input-group-append').length) {
                    input.parent().find('.input-group-append').next().not('.form-text').remove();
                }

                // group input prepend
                if (input.parent().find('.input-group-prepend').length) {
                    input.parent().find('.input-group-prepend').next().not('.form-text').remove();
                }

                // group input prepend
                if (input.hasClass('custom-file-input')) {
                    input.parent().find('.custom-file-label').next().not('.form-text').remove();
                }

                // selectize
                if (input.hasClass('selectize')) {
                    input.parent().find('.selectize-control').next().not('.form-text').remove();
                }

                if (msg) {
                    input.addClass('is-invalid');

                    if (input.hasClass('picker__input')) {
                        input.addClass('input--danger');
                    }

                    if (input.hasClass('custom-file-input')) {
                        input.parent().find('.custom-file-label').addClass('custom-file-label-danger');
                        input.parent().find('.custom-file-label').after('<div class="invalid-feedback">' + msg + '</div>');
                        return;
                    }

                    if (input.parent().find('.input-group-append').length) {
                        input.parent().find('.input-group-append').after('<div class="invalid-feedback">' + msg + '</div>');

                        if (input.parent().find('.input-group-append').find('.btn').length === 1) {
                            input.parent().find('.input-group-append').find('.btn').addClass('input-group-append-danger');
                        }

                        return;
                    }

                    if (input.parent().find('.input-group-prepend').length) {
                        input.parent().find('.input-group-prepend').after('<div class="invalid-feedback">' + msg + '</div>');

                        if (input.parent().find('.input-group-prepend').find('.btn').length === 1) {
                            input.parent().find('.input-group-prepend').find('.btn').addClass('input-group-prepend-danger');
                        }

                        return;
                    }

                    if (input.parent().find('.selectize').length) {
                        input.parent().find('.selectize-control').after('<div class="invalid-feedback">' + msg + '</div>');
                        input.parent().find('.selectize').find('.selectize-input').addClass('input--danger');

                        return;
                    }

                    input.after('<div class="invalid-feedback">' + msg + '</div>');
                } else {
                    input.removeClass('is-invalid');

                    if (input.parent().find('.input-group-append').length) {
                        input.parent().find('.input-group-append').find('.btn').removeClass('input-group-append-danger');
                    }

                    if (input.parent().find('.input-group-prepend').length) {
                        input.parent().find('.input-group-prepend').find('.btn').removeClass('input-group-prepend-danger');
                    }

                    if (input.hasClass('custom-file-input')) {
                        input.parent().find('.custom-file-label').removeClass('custom-file-label-danger');
                    }

                    if (input.parent().find('.selectize').length) {
                        input.parent().find('.selectize').find('.selectize-input').removeClass('input--danger');
                    }

                    if (input.hasClass('picker__input')) {
                        input.removeClass('input--danger');
                    }
                }
            },

            extractErrorMsg(e) {
                let msg = 'Unidentified error occurred.';

                // e.response.data.errors
                if (typeof e.response === 'undefined') {
                    msg = 'Response is unknown.';
                } else if (typeof e.response.data === 'undefined') {
                    msg = 'No data in response.';
                } else if (typeof e.response.data.errors === 'undefined') {
                    msg = 'Can not identify error data.';
                } else if (Array.isArray(e.response.data.errors) || typeof e.response.data.errors === 'object') {
                    jQ.each(e.response.data.errors, function (name, error) {
                        msg = error;
                    });
                } else if (typeof e.response.data.errors === 'string') {
                    msg = e.response.data.errors;
                }

                return msg;
            },

            disable() {
                jQ('form').find('input, textarea, button, select').not('.picker').prop('readonly', true);
            },

            enable() {
                // do not enable if attributes of input is data-disabled="yes"
                jQ('form').find('input, textarea, button, select')
                    .not('[data-disabled="yes"]')
                    .not('.date-picker-no-future')
                    .not('.date-picker-no-limit')
                    .not('.date-picker-no-pass')
                    .not('.time-picker')
                    .prop('readonly', false);
            }
        },
    };

    return {
        raw: {
            get(uri, params, config) {
                return axios.get(uri, _helpers.http.config(params, config, false)).then(function (response) {
                    WBHelper.console.log('HTTP Get Response: ' + JSON.stringify(response.data));
                    return response.data;
                });
            },

            post(uri, data, config) {
                return axios.post(uri, data, _helpers.http.config(null, config, false)).then(function (response) {
                    WBHelper.console.log('HTTP Post Response: ' + JSON.stringify(response.data));
                    return response.data;
                });
            },

            upload(uri, data, config) {
                return axios.post(uri, data, _helpers.http.config(null, config, true)).then(function (response) {
                    WBHelper.console.log('HTTP Upload Response: ' + JSON.stringify(response.data));
                    return response.data;
                });
            },

            put(uri, data, config) {
                return axios.put(uri, data, _helpers.http.config(null, config, true)).then(function (response) {
                    WBHelper.console.log('HTTP Put Response: ' + JSON.stringify(response.data));
                    return response.data;
                });
            },

            delete(uri, params, config) {
                return axios.delete(uri, _helpers.http.config(params, config, false)).then(function (response) {
                    WBHelper.console.log('HTTP Delete Response: ' + JSON.stringify(response.data));
                    return response.data;
                });
            },
        },

        http: {
            get(uri, params) {
                return new Promise(function (resolve, reject) {
                    _raw().get(uri, _helpers.http.params(params)).then(function (response) {
                        resolve(response);
                    }).catch(function (error) {
                        reject(error);
                    });
                });
            },

            post(uri, params) {
                return new Promise(function (resolve, reject) {
                    _raw().post(uri, _helpers.http.params(params)).then(function (response) {
                        resolve(response);
                    }).catch(function (error) {
                        reject(error);
                    });
                });
            },

            upload(uri, params) {
                return new Promise(function (resolve, reject) {
                    _raw().upload(uri, _helpers.http.params(params)).then(function (response) {
                        resolve(response);
                    }).catch(function (error) {
                        reject(error);
                    });
                });
            },

            delete(uri, params) {
                return new Promise(function (resolve, reject) {
                    _raw().delete(uri, _helpers.http.params(params)).then(function (response) {
                        resolve(response);
                    }).catch(function (error) {
                        reject(error);
                    });
                });
            },

            redirect(url) {
                window.location.href = url;
            }
        },

        view: {
            loading(show, msg) {
                if (!show) {
                    jQ('#loadingModal').modal('hide');
                    jQ('#loadingModal').remove();
                    return;
                }

                if (typeof msg === 'undefined') {
                    msg = 'Please wait...';
                }

                _helpers.view.createModalContainer(
                    '<div class="modal" role="dialog" id="loadingModal" data-backdrop="static">' +
                    '    <div class="modal-dialog modal-dialog-centered" role="document">' +
                    '        <div class="modal-content">' +
                    '            <div class="modal-body text-center rounded shadow-sm">' +
                    '                <div class="d-flex justify-content-center">' +
                    '                    <div class="spinner-border text-primary" role="status">' +
                    '                        <span class="sr-only">Loading...</span>' +
                    '                    </div>' +
                    '                </div>' +
                    '                <h6 id="loadingModalMessage" class="mt-2">' + msg + '</h6>' +
                    '            </div>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>'
                );

                jQ('#loadingModalMessage').html(msg);
                jQ('#loadingModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            },

            dialogs(type, viewCallback, callbackConfirm, callbackDismiss) {
                let _dialogContainer = 'dialog_' + Date.now();

                let removeContainer = function () {
                    setTimeout(function () {
                        _helpers.view.removeDialogContainer(_dialogContainer);
                    }, 300);
                };

                // events
                let views = {
                    data: null,
                    buttons: function () {
                        // dismiss
                        jQ('.dialogDismiss').off().on('click', function (e) {
                            e.preventDefault();
                            callbackDismiss(views.data);
                            removeContainer();
                        });

                        // confirm
                        jQ('.dialogConfirm').off().on('click', function (e) {
                            e.preventDefault();
                            callbackConfirm(views.data);
                            removeContainer();
                        });
                    },
                    dismiss: removeContainer
                };

                // confirm callback
                callbackConfirm = (typeof callbackConfirm === 'function') ? callbackConfirm : function () {
                    WBHelper.console.log('Confirm Button Dialogs');
                };

                // dismiss callback
                callbackDismiss = (typeof callbackDismiss === 'function') ? callbackDismiss : function () {
                    WBHelper.console.log('Dismiss Button Dialogs');
                };

                // views
                _helpers.view.createModalContainer(
                    '<div class="modal" data-keyboard="false" role="dialog" id="' + _dialogContainer + '">' +
                    '   <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">' +
                    '       <div class="modal-content" id="content_' + _dialogContainer + '">' +
                    '           <div class="modal-body">' +
                    '               <div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">' +
                    '               <span class="sr-only">Loading...</span></div></div><p class="text-center mt-3">Loading...</p>' +
                    '           </div>' +
                    '       </div>' +
                    '   </div>' +
                    '</div>'
                );

                // toggle dialog
                jQ('#' + _dialogContainer).modal('toggle');

                // resource
                _raw().get('/view/' + type).then(function (response) {
                    jQ('#content_' + _dialogContainer).html(response);

                    // remove the dialog upon dismiss from outside event
                    jQ('#' + _dialogContainer).on('hidden.bs.modal', function () {
                        jQ('#' + _dialogContainer).remove();
                        callbackDismiss(views.data);
                    });

                    if (viewCallback) {
                        viewCallback(views);
                    }

                    views.buttons();
                }).catch(function (e) {
                    _error().snackbar(e);
                    _helpers.view.removeDialogContainer(_dialogContainer);
                    callbackDismiss();
                });
            },

            onDialogDelete(path, callback) {
                _view().dialogs('delete', null, function () {
                    _raw().delete(path).then(function (response) {
                        if (callback !== 'undefined') {
                            callback(response);
                        }
                    }).catch(function (e) {
                        _error().snackbar(e, true);
                    });
                }, function (r) {

                });
            },

            onDeleteResource(e, id) {
                _view().onDialogDelete(e.currentTarget.getAttribute('href'), function () {
                    if (typeof id !== 'undefined') {
                        let parentTBody = jQ(id).parent();
                        let cartList = jQ(id).attr('data-parent-list');
                        // remove the container
                        jQ(id).remove();

                        // list of cards
                        if (cartList) {
                            if (jQ('.' + cartList).length === 0) {
                                location.reload();
                            }

                            return;
                        }

                        // table
                        if (parentTBody.children().length === 0) {
                            // refresh the page
                            location.reload();
                        }
                    }
                });
            },

            onReport() {
                let href = window.location.href;

                _view().dialogs('reportPage', function (views) {
                    // initialize JS libraries
                    WBJSOnInit();

                    jQ('#url_report').val(href);

                    jQ('#frmPageReport').submit(function (e) {
                        e.preventDefault();
                        _form().onUpload(e, function () {
                            views.dismiss();

                            swal("Submit Success", 'Your report is now under verification.', "success");
                        }, function () {

                        });
                    });

                    jQ('#dialogDismiss').click(function (e) {
                        e.preventDefault();
                        views.dismiss();
                    });
                });
            }
        },

        form: {
            selectPickerSearch() {
                jQ('.select_picker').selectpicker('destroy');
                jQ('.select_picker').selectpicker('render');

                jQ('.select_picker').each(function (i, obj) {
                    let self = jQ(obj);

                    if (self.attr('data-live-search') === 'true' && self.attr('data-live-path') && self.attr('id')) {
                        let id = jQ('#' + self.attr('id'));
                        let path = self.attr('data-live-path');
                        let ajaxMethod = self.attr('data-live-method') ? self.attr('data-live-method') : 'GET';
                        let dataValue = self.attr('data-live-value') ? self.attr('data-live-value') : 'id';
                        let dataText = self.attr('data-live-text') ? self.attr('data-live-text') : 'name';

                        id.selectpicker()
                            .ajaxSelectPicker({
                                ajax: {
                                    url: path,
                                    method: ajaxMethod,
                                    beforeSend: function (attribute, form, options) {

                                    },
                                    data: function () {
                                        return {
                                            search: '{{{q}}}'
                                        };
                                    }
                                },
                                preprocessData: function (response) {
                                    let data = [];

                                    response.data.forEach(function (val, i) {
                                        data.push({
                                            'value': val[dataValue],
                                            'text': val[dataText],
                                            'disabled': false
                                        });
                                    });

                                    return data;
                                },
                                preserveSelected: false
                            });
                    } else {
                        self.selectpicker();
                    }
                });
            },

            onUpload(e, success, failed) {
                let formAction = jQ(e.target);
                let btnSubmit = null;
                let btnSubmitDefaultTxt = null;

                if (_helpers.form.validation(formAction) === true) {
                    _helpers.form.disable();

                    if (formAction.attr('data-show-loading') !== 'no') {
                        _view().loading(true);
                    }

                    btnSubmit = formAction.find(':submit');
                    if (btnSubmit) {
                        if (btnSubmit.attr('data-show-loading') !== 'no') {
                            let loadingTxt = btnSubmit.attr('data-loading-txt') ? btnSubmit.attr('data-loading-txt') : 'Loading...';
                            btnSubmitDefaultTxt = btnSubmit.html();
                            btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingTxt);
                        }
                    }

                    _raw().upload(formAction.attr('action'), new FormData(e.target)).then(function (response) {
                        if (formAction.attr('data-show-loading') !== 'no') {
                            _view().loading(false);
                        }

                        _helpers.form.enable();
                        _helpers.form.processResponse(formAction, response);

                        if (typeof success !== 'undefined') {
                            success(response);
                        }
                    }).catch(function (error) {
                        if (formAction.attr('data-show-loading') !== 'no') {
                            _view().loading(false);
                        }

                        _helpers.form.enable();

                        let _error = null;
                        if (typeof error.response !== 'undefined') {
                            _error = error.response.data;
                            _helpers.form.processResponse(formAction, error.response.data);
                        }

                        if (typeof failed !== 'undefined') {
                            failed(_error);
                        }
                    }).finally(function () {
                        if (btnSubmitDefaultTxt) {
                            btnSubmit.html(btnSubmitDefaultTxt);
                            btnSubmit.find('.waves-ripple').remove();
                        }
                    });
                }
            },

            onPost(e, success, failed) {
                let formAction = jQ(e.target);
                let btnSubmit = null;
                let btnSubmitDefaultTxt = null;

                if (_helpers.form.validation(formAction) === true) {
                    _helpers.form.disable();

                    btnSubmit = formAction.find(':submit');
                    if (btnSubmit) {
                        if (btnSubmit.attr('data-show-loading') !== 'no') {
                            let loadingTxt = btnSubmit.attr('data-loading-txt') ? btnSubmit.attr('data-loading-txt') : 'Loading...';
                            btnSubmitDefaultTxt = btnSubmit.html();
                            btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingTxt);
                        }
                    }

                    _raw().post(formAction.attr('action'), new FormData(e.target)).then(function (response) {
                        _helpers.form.enable();
                        _helpers.form.processResponse(formAction, response);

                        if (typeof success !== 'undefined') {
                            success(response, formAction);
                        }
                    }).catch(function (error) {
                        _helpers.form.enable();

                        if (typeof error.response !== 'undefined') {
                            _helpers.form.processResponse(formAction, error.response.data);
                        }

                        if (typeof success !== 'undefined') {
                            failed(error, formAction);
                        }
                    }).finally(function () {
                        if (btnSubmitDefaultTxt) {
                            btnSubmit.html(btnSubmitDefaultTxt);
                            btnSubmit.find('.waves-ripple').remove();
                        }
                    });
                }
            },

            onGet(e, success, failed) {
                let formAction = jQ(e.target);
                let btnSubmit = null;
                let btnSubmitDefaultTxt = null;

                if (_helpers.form.validation(formAction) === true) {
                    btnSubmit = formAction.find(':submit');
                    if (btnSubmit) {
                        if (btnSubmit.attr('data-show-loading') !== 'no') {
                            let loadingTxt = btnSubmit.attr('data-loading-txt') ? btnSubmit.attr('data-loading-txt') : 'Loading...';
                            btnSubmitDefaultTxt = btnSubmit.html();
                            btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + loadingTxt);
                        }
                    }

                    _raw().get(formAction.attr('action'), formAction.serialize()).then(function (response) {
                        _helpers.form.processResponse(formAction, response);

                        if (typeof success !== 'undefined') {
                            success(response, formAction);
                        }
                    }).catch(function (error) {
                        if (typeof error.response !== 'undefined') {
                            _helpers.form.processResponse(formAction, error.response.data);
                        }

                        if (typeof success !== 'undefined') {
                            failed(error, formAction);
                        }
                    }).finally(function () {
                        if (btnSubmitDefaultTxt) {
                            btnSubmit.html(btnSubmitDefaultTxt);
                            btnSubmit.find('.waves-ripple').remove();
                        }
                    });
                }
            }
        },

        error: {
            snackbar(e) {
                let msg = _helpers.form.extractErrorMsg(e);

                jQ.snackbar({
                    content: msg,
                    timeout: 3000
                });
            }
        },

        helpers: _helpers
    };
}());