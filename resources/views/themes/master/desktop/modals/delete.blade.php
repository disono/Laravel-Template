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
    <h5>Deleting an item('s) will permanently remove it from our database.</h5>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light dialogDismiss">
        <i class="fas fa-times"></i> No, Keep Item
    </button>
    <button type="button" class="btn btn-danger dialogConfirm">
        <i class="fas fa-exclamation-triangle"></i> Yes, Delete Item
    </button>
</div>