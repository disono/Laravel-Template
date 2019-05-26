{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@if(isset($toolbarHasDel))
    <div class="btn-group" role="group">
        {{-- Delete Button --}}
        @if(isset($toolbarHasDel))
            <button v-if="toolbar.selectedItems.length > 0" type="button" class="btn btn-danger"
                    v-on:click="toolbarDeleteSelected(toolbar.selectedItems)">
                <i class="fas fa-trash"></i>
            </button>
            <button v-if="toolbar.selectedItems.length <= 0" type="button" class="btn btn-light" disabled>
                <i class="fas fa-trash"></i>
            </button>
        @endif
    </div>
@endif