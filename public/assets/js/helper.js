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
 * @type {{initDefaults, enableFormInputs, disableFormInputs, ajax, delAjax, clearForm, preLoadImg, goTo}}
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
                serverError: function (data, textStatus, jqXHR) {
                    // server custom error
                    console.log('AJAX Server Error');
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
                    options.enableForm = options.enableFormSuccess;

                    // success
                    options.success(data, textStatus, jqXHR);
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 422) {
                        var response = (typeof xhr.responseText == 'string') ? xhr.responseText : "";
                        WBErrors.run(JSON.parse(response), options);
                    }

                    options.error(xhr, status, error);

                    // show critical error in console
                    WBErrors.log(xhr.status + ' ' + error);
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
                        if (jqXHR.status == 422) {
                            var response = (typeof jqXHR.responseText == 'string') ? jqXHR.responseText : "";
                            WBErrors.run(JSON.parse(response), options);
                        }

                        options.error(jqXHR, textStatus, errorThrown);

                        // shows error message
                        WBErrors.log(jqXHR.status + ' ' + textStatus);
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
        datePicker: function () {
            jQ('.date-picker').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                max: true
            });
        },

        datePickerMin: function () {
            jQ('.date-picker-min').pickadate({
                format: 'mmmm d, yyyy',
                selectMonths: true,
                selectYears: 80,
                min: true
            });
        },

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
            jQ('.time-picker').pickatime();
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
                            WBErrors.bottomMessage({
                                errors: data.errors,
                                formId: options.formId,
                                container: options.statusContainer
                            });
                        } else if (errorType == 'responseMessage') {
                            WBErrors.responseMessage({
                                errors: data.errors,
                                formId: options.formId,
                                container: options.statusContainer,
                                removeMessage: options.removeMessage
                            });
                        } else if (errorType == 'toastMessage') {
                            WBErrors.toastMessage({
                                title: 'Oops error occurred',
                                message: data.errors,
                                type: 'error'
                            });
                        }
                    }
                }
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
                    var inputField = jQ(options.formId + ' [data-error="' + inputName + '"]');

                    // add highlight on input form
                    WBErrors.inputHighlight(options.formId, inputName);

                    if (inputField.length) {
                        // add error markings on input fields in form
                        inputField.addClass('has-error');
                        inputField.find('[name="' + inputName + '"]').after('<span class="help-block error-block" data-error-block="' +
                            inputName + '"><strong>' +
                            error + '</strong></span>');
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
                    setTimeout(function () {
                        // invalid token reload page
                        location.reload();
                    }, 800);

                    break;
                case 401:
                    // authentication required show login modal
                    WBErrors.clearMessages(false);

                    // hide or close all current modals
                    jQ('.modal').modal('hide');

                    // show login modal
                    jQ('#modalLogin').modal({
                        show: true
                    });

                    break;
                case 400:
                    WBErrors.clearMessages(false);

                    // show bad request for token
                    WBErrors.toastMessage({message: '400 Bad Request', type: 'error'});

                    setTimeout(function () {
                        location.reload();
                    }, 500);

                    break;
                case 405:
                    WBErrors.clearMessages(false);

                    // show bad request for token
                    WBErrors.toastMessage({message: '405 (Method Not Allowed)', type: 'error'});

                    break;
                case 500:
                    // show bad request for token
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