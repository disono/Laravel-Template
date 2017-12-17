{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Create New User</h3>

                <form action="{{url('admin/user/store')}}" method="post" role="form" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="image">Upload Image/Avatar</label>
                                <input type="file" id="image" name="image"
                                       class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}">

                                @if ($errors->has('image'))
                                    <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name*</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                       name="first_name" id="first_name"
                                       placeholder="First Name" value="{{old('first_name')}}">

                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name*</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                       name="last_name" id="last_name"
                                       placeholder="Last Name" value="{{old('last_name')}}">

                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone*</label>
                                <input type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                       name="phone" id="phone" placeholder="Phone"
                                       value="{{old('phone')}}">

                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="birthday">Birthday*</label>
                                <input type="text"
                                       class="form-control date-picker{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                                       name="birthday"
                                       value="{{ old('birthday') }}">

                                @if ($errors->has('birthday'))
                                    <div class="invalid-feedback">{{ $errors->first('birthday') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="address">Address*</label>
                                <textarea name="address" id="address"
                                          class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                          rows="4">{{ old('address') }}</textarea>

                                @if ($errors->has('address'))
                                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="username">Username*</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                       name="username" id="username"
                                       placeholder="Username" value="{{old('username')}}">

                                @if ($errors->has('username'))
                                    <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail*</label>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" id="email"
                                       placeholder="E-mail" value="{{old('email')}}">

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password">Password*</label>
                                <input type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" id="password"
                                       placeholder="Password" value="">

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="role">Role*</label>
                                <select name="role" id="role"
                                        class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $row)
                                        <option value="{{$row->slug}}" {{is_selected(old('role', $request->get('role')), $row->slug)}}>{{$row->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('role'))
                                    <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                                @endif
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input{{ $errors->has('email_confirmed') ? ' is-invalid' : '' }}"
                                           type="checkbox" value="1" name="email_confirmed">
                                    Confirmed E-mail
                                </label>

                                @if ($errors->has('email_confirmed'))
                                    <div class="invalid-feedback">{{ $errors->first('email_confirmed') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Create new user
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection