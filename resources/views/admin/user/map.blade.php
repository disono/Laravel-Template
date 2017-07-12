{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <h2>User Locator <small>(Mobile User ONLY)</small></h2>

        {{-- search options --}}
        <div class="app-container">
            <form action="" method="get" role="form" class="form-inline">
                <div class="form-group">
                    <select class="form-control" name="role" id="locator_role_selector">
                        <option value="">Role</option>
                        @foreach($role as $row)
                            <option value="{{$row->slug}}" {{ ($request->get('role') == $row->slug) ? 'selected' : '' }}>
                                {{$row->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div id="map_user_locator" class="text-center" style="width: 100% !important; height: 620px !important;">Map is Loading...</div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
    asset('assets/js/admin/user_locator.js')
]])