{{-- Guest Styles --}}
@if(!isset($load_admin_styles))
    @foreach([
        'https://fonts.googleapis.com/css?family=Lato:400,700',
        'assets/css/vendor.css',
        'assets/css/main.css'
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{!!asset($href) . url_ext()!!}">
    @endforeach
@endif

{{-- Admin Styles --}}
@if(isset($load_admin_styles))
    @foreach([
        'assets/css/vendor.css',
        'assets/css/admin.css',
        'assets/css/main.css'
    ] as $href)
        <link rel="stylesheet" type="text/css" href="{!!asset($href) . url_ext()!!}">
    @endforeach
@endif