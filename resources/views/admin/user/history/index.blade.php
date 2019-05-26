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
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.user.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{ route('admin.user.authentication.history') }}" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar')

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="ip"
                                           placeholder="IP" value="{{ $request->get('ip') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="platform"
                                           placeholder="Platform" value="{{ $request->get('platform') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="type" data-style="btn-gray"
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
                                <th><input type="text" class="form-control form-control-sm date-picker-no-future" name="created_at"
                                           placeholder="Date Created" data-form-submit="#frmTableFilter"
                                           value="{{ $request->get('created_at') }}"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($histories as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->ip }}</td>
                                    <td>{{ $row->platform }}</td>
                                    <td>{{ $row->type }}</td>
                                    <td>{{ $row->lat }}</td>
                                    <td>{{ $row->lng }}</td>
                                    <td>{{ humanDate($row->created_at) }}</td>
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