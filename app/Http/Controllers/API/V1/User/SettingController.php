<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\User\AccountSecurity;
use App\Http\Requests\API\V1\User\AccountSettings;
use App\Models\User;

class SettingController extends APIController
{
    /**
     * Update profile details
     *
     * @param AccountSettings $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingAction(AccountSettings $request)
    {
        $inputs = $request->only([
            'first_name', 'last_name', 'phone', 'gender', 'birthday', 'address', 'country_id', 'city_id'
        ]);
        $inputs['profile_picture'] = $request->file('profile_picture');

        User::clearBoolean();
        User::edit(__me()->id, $inputs);

        return $this->json('Profile is successfully updated.');
    }

    /**
     * Update profile security
     *
     * @param AccountSecurity $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function securityAction(AccountSecurity $request)
    {
        User::clearBoolean();
        User::edit(__me()->id, $request->only(['email', 'password']));

        return $this->json('Profile is successfully updated.');
    }
}
