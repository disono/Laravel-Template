@if(isset($scripts))
    {{-- let's load the vendors javascript on session --}}
    <?php js_view_loader($scripts) ?>
@elseif(isset($js_run))
    @foreach(js_view_runner() as $script_load)
        <script src="{{$script_load . url_ext()}}"></script>
    @endforeach

    <script>
        setTimeout(function () {
            console.log('All script is loaded.');

            if (document.querySelector('#WBMainApp')) {
                document.querySelector('#WBMainApp').style.display = null;
            }

            if (document.getElementById("loaderContent")) {
                document.getElementById("loaderContent").remove();
                document.getElementById("loaderStyles").remove();
            }
        }, 300);
    </script>

    {{-- Google Analytics --}}
@endif
