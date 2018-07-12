<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\FCMStore;
use App\Http\Requests\Admin\Notification\FCMUpdate;
use App\Models\FirebaseNotification;

class FCMController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'notification.fcm';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'FCM Notifications');
        return $this->view('index', [
            'notifications' => FirebaseNotification::fetch(requestValues('search'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Send New FCM Notification');
        return $this->view('create');
    }

    public function storeAction(FCMStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;

        try {
            $fcm = FirebaseNotification::store($inputs);
            if (!$fcm) {
                return $this->json(['title' => 'Failed to crate a new FCM notification.'], 422, false);
            }

            return $this->json(['redirect' => '/admin/fcm-notification/edit/' . $fcm->id]);
        } catch (\Exception $e) {
            return $this->json(['title' => $e->getMessage()], 422, false);
        }
    }

    public function editAction($id)
    {
        $fcm = FirebaseNotification::single($id);
        if (!$fcm) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $fcm->title);
        return $this->view('edit', ['notification' => $fcm]);
    }

    public function updateAction(FCMUpdate $request)
    {
        try {
            FirebaseNotification::edit($request->get('id'), $request->all());
            return $this->json('FCM notification is successfully updated.');
        } catch (\Exception $e) {
            return $this->json(['title' => $e->getMessage()], 422, false);
        }
    }

    public function destroyAction($id)
    {
        FirebaseNotification::remove($id);
        return $this->json('FCM notification is successfully deleted.');
    }
}
