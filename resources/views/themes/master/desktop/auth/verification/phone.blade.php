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
                <div class="alert alert-light rounded shadow-sm" role="alert">
                    <form action="{{ route('auth.verify.phone.process') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="token">Verification Code</label>

                            <input type="text" class="form-control" id="token" placeholder="Enter Code" name="token">
                        </div>

                        @if(!__me())
                            <div class="form-group">
                                <label for="phone">Phone Number</label>

                                <input type="tel" class="form-control" id="phone" placeholder="Enter Your Phone Number"
                                       name="phone">
                            </div>
                        @else
                            <input type="hidden" value="{{ __me()->phone }}" name="phone">
                        @endif

                        <button type="submit" class="btn btn-primary">Submit & Verify</button>

                        {{-- show link to resend code --}}
                        @if(\App\Models\Vendor\Facades\Verification::isExpired('phone') || !__me())
                            <a href="{{ route('auth.verify.resend.view', ['type' => 'phone']) }}"
                               class="btn btn-link">Resend Code</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection