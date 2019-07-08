/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 * @desc        Initialize all non vue libraries and codes
 */

let WBInitialize = function () {
    jQ(document).ready(function () {
        // feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // profile menu
        jQ('#profileSettingsModal').on('click', function (e) {
            e.preventDefault();

            WBServices.view.dialogs('profileMenu', null, function () {

            }, function (r) {

            });
        });

        // file selector (input)
        jQ(document).on('change', '.custom-file-input', function (e) {
            let fileName = jQ(this).val().split("\\").pop();
            jQ(e.currentTarget).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // select picker
        jQ.fn.selectpicker.Constructor.BootstrapVersion = '4';

        // Bootstrap Tooltip
        jQ('[data-toggle="tooltip"]').tooltip({boundary: 'window'});

        // mater waves
        Waves.attach('.btn', ['waves-effect']);

        // initialize JS libraries
        WBJSOnInit();
    });
};

let WBJSOnInit = function () {
    setTimeout(function () {
        // select picker
        WBServices.form.selectPickerSearch();
        jQ("[data-validate]").off();

        // validate input form upon change on values
        jQ("[data-validate]").on('change', function (e) {
            _validateInputs(jQ(this));
        });

        // validate input form upon keyup
        jQ("input[data-validate]").on('keyup', function (e) {
            _validateInputs(jQ(this));
        });
        jQ("textarea[data-validate]").on('keyup', function (e) {
            _validateInputs(jQ(this));
        });

        // custom file input upon selection of file
        jQ('.custom-file-input').off().on("change", function (e) {
            let input = jQ(this);

            if (input.val() && input.hasClass('custom-file-input')) {
                input.parent().find('.custom-file-label').text('Choose file');
                input.removeClass('is-invalid');
                input.parent().find('.custom-file-label').removeClass('custom-file-label-danger');
            }
        });

        jQ('.date-picker-no-future').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 80,
            max: true,
            onClose: _datePickerEvents.onPickDate,
            onOpen: _datePickerEvents.onOpenEvent,
        });

        jQ('.date-picker-no-pass').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 120,
            min: true,
            onClose: _datePickerEvents.onPickDate,
            onOpen: _datePickerEvents.onOpenEvent,
        });

        jQ('.date-picker-no-limit').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 120,
            min: false,
            max: false,
            onClose: _datePickerEvents.onPickDate,
            onOpen: _datePickerEvents.onOpenEvent,
        });

        jQ('.time-picker').pickatime({
            format: 'hh:i A',
            onClose: _datePickerEvents.onPickDate,
            onOpen: _datePickerEvents.onOpenEvent,
        });

       jQ('.selectize').selectize({
           persist: false,
           createOnBlur: true,
           create: true
       });

        // on checkbox change redirect to uri
        jQ('.is-checkbox-change').off().on('change', function (e) {
            let self = jQ(this);

            if (self.attr('data-uri')) {
                if (self.attr('data-is-redirect') === 'yes') {
                    WBServices.http.redirect(self.attr('data-uri'));
                } else {
                    WBServices.http.get(self.attr('data-uri'), {response: 'ajax'}).then(function (res) {

                    }, function (e) {
                        self.is(':checked') ? self.prop('checked', false) : self.prop('checked', true);
                    });
                }
            }
        });

        // show password input
        jQ('.btn-show-password').off().on('click', function (e) {
            e.preventDefault();
            let x = document.getElementById(jQ(this).attr('data-pass-to'));

            if (x.type === "password") {
                jQ(this).html('<i class="far fa-eye-slash"></i>');
                x.type = "text";
            } else {
                jQ(this).html('<i class="far fa-eye"></i>');
                x.type = "password";
            }
        });
    }, 50);

    let _datePickerEvents = {
        // on pick a date callback
        onPickDate() {
            if (this.$node.attr('data-form-submit') && this.$node.val()) {
                jQ(this.$node.attr('data-form-submit')).submit();
            }
        },

        // on open callback
        onOpenEvent() {

        }
    };

    // validate inputs
    function _validateInputs(self) {
        let rules = {};
        let data = {};
        let inputName = self.attr('name');

        data[inputName] = self.val() ? self.val() : null;
        rules[inputName] = self.attr('data-validate');

        let validation = new Validator(data, rules);
        validation.passes();
        WBServices.helpers.form.formInValid(self, validation.errors.first(inputName));
    }
};