{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@php
if (isset($request)) {
    $_toTotalPage = $request->get('page') + $request->get('pagination_show');
}
@endphp

@if(count($_lists))
    <div class="row">
        <div class="col">
            Showing {{ $request->get('page') ? $request->get('page') : 1 }}
            to {{ ($_toTotalPage) ? $_toTotalPage : count($_lists) }}
            of {{ $_lists->total_records }} records.
        </div>

        <div class="col">
            {{ $_lists->appends($request->all())->render() }}
        </div>
    </div>
@else
    <h3 class="text-center"><i class="far fa-frown"></i> No Records Found.</h3>
@endif