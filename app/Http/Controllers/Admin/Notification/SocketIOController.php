<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Notification\SocketIOStore;
use App\Http\Requests\Admin\Notification\SocketIOUpdate;
use App\Models\Vendor\Facades\SocketNotification;
use App\Models\Vendor\Facades\Token;

class SocketIOController extends BaseController
{
    protected $viewType = 'admin';
    protected $dataName = 'socket_notification';

    protected $requestValuesSearch = 'title|content';
    protected $indexEnableSearch = true;

    protected $allowShow = true;
    protected $allowCreate = true;
    protected $allowEdit = true;
    protected $allowDelete = true;

    protected $createData = [];

    protected $failedCreation = 'Failed to send a new notification.';
    protected $failedUpdate = 'Failed to update notification.';

    protected $afterRedirectUrl = '/admin/socket-io-notifications';

    protected $indexTitle = 'SocketIO Notifications';
    protected $createTitle = 'Send New SocketIO Notification';
    protected $editTitle = 'Updating SocketIO Notification';

    protected $hasOwner = 'user_id';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'notification.io';
        $this->modelName = SocketNotification::self();

        $this->allowShow = __settings('socketIO')->value === 'enabled';
        $this->allowCreate = __settings('socketIO')->value === 'enabled';
        $this->allowEdit = __settings('socketIO')->value === 'enabled';

        $this->createData = ['topics' => explode(',', __settings('socketIOTopics')->input_value)];

        $this->middleware(function ($request, $next) {
            $this->setAppView('app_scripts', [
                'assets/js/vue/io.admin.js'
            ]);

            return $next($request);
        });
    }

    public function tokenListAction()
    {
        return $this->json(Token::fetchAll(requestValues('search', ['limit' => 30, 'is_expired' => 0])));
    }

    public function storeAction(SocketIOStore $request)
    {
        return $this->processStore($request);
    }

    public function updateAction(SocketIOUpdate $request)
    {
        return $this->processUpdate($request);
    }
}
