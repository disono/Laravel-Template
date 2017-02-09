{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="source_type">
                                <option value="">Select Source</option>
                                @foreach(\App\Models\ActivityLog::source_type() as $row)
                                    <option value="{{$row}}" {{ ($request->get('source_type') == $row) ? 'selected' : '' }}>
                                        {{$row}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                @include('admin.activity-log.template-list', ['logs' => $logs])
            </div>
        </div>
    </div>
@endsection