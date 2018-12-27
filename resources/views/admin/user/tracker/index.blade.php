{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="header">{{ $view_title }}</h1>
                @include('admin.user.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.user.tracker.index') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Location</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($tracks as $row)
                        <tr id="parent_tr_{{$row->id}}">
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->full_name }}</td>
                            <td><a href="https://www.google.com/maps/?q={{ $row->lat }},{{ $row->lng }}"
                                   target="_blank">View Map</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(!count($tracks))
                    <h3 class="text-center"><i class="far fa-frown"></i> No Location Log.</h3>
                @endif

                {{$tracks->appends($request->all())->render()}}
            </div>
        </div>
    </div>
@endsection