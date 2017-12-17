<!DOCTYPE html>
<html lang="en" {{html_app_cache()}}>
{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
 *
 * Web styles
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
        @include('vendor.css')
    </head>

    <body>
        {{-- header --}}
        @include(current_theme() . 'layout.header')

        <div id="WBMainApp" v-cloak>
            {{-- contents --}}
            @yield('content')

            {{-- subscriber form --}}
            @include(current_theme() . 'page.subscriber')
        </div>

        {{-- footers --}}
        @include(current_theme() . 'layout.footer')

        {{-- Load all JS --}}
        @include('vendor.loaders', ['js_run' => true])
        @yield('javascript')
    </body>
</html>