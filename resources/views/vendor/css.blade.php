{{-- CSS Loaders --}}
<script src="{{ asset('assets/js/lib/loadCSS.js') . url_ext() }}"></script>
<script src="{{ asset('assets/js/lib/onloadCSS.js') . url_ext() }}"></script>

{{-- Guest Styles --}}
@if(!isset($load_admin_styles))
    {{-- load all CSS --}}
    <script>
        {{-- Google Fonts --}}
        onloadCSS(loadCSS('https://fonts.googleapis.com/css?family=Lato:400,700'), function () {
            {{-- load the vendor CSS --}}
            onloadCSS(loadCSS('{{ asset('assets/css/vendor.css') . url_ext() }}'), function () {
                {{-- load the style for app --}}
                onloadCSS(loadCSS('{{ asset('assets/css/main.css') . url_ext() }}'), function () {
                    WBLoadJS();
                });
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
@endif

{{-- Admin Styles --}}
@if(isset($load_admin_styles))
    {{-- load all CSS --}}
    <script>
        {{-- load the vendor CSS --}}
        onloadCSS(loadCSS('{{ asset('assets/css/vendor.css') . url_ext() }}'), function () {
            {{-- load the style for app --}}
            onloadCSS(loadCSS('{{ asset('assets/css/main.css') . url_ext() }}'), function () {
                {{-- load the style for admin --}}
                onloadCSS(loadCSS('{{ asset('assets/css/admin.css') . url_ext() }}'), function () {
                    WBLoadJS();
                });
            });
        });
    </script>

    {{-- if no javascript load the CSS normally --}}
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') . url_ext() }}"/>
        @if(env('APP_DEBUG'))
            <link rel="stylesheet" href="{{ asset('assets/css/main.css') . url_ext() }}"/>
        @endif
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') . url_ext() }}"/>
    </noscript>
@endif

{{-- remove the loader look for at the bottom of assets/js/main.js --}}
<script>
    var WBRemoveLoader = function () {
        if (document.querySelector('#WBMainApp')) {
            document.querySelector('#WBMainApp').style.display = null;
        }

        if (document.getElementById("loaderContent")) {
            document.getElementById("loaderContent").remove();
            document.getElementById("loaderStyles").remove();
        }
    };
</script>