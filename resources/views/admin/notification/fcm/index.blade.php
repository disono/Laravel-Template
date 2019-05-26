{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.notification.fcm.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{ route('admin.fcm.notification.list') }}" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['createRoute' => 'admin.fcm.notification.create'])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="title"
                                           placeholder="Title" value="{{ $request->get('title') }}"></th>
                                <th><input type="text" class="form-control form-control-sm date-picker-no-future"
                                           name="created_at" data-form-submit="#frmTableFilter"
                                           placeholder="Date" value="{{ $request->get('created_at') }}"></th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($notifications as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <th>{{ $row->id }}</th>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/fcm-notification/edit/' . $row->id) }}">Edit</a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/fcm-notification/destroy/' . $row->id) }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{ $row->id }}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @include('vendor.app.pagination', ['_lists' => $notifications])
            </div>
        </div>
    </div>
@endsection