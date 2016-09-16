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

    <meta name="description" content="{{ app_header('description') }}">
    <meta name="keywords" content="{{ app_header('keywords') }}">
    <meta name="author" content="{{ app_header('author') }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

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
    </style>

    {{-- load all CSS --}}
    <script>
        {{-- load the vendor CSS --}}
        onloadCSS(loadCSS('{{ asset('assets/css/vendor.css') . url_ext() }}'), function () {
            {{-- load the style for app --}}
            onloadCSS(loadCSS('{{ asset('assets/css/main.css') . url_ext() }}'), function () {
                {{-- load the style for admin --}}
                onloadCSS(loadCSS('{{ asset('assets/css/admin.css') . url_ext() }}'), function () {
                    document.querySelector('#WBMainApp').style.display = null;
                    document.getElementById("loaderContent").remove();
                    document.getElementById("loaderStyles").remove();
                });
            });
        });
    </script>

    {{-- if no javascript load the CSS normally --}}
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') . url_ext() }}"/>
        @if(env('APP_DEBUG'))
            <link rel="stylesheet" href="{{ asset('assets/css/main.css') . url_ext() }}"/>
        @endif
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') . url_ext() }}"/>
    </noscript>
</head>

<body ng-app="WBApp">
{{-- loader --}}
<div id="loaderContent">
    <img src="{{asset('assets/img/loaders/content-loader.svg')}}" alt="Loading...">
</div>

{{-- main application content --}}
<main id="WBMainApp" style="display: none;">
    {{-- header menu --}}
    @include('admin.layout.header')

    <div class="container-fluid">
        <div id="wrapper">
            {{-- side-bar --}}
            @include('admin.sidebar.main')

            {{-- content --}}
            <div id="page-content-wrapper">
                <div class="container-fluid no-padding">
                    <div class="row">
                        <div class="col-lg-12 no-padding">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            {{-- fotter --}}
            @include('admin.layout.footer')
        </div>
    </div>
</main>

@yield('javascript')

@if(env('APP_DEBUG'))
    @include('vendor.loaders', array('after_load' => true, 'scripts' => [
        'assets/js/vendor.js',
        'assets/js/helper.js',
        'assets/js/main.js',
        'assets/js/app.js',
        'assets/js/admin.js',
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&v=3.exp&libraries=places'
    ]))
@else
    @include('vendor.loaders', ['after_load' => true, 'scripts' => [
        'assets/js/vendor.js',
        'assets/js/admin.js',
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&v=3.exp&libraries=places'
    ]])
@endif
</body>
</html>