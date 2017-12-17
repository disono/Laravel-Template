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
                <h3>Settings</h3>

                <form action="{{url('admin/setting/update')}}" method="post" role="form">
                    {{csrf_field()}}

                    @foreach($settings as $row)
                        @if($row->input_type == 'select')

                            <div class="form-group">
                                <label for="{{$row->key}}">{{$row->name}}</label>
                                <select name="{{$row->key}}" id="{{$row->key}}"
                                        class="form-control{{ $errors->has($row->key) ? ' is-invalid' : '' }}">
                                    <option value="enabled" {{is_selected($row->value, 'enabled')}}>enabled</option>
                                    <option value="disabled" {{is_selected($row->value, 'disabled')}}>disabled
                                    </option>
                                </select>

                                @if ($errors->has($row->key))
                                    <div class="invalid-feedback">{{ $errors->first($row->key) }}</div>
                                @endif
                            </div>

                        @else

                            <div class="form-group">
                                <label for="{{$row->key}}">{{$row->name}}</label>
                                <input type="text"
                                       class="form-control{{ $errors->has($row->key) ? ' is-invalid' : '' }}"
                                       name="{{$row->key}}" id="{{$row->key}}"
                                       value="{{$row->value}}" placeholder="{{$row->name}}">

                                @if ($errors->has($row->key))
                                    <div class="invalid-feedback">{{ $errors->first($row->key) }}</div>
                                @endif
                            </div>

                        @endif
                    @endforeach

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection