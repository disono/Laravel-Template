<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\User\PhoneStore;
use App\Http\Requests\Module\User\PhoneUpdate;
use App\Models\Vendor\Facades\UserPhone;

class PhoneController extends Controller
{
    private $_userPhone;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.settings.phone';
        $this->_userPhone = UserPhone::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Phone Numbers');
        return $this->view('index', [
            'phones' => $this->_userPhone->fetch(requestValues('search', ['user_id' => __me()->id]))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add a New Phone Number');
        return $this->view('create');
    }

    public function storeAction(PhoneStore $request)
    {
        $inputs = $request->only(['phone']);
        $inputs['user_id'] = $this->me->id;
        if (!$this->_userPhone->store($inputs)) {
            return $this->json(['name' => 'Failed to crate a new address.'], 422, false);
        }

        return $this->json(['redirect' => '/user/setting/phones']);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Phone Number');
        $this->content['phone'] = $this->_userPhone->fetch(['id' => $id, 'single' => true, 'user_id' => $this->me->id]);
        if (!$this->content['phone']) {
            abort(404);
        }

        return $this->view('edit');
    }

    public function updateAction(PhoneUpdate $request)
    {
        $error = $this->_checkVerification($request);
        if ($error !== true) {
            return failedJSONResponse(['verify_code' => $error], 422, false);
        }

        $this->_userPhone->edit(['id' => $request->get('id'), 'user_id' => $this->me->id], $request->only(['phone']));
        return $this->json('Phone number is successfully updated.');
    }

    public function destroyAction($id)
    {
        $this->_userPhone->remove(['id' => $id, 'user_id' => __me()->id]);
        return $this->json('Phone number is successfully deleted.');
    }

    public function _checkVerification($request)
    {
        if (__settings('phoneVerification')->value === 'enabled') {
            $q = UserPhone::where('id', $request->get('id'))->where('user_id', __me()->id);
            $address = $q->first();

            if ($address && $request->get('verify_code')) {
                $phoneVerificationThreshold = (int)__settings('phoneVerificationThreshold')->value;
                if ($phoneVerificationThreshold != 0 && $address->verification_tries == $phoneVerificationThreshold) {
                    return 'Too many attempts to verify your phone number.';
                }

                if ($address->verification_code != $request->get('verify_code')) {
                    $q->update([
                        'verification_tries' => $address->verification_tries + 1
                    ]);

                    return 'Invalid code to verify your phone number';
                }

                if ($address->is_verification_expired == 0) {
                    $q->update([
                        'is_verified' => 1,
                        'verification_code' => NULL,
                        'verification_expired_at' => NULL
                    ]);
                }
            }
        }

        return TRUE;
    }
}
