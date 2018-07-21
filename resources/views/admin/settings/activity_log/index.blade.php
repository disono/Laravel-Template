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

                @include('admin.settings.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.activityLog.index') }}">
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
                @foreach($logs as $log)
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title"># {{ $log->table_id }} - {{ $log->table_name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ humanDate($log->created_at) }}</h6>

                            <p class="card-text">
                            <table class="table">
                                <tbody>
                                @foreach($log->content as $key => $value)
                                    <tr>
                                        <th>{{ strtoupper($key) }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th>Reason</th>
                                    <td>{{ ($log->reason) ? $log->reason : 'n/a' }}</td>
                                </tr>
                                </tbody>
                            </table>
                            </p>
                        </div>
                    </div>
                @endforeach

                @if(!count($logs))
                    <h3 class="text-center mt-3"><i class="far fa-frown"></i> No Activity Logs Created.</h3>
                @endif

                {{$logs->appends($request->all())->render()}}
            </div>
        </div>
    </div>
@endsection