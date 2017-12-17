<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;

/**
 * @property string response_type
 */
class MessengerController extends Controller
{
    public function __construct()
    {
        $this->response_type = 'json';
        parent::__construct();
    }

    /**
     * List of message on inbox
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inbox()
    {
        $this->content = Message::fetch([
            'to_id' => authenticated_id(),
            'from_id' => authenticated_id(),
            'group_by' => true
        ]);

        return $this->response();
    }

    /**
     * Reading message from
     *
     * @param $from_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reading($from_id)
    {
        $this->content = Message::fetch([
            'to_id' => authenticated_id(),
            'from_id' => $from_id,
            'reading' => true
        ]);;

        return $this->response();
    }

    /**
     * Group
     *
     * @param $group_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function group($group_id)
    {
        $this->content = Message::fetch([
            'group_id' => $group_id,
            'from_id' => authenticated_id()
        ]);

        return $this->response();
    }

    /**
     * Send message to
     *
     * @param $to_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function send($to_id)
    {
        $content = Message::single(Message::store([
            'message' => $this->request->get('message'),
            'file' => $this->request->file('msg_file'),
            'to_id' => $to_id,
            'from_id' => authenticated_id()
        ]));

        if ($content) {
            $content->sender_type = 'you';
        } else {
            return $this->failed_response('Unable to send your message.', 422);
        }

        $this->content = $content;
        return $this->response();
    }
}
