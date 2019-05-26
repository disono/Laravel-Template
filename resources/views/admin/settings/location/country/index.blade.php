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
                @include('admin.settings.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.setting.country.list') }}" method="get" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['createRoute' => 'admin.setting.country.create'])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="name"
                                            placeholder="Name" value="{{ $request->get('name') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="code" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Code (All)</option>
                                        @foreach(\App\Models\Country::all() as $country)
                                            <option value="{{ $country->code }}" {{ frmIsSelected('code', $country->code) }}>{{ $country->code }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($countries as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <th>{{ $row->id }}</th>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->code }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/setting/country/edit/' . $row->id) }}">Edit</a>
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/setting/cities?country_id=' . $row->id) }}">Cities</a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/setting/country/destroy/' . $row->id) }}"
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

                @include('vendor.app.pagination', ['_lists' => $countries])
            </div>
        </div>
    </div>
@endsection