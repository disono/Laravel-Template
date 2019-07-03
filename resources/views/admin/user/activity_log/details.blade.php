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
        <div class="row">
            <div class="col">
                @include('admin.user.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <h4><u>Modified By</u></h4>
                <p>{{ $details->full_name }}</p>

                <h4><u>Reason</u></h4>
                <p>{{ $details->reason ? $details->reason : 'n/a' }}</p>

                <h4><u>Table Details</u></h4>
                <p>Name: {{ $details->table_name }}</p>
                <p>Id: {{ $details->table_id }}</p>

                <h4><u>Affected Columns</u></h4>
                @foreach($details->content as $key => $content)
                    <p>{{ $key }} : {{ $content }}</p>
                @endforeach
            </div>
        </div>
    </div>
@endsection