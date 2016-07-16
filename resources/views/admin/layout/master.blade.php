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

    <meta name="description" content="{{ app_header('description') }}">
    <meta name="keywords" content="{{ app_header('keywords') }}">
    <meta name="author" content="{{ app_header('author') }}">

    <link rel="icon" type="image/png" href="{{url('assets/img/favicon.png')}}"/>

    <title>{{ app_header('title') }} @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') . url_ext() }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') . url_ext() }}"/>
</head>

<body ng-app="WBApp">
{{-- header menu --}}
@include('admin.layout.header')

<div class="container-fluid">
    <div id="wrapper">
        {{-- side-bar --}}
        @include('admin.sidebar.main')

        {{-- content --}}
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        {{-- fotter --}}
        @include('admin.layout.footer')
    </div>
</div>

<script src="{{ asset('assets/js/vendor.js') . url_ext() }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places" type="application/javascript"></script>

@if(env('APP_DEBUG'))
    <script src="{{ asset('assets/js/helper.js') . url_ext() }}"></script>
    <script src="{{ asset('assets/js/main.js') . url_ext() }}"></script>
    <script src="{{ asset('assets/js/app.js') . url_ext() }}"></script>
@endif

<script src="{{ asset('assets/js/admin.js') . url_ext() }}"></script>
@yield('javascript')
</body>
</html>