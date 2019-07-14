{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @includeTheme('user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9">
                <div class="p-3 shadow-sm rounded bg-white border-0">
                    <h3>
                        {{ $view_title }}
                        @if(__settings('phoneVerification')->value === 'enabled')
                            @if($phone->is_verified)
                                <span class="badge badge-success font-weight-light font-size-sm">Verified</span>
                            @else
                                <span class="badge badge-danger font-weight-light font-size-sm">Unverified</span>
                            @endif
                        @endif
                    </h3>
                    <hr>

                    <form action="{{ route('module.user.setting.phone.update') }}" method="post"
                          v-on:submit.prevent="onFormUpload">
                        {{ csrf_field() }}

                        <input type="hidden" value="{{ $phone->id }}" name="id">

                        <div class="form-group">
                            <label for="phone">Phone Number <strong class="text-danger">*</strong></label>

                            <input id="phone" type="text"
                                   class="form-control{{ hasInputError($errors, 'phone') }}"
                                   name="phone" value="{{ old('phone', $phone->phone) }}"
                                   data-validate="required|numeric">

                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>

                        @if(__settings('phoneVerification')->value === 'enabled' && !$phone->is_verified)
                            <div class="form-group">
                                <label for="verify_code">Verification Code</label>

                                <input id="verify_code" type="text"
                                       placeholder="Received verification code"
                                       class="form-control{{ hasInputError($errors, 'verify_code') }}"
                                       name="verify_code"
                                       value="{{ old('verify_code') }}">

                                @if ($errors->has('verify_code'))
                                    <div class="invalid-feedback">{{ $errors->first('verify_code') }}</div>
                                @endif
                            </div>
                        @endif

                        <hr>
                        <button type="submit" class="btn btn-raised btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection