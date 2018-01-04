/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
 *
 * VueJS 2.0
 */

var _appConfig = function () {
    return {
        httpHeaders: function () {
            return {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
            };
        },
        httpUploadHeaders: function () {
            return {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': jQ('meta[name="_token"]').attr('content')
            };
        }
    };
};

var _mounted = function () {
    jQ(document).ready(function () {
        // initialize libraries
        WBLibraries();
    });
};

var _appData = {};

var _appMethods = {
    /**
     * Go to a page
     *
     * @param url
     */
    redirect: function (url) {
        window.location.href = url;
    },

    /**
     * Delete resource
     *
     * @param path
     * @param callback
     */
    onDialogDelete: function (path, callback) {
        var thisApp = this;

        thisApp.dialogs('delete', null, function (r) {
            thisApp.httpDelete(path)
                .then(function (response) {
                    if (callback) {
                        callback(response);
                    }
                })
                .catch(function (error) {

                });
        }, function (r) {

        });
    },

    /**
     * Delete resource from list
     *
     * @param e
     *  Event
     * @param id
     *  Parent id for list to remove if success
     */
    onDeleteResource: function (e, id) {
        this.onDialogDelete(e.currentTarget.getAttribute('href'), function (response) {
            // refresh the page Optional
            if (typeof id !== 'undefined') {
                jQ(id).remove();
            }
        });
    },

    /**
     * Open dialogs (Response view is from server)
     *
     * @param type
     * @param viewCallback
     * @param callbackConfirm
     * @param callbackDismiss
     */
    dialogs: function (type, viewCallback, callbackConfirm, callbackDismiss) {
        var thisApp = this;
        var _dialogContainer = 'dialog_' + Date.now();

        // event
        var views = {
            data: null,
            buttons: function () {
                // dismiss
                jQ('.dialogDismiss').on('click', function (e) {
                    e.preventDefault();
                    thisApp.dialogRemoveContainer(_dialogContainer);
                    callbackDismiss(views.data);
                });

                // confirm
                jQ('.dialogConfirm').on('click', function (e) {
                    e.preventDefault();
                    thisApp.dialogRemoveContainer(_dialogContainer);
                    callbackConfirm(views.data);
                });
            }
        };

        // callbacks
        callbackConfirm = (typeof callbackConfirm === 'function') ? callbackConfirm : function () {
            _debugging('Confirm Button Dialogs')
        };
        callbackDismiss = (typeof callbackDismiss === 'function') ? callbackDismiss : function () {
            _debugging('Dismiss Button Dialogs')
        };

        // views
        jQ('#WBMainApp').append(
            '<div class="modal text-center" data-backdrop="static" data-keyboard="false" id="' + _dialogContainer +
            '" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000 !important;">' +
            '<div class="modal-dialog modal-lg" role="document">' +
            '<div class="modal-content" id="content_' + _dialogContainer + '">' +
            '<div class="modal-body">Loading...</div>' +
            '</div>' +
            '</div>' +
            '</div>'
        );

        // toggle dialog
        jQ('#' + _dialogContainer).modal('toggle');

        // resource
        thisApp.httpGet('/views', {
            type: type
        }).then(function (response) {
            jQ('#content_' + _dialogContainer).html(response);

            if (viewCallback) {
                viewCallback(views);
                return;
            }

            views.buttons();
        }).catch(function (error) {
            thisApp.dialogRemoveContainer(_dialogContainer);
            callbackDismiss();
        });
    },

    /**
     * Remove dialog views
     *
     * @param _dialogContainer
     */
    dialogRemoveContainer: function (_dialogContainer) {
        jQ('#' + _dialogContainer).modal('toggle');
        jQ('#' + _dialogContainer).remove();
    },

    onFormUpload: function (e) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.httpPost(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    thisApp.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    thisApp.processFormResponse(formAction, error.response.data);
                });
        }
    },

    onFormPost: function (e) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.httpPost(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    thisApp.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    thisApp.processFormResponse(formAction, error);
                });
        }
    },

    onFormGet: function (e) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.httpGet(formAction.attr('action'), formAction.serialize())
                .then(function (response) {
                    thisApp.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    thisApp.processFormResponse(formAction, error);
                });
        }
    },

    httpGet: function (uri, params, config) {
        var _config = {};
        params = (typeof params !== 'undefined') ? params : null;
        if (typeof config !== 'undefined') {
            _config = config;
            _config.params = params;
            _config.headers = _appConfig().httpHeaders();
        } else {
            _config = {
                params: params,
                headers: _appConfig().httpHeaders()
            };
        }

        return axios.get(uri, _config)
            .then(function (response) {
                _debugging('HTTP Get Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    httpPost: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = _appConfig().httpHeaders();
        } else {
            _config = {
                headers: _appConfig().httpHeaders()
            };
        }

        return axios.post(uri, data, _config)
            .then(function (response) {
                _debugging('HTTP Post Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    httpUpload: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = _appConfig().httpUploadHeaders();
        } else {
            _config = {
                headers: _appConfig().httpUploadHeaders()
            };
        }

        return axios.post(uri, data, _config)
            .then(function (response) {
                _debugging('HTTP Post Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    httpPut: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = _appConfig().httpUploadHeaders();
        } else {
            _config = {
                headers: _appConfig().httpUploadHeaders()
            };
        }

        return axios.put(uri, data, _config)
            .then(function (response) {
                _debugging('HTTP Put Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    httpDelete: function (uri, params, config) {
        var _config = {};
        params = (typeof params !== 'undefined') ? params : null;
        if (typeof config !== 'undefined') {
            _config = config;
            _config.params = params;
            _config.headers = _appConfig().httpHeaders();
        } else {
            _config = {
                params: params,
                headers: _appConfig().httpHeaders()
            };
        }

        return axios.delete(uri, _config)
            .then(function (response) {
                _debugging('HTTP Delete Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    processFormResponse: function (formAction, response) {
        var thisApp = this;
        _debugging(JSON.stringify(response));
        if (response.success === true) {
            // data
            if (typeof response.data !== 'undefined') {
                // redirect
                if (typeof response.data.redirect !== 'undefined') {
                    thisApp.redirect(response.data.redirect);
                }
            }
        } else if (response.success === false && typeof response.errors !== 'undefined') {
            if (typeof response.errors === 'object') {
                jQ.each(response.errors, function (name, error) {
                    thisApp.formValid(formAction.find('[name=' + name + ']'), error);
                });
            } else {
                toastr.error(response.errors);
            }
        }
    },

    formValidation: function (formAction, options) {
        options = (typeof options !== 'undefined') ? options : {};
        var inputData = {};
        var inputRules = {};
        var hasValidation = false;
        var _options = {
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
        var validation = new Validator(inputData, inputRules);
        var validated = (hasValidation === true) ? validation.passes() : true;

        // options
        if (_options.showErrors === true) {
            for (var key in inputData) {
                this.formValid(formAction.find('[name=' + key + ']'), validation.errors.first(key));
            }
        }

        return validated;
    },

    formValid: function (input, validation_message) {
        input.nextAll().remove();

        if (validation_message) {
            input.removeClass('is-valid');
            input.addClass('is-invalid');
            input.after('<div class="invalid-feedback">' + validation_message + '</div>');
        } else {
            input.removeClass('is-invalid');
        }
    }
};

var WBVueApp = new Vue({
    el: '#WBMainApp',
    mounted: _mounted,

    data: _appData,

    methods: _appMethods
});