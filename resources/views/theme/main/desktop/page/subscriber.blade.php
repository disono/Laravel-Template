{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
<div class="row">
    <div class="col-md-12">
        <div id="wb_subscriber" style="padding-bottom: 4%; padding-top: 2%; background: #e3e5e4;">
            <form action="{{url('subscriber/store')}}" method="post">
                {{csrf_field()}}

                <div id="wb_subscriber_scroll">
                    <div class="row">
                        <div class="col-md-12 col-centered">
                            <div class="col-md-12">
                                <h4 style="font-family: 'Oswald', sans-serif; text-transform: uppercase; font-weight: bold">
                                    Subscribe to our mailing list
                                    <small>(* indicates required)</small>
                                </h4>
                            </div>

                            <div class="col-sm-12 col-md-3 form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" value="" name="email" class="required email form-control"
                                       id="mce-EMAIL" placeholder="Email Address*">

                                @if ($errors->has('email'))<p
                                        class="help-block text-danger">{{ $errors->first('email') }}</p>@endif
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" value="" name="first_name" class=" form-control" id="mce-FNAME"
                                       placeholder="First Name">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" value="" name="last_name" class=" form-control" id="mce-LNAME"
                                       placeholder="Last Name">
                            </div>

                            <div class="col-md-3 form-group">
                                <button type="submit"
                                        class="btn btn-danger"
                                        style="width: 100%; margin: 0;">Subscribe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>