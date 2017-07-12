{{-- Guest Styles --}}
@if(!isset($load_admin_styles))
    @foreach([
        'https://fonts.googleapis.com/css?family=Lato:400,700',
        asset('assets/css/vendor.css') . url_ext(),
        asset('assets/css/main.css') . url_ext()
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{{$href}}">
    @endforeach
@endif

{{-- Admin Styles --}}
@if(isset($load_admin_styles))
    @foreach([
        asset('assets/css/vendor.css') . url_ext(),
        asset('assets/css/admin.css') . url_ext(),
        asset('assets/css/main.css') . url_ext()
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{{$href}}">
    @endforeach
@endif