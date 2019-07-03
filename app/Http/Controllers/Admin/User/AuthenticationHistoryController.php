<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\AuthenticationHistory;

class AuthenticationHistoryController extends Controller
{
    protected $viewType = 'admin';
    private $_authenticationHistory;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.history';
        $this->_authenticationHistory = AuthenticationHistory::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Authentication Histories');
        $this->_authenticationHistory->setWritableColumn('created_at');
        $this->_authenticationHistory->enableSearch = true;
        return $this->view('index', [
            'histories' => $this->_authenticationHistory->fetch(requestValues('search|pagination_show|user_id|ip|platform|type|lat|lng|created_at'))
        ]);
    }

    public function destroyAction($id)
    {
        $this->_authenticationHistory->remove($id);
        return $this->json('Authentication history is successfully deleted.');
    }
}
