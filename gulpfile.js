var elixir = require('laravel-elixir');
var type = 'local';

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


if (type == 'local') {
    elixir(function (mix) {
        mix.scripts([
            '../bower/jquery/dist/jquery.js',
            '../bower/tether/dist/js/tether.min.js',
            '../bower/bootstrap/dist/js/bootstrap.js',
            '../bower/moment/min/moment.min.js',
            '../bower/toastr/toastr.min.js',
            '../bower/nanoscroller/bin/javascripts/jquery.nanoscroller.js',
            '../bower/pickadate/lib/compressed/picker.js',
            '../bower/pickadate/lib/compressed/picker.date.js',
            '../bower/pickadate/lib/compressed/picker.time.js',
            '../bower/Chart.js/dist/Chart.bundle.min.js',
            '../bower/sweetalert/dist/sweetalert.min.js',
            '../bower/angular/angular.min.js'
        ], 'public/assets/js/vendor.js');

        mix.copy('resources/assets/bower/loadcss/src/loadCSS.js', 'public/assets/js');
        mix.copy('resources/assets/bower/loadcss/src/onloadCSS.js', 'public/assets/js');

        mix.less("app.less", 'public/assets/css/vendor.css');

        mix.styles([
            '../../../public/assets/css/vendor.css',

            '../bower/tether/dist/css/tether.min.css',
            '../bower/tether/dist/css/tether-theme-basic.min.css',
            '../bower/bootstrap/dist/css/bootstrap.min.css',
            '../bower/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
            '../bower/toastr/toastr.css',
            '../bower/animate.css/animate.css',
            '../bower/nanoscroller/bin/css/nanoscroller.css',
            '../bower/pickadate/lib/compressed/themes/default.css',
            '../bower/pickadate/lib/compressed/themes/default.date.css',
            '../bower/pickadate/lib/compressed/themes/default.time.css',
            '../bower/sweetalert/dist/sweetalert.css'
        ], 'public/assets/css/vendor.css');

        mix.copy('resources/assets/bower/font-awesome/fonts', 'public/assets/fonts');
        mix.copy('resources/assets/bower/bootstrap/fonts', 'public/assets/fonts');
        mix.copy('resources/assets/bower/ionicons/fonts', 'public/assets/fonts');
    });
} else if (type == 'admin') {
    
} else {
    elixir(function (mix) {
        mix.scripts([
            '../bower/jquery/dist/jquery.js',
            '../bower/tether/dist/js/tether.min.js',
            '../bower/bootstrap/dist/js/bootstrap.js',
            '../bower/moment/min/moment.min.js',
            '../bower/toastr/toastr.min.js',
            '../bower/nanoscroller/bin/javascripts/jquery.nanoscroller.js',
            '../bower/pickadate/lib/compressed/picker.js',
            '../bower/pickadate/lib/compressed/picker.date.js',
            '../bower/pickadate/lib/compressed/picker.time.js',
            '../bower/Chart.js/dist/Chart.bundle.min.js',
            '../bower/sweetalert/dist/sweetalert.min.js',
            '../bower/angular/angular.min.js',

            '../../../public/assets/js/helper.js',
            '../../../public/assets/js/main.js',
            '../../../public/assets/js/app.js'
        ], 'public/assets/js/vendor.js');

        mix.copy('resources/assets/bower/loadcss/src/loadCSS.js', 'public/assets/js');
        mix.copy('resources/assets/bower/loadcss/src/onloadCSS.js', 'public/assets/js');

        mix.less("app.less", 'public/assets/css/vendor.css');

        mix.styles([
            '../../../public/assets/css/vendor.css',

            '../bower/tether/dist/css/tether.min.css',
            '../bower/tether/dist/css/tether-theme-basic.min.css',
            '../bower/bootstrap/dist/css/bootstrap.min.css',
            '../bower/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
            '../bower/toastr/toastr.css',
            '../bower/animate.css/animate.css',
            '../bower/nanoscroller/bin/css/nanoscroller.css',
            '../bower/pickadate/lib/compressed/themes/default.css',
            '../bower/pickadate/lib/compressed/themes/default.date.css',
            '../bower/pickadate/lib/compressed/themes/default.time.css',
            '../bower/sweetalert/dist/sweetalert.css',

            '../../../public/assets/css/main.css'
        ], 'public/assets/css/vendor.css');

        mix.copy('resources/assets/bower/font-awesome/fonts', 'public/assets/fonts');
        mix.copy('resources/assets/bower/bootstrap/fonts', 'public/assets/fonts');
        mix.copy('resources/assets/bower/ionicons/fonts', 'public/assets/fonts');
    });
}