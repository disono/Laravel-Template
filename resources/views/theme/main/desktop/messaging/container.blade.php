{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <input type="hidden" id="inbox_from_id" value="{{(isset($from_id)) ? $from_id : 0}}">

    <div class="container has-header">
        <div class="row">
            <div class="col-xs-12 col-md-3">
                <h4 class="text-center page-header">Inbox</h4>

                @include(current_theme() . 'messaging.inbox')
            </div>

            <div class="col-xs-12 col-md-9">
                <h4 class="text-center page-header">Messages</h4>

                @include(current_theme() . 'messaging.reading')
            </div>
        </div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
    asset('assets/js/message.js')
]])

