<!DOCTYPE html>
<html lang="en" {{html_app_cache()}}>
{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
 *
 * Admin styles
--}}

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="_token" content="{{csrf_token()}}">
        @if(auth()->check())
            <meta name="_authenticated_id" content="{{auth()->user()->id}}">
        @endif

        {{-- SEO variables --}}
        <title>{{ app_header('title') }} @yield('title')</title>
        <meta name="description" content="{{ $page_description }}">
        <meta name="keywords" content="{{ $page_keywords }}">
        <meta name="author" content="{{ $page_author }}">

        {{-- ICON --}}
        <link rel="icon" type="image/png" href="{{url('assets/img/placeholder/favicon.png')}}"/>

        {{-- Styles --}}
        @include('vendor.css', ['load_admin_styles' => true])
    </head>

    <body>
        {{-- header menu --}}
        @include('admin.layout.header')

        <div class="container-fluid">
            <div class="row">
                {{-- side-bar --}}
                @include('admin.sidebar.main')

                <div class="col-sm-12 col-lg-9" id="WBMainApp">
                    {{-- content --}}
                    <div class="row" v-cloak>
                        @yield('content')
                    </div>

                    {{-- fotter --}}
                    <div class="row">
                        @include('admin.layout.footer')
                    </div>
                </div>
            </div>
        </div>

        {{-- Load all JS --}}
        @include('vendor.loaders', ['js_run' => true])
        @yield('javascript')
    </body>
</html>