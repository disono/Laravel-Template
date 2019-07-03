{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <h3 class="mb-3 font-weight-bold">{{ $view_title }}</h3>

    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                @include('admin.settings.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.setting.browse') }}" method="get" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['createRoute' => 'admin.setting.create', 'toolbarHasDel' => true])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                {!! thDelete() !!}

                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="name"
                                           placeholder="Name" value="{{ $request->get('name') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="description"
                                           placeholder="Description" value="{{ $request->get('description') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="value"
                                           placeholder="Current Value" value="{{ $request->get('value') }}"></th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($settings as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    {!! tdDelete($row->id) !!}

                                    <th>{{ $row->id }}</th>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ str_limit($row->description, 22) }}</td>
                                    <td>{{ str_limit($row->original_value, 22) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/setting/application/edit/' . $row->id) }}">Edit</a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/setting/application/destroy/' . $row->id) }}"
                                                   id="parent_tr_del_{{ $row->id }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @include('vendor.app.pagination', ['_lists' => $settings])
            </div>
        </div>
    </div>
@endsection