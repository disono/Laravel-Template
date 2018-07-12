<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\API\APIController;
use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends APIController
{
    /**
     * Login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAction()
    {
        if (Auth::attempt($this->_requestInputs())) {
            return $this->json($this->_profile());
        }

        if (Auth::attempt($this->_requestInputs('username'))) {
            return $this->json($this->_profile('username'));
        }

        return $this->json(['username' => 'Invalid username or password.'], 422, false);
    }

    /**
     * Default request inputs
     *
     * @param string $username
     * @return array
     */
    private function _requestInputs($username = 'email')
    {
        return [
            $username => $this->request->get('username'),
            'password' => $this->request->get('password'),
            'is_account_enabled' => 1
        ];
    }

    /**
     * Fetch profile details
     *
     * @param string $username
     * @return null
     */
    private function _profile($username = 'email')
    {
        return User::crateToken(User::single($this->request->get('username'), $username));
    }

    /**
     * Logout user delete token
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAction($id)
    {
        return $this->json(Token::remove($id));
    }
}
