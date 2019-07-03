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
        <div class="row mb-3">
            <div class="col">
                @include('admin.settings.menu')
            </div>
        </div>

        <div class="row">
            <div class="col">
                <form action="{{ route('admin.authRole.update') }}" method="post" v-on:submit.prevent="onFormUpload">
                    <input type="hidden" name="role_id" value="{{ $role->id }}">

                    @foreach($routes->chunk(3) as $category)
                        <div class="row mb-3">
                            @foreach($category as $row)
                                <div class="col-md-4 col-sm-12">
                                    <h5>{{ $row['category_name'] }}</h5>
                                    <hr>

                                    @foreach($row['data'] as $route)
                                        <label class="custom-control border-switch">
                                            <input type="checkbox" class="border-switch-control-input"
                                                   name="route_name[]"
                                                   {{ frmIsChecked('route_name', $route->value, $authorizations) }}
                                                   value="{{ $route->value }}">
                                            <span class="border-switch-control-indicator"></span>
                                            <span class="border-switch-control-description">{{ $route->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection