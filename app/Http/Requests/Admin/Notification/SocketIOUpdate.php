<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Notification;

use App\Http\Requests\Admin\AdminRequest;

class SocketIOUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:socket_notifications,id',
            'title' => 'required|max:100',
            'content' => 'required|max:100',
            'type' => 'required|in:topic,token',
            'topic_name' => 'max:100',
            'token' => 'max:1000'
        ];
    }
}
