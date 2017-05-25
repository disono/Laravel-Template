<?php

namespace App\Http\Controllers\Web\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->response_type = 'json';
        parent::__construct();
    }

    /**
     * View container
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->response_type = 'web';

        return $this->response('messaging.container');
    }

    /**
     * List of message on inbox
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inbox()
    {
        $me = me();

        $this->content = Message::get([
            'to_id' => $me->id,
            'from_id' => $me->id,
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
        $this->response_type = 'web';
        $me = me();

        $messages = Message::get([
            'to_id' => $me->id,
            'from_id' => $from_id,
            'reading' => true
        ]);;

        if ($this->request->ajax()) {
            $this->content = $messages;
        } else {
            $this->content['from_id'] = $from_id;
        }

        return $this->response('messaging.container');
    }

    /**
     * Group
     *
     * @param $group_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function group($group_id)
    {
        $me = me();

        $this->content = Message::get([
            'group_id' => $group_id,
            'from_id' => $me->id
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
            'from_id' => me()->id
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
