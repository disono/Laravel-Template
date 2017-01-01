{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container-fluid has-header" style="height: 500px !important;">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <input type="text" id="searchBox" placeholder="Search...">
                <button id="deleteMarkers">Delete Markers</button>

                <div id="leafMap" class="text-center" style="height: 500px !important;">Map is loading</div>
            </div>
        </div>
    </div>
@endsection