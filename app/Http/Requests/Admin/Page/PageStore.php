<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Page;

use App\Http\Requests\Admin\AdminRequest;

class PageStore extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page_category_id' => 'required|exists:page_categories,id',
            'name' => 'required|max:100',
            'content' => 'required|max:100000',
            'summary' => 'max:220',
            'tags' => 'string_list',
            'slug' => 'required|alpha_dash|unique:pages,slug',
            'template' => 'max:100',
            'is_draft' => 'in:0,1',
            'is_email_to_subscriber' => 'in:0,1',
            'post_at' => 'date',
            'expired_at' => 'date',
        ];
    }
}
