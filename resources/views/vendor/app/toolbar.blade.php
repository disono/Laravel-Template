{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@php
    $_toolbarOffSetSize = '';
@endphp

<div class="row mb-3">
    @if(isset($csvSource))
        <div class="col-md-5 col-sm-12 mb-3 mb-sm-0">
            @include('vendor.menuCSV', ['csvSource' => $csvSource])
        </div>
    @else
        @php
            $_toolbarOffSetSize = 'offset-md-5';
        @endphp
    @endif

    {{-- Buttons --}}
    <div class="col-md-3 {{ $_toolbarOffSetSize }} col-sm-12 mb-3 mb-sm-0 text-right">
        @include('vendor.app.toolbarButtons')
    </div>

    {{-- Per Page Option --}}
    <div class="col-md-1 col-sm-12 mb-3 mb-sm-0">
        <select class="form-control select_picker"
                data-style="btn-blue-50"
                name="pagination_show"
                @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
            <option value="12" {{ frmIsSelected('pagination_show', 12) }}>12</option>
            <option value="24" {{ frmIsSelected('pagination_show', 24) }}>24</option>
            <option value="36" {{ frmIsSelected('pagination_show', 36) }}>36</option>
            <option value="48" {{ frmIsSelected('pagination_show', 48) }}>48</option>
            <option value="100" {{ frmIsSelected('pagination_show', 100) }}>100</option>
        </select>
    </div>

    {{-- Global Search --}}
    <div class="col-md-3 col-sm-12 mb-3 mb-sm-0">
        <input type="text" name="search"
               value="{{ $request->get('search') }}" class="form-control" placeholder="Search Records...">
    </div>
</div>