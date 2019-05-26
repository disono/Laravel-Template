{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{-- controller defined javascript libraries (app/Http/Middleware/ViewVariables.php) --}}
@foreach($app_libraries as $_script)
    <script src="{{ $_script }}"></script>
@endforeach

<script src="{{ devAssets('assets/js/vendor.js') }}"></script>

{{-- FOR DEVELOPMENT PURPOSES ONLY --}}
@if(env('APP_ENV') == 'local')
    <script src="{{ devAssets('assets/js/app/config.js') }}"></script>
    <script src="{{ devAssets('assets/js/app/helper.js') }}"></script>
    <script src="{{ devAssets('assets/js/app/initialize.js') }}"></script>
    <script src="{{ devAssets('assets/js/app/services.js') }}"></script>

    <script src="{{ devAssets('assets/js/plugins/providers.js') }}"></script>
    <script src="{{ devAssets('assets/js/plugins/toolbar.js') }}"></script>
@endif

{{-- controller defined javascript (app/Http/Middleware/ViewVariables.php) --}}
@foreach($app_scripts as $_script)
    <script src="{{ devAssets($_script) }}"></script>
@endforeach

{{-- customer javascript --}}
@yield('javascript')