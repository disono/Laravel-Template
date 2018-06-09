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
                    <h1 class="alert-heading">Email verification is required to proceed.</h1>
                    <p>If you din't received any verification link please wait a couple of minutes or check your spam folder.</p>

                    {{-- show link to resend code --}}
                    @if(\App\Models\Verification::isExpired('email') && __me())
                        <a href="{{ route('auth.verify.resend.view', ['type' => 'email']) }}"
                           class="btn btn-danger">Resend Verification</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection