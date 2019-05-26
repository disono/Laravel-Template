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
    <meta name="description" content="{{ $page_description }}">
    <meta name="keywords" content="{{ $page_keywords }}">
    <meta name="author" content="{{ $page_author }}">

    {{-- ICON --}}
    <link rel="icon" type="image/png" href="{{ url('assets/img/placeholder/favicon.png') }}"/>

    {{-- CSS --}}
    @include('vendor.view.adminStyles')
</head>

<body>
{{-- sidebars --}}
@include('admin.layouts.sidebar')

<main class="container-fluid content-panel" id="{{ $vue_app }}" v-cloak>
    {{-- contents --}}
    @yield('content')

    {{-- footer --}}
    @include('admin.layouts.footer')
</main>

{{-- loading modal --}}
@include(currentTheme() . 'modals.loading')

{{-- load all javascript --}}
@include('vendor.view.javascript')
</body>
</html>