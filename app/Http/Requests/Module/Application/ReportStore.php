<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Module\Application;

use App\Http\Requests\Module\ModuleRequest;

class ReportStore extends ModuleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page_report_reason_id' => 'required|exists:page_report_reasons,id',
            'url' => 'required',
            'description' => 'required',
            'screenshots' => 'image|max:' . __settings('fileSizeLimitImage')->value
        ];
    }
}
