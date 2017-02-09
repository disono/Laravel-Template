@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container has-header">
        <div class="row">
            <div class="container text-center">
                <div class="col-md-12">
                    <h1>Error</h1>

                    {{-- errors --}}
                    @if($errors)
                        @if ($errors->has('message'))
                            <h3 class="text-danger">{{ $errors->first('message') }}</h3>
                        @endif
                    @endif

                    {{-- message error --}}
                    @if(isset($message))
                        <h3 class="text-danger">{{ $message }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection