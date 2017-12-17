{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@if(count($logs))
    @foreach($logs as $row)
        <table class="table table-hover table-responsive">
            <thead class="thead-dark">
            <tr>
                @foreach($row->content as $key => $value)
                    <th>{{strtoupper($key)}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>

            <tr>
                @foreach($row->content as $key => $value)
                    <td>{{$value}}</td>
                @endforeach
            </tr>

            <tr>
                <td>Source</td>
                <td>{{$row->source_id}} / {{$row->source_type}}</td>
            </tr>

            <tr>
                <td>Reason</td>
                <td>{{$row->reason}}</td>
            </tr>

            <tr>
                <td>Created At</td>
                <td>{{$row->formatted_created_at}}</td>
            </tr>
            </tbody>
        </table>
    @endforeach

    @if(!isset($no_pagination))
        {{$logs->appends($request->all())->render()}}
    @endif
@else
    <h1 class="text-center">No activity log.</h1>
@endif