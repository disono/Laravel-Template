let mix = require('laravel-mix');

// l = local, p = production
let env = 'l';

let vendorJS = 'public/assets/js/vendor.js';
let vendorCSS = 'public/assets/css/vendor.css';

// SCSS
mix.sass('resources/assets/sass/bootstrap/bootstrap.scss', '../resources/assets/css');
mix.sass('resources/assets/sass/bootstrap-select/bootstrap-select.scss', '../resources/assets/css');
mix.sass('node_modules/mdbootstrap/scss/core/_waves.scss', '../resources/assets/css');
mix.sass('resources/assets/sass/app/theme.scss', 'public/assets/css');
mix.sass('resources/assets/sass/jro/jro-admin.scss', 'public/assets/css');

let WB_JS = [
    'node_modules/jquery/dist/jquery.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
    'node_modules/ajax-bootstrap-select/dist/js/ajax-bootstrap-select.js',
    'node_modules/selectize/dist/js/standalone/selectize.min.js',
    'node_modules/mdbootstrap/js/modules/waves.js',
    'node_modules/sweetalert/dist/sweetalert.min.js',
    'node_modules/snackbarjs/dist/snackbar.min.js',
    'node_modules/socket.io-client/dist/socket.io.js',
    'node_modules/validatorjs/dist/validator.js',
    'node_modules/pickadate/lib/compressed/picker.js',
    'node_modules/pickadate/lib/compressed/picker.date.js',
    'node_modules/pickadate/lib/compressed/picker.time.js',
    'node_modules/@fortawesome/fontawesome-free/js/all.js',
    'node_modules/feather-icons/dist/feather.min.js',
    'node_modules/axios/dist/axios.min.js',
    'node_modules/vue/dist/vue.min.js'
];

let WB_CSS = [
    'node_modules/tether/dist/css/tether.css',
    'resources/assets/css/bootstrap.css',
    'resources/assets/css/bootstrap-select.css',
    'node_modules/ajax-bootstrap-select/dist/css/ajax-bootstrap-select.css',
    'resources/assets/css/_waves.css',
    'node_modules/pickadate/lib/compressed/themes/default.css',
    'node_modules/pickadate/lib/compressed/themes/default.date.css',
    'node_modules/pickadate/lib/compressed/themes/default.time.css',
    'node_modules/@fortawesome/fontawesome-free/css/all.css',
    'node_modules/snackbarjs/dist/snackbar.min.css',
    'node_modules/snackbarjs/themes-css/material.css',
];

if (env === 'p') {
    console.log('Processing Production...');

    mix.scripts(WB_JS.concat([
        'public/assets/js/app/config.js',
        'public/assets/js/app/helper.js',
        'public/assets/js/app/initialize.js',
        'public/assets/js/app/services.js',

        'public/assets/js/vue/providers.js',
        'public/assets/js/vue/toolbar.js'
    ]), vendorJS);

    mix.styles(WB_CSS.concat([
        'public/assets/css/theme.css'
    ]), vendorCSS);
} else if (env === 'l') {
    console.log('Processing Development...');

    mix.scripts(WB_JS, vendorJS);
    mix.styles(WB_CSS, vendorCSS);
}

// FontAwesome
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/assets/webfonts');

// TinyMCE
mix.copyDirectory('node_modules/tinymce/tinymce.min.js', 'public/assets/js/lib/tinymce');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/assets/js/lib/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/assets/js/lib/tinymce/themes');
mix.copyDirectory('node_modules/tinymce/skins', 'public/assets/js/lib/tinymce/skins');

// ChartJS
mix.copyDirectory('node_modules/chart.js/dist/Chart.min.js', 'public/assets/js/lib/chart.min.js');
mix.copyDirectory('node_modules/moment/min/moment.min.js', 'public/assets/js/lib/moment.min.js');