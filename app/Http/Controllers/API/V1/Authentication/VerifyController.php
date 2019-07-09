<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\API\APIController;
use App\Models\Vendor\Facades\User;
use Illuminate\Http\JsonResponse;

class VerifyController extends APIController
{
    private $_user;

    public function __construct()
    {
        parent::__construct();
        $this->_user = User::self();
    }

    /**
     * Verify phone
     *
     * Inputs: token, email|phone
     *
     * @return JsonResponse
     */
    public function verifyPhoneAction()
    {
        try {
            return $this->json($this->_user->verify('phone'));
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 422);
        }
    }

    /**
     * Resend verification for email and phone
     *
     * Inputs: type_value
     *
     * @param $type
     * @return JsonResponse
     */
    public function resendVerificationAction($type)
    {
        try {
            $value = $this->_user->resendVerification($type);

            if ($type == 'email') {
                return $this->json('We already sent you a verification link for ' . $value . '. Thank You.');
            }

            return $this->json($value);
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), 422);
        }
    }
}
