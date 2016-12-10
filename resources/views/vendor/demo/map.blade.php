{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <input type="text" value="" id="search_location">
                <div class="map text-center" id="demoMap">Map is loading...</div>
            </div>
        </div>
    </div>
@endsection