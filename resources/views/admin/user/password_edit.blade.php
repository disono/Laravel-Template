{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
                <div class="admin-container">
                    <h3 class="page-header">Reset Password for
                        <small>{{$user->full_name}}</small>
                    </h3>

                    <form action="{{url('admin/user/password/update')}}" method="post" role="form">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$user->id}}">

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                                   value="{{$user->username}}" disabled>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Send e-mail to</label>
                            <input type="email" class="form-control" name="email" id="email"
                                   placeholder="Send e-mail to" value="{{$user->email}}">

                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="New Password">

                            @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Save new password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection