{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<link rel="stylesheet" href="{{ devAssets('assets/css/vendor.css') }}"/>

{{-- FOR DEVELOPMENT PURPOSES ONLY --}}
@if(env('APP_ENV') == 'local')
    <link rel="stylesheet" href="{{ devAssets('assets/css/theme.css') }}"/>
@endif

{{-- default admin theme --}}
<link rel="stylesheet" href="{{ devAssets('assets/css/jro-admin.css') }}"/>

{{-- controller defined styles (app/Http/Middleware/ViewVariables.php) --}}
@foreach($app_styles as $_style)
    <link rel="stylesheet" href="{{ devAssets($_style) }}"/>
@endforeach

{{-- Inlince CSS for VueJS --}}
@include('vendor.view.vueInline')