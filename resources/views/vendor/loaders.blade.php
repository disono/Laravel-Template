@if(isset($scripts))
    {{-- let's load the vendors javascript on session --}}
    <?php js_view_loader($scripts) ?>
@endif

@if(isset($js_run))
    @foreach(js_view_runner() as $script_load)
        <script src="{{$script_load . url_ext()}}"></script>
    @endforeach

    {{-- Google Analytics --}}
@endif
