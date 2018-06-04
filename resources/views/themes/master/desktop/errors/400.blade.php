{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-12"><h1>{{ exceptionMessages('BAD_REQUEST') }}</h1></div>
        </div>
    </div>
@endsection