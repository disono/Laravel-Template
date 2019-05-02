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

    let _error = function () {
        return WBServices.error;
    };

    let _form = function () {
        return WBServices.form;
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
            }
        },

        form: {
            validation(formAction, options) {
                let self = this;

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
                        self.formValid(formAction.find('[name=' + key + ']'), validation.errors.first(key));
                    }
                }

                return validated;
            },

            formValid(input, validation_message) {
                input.next().not('.custom-file-input').not('.custom-file-label').not('.custom-control-label').not('form-text').not('.picker').remove();

                if (validation_message) {
                    input.removeClass('is-valid');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + validation_message + '</div>');
                } else {
                    input.removeClass('is-invalid');
                }
            },

            processResponse(formAction, response) {
                let self = this;
                if (response.success === true) {
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
                            self.error(formAction.find('[name=' + name + ']'), error);
                        });
                    } else {
                        if (typeof response.errors === 'string') {
                            jQ.snackbar({content: response.errors});

                            swal("Oops!", response.data, "error");
                            setTimeout(function () {
                                swal.close();
                            }, 3000);
                        }
                    }
                }
            },

            error(input, msg) {
                input.next().not('.custom-file-input').not('.custom-file-label').not('.custom-control-label').not('form-text').not('.picker').remove();

                if (msg) {
                    input.removeClass('is-valid');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + msg + '</div>');
                } else {
                    input.removeClass('is-invalid');
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
                    return;
                }

                if (typeof msg === 'undefined') {
                    msg = 'Please wait...';
                }

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
                jQ('#WBApp').append(
                    '<div class="modal" data-keyboard="false" tabindex="-1" role="dialog" id="' + _dialogContainer + '" style="z-index: 100000 !important;">' +
                    '<div class="modal-dialog modal-lg" role="document">' +
                    '<div class="modal-content" id="content_' + _dialogContainer + '">' +
                    '<div class="modal-body">' +
                    '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status">' +
                    '<span class="sr-only">Loading...</span></div></div><p class="text-center mt-3">Loading...</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
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
                            let loadingTxt = (btnSubmit.attr('data-loading-txt')) ? btnSubmit.attr('data-loading-txt') : 'Loading...';
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
                            let loadingTxt = (btnSubmit.attr('data-loading-txt')) ? btnSubmit.attr('data-loading-txt') : 'Loading...';
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
                            let loadingTxt = (btnSubmit.attr('data-loading-txt')) ? btnSubmit.attr('data-loading-txt') : 'Loading...';
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
        }
    };
}());