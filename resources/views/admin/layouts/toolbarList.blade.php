{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="row mt-3" v-if="toolbar.selectedItems.length > 0">
    <div class="col">
        <button class="btn btn-danger" v-on:click="toolbarDeleteSelected(toolbar.selectedItems)">
            <i class="fas fa-trash"></i> Delete Selected
        </button>
    </div>
</div>