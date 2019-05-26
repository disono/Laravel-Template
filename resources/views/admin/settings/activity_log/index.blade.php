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
                <form method="get" action="{{ route('admin.activityLog.list') }}">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
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
                                        <th class="w-50">{{ strtoupper($key) }}</th>
                                        <td class="w-50">{{ $value }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th class="w-50">Reason</th>
                                    <td class="w-50">{{ ($log->reason) ? $log->reason : 'n/a' }}</td>
                                </tr>
                                </tbody>
                            </table>
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="mt-3">
                    @include('vendor.app.pagination', ['_lists' => $logs])
                </div>
            </div>
        </div>
    </div>
@endsection