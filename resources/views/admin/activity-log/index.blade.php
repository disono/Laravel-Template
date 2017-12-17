{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Activity Logs</h3>

                {{-- search options --}}
                <form action="" method="get" role="form" class="form-inline mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="col">
                            <select class="form-control" name="source_type">
                                <option value="">Select Source</option>
                                @foreach(\App\Models\ActivityLog::source_type() as $row)
                                    <option value="{{$row}}" {{ ($request->get('source_type') == $row) ? 'selected' : '' }}>
                                        {{$row}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-12">
                        @include('admin.activity-log.template-list', ['logs' => $logs])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection