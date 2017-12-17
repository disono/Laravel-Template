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
                <h3>Reset Password for
                    <small>{{$user->full_name}}</small>
                </h3>

                <form action="{{url('admin/user/password/update')}}" method="post" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$user->id}}">

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                               value="{{old('username', $user->username)}}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="email">Send e-mail to</label>
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" id="email"
                               placeholder="Send e-mail to" value="{{old('email', $user->email)}}">

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" id="password"
                               placeholder="New Password">

                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save new password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection