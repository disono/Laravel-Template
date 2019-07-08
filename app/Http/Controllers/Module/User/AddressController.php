<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\User\AddressStore;
use App\Http\Requests\Module\User\AddressUpdate;
use App\Models\Vendor\Facades\City;
use App\Models\Vendor\Facades\Country;
use App\Models\Vendor\Facades\UserAddress;

class AddressController extends Controller
{
    private $_userAddress;
    private $_country;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.settings.address';
        $this->_userAddress = UserAddress::self();
        $this->_country = Country::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Addresses');
        return $this->view('index', [
            'addresses' => $this->_userAddress->fetch(requestValues('search', ['user_id' => __me()->id]))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Address');
        return $this->view('create', [
            'countries' => $this->_country->fetchAll()
        ]);
    }

    public function storeAction(AddressStore $request)
    {
        $inputs = $request->only(['address', 'postal_code', 'country_id', 'city_id']);
        $inputs['user_id'] = $this->me->id;
        if (!$this->_userAddress->store($inputs)) {
            return $this->json(['name' => 'Failed to crate a new address.'], 422, false);
        }

        return $this->json(['redirect' => '/user/setting/addresses']);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Address');
        $this->content['address'] = $this->_userAddress->fetch(['id' => $id, 'single' => true, 'user_id' => $this->me->id]);
        if (!$this->content['address']) {
            abort(404);
        }

        $this->content['countries'] = $this->_country->fetchAll();
        $this->content['cities'] = function ($country_id) {
            return City::fetchAll(['country_id' => $country_id]);
        };

        return $this->view('edit');
    }

    public function updateAction(AddressUpdate $request)
    {
        $error = $this->_checkVerification($request);
        if ($error !== true) {
            return failedJSONResponse(['verify_code' => $error], 422, false);
        }

        $this->_userAddress->edit(['id' => $request->get('id'), 'user_id' => $this->me->id], $request->only([
            'address', 'postal_code', 'country_id', 'city_id'
        ]));

        return $this->json('Address is successfully updated.');
    }

    public function destroyAction($id)
    {
        $this->_userAddress->remove(['id' => $id, 'user_id' => __me()->id]);
        return $this->json('Address is successfully deleted.');
    }

    public function _checkVerification($request)
    {
        if (__settings('addressVerification')->value === 'enabled') {
            $q = UserAddress::where('id', $request->get('id'))->where('user_id', __me()->id);
            $address = $q->first();

            if ($address && $request->get('verify_code')) {
                $addressVerificationThreshold = (int)__settings('addressVerificationThreshold')->value;
                if ($addressVerificationThreshold != 0 && $address->verification_tries == $addressVerificationThreshold) {
                    return 'Too many attempts to verify your address.';
                }

                if ($address->verification_code != $request->get('verify_code')) {
                    $q->update([
                        'verification_tries' => $address->verification_tries + 1
                    ]);

                    return 'Invalid code to verify your address';
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

        return true;
    }
}
