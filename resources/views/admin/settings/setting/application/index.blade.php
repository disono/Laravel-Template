{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="header">{{ $view_title }}</h1>

                @include('admin.settings.menu')
                @include('admin.settings.setting.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.setting.index') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Current Value</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($settings as $row)
                        <tr id="parent_tr_{{$row->id}}">
                            <th>{{ $row->id }}</th>
                            <td>{{ $row->name }}</td>
                            <td>{{ str_limit($row->description, 22) }}</td>
                            <td>{{ str_limit($row->original_value, 22) }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                           href="{{ url('admin/setting/application/edit/' . $row->id) }}">Edit</a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item"
                                           href="{{ url('admin/setting/application/destroy/' . $row->id) }}"
                                           v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(!count($settings))
                    <h3 class="text-center"><i class="far fa-frown"></i> No Settings Added.</h3>
                @endif

                {{$settings->appends($request->all())->render()}}
            </div>
        </div>
    </div>
@endsection