{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-xs-offset-0 col-md-offset-3">
                <div class="app-container">
                    <h3 class="page-header">Edit Authorization</h3>

                    <form action="{{url('admin/authorization/update')}}" method="post" role="form">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$authorization->id}}" name="id">

                        <div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{old('name', $authorization->name)}}" placeholder="Name">

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('identifier') ? ' is-invalid' : '' }}">
                            <label for="identifier">Identifier/Access Route*</label>
                            <select name="identifier" id="identifier" class="form-control">
                                <option value="">Select Identifier</option>
                                @foreach($route_names as $key => $value)
                                    <option value="{{$key}}" {{is_selected($key, old('identifier', $authorization->identifier))}}>{{$value}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('identifier'))
                                <div class="invalid-feedback">{{ $errors->first('identifier') }}</div>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' is-invalid' : '' }}">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Description">{{old('description', $authorization->description)}}</textarea>

                            @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection