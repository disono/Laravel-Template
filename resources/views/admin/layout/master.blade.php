<!DOCTYPE html>
<html lang="en" {{html_app_cache()}}>
{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 *
 * Admin styles and master view
--}}

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{{csrf_token()}}">
    @if(auth()->check())
        <meta name="_authenticated_id" content="{{auth()->user()->id}}">
    @endif

    <title>{{ app_header('title') }} @yield('title')</title>
    <meta name="description" content="{{ app_header('description') }}">
    <meta name="keywords" content="{{ app_header('keywords') }}">
    <meta name="author" content="{{ app_header('author') }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

    {{-- Styles --}}
    @include('vendor.css', ['load_admin_styles' => true])

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
</head>

<body>
{{-- loader --}}
<div id="loaderContent">
    <img src="{{asset('assets/img/loaders/rolling.svg')}}" alt="Loading...">
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

                        {{-- fotter --}}
                        @include('admin.layout.footer')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    @include('modals.include')

    {{-- javascript dynamic container --}}
    <div id="dynamic_container"></div>
</main>

{{-- Scripts --}}
@if(env('APP_DEBUG'))
    @include('vendor.loaders', ['after_load' => true, 'scripts' => [
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places',

        asset('assets/js/vendor.js'),
        asset('assets/js/lib/helper.js'),
        asset('assets/js/lib/socket.js'),
        asset('assets/js/main.js'),
        asset('assets/js/app.js'),
        asset('assets/js/admin/main.js')
    ]])
@else
    @include('vendor.loaders', ['after_load' => true, 'scripts' => [
        'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_API_KEY') . '&libraries=places',

        asset('assets/js/vendor.js'),
        asset('assets/js/app.js')
    ]])
@endif

@yield('javascript')
@include('vendor.loaders', ['js_run' => true])
</body>
</html>