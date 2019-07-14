<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user';
    }

    public function showAction($username)
    {
        $username = $username === 'me' ? __me()->username : $username;
        $profile = User::single($username, 'username');
        if (!$profile) {
            return $this->error(404);
        }

        return $this->view('profile', ['profile' => $profile]);
    }

    public function searchAction()
    {
        return $this->view('search', User::fetchAll(requestValues('search|role')));
    }

    public function tokenAction()
    {
        $token = Token::fetchAll(['single' => true, 'user_id' => __me()->id, 'token' => session()->getId(), 'source' => 'client']);
        if (!$token) {
            return $this->error('404', 'Token not found.');
        }

        unset($token->secret);
        $token->jwt = jwtEncode();
        return $this->json($token);
    }
}
