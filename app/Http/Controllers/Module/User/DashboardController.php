<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user';
    }

    /**
     * Show specific dashboard for user
     *
     * @return JsonResponse|Response
     */
    public function showAction()
    {
        if (__me()->role == 'client') {
            return $this->view('dashboard.client', $this->_client());
        } else {
            return $this->view('dashboard.admin', $this->_admin());
        }
    }

    /**
     * Client data
     *
     * @return array
     */
    private function _client()
    {
        return [];
    }

    /**
     * Admin data
     *
     * @return array
     */
    private function _admin()
    {
        $this->addAppView('app_libraries', 'assets/js/lib/chart.js');

        return [
            'latest_members' => (new User())->fetchAll(['is_latest' => 1, 'limit' => 3]),
            'count_active_members' => (new User())->fetch(['object' => true, 'is_account_activated' => 1])->count(),
            'count_inactive_members' => (new User())->fetch(['object' => true, 'is_account_activated' => 0])->count()
        ];
    }
}
