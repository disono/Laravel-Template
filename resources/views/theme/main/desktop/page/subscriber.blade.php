{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}

@if(app_settings('subscriber_form')->value == 'enabled' && !auth()->check() &&
    !request()->routeIs('login') && !request()->routeIs('web_auth_register') && !request()->routeIs('web_auth_password_recover') &&
    !request()->routeIs('web_auth_password_recover') && !request()->routeIs('password.reset') && isset($errors))
    <div class="container">
        <div class="jumbotron jumbotron-sm">
            <div class="col-md-12 text-center">
                <h5>
                    Subscribe to our mailing list
                    <small>(* indicates required)</small>
                </h5>
            </div>

            <form action="{{url('subscriber/store')}}" method="POST" class="form-inline"
                  v-on:submit.prevent="onFormPost">
                {{csrf_field()}}

                <div class="form-row" style="width: 100% !important;">
                    <div class="col-md-3">
                        <input type="email" value="" name="email"
                               class="form-control {{ $errors->has('email') ? ' is-invalid invalid' : '' }}"
                               placeholder="Email Address*" data-validate="required|email">

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <input type="text" value="" name="first_name" class=" form-control "
                               placeholder="First Name">
                    </div>

                    <div class="col-md-3">
                        <input type="text" value="" name="last_name" class=" form-control "
                               placeholder="Last Name">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger btn-block z-depth-0 red darken-2">Subscribe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif