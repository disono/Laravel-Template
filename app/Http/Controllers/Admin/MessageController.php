<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->view = 'message.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @return mixed
     */
    public function index()
    {
        $this->title = 'Messages';
        $this->content['message'] = Message::fetch();
        return $this->response('index');
    }

    /**
     * Show message
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->title = 'Reading Message';
        $this->content['message'] = Message::single($id);
        return $this->response('show');
    }
}
