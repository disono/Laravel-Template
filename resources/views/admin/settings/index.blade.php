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
                    <h3 class="page-header">Settings</h3>

                    <form action="{{url('admin/setting/update')}}" method="post" role="form">
                        {{csrf_field()}}

                        @foreach($settings as $row)
                            @if($row->key == 'ecommerce_support')
                                <div class="form-group {{ $errors->has($row->key) ? ' has-error' : '' }}">
                                    <label for="{{$row->key}}">{{$row->name}}</label>
                                    <select name="" id="" class="form-control">
                                        <option value="enabled" {{is_selected($row->value, 'enabled')}}>enabled</option>
                                        <option value="disabled" {{is_selected($row->value, 'disabled')}}>disabled</option>
                                    </select>

                                    @if ($errors->has($row->key))
                                        <span class="help-block"><strong>{{ $errors->first($row->key) }}</strong></span>
                                    @endif
                                </div>
                            @else
                                <div class="form-group {{ $errors->has($row->key) ? ' has-error' : '' }}">
                                    <label for="{{$row->key}}">{{$row->name}}</label>
                                    <input type="text" class="form-control" name="{{$row->key}}" id="{{$row->key}}"
                                           value="{{$row->value}}" placeholder="{{$row->name}}">

                                    @if ($errors->has($row->key))
                                        <span class="help-block"><strong>{{ $errors->first($row->key) }}</strong></span>
                                    @endif
                                </div>
                            @endif
                        @endforeach

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection