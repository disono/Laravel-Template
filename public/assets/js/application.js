/**
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

var VueMounted = function () {
    jQ(document).ready(function () {
        jQ('[data-toggle="offcanvas"]').on('click', function () {
            jQ('.offcanvas-collapse').toggleClass('open')
        });

        // Tooltip
        jQ('[data-toggle="tooltip"]').tooltip();

        // Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // initialize libraries
        WBLibraries();
    });
};

var VueAppData = {
    frmFCM: {
        type: '',
        topic_name: '',
        token: ''
    },

    adminPage: {
        name: jQ('#name[data-value]').val(),
        slug: jQ('#slug[data-value]').val()
    },

    chat: {
        inboxMessages: [],
        messages: [],

        profileSearchGroup: [],
        profileSearchWrite: [],

        groupDetail: null,

        writeMessage: {
            to: [],
            group_id: null,
            message: null
        },

        createGroup: {
            name: null,
            members: []
        }
    },

    deleteListCheckbox: []
};

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
        var self = this;
        self.dialogs('delete', null, function (r) {
            self.httpDelete(path)
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
        var self = this;
        var _dialogContainer = 'dialog_' + Date.now();

        // event
        var views = {
            data: null,
            buttons: function () {
                // dismiss
                jQ('.dialogDismiss').off().on('click', function (e) {
                    e.preventDefault();
                    self.dialogRemoveContainer(_dialogContainer);
                    callbackDismiss(views.data);
                });

                // confirm
                jQ('.dialogConfirm').off().on('click', function (e) {
                    e.preventDefault();
                    self.dialogRemoveContainer(_dialogContainer);
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
        self.httpGet('/view/' + type)
            .then(function (response) {
                jQ('#content_' + _dialogContainer).html(response);

                if (viewCallback) {
                    viewCallback(views);
                    return;
                }

                views.buttons();
            })
            .catch(function (error) {
                self.dialogRemoveContainer(_dialogContainer);
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
        var self = this;
        var formAction = jQ(e.target);
        if (self.formValidation(formAction) === true) {
            self.formDisable();

            self.httpPost(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    self.formEnable();
                    self.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    self.formEnable();
                    if (typeof error.response !== 'undefined') {
                        self.processFormResponse(formAction, error.response.data);
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
        var self = this;
        var formAction = jQ(e.target);
        if (self.formValidation(formAction) === true) {
            self.httpGet(formAction.attr('action'), formAction.serialize())
                .then(function (response) {
                    self.processFormResponse(formAction, response);
                })
                .catch(function (error) {
                    if (typeof error.response !== 'undefined') {
                        self.processFormResponse(formAction, error.response.data);
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
        var self = this;
        var formAction = jQ(e.target);
        if (self.formValidation(formAction) === true) {
            self.formDisable();

            jQ('#WBApp').hide();
            jQ('#loaderUpload').show();
            self.httpUpload(formAction.attr('action'), new FormData(e.target))
                .then(function (response) {
                    jQ('#WBApp').show();
                    jQ('#loaderUpload').hide();

                    self.formEnable();
                    self.processFormResponse(formAction, response);

                    if (callbackSuccess !== null) {
                        callbackSuccess(response);
                    }
                })
                .catch(function (error) {
                    jQ('#WBApp').show();
                    jQ('#loaderUpload').hide();

                    self.formEnable();
                    var _error = null;
                    if (typeof error.response !== 'undefined') {
                        _error = error.response.data;
                        self.processFormResponse(formAction, error.response.data);
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
        var self = this;
        if (response.success === true) {
            // data
            if (typeof response.data !== 'undefined') {
                // redirect
                if (typeof response.data.redirect !== 'undefined') {
                    self.redirect(response.data.redirect);
                }

                if (typeof response.data === 'string') {
                    jQ.snackbar({content: response.data});
                    swal("Success", response.data, "success");
                }
            }
        } else if (response.success === false && typeof response.errors !== 'undefined') {
            if (typeof response.errors === 'object') {
                jQ.each(response.errors, function (name, error) {
                    debugging(error);
                    self.formValid(formAction.find('[name=' + name + ']'), error);
                });
            } else {
                if (typeof response.errors === 'string') {
                    jQ.snackbar({content: response.errors});
                    swal("Oops!", response.data, "error");
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

VueAppMethods.frmFCMTokenOnChangeType = function () {
    if (this.frmFCM.type === 'topic') {
        this.frmFCM.topic_name = '';
    } else if (this.frmFCM.type === 'token') {
        this.frmFCM.token = '';
    }
};

VueAppMethods.adminPageOnNameChange = function () {
    if (this.adminPage.name) {
        this.adminPage.slug = this.adminPage.name.replace(/\s+/g, '-').toLowerCase();
    }
};

// fetch for users
VueAppMethods.fetchUsers = function (params, success, failed) {
    var self = this;
    params = (params) ? params.response = 'json' : null;
    self.httpGet('/u/search', params)
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// fetch inbox
VueAppMethods.fetchInbox = function (params, success, failed) {
    var self = this;
    params = (params) ? params.response = 'json' : null;
    self.httpGet('/chat', params)
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// fetch inbox (messages)
VueAppMethods.fetchMessages = function (group_id, params, success, failed) {
    var self = this;
    params = (params) ? params.response = 'json' : null;
    self.httpGet('/chat/group/show/' + group_id, params)
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// create group chat
VueAppMethods.createGroupChat = function (params, success, failed) {
    var self = this;
    params = (params) ? params.response = 'json' : null;
    self.httpPost('/chat/group/store', params)
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// leave group chat
VueAppMethods.leaveGroupChat = function (group_id) {
    var self = this;
    self.httpPost('/chat/group/leave/' + group_id, {response: 'json'})
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// add to group chat
VueAppMethods.addToGroupChat = function (group_id, user_id) {
    var self = this;
    self.httpPost('/chat/group/add/' + group_id + '/' + user_id, {response: 'json'})
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// send message
VueAppMethods.sendMessage = function (params) {
    var self = this;
    params = (params) ? params.response = 'json' : null;
    self.httpUpload('/chat/send', params)
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

// fetch profile
VueAppMethods.profile = function (username) {
    var self = this;
    self.httpGet('/u/' + username, {response: 'json'})
        .then(function (response) {
            success(response);
        })
        .catch(function (error) {
            success(failed);
        });
};

VueAppMethods.btnChatWriteMessageModal = function () {
    jQ('#writeMessageModal').modal('toggle');
};

VueAppMethods.btnChatCreateGroupModal = function () {
    jQ('#writeGroupModal').modal('toggle');
};

VueAppMethods.deleteSelectAll = function (event) {
    if (event.target.checked) {
        var self = this;
        jQ('input[delete-data="deleteListCheckbox"]').each(function (val, index) {
            self.deleteListCheckbox.push(jQ(this).val());
        });
    } else {
        this.deleteListCheckbox = [];
    }
};

VueAppMethods.btnDeleteSelected = function () {
    var self = this;

    self.dialogs('delete', null, function (r) {
        var list = [];
        var index = 0;
        jQ('input[delete-data="deleteListCheckbox"]').each(function (val, index) {
            if (jQ(this).is(':checked')) {
                list.push(jQ(this));
            }
        });

        deleteSelectedItem();

        function deleteSelectedItem() {
            if (!isDone()) {
                return;
            }

            var id = '#parent_tr_' + list[index].val();
            self.httpDelete(jQ('#parent_tr_del_' + list[index].val()).attr('href'))
                .then(function (response) {
                    var parentTBody = jQ(id).parent();
                    jQ(id).remove();

                    if (parentTBody.children().length === 0) {
                        // refresh the page
                        location.reload();
                    }

                    index++;
                    deleteSelectedItem();
                })
                .catch(function (error) {
                    index++;
                    deleteSelectedItem();
                });
        }

        function isDone() {
            return index <= (list.length - 1);
        }
    }, function (r) {

    });
};

new Vue({
    el: '#WBApp',
    mounted: VueMounted,
    data: VueAppData,
    methods: VueAppMethods
});

