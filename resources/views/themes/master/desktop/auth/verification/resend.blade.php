{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-12 mr-auto ml-auto">
                @if(session('message'))
                    <div class="alert alert-success mb-3" role="alert">
                        <strong>{{ session('message') }}</strong>
                    </div>
                @endif

                <div class="alert alert-warning" role="alert">
                    <form action="{{ route('auth.verify.resend', ['type' => $type]) }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type_value">Enter your {{ $type }}</label>

                            <input type="text" class="form-control{{ session('error') ? ' is-invalid invalid' : '' }}"
                                   id="type_value" name="type_value" placeholder="Enter {{ $type }}">

                            @if(session('error'))
                                <div class="invalid-feedback">{{ ucfirst(session('error')) }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Resend</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection