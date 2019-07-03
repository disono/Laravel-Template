{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ $token }}">
    <meta name="_routeName" content="{{ getRouteName() }}">

    {{-- SEO variables --}}
    <title>{{ $page_title }}</title>
    {!! html_meta_tag('description', $page_description) !!}
    {!! html_meta_tag('keywords', $page_keywords) !!}
    {!! html_meta_tag('author', $page_author) !!}
    {!! html_meta_tag('robots', $seo_robots) !!}

    {{--  Meta og  --}}
    {!! html_meta_tag('og:url', $og_url) !!}
    {!! html_meta_tag('og:type', $og_type) !!}
    {!! html_meta_tag('og:title', $og_title) !!}
    {!! html_meta_tag('og:description', $og_description) !!}
    {!! html_meta_tag('og:image', $og_image) !!}
    {!! html_meta_tag('og:image:width', $og_image_width) !!}
    {!! html_meta_tag('og:image:height', $og_image_height) !!}

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{ url('assets/img/placeholder/favicon.png') }}"/>

    {{-- CSS --}}
    @include('vendor.view.themeStyles')
</head>

<body>
{{-- header --}}
@includeTheme('layouts.header')

{{-- contents --}}
<div id="{{ $vue_app }}" v-cloak style="margin-top: 22px;">
    @yield('content')
</div>

{{-- footer --}}
@includeTheme('layouts.footer')

{{-- load all javascript --}}
@include('vendor.view.javascript')
</body>
</html>