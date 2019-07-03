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
                @include('admin.user.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.activityLog.browse') }}" method="get" class="mb-3" id="frmTableFilter">
                    <input type="submit" style="display: none;">
                    @include('vendor.app.toolbar', ['toolbarHasDel' => true])

                    <table class="table table-bordered">
                        <thead class="table-borderless bg-light">
                        <tr>
                            {!! thDelete() !!}

                            <th>Column Id #</th>
                            <th><input type="text" class="form-control form-control-sm" name="full_name"
                                       placeholder="Modified By" value="{{ $request->get('full_name') }}"></th>
                            <th><input type="text" class="form-control form-control-sm" name="table_name"
                                       placeholder="Table Name" value="{{ $request->get('table_name') }}"></th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($logs as $row)
                            <tr id="parent_tr_{{ $row->id }}">
                                {!! tdDelete($row->id) !!}

                                <td>{{ $row->table_id }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->table_name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                               href="{{ url('admin/activity-logs/details/' . $row->id) }}">View</a>

                                            <a class="dropdown-item"
                                               href="{{ url('admin/activity-logs/destroy/' . $row->id) }}"
                                               id="parent_tr_del_{{ $row->id }}"
                                               v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>

                <div class="mt-3">
                    @include('vendor.app.pagination', ['_lists' => $logs])
                </div>
            </div>
        </div>
    </div>
@endsection