<!DOCTYPE html>
{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ $token }}">

    {{-- SEO variables --}}
    <title>{{ $page_title }}</title>
    <meta name="description" content="{{ $page_description }}">
    <meta name="keywords" content="{{ $page_keywords }}">
    <meta name="author" content="{{ $page_author }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

    <link rel="stylesheet" href="{{ devAssets('assets/css/vendor.css') }}"/>
    @if(env('APP_ENV') == 'local')
        <link rel="stylesheet" href="{{ devAssets('assets/css/theme.css') }}"/>
    @endif

    {{-- Inlince CSS for VueJS --}}
    <style>
        [v-cloak] > * {
            display: none;
            text-align: center !important;
        }

        [v-cloak]::before {
            content: "Loading…";
            font-size: 22px;
            text-align: center !important;
            display: block;
            margin: 12em 0 !important;
        }
    </style>
</head>

<body>
{{-- header --}}
@include(currentTheme() . 'layouts.header')

{{-- contents --}}
<div id="WBApp" v-cloak style="margin-top: 22px;">
    @yield('content')
</div>

{{-- footer --}}
@include(currentTheme() . 'layouts.footer')

@if(env('GOOGLE_API_KEY'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places"></script>
@endif

<script src="{{ devAssets('assets/js/vendor.js') }}"></script>
@if(env('APP_ENV') == 'local')
    <script src="{{ devAssets('assets/js/vendor/config.js') }}"></script>
    <script src="{{ devAssets('assets/js/vendor/helper.js') }}"></script>
    <script src="{{ devAssets('assets/js/vendor/libraries.js') }}"></script>
    <script src="{{ devAssets('assets/js/application.js') }}"></script>
@endif
@yield('javascript')
</body>
</html>