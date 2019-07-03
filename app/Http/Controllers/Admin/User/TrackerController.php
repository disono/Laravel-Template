<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\UserTracker;

class TrackerController extends Controller
{
    protected $viewType = 'admin';
    private $_userTracker;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.tracker';
        $this->_userTracker = UserTracker::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Users Location');
        $this->_userTracker->enableSearch = true;
        return $this->view('index', [
            'tracks' => $this->_userTracker->fetch(requestValues('search|pagination_show|full_name|ip_address|location'))
        ]);
    }

    public function destroyAction($id)
    {
        $this->_userTracker->remove($id);
        return $this->json('User location log is successfully deleted.');
    }
}
