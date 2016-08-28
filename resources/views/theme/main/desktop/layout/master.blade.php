{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
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
                document.querySelector('#WBMainApp').style.display = null;
                jQ('#loaderContent').remove();
                jQ('#loaderStyles').remove();
            });
        });
    </script>

    {{-- if no javascript load the CSS normally --}}
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') . url_ext() }}"/>
        @if(env('APP_DEBUG'))
            <link rel="stylesheet" href="{{ asset('assets/css/main.css') . url_ext() }}"/>
        @endif
    </noscript>
</head>

<body ng-app="WBApp">
{{-- loader --}}
<div id="loaderContent">
    <img src="{{asset('assets/img/loaders/content-loader.svg')}}" alt="Loading...">
</div>

{{-- main application content --}}
<main id="WBMainApp" style="display: none;">
    @include(current_theme() . 'layout.header')

    @yield('content')

    @include(current_theme() . 'layout.footer')
</main>

@yield('javascript')

@if(env('APP_DEBUG'))
    <script>
        [
            '{{asset("assets/js/vendor.js") . url_ext()}}',
            '{{asset("assets/js/helper.js") . url_ext()}}',
            '{{asset("assets/js/main.js") . url_ext()}}',
            '{{asset("assets/js/app.js") . url_ext()}}',

            'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places'
        ].forEach(function (src) {
            var script = document.createElement('script');
            script.src = src;
            script.async = false;
            document.head.appendChild(script);
        });

        if (typeof appScriptLoader == 'function') {
            appScriptLoader();
        }
    </script>
@else
    <script>
        [
            '{{asset("assets/js/vendor.js") . url_ext()}}',

            'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places'
        ].forEach(function (src) {
            var script = document.createElement('script');
            script.src = src;
            script.async = false;
            document.head.appendChild(script);
        });

        if (typeof appScriptLoader == 'function') {
            appScriptLoader();
        }
    </script>
@endif
</body>
</html>