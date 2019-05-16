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

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user';
    }

    public function showAction($username)
    {
        $profile = (new User())->single($username, 'username');
        if (!$profile) {
            return $this->error(404);
        }

        return $this->view('profile', ['profile' => $profile]);
    }

    public function searchAction()
    {
        return $this->view('search', (new User())->fetchAll(requestValues('search|role')));
    }
}
