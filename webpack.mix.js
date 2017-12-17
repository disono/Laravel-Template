let mix = require('laravel-mix');

// l = local, p = production
let env = 'l';

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let vendorJS = 'public/assets/js/vendor.js';
let vendorCSS = 'public/assets/css/vendor.css';

let WB_JS = [
    'node_modules/jquery/dist/jquery.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',

    'node_modules/moment/min/moment.min.js',
    'node_modules/toastr/build/toastr.min.js',
    'node_modules/socket.io-client/dist/socket.io.js',
    'node_modules/Chart.js/dist/Chart.js',
    'node_modules/wolfy87-eventemitter/EventEmitter.min.js',
    'node_modules/validatorjs/dist/validator.js',
    'node_modules/pickadate/lib/compressed/picker.js',
    'node_modules/pickadate/lib/compressed/picker.date.js',
    'node_modules/pickadate/lib/compressed/picker.time.js',
    'node_modules/wolfy87-eventemitter/EventEmitter.min.js',

    // this is optional
    // 'node_modules/leaflet/dist/leaflet.js',
    // 'node_modules/leaflet-search/dist/leaflet-search.min.js',
    // 'node_modules/leaflet-routing-machine/dist/leaflet-routing-machine.min.js',

    'node_modules/axios/dist/axios.min.js',
    'node_modules/vue/dist/vue.min.js'
];

let WB_CSS = [
    'node_modules/bootstrap/dist/css/bootstrap.min.css',

    'node_modules/toastr/build/toastr.min.css',
    'node_modules/font-awesome/css/font-awesome.css',
    'node_modules/pickadate/lib/compressed/themes/default.css',
    'node_modules/pickadate/lib/compressed/themes/default.date.css',
    'node_modules/pickadate/lib/compressed/themes/default.time.css'

    // this is optional
    // 'node_modules/leaflet/dist/leaflet.css',
    // 'node_modules/leaflet-search/dist/leaflet-search.min.css',
    // 'node_modules/leaflet-routing-machine/dist/leaflet-routing-machine.css'
];

if (env === 'p') {
    console.log('Processing Production...');

    mix.scripts(WB_JS.concat([
        'public/assets/js/vendor/libraries.js',
        'public/assets/js/vendor/helper.js',
        'public/assets/js/vendor/socket.js',

        'public/assets/js/application.js',
        'public/assets/js/admin.js'
    ]), vendorJS);

    mix.styles(WB_CSS.concat([
        'public/assets/css/theme.css'
    ]), vendorCSS);
} else if (env === 'l') {
    console.log('Processing Development...');

    mix.scripts(WB_JS, vendorJS);
    mix.styles(WB_CSS, vendorCSS);
}

// copy fonts and other files
// mix.copyDirectory('node_modules/leaflet/dist/images', 'public/assets/css/images');
mix.copyDirectory('node_modules/font-awesome/fonts', 'public/assets/fonts');

// TinyMCE
mix.copyDirectory('node_modules/tinymce/tinymce.min.js', 'public/assets/js/lib/tinymce');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/assets/js/lib/tinymce/plugins');
mix.copyDirectory('node_modules/tinymce/themes', 'public/assets/js/lib/tinymce/themes');
mix.copyDirectory('node_modules/tinymce/skins', 'public/assets/js/lib/tinymce/skins');