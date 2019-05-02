{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="modal-header">
    <h5 class="modal-title">Delete Item?</h5>
    <button type="button" class="close dialogDismiss" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body text-center">
    Deleting a item will permanently remove it from our database.
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default dialogDismiss">No, Keep Item</button>
    <button type="button" class="btn btn-danger dialogConfirm">Yes, Delete Item</button>
</div>