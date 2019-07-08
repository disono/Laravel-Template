{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="form-row justify-content-end">
    @if(isset($csvSource))
        <div class="col-auto mb-3">
            @include('vendor.menuCSV', ['csvSource' => $csvSource])
        </div>
    @endif

    <div class="col-auto mb-3">
        @include('vendor.app.toolbarButtons')
    </div>

    <div class="col-auto mb-3">
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

    <div class="col-auto mb-3">
        <input type="text" name="search"
               value="{{ $request->get('search') }}" class="form-control" placeholder="Search Records...">
    </div>
</div>