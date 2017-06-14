{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="app-container">
            <h2 class="page-header">Dashboard</h2>

            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="jumbotron text-center">
                        <h4>Active Users</h4>
                        <h2 id="totalActiveUsers">0</h2>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="jumbotron text-center">
                        <h4>InActive Users</h4>
                        <h2 id="totalInActiveUsers">0</h2>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="jumbotron text-center">
                        <h4>Subscriber</h4>
                        <h2 id="totalSubscriber">0</h2>
                    </div>
                </div>
            </div>

            <canvas id="chartPageViews" style="width: 100%; height: 420px;"></canvas>
        </div>
    </div>
@endsection