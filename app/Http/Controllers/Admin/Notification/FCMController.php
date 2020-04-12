<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\FCMStore;
use App\Http\Requests\Admin\Notification\FCMUpdate;
use App\Models\Vendor\Facades\FirebaseNotification;
use App\Models\Vendor\Facades\FirebaseToken;
use Exception;

class FCMController extends Controller
{
    protected $viewType = 'admin';
    private $_firebaseNotification;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'notification.fcm';
        $this->_firebaseNotification = FirebaseNotification::self();

        $this->middleware(function ($request, $next) {
            $this->setAppView('app_scripts', [
                'assets/js/vue/fcm.admin.js'
            ]);

            return $next($request);
        });
    }

    public function indexAction()
    {
        $this->setHeader('title', 'FCM Notifications');
        $this->_firebaseNotification->enableSearch = true;
        $this->_firebaseNotification->setWritableColumn('created_at');
        return $this->view('index', [
            'notifications' => $this->_firebaseNotification->fetch(requestValues('search|pagination_show|title|created_at'))
        ]);
    }

    public function tokenListAction()
    {
        return $this->json(FirebaseToken::fetchAll(requestValues('search', ['limit' => 30, 'is_expired' => 0])));
    }

    public function createAction()
    {
        if (__settings('fcm')->value !== 'enabled') {
            abort(409);
        }

        $this->setHeader('title', 'Send New FCM Notification');
        return $this->view('create', ['fcmTopics' => explode(',', __settings('fcmTopics')->input_value)]);
    }

    public function storeAction(FCMStore $request)
    {
        if (__settings('fcm')->value !== 'enabled') {
            abort(409);
        }

        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;

        try {
            $fcm = $this->_firebaseNotification->store($inputs);
            if (!$fcm) {
                return $this->json(['title' => 'Failed to crate a new FCM notification.'], 422, false);
            }

            return $this->json(['redirect' => '/admin/fcm-notification/edit/' . $fcm->id]);
        } catch (Exception $e) {
            return $this->json(['title' => $e->getMessage()], 422, false);
        }
    }

    public function editAction($id)
    {
        if (__settings('fcm')->value !== 'enabled') {
            abort(409);
        }

        $fcm = $this->_firebaseNotification->single($id);
        if (!$fcm) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $fcm->title);
        return $this->view('edit', ['notification' => $fcm]);
    }

    public function updateAction(FCMUpdate $request)
    {
        if (__settings('fcm')->value !== 'enabled') {
            abort(409);
        }

        try {
            $this->_firebaseNotification->edit($request->get('id'), $request->all());
            return $this->json('FCM notification is successfully updated.');
        } catch (Exception $e) {
            return $this->json(['title' => $e->getMessage()], 422, false);
        }
    }

    public function destroyAction($id)
    {
        $this->_firebaseNotification->remove($id);
        return $this->json('FCM notification is successfully deleted.');
    }
}
