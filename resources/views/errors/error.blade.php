@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Whoops, looks like something went wrong.</h1>

                {{-- message error --}}
                @if(isset($message))
                    <p class="alert alert-danger" role="alert">{{ $message }}</p>
                @endif

                {{-- message error --}}
                @if(isset($error_message))
                    <p class="alert alert-danger" role="alert">{{ $error_message }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection