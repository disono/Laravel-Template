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
                <form method="get" action="{{ route('admin.user.tracker.list') }}">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar')

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="full_name"
                                           placeholder="Name" value="{{ $request->get('full_name') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="ip_address"
                                           placeholder="IP Address" value="{{ $request->get('ip_address') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="location"
                                           placeholder="Location (Lat/Lng)" value="{{ $request->get('location') }}"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($tracks as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->full_name }}</td>
                                    <td>{{ $row->ip_address }}</td>
                                    <td><a href="{{ $row->location }}" target="_blank">View to Google Map</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @include('vendor.app.pagination', ['_lists' => $tracks])
            </div>
        </div>
    </div>
@endsection