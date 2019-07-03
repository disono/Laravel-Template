<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Page;

use App\Http\Requests\Admin\AdminRequest;

class PageCategoryUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:page_categories,id',
            'parent_id' => 'integer|exists:page_categories,id',
            'name' => 'required|max:100',
            'description' => 'max:100',
            'slug' => 'required|alpha_dash|unique:page_categories,slug,' . request('id'),
            'img_active' => 'image|max:' . __settings('fileSizeLimitImage')->value,
            'img_inactive' => 'image|max:' . __settings('fileSizeLimitImage')->value,
            'img_banner' => 'image|max:' . __settings('fileSizeLimitImage')->value,
            'is_enabled' => 'in:0,1'
        ];
    }
}
