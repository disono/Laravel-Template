/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 * @desc        Initialize all non vue libraries and codes
 */

let WBInitialize = function () {
    jQ(document).ready(function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        jQ('.date-picker-no-future').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 80,
            max: true
        });

        jQ('.date-picker-no-pass').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 80,
            min: true
        });

        jQ('.date-picker-no-limit').pickadate({
            format: 'mmmm d, yyyy',
            selectMonths: true,
            selectYears: 90,
            min: false,
            max: false
        });

        jQ('.time-picker').pickatime({
            format: 'hh:i A'
        });

        jQ(document).on('change', '.custom-file-input', function () {
            let fileName = jQ(this).val().split("\\").pop();
            jQ(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        jQ('[data-toggle="tooltip"]').tooltip();

        jQ.fn.selectpicker.Constructor.BootstrapVersion = '4';
        jQ('.select_picker').selectpicker();
    });
};