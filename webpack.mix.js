let mix = require('laravel-mix');

// l = local, p = production
let env = 'l';

let vendorJS = 'public/assets/js/vendor.js';
let vendorCSS = 'public/assets/css/vendor.css';

let WB_JS = [
    'node_modules/jquery/dist/jquery.slim.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/snackbarjs/dist/snackbar.min.js',
    'node_modules/socket.io-client/dist/socket.io.js',
    'node_modules/validatorjs/dist/validator.js',
    'node_modules/pickadate/lib/compressed/picker.js',
    'node_modules/pickadate/lib/compressed/picker.date.js',
    'node_modules/pickadate/lib/compressed/picker.time.js',
    'node_modules/axios/dist/axios.min.js',
    'node_modules/vue/dist/vue.min.js'
];

let WB_CSS = [
    'node_modules/pickadate/lib/compressed/themes/default.css',
    'node_modules/pickadate/lib/compressed/themes/default.date.css',
    'node_modules/pickadate/lib/compressed/themes/default.time.css',
    'node_modules/tether/dist/css/tether.css',
    'node_modules/@fortawesome/fontawesome-free-webfonts/css/fa-brands.css',
    'node_modules/@fortawesome/fontawesome-free-webfonts/css/fa-regular.css',
    'node_modules/@fortawesome/fontawesome-free-webfonts/css/fa-solid.css',
    'node_modules/@fortawesome/fontawesome-free-webfonts/css/fontawesome.css',
    'node_modules/snackbarjs/dist/snackbar.min.css',
    'node_modules/snackbarjs/themes-css/material.css',
    'node_modules/bootstrap/dist/css/bootstrap.min.css'
];

if (env === 'p') {
    console.log('Processing Production...');

    mix.scripts(WB_JS.concat([
        'public/assets/js/vendor/config.js',
        'public/assets/js/vendor/helper.js',
        'public/assets/js/vendor/libraries.js',
        'public/assets/js/application.js'
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
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free-webfonts/webfonts', 'public/assets/webfonts');

// TinyMCE
mix.copyDirectory('node_modules/tinymce/tinymce.min.js', 'public/assets/js/lib/tinymce');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/assets/js/lib/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/assets/js/lib/tinymce/themes');
mix.copyDirectory('node_modules/tinymce/skins', 'public/assets/js/lib/tinymce/skins');

// ChartJS
mix.copyDirectory('node_modules/chart.js/dist/Chart.js', 'public/assets/js/lib/chart.js');