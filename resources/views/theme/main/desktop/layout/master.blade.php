{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{csrf_token()}}">

    {{-- SEO variables --}}
    <meta name="description" content="{{ (isset($page_description)) ? $page_description : app_header('description') }}">
    <meta name="keywords" content="{{ (isset($page_keywords)) ? $page_keywords : app_header('keywords') }}">
    <meta name="author" content="{{ (isset($page_author)) ? $page_author : app_header('author') }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

    <title>{{ app_header('title') }} @yield('title')</title>

    {{-- CSS loader --}}
    <script src="{{ asset('assets/js/loadCSS.js') . url_ext() }}"></script>
    <script src="{{ asset('assets/js/onloadCSS.js') . url_ext() }}"></script>

    <style id="loaderStyles">
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table
        }

        #loaderContent {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        #loaderContent img {
            opacity: 0.5;
        }
    </style>

    @include('vendor.css')
</head>

<body ng-app="WBApp">
{{-- loader --}}
<div id="loaderContent">
    <img src="{{asset('assets/img/loaders/content-loader.svg')}}" alt="Loading...">
</div>

{{-- main application content --}}
<main id="WBMainApp" style="display: none;">
    @include(current_theme() . 'layout.header')

    <div class="page-content">
        @yield('content')
    </div>

    @include(current_theme() . 'layout.footer')
</main>

@yield('javascript')

@if(env('APP_DEBUG'))
    @include('vendor.loaders', ['scripts' => [
        'assets/js/vendor.js',
        'assets/js/helper.js',
        'assets/js/main.js',
        'assets/js/app.js',
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&v=3.exp&libraries=places'
    ], 'after_load' => true])
@else
    @include('vendor.loaders', ['scripts' => [
        'assets/js/vendor.js',
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&v=3.exp&libraries=places'
    ], 'after_load' => true])
@endif
</body>
</html>