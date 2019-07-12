{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-12 mr-auto ml-auto">
                @if(session('message'))
                    <div class="alert alert-success mb-3 rounded shadow-sm" role="alert">
                        <strong>{{ session('message') }}</strong>
                    </div>
                @endif

                <div class="alert alert-light rounded shadow-sm" role="alert">
                    <form action="{{ route('auth.verify.resend', ['type' => $type]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary">
                            Resend your new code for {{ $type }} - ({{ __me()->$type }}).
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection