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
                    <h3>{{ $view_title }}</h3>
                    <hr>

                    <form action="{{ route('user.setting.phone.store') }}" method="post"
                          v-on:submit.prevent="onFormUpload">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="phone">Phone Number <strong class="text-danger">*</strong></label>

                            <input id="phone" type="text"
                                   class="form-control{{ hasInputError($errors, 'phone') }}"
                                   name="phone" value="{{ old('phone') }}" data-validate="required|numeric">

                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-raised btn-primary">Add new phone number</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection