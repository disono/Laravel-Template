{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row m-auto">
            <div class="col-sm-12 col-lg-3 mt-3 h-50 p-0 mb-3 rounded shadow-sm bg-white">
                @include(currentTheme() . 'chat.inbox')
            </div>

            <div class="col-sm-12 col-lg-9 mt-3 p-0 h-50">
                @include(currentTheme() . 'chat.messages')
            </div>
        </div>
    </div>

    @include(currentTheme() . 'chat.modals')
@endsection