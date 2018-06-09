/**
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

var VueMounted = function () {
    jQ(document).ready(function () {
        // initialize libraries
        WBLibraries();
    });
};

var VueAppData = {};

var VueAppMethods = {
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
            if (typeof id !== 'undefined') {
                var parentTBody = jQ(id).parent();
                jQ(id).remove();

                if (parentTBody.children().length === 0) {
                    // refresh the page
                    location.reload();
                }
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
                jQ('.dialogDismiss').off().on('click', function (e) {
                    e.preventDefault();
                    thisApp.dialogRemoveContainer(_dialogContainer);
                    callbackDismiss(views.data);
                });

                // confirm
                jQ('.dialogConfirm').off().on('click', function (e) {
                    e.preventDefault();
                    thisApp.dialogRemoveContainer(_dialogContainer);
                    callbackConfirm(views.data);
                });
            }
        };

        // callbacks
        callbackConfirm = (typeof callbackConfirm === 'function') ? callbackConfirm : function () {
            debugging('Confirm Button Dialogs');
        };
        callbackDismiss = (typeof callbackDismiss === 'function') ? callbackDismiss : function () {
            debugging('Dismiss Button Dialogs');
        };

        // views
        jQ('#WBApp').append(
            '<div class="modal" data-backdrop="static" data-keyboard="false" id="' + _dialogContainer +
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
        thisApp.httpGet('/view/' + type)
            .then(function (response) {
                jQ('#content_' + _dialogContainer).html(response);

                if (viewCallback) {
                    viewCallback(views);
                    return;
                }

                views.buttons();
            })
            .catch(function (error) {
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

    /**
     * Form upload
     *
     * @param e
     */
    onFormUpload: function (e) {
        this.onUpload(e, null, null);
    },

    /**
     * Form post
     *
     * @param e
     */
    onFormPost: function (e) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.formDisable();

            thisApp.httpPost(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    thisApp.formEnable();
                    thisApp.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    thisApp.formEnable();
                    if (typeof error.response !== 'undefined') {
                        thisApp.processFormResponse(formAction, error.response.data);
                    }
                });
        }
    },

    /**
     * Form get
     *
     * @param e
     */
    onFormGet: function (e) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.httpGet(formAction.attr('action'), formAction.serialize())
                .then(function (response) {
                    thisApp.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    if (typeof error.response !== 'undefined') {
                        thisApp.processFormResponse(formAction, error.response.data);
                    }
                });
        }
    },

    /**
     * On Upload process
     *
     * @param e
     * @param callbackSuccess
     * @param callbackFailed
     */
    onUpload: function (e, callbackSuccess, callbackFailed) {
        var thisApp = this;
        var formAction = jQ(e.target);
        if (thisApp.formValidation(formAction) === true) {
            thisApp.formDisable();

            thisApp.httpUpload(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    thisApp.formEnable();
                    thisApp.processFormResponse(formAction, response);

                    if (callbackSuccess !== null) {
                        callbackSuccess(response);
                    }
                })
                .catch(function (error) {
                    thisApp.formEnable();
                    var _error = null;
                    if (typeof error.response !== 'undefined') {
                        _error = error.response.data;
                        thisApp.processFormResponse(formAction, error.response.data);
                    }

                    if (callbackFailed !== null) {
                        callbackFailed(_error);
                    }
                });
        }
    },

    /**
     * HTTP request GET
     *
     * @param uri
     * @param params
     * @param config
     * @returns {*}
     */
    httpGet: function (uri, params, config) {
        var _config = {};
        params = (typeof params !== 'undefined') ? params : null;
        if (typeof config !== 'undefined') {
            _config = config;
            _config.params = params;
            _config.headers = WBAppConfig().httpHeaders();
        } else {
            _config = {
                params: params,
                headers: WBAppConfig().httpHeaders()
            };
        }

        return axios.get(uri, _config)
            .then(function (response) {
                debugging('HTTP Get Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    /**
     * HTTP request POST
     *
     * @param uri
     * @param data
     * @param config
     * @returns {Promise<T>}
     */
    httpPost: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = WBAppConfig().httpHeaders();
        } else {
            _config = {
                headers: WBAppConfig().httpHeaders()
            };
        }

        return axios.post(uri, data, _config)
            .then(function (response) {
                debugging('HTTP Post Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    /**
     * HTTP request Upload
     *
     * @param uri
     * @param data
     * @param config
     * @returns {Promise<T>}
     */
    httpUpload: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = WBAppConfig().httpUploadHeaders();
        } else {
            _config = {
                headers: WBAppConfig().httpUploadHeaders()
            };
        }

        return axios.post(uri, data, _config)
            .then(function (response) {
                debugging('HTTP Upload Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    /**
     * HTTP request PUT
     *
     * @param uri
     * @param data
     * @param config
     * @returns {Promise<T>}
     */
    httpPut: function (uri, data, config) {
        var _config = {};
        if (typeof config !== 'undefined') {
            _config = config;
            _config.headers = WBAppConfig().httpUploadHeaders();
        } else {
            _config = {
                headers: WBAppConfig().httpUploadHeaders()
            };
        }

        return axios.put(uri, data, _config)
            .then(function (response) {
                debugging('HTTP Put Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    /**
     * HTTP request DELETE
     *
     * @param uri
     * @param params
     * @param config
     * @returns {*}
     */
    httpDelete: function (uri, params, config) {
        var _config = {};
        params = (typeof params !== 'undefined') ? params : null;
        if (typeof config !== 'undefined') {
            _config = config;
            _config.params = params;
            _config.headers = WBAppConfig().httpHeaders();
        } else {
            _config = {
                params: params,
                headers: WBAppConfig().httpHeaders()
            };
        }

        return axios.delete(uri, _config)
            .then(function (response) {
                debugging('HTTP Delete Response: ' + JSON.stringify(response.data));
                return response.data;
            });
    },

    /**
     * Process FORM response
     *
     * @param formAction
     * @param response
     */
    processFormResponse: function (formAction, response) {
        var thisApp = this;
        if (response.success === true) {
            // data
            if (typeof response.data !== 'undefined') {
                // redirect
                if (typeof response.data.redirect !== 'undefined') {
                    thisApp.redirect(response.data.redirect);
                }

                if (typeof response.data === 'string') {
                    jQ.snackbar({content: response.data});
                }
            }
        } else if (response.success === false && typeof response.errors !== 'undefined') {
            if (typeof response.errors === 'object') {
                jQ.each(response.errors, function (name, error) {
                    debugging(error);
                    thisApp.formValid(formAction.find('[name=' + name + ']'), error);
                });
            } else {
                if (typeof response.errors === 'string') {
                    jQ.snackbar({content: response.errors});
                }
            }
        }
    },

    /**
     * Validate form
     *
     * @param formAction
     * @param options
     * @returns {*}
     */
    formValidation: function (formAction, options) {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

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

    /**
     * Is form valid
     *
     * @param input
     * @param validation_message
     */
    formValid: function (input, validation_message) {
        input.next().not('.custom-file-input').not('.custom-file-label').not('.custom-control-label').not('form-text').not('.picker').remove();

        if (validation_message) {
            input.removeClass('is-valid');
            input.addClass('is-invalid');
            input.after('<div class="invalid-feedback">' + validation_message + '</div>');
        } else {
            input.removeClass('is-invalid');
        }
    },

    formDisable: function () {
        jQ('form').find('input, textarea, button, select').not('.picker').prop('readonly', true);
    },

    formEnable: function () {
        // do not enable if attributes of input is data-disabled="yes"
        jQ('form').find('input, textarea, button, select')
            .not('[data-disabled="yes"]')
            .not('.date-picker')
            .not('.time-picker')
            .not('.date-picker-limit')
            .not('.date-picker-current')
            .prop("readonly", false);
    }
};

new Vue({
    el: '#WBApp',
    mounted: VueMounted,
    data: VueAppData,
    methods: VueAppMethods
});