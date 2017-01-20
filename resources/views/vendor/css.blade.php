{{-- load all CSS --}}
<script>
    {{-- load the vendor CSS --}}
    onloadCSS(loadCSS('{{ asset('assets/css/vendor.css') . url_ext() }}'), function () {
        {{-- load the style for app --}}
        onloadCSS(loadCSS('{{ asset('assets/css/main.css') . url_ext() }}'), function () {
            document.querySelector('#WBMainApp').style.display = null;
            document.getElementById("loaderContent").remove();
            document.getElementById("loaderStyles").remove();

                {{-- load custom CSS --}}
                @if(isset($styles))
            [
                @foreach($styles as $style)
                    '{!! asset($style) . url_ext() !!}',
                @endforeach
            ].forEach(function (href) {
                var script = document.createElement('link');
                script.href = href;
                script.async = false;
                document.head.appendChild(script);
            });
            @endif
        });
    });
</script>

{{-- if no javascript load the CSS normally --}}
<noscript>
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') . url_ext() }}"/>
    @if(env('APP_DEBUG'))
        <link rel="stylesheet" href="{{ asset('assets/css/animated-loading.css') . url_ext() }}"/>
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') . url_ext() }}"/>
    @endif
</noscript>