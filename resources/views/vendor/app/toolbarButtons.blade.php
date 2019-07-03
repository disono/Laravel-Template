{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="btn-group" role="group">
    {{-- Add Button --}}
    @if(isset($createRoute))
        <a href="{{ route($createRoute) }}" class="btn btn-light"><i class="fas fa-plus"></i> Add</a>
    @endif

    {{-- Delete Button --}}
    @if(isset($toolbarHasDel))
        <button v-if="toolbar.selectedItems.length > 0" type="button" class="btn btn-danger"
                v-on:click="toolbarDeleteSelected(toolbar.selectedItems)">
            <i class="fas fa-trash"></i> Delete
        </button>
        <button v-if="toolbar.selectedItems.length <= 0" type="button" class="btn btn-light" disabled>
            <i class="fas fa-trash"></i> Delete
        </button>
    @endif
</div>