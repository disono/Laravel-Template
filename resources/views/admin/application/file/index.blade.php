{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row mb-3">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.file.list') }}" method="get" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar')

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="file_name"
                                           placeholder="Filename" value="{{ $request->get('file_name') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="title"
                                           placeholder="Title" value="{{ $request->get('title') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="type" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Type (All)</option>
                                        <option value="video" {{ frmIsSelected('type', 'video') }}>Video</option>
                                        <option value="photo" {{ frmIsSelected('type', 'photo') }}>Photo</option>
                                        <option value="doc" {{ frmIsSelected('type', 'doc') }}>Doc</option>
                                        <option value="file" {{ frmIsSelected('type', 'file') }}>File</option>
                                        <option value="audio" {{ frmIsSelected('type', 'audio') }}>Audio</option>
                                    </select>
                                </th>
                                <th><input type="text" class="form-control form-control-sm date-picker-no-future" name="created_at"
                                           placeholder="Date" data-form-submit="#frmTableFilter"
                                           value="{{ $request->get('created_at') }}"></th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($files as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <td>{{ $row->id }}</td>
                                    <td><a href="{{ $row->path }}" target="_blank">{{ $row->file_name }}</a></td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->type }}</td>
                                    <td>{{ humanDate($row->created_at) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/file/destroy/' . $row->id) }}"
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

                @include('vendor.app.pagination', ['_lists' => $files])
            </div>
        </div>
    </div>
@endsection