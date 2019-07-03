{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <h3 class="font-weight-bold">Dashboard</h3>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="jumbotron shadow-sm p-3 bg-white rounded-lg">
                    <h4 class="display-4">{{ $count_active_members }}</h4>
                    <p class="lead">Total number of activated accounts.</p>
                    <h2>Active Users</h2>
                </div>
            </div>

            <div class="col">
                <div class="jumbotron shadow-sm p-3 bg-white rounded-lg">
                    <h4 class="display-4">{{ $count_inactive_members }}</h4>
                    <p class="lead">Total number of deactivated accounts.</p>
                    <h2>In-Active Users</h2>
                </div>
            </div>

            <div class="col">
                <div class="jumbotron shadow-sm p-3 bg-white rounded-lg">
                    <h4>New Members</h4>
                    <hr>

                    @foreach($latest_members as $member)
                        <div class="media mb-3">
                            <img src="{{ $member->profile_picture }}"
                                 class="mr-3 rounded-circle shadow-sm image-sm"
                                 alt="{{ $member->full_name }}">
                            <div class="media-body">
                                <h5 class="mt-0">
                                    <a href="{{ url('admin/user/edit/' . $member->id) }}">{{ $member->full_name }}</a>
                                </h5>
                                <p>{{ $member->email }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if(!count($latest_members))
                        <h5 class="text-center">No New Members for pass 10 days.</h5>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="jumbotron shadow-sm p-3 bg-white rounded-lg">
                    <canvas id="myChart" width="100" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        let ctx = document.getElementById('myChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Member Growth',
                    data: [],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection