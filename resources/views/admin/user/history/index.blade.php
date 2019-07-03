{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}
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
                <form method="get" action="{{ route('admin.userAuthenticationHistory.browse') }}" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['toolbarHasDel' => true])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                {!! thDelete() !!}

                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="ip"
                                           placeholder="IP" value="{{ $request->get('ip') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="platform"
                                           placeholder="Platform" value="{{ $request->get('platform') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="type"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Type (All)</option>
                                        <option value="login" {{ frmIsSelected('type', 'login') }}>Login</option>
                                        <option value="logout" {{ frmIsSelected('type', 'logout') }}>Logout</option>
                                    </select>
                                </th>
                                <th><input type="text" class="form-control form-control-sm" name="lat"
                                           placeholder="Lat" value="{{ $request->get('lat') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="lng"
                                           placeholder="Lng" value="{{ $request->get('lng') }}"></th>
                                <th><input type="text"
                                           class="form-control form-control-sm date-picker-no-future"
                                           name="created_at"
                                           placeholder="Date Created" data-form-submit="#frmTableFilter"
                                           value="{{ $request->get('created_at') }}"></th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($histories as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    {!! tdDelete($row->id) !!}

                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->ip }}</td>
                                    <td>{{ $row->platform }}</td>
                                    <td>{{ $row->type }}</td>
                                    <td>{{ $row->lat }}</td>
                                    <td>{{ $row->lng }}</td>
                                    <td>{{ $row->formatted_created_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/user/authentication/destroy/' . $row->id) }}"
                                                   id="parent_tr_del_{{ $row->id }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{ $row->id }}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @include('vendor.app.pagination', ['_lists' => $histories])
                </form>
            </div>
        </div>
    </div>
@endsection