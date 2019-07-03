{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container text-center">
        <div class="row p-3 rounded shadow-sm bg-white">
            <div class="col-md-8 col-sm-12 mr-auto ml-auto">
                <h4 class="alert-heading">Phone number verification is required to proceed.</h4>
                <p>To verify your phone number follow this page and steps <a
                            href="{{ route('auth.verify.phone') }}">Verify Phone # Now.</a></p>
            </div>
        </div>
    </div>
@endsection