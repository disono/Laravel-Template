<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\API\APIController;
use App\Models\Vendor\Facades\AuthenticationHistory;
use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends APIController
{
    /**
     * Login user
     *
     * @return JsonResponse
     */
    public function loginAction()
    {
        if (Auth::attempt($this->_requestInputs())) {
            $this->_logAuthentication();
            return $this->json($this->_profile());
        }

        if (Auth::attempt($this->_requestInputs('username'))) {
            $this->_logAuthentication();
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
     * Log the authentication
     * @param string $type
     */
    private function _logAuthentication($type = 'login')
    {
        if (!__me()) {
            return;
        }

        try {
            $userAgent = userAgent();

            AuthenticationHistory::store([
                'user_id' => __me()->id,
                'ip' => ipAddress(),
                'platform' => $userAgent->platform . ', ' . $userAgent->browserName,
                'type' => $type
            ]);
        } catch (Exception $e) {
            logErrors('LoginController._logAuthentication (V1): ' . $e->getMessage());
        }
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
     * @return JsonResponse
     */
    public function logoutAction($id)
    {
        $this->_logAuthentication('logout');
        return $this->json(Token::remove($id));
    }
}
