{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<form action="{{ route('module.report.store') }}" method="post" id="frmPageReport" data-show-loading="no">
    <div class="modal-header">
        <h5 class="modal-title">Submit a Report?</h5>
        <button type="button" class="close dialogDismiss" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="form-group">
            <label for="url_report">URL</label>
            <input type="text" name="url" id="url_report" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="page_report_reason_id_report">What's wrong with this? <strong class="text-danger">*</strong></label>
            <select class="form-control select_picker"
                    data-validate="required"
                    data-style="btn-blue-50"
                    name="page_report_reason_id"
                    id="page_report_reason_id_report">
                <option value="">Select your reason</option>
                @foreach(\App\Models\App\PageReportReason::get() as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description_report">Describe your reason for reporting? <strong class="text-danger">*</strong></label>
            <textarea class="form-control" id="description_report" name="description" rows="3"
                      data-validate="required"></textarea>
        </div>

        <div class="custom-file">
            <input type="file" class="custom-file-input" id="screenshots_report"
                   accept="image/x-png,image/gif,image/jpeg" name="screenshots">
            <label class="custom-file-label" for="screenshots_report">Choose a photo/screenshots (Optional)</label>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default dialogDismiss">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>