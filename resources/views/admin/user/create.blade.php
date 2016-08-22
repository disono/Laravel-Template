{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="admin-container">
                    <h3 class="page-header">Create New User</h3>

                    <form action="{{url('admin/user/store')}}" method="post" role="form" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label for="image">Upload Image/Avatar</label>

                                    <input type="file" id="image" name="image" class="form-control">

                                    @if ($errors->has('image'))
                                        <span class="help-block">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name">First Name*</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                           placeholder="First Name" value="{{old('first_name')}}">

                                    @if ($errors->has('first_name'))
                                        <span class="help-block">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name">Last Name*</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                           placeholder="Last Name" value="{{old('last_name')}}">

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">{{ $errors->first('last_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone">Phone*</label>
                                    <input type="tel" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{old('phone')}}">

                                    @if ($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                    <label for="birthday">Birthday*</label>

                                    <input type="text" class="form-control date-picker" name="birthday"
                                           value="{{ old('birthday') }}">

                                    @if ($errors->has('birthday'))
                                        <span class="help-block">{{ $errors->first('birthday') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username">Username*</label>
                                    <input type="text" class="form-control" name="username" id="username"
                                           placeholder="Username" value="{{old('username')}}">

                                    @if ($errors->has('username'))
                                        <span class="help-block">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">E-mail*</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           placeholder="E-mail" value="{{old('email')}}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">Password*</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                           placeholder="Password" value="">

                                    @if ($errors->has('password'))
                                        <span class="help-block">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
                                    <label for="role">Role*</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Select Role</option>
                                        @foreach($roles as $row)
                                            <option value="{{$row->slug}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('role'))
                                        <span class="help-block">{{ $errors->first('role') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('email_confirmed') ? ' has-error' : '' }}">
                                    <div class="checkbox">
                                        <input type="checkbox" id="email_confirmed" name="email_confirmed" value="1">
                                        <label for="email_confirmed">
                                            Confirmed E-mail
                                        </label>
                                    </div>

                                    @if ($errors->has('email_confirmed'))
                                        <span class="help-block">{{ $errors->first('email_confirmed') }}</span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Create new user</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection