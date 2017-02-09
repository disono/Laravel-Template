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
                <div class="app-container">
                    <h3 class="page-header">Settings</h3>

                    <form action="{{url('admin/setting/update')}}" method="post" role="form">
                        {{csrf_field()}}

                        @foreach($settings as $row)
                            @if($row->key == 'ecommerce_support' || $row->key == 'ecommerce_cart' || $row->key == 'ecommerce_branch'
                                || $row->key == 'ecommerce_pos' || $row->key == 'ecommerce_separate_delivery_charge'
                                || $row->key == 'ecommerce_buy_button' &&
                                 $row->type == 'ecommerce')

                                <div class="form-group {{ $errors->has($row->key) ? ' has-error' : '' }}">
                                    <label for="{{$row->key}}">{{$row->name}}</label>
                                    <select name="{{$row->key}}" id="{{$row->key}}" class="form-control">
                                        <option value="enabled" {{is_selected($row->value, 'enabled')}}>enabled</option>
                                        <option value="disabled" {{is_selected($row->value, 'disabled')}}>disabled
                                        </option>
                                    </select>

                                    @if ($errors->has($row->key))
                                        <span class="help-block"><strong>{{ $errors->first($row->key) }}</strong></span>
                                    @endif
                                </div>

                            @elseif($row->key == 'ecommerce_default_branch_shop')
                                <div class="form-group {{ $errors->has($row->key) ? ' has-error' : '' }}">
                                    <label for="{{$row->key}}">{{$row->name}} (*If Branch Enabled)</label>
                                    <select name="{{$row->key}}" id="{{$row->key}}" class="form-control">
                                        <option value="">Select Branch</option>
                                        @foreach(\App\Models\ECommerce\Branch::getAll() as $row_branch)
                                            <option value="{{$row_branch->id}}" {{is_selected($row->value, $row_branch->id)}}>{{$row_branch->name}}</option>
                                        @endforeach
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