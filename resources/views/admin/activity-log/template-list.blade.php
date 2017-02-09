@if(count($logs))
    @foreach($logs as $row)
        <div class="app-container">
            <table class="table table-hover">
                <thead>
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
        </div>
    @endforeach

    @if(!isset($no_pagination))
        <div class="app-container">
            {{$logs->appends($request->all())->render()}}
        </div>
    @endif
@else
    <h1 class="text-center">No activity log.</h1>
@endif