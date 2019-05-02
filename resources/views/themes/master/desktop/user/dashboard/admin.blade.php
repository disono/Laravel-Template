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
                <h3>Dashboard</h3>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-4">{{ \App\Models\User::fetch(['object' => true, 'is_account_activated' => 1])->count() }}</h4>
                    <p class="lead">Total number of activated accounts/users.</p>
                    <h2>Active Users</h2>
                </div>
            </div>

            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-4">{{ \App\Models\User::fetch(['object' => true, 'is_account_activated' => 0])->count() }}</h4>
                    <p class="lead">Total number of deactivated accounts/users.</p>
                    <h2>In-Active Users</h2>
                </div>
            </div>
        </div>
    </div>
@endsection