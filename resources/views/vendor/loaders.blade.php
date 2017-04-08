@if(isset($scripts))
    {{-- let's load the vendors javascript on session --}}
    <?php js_view_loader($scripts) ?>
@elseif(isset($js_run))
    <script>
            {{-- JS loaders --}}
        [
            @foreach(js_view_runner() as $script_load)
                '{!! asset($script_load) . url_ext() !!}',
            @endforeach
        ].forEach(function (src) {
            var script = document.createElement('script');
            script.src = src;
            script.async = false;
            document.head.appendChild(script);
        });

        {{-- Google Analytics --}}
    </script>
@endif
