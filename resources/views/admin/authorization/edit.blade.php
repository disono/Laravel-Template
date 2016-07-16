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
            <div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
                <div class="admin-container">
                    <h3 class="page-header">Edit Authorization</h3>

                    <form action="{{url('admin/authorization/update')}}" method="post" role="form">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$authorization->id}}" name="id">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{$authorization->name}}" placeholder="Name">

                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('identifier') ? ' has-error' : '' }}">
                            <label for="identifier">Identifier*</label>
                            <select name="identifier" id="identifier" class="form-control">
                                <option value="">Select Identifier</option>
                                @foreach($route_names as $value)
                                    @if($value->getName())
                                        <option value="{{$value->getName()}}"
                                                {{($authorization->identifier == $value->getName()) ? 'selected' : ''}}>{{$value->getName()}}</option>
                                    @endif
                                @endforeach
                            </select>

                            @if ($errors->has('identifier'))
                                <span class="help-block">{{ $errors->first('identifier') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="10"
                                      placeholder="Description">{{$authorization->description}}</textarea>

                            @if ($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection