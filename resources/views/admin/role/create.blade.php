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
                <h3>Create Role</h3>

                <form action="{{url('admin/role/store')}}" method="post" role="form">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" id="name" placeholder="Name">

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                               name="slug" id="slug"
                               placeholder="Slug">

                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4"
                                  placeholder="Description"></textarea>

                        @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection