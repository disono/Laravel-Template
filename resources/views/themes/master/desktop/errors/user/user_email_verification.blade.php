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
                <h4 class="alert-heading">Email verification is required to proceed.</h4>
                <p>If you din't received any verification link please wait a couple of minutes or check your spam
                    folder.</p>

                {{-- show link to resend code --}}
                @if((new \App\Models\Verification())->isExpired('email') && __me())
                    <a href="{{ route('auth.verify.resend.view', ['type' => 'email']) }}"
                       class="btn btn-primary">Resend Verification</a>
                @endif
            </div>
        </div>
    </div>
@endsection