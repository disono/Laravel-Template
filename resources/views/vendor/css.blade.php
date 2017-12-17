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

{{-- Google Fonts Here --}}

{{-- Guest Styles --}}
@if(!isset($load_admin_styles))
    @foreach([
        asset('assets/css/vendor.css') . url_ext(),
        asset('assets/css/theme.css') . url_ext()
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{{$href}}">
    @endforeach
@endif

{{-- Admin Styles --}}
@if(isset($load_admin_styles))
    @foreach([
        asset('assets/css/vendor.css') . url_ext(),
        asset('assets/css/admin.css') . url_ext(),
        asset('assets/css/theme.css') . url_ext()
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{{$href}}">
    @endforeach
@endif