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
            <div class="col-md-8 col-sm-12 mr-auto ml-auto">
                <div class="alert alert-warning" role="alert">
                    <h1 class="alert-heading">Your account has been disabled.</h1>
                    <p>If you have any questions or concerns, you can visit our FAQ's or contact us.</p>
                </div>
            </div>
        </div>
    </div>
@endsection