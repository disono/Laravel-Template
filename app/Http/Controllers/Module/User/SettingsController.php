<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\User\AccountSecurity;
use App\Http\Requests\Module\User\AccountSettings;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.settings';
    }

    /**
     * View general settings
     *
     * @return JsonResponse|Response
     */
    public function settingsAction()
    {
        return $this->view('settings',
            ['user' => __me(), 'countries' => Country::get(), 'cities' => City::get()]);
    }

    /**
     * Save settings
     *
     * @param AccountSettings $request
     * @return bool|JsonResponse
     */
    public function settingsUpdateAction(AccountSettings $request)
    {
        $inputs = $request->only([
            'first_name', 'last_name', 'phone', 'gender', 'birthday', 'address', 'country_id', 'city_id'
        ]);
        $inputs['profile_picture'] = $request->file('profile_picture');

        User::clearBoolean();
        User::edit(__me()->id, $inputs);

        if ($this->request->ajax()) {
            return $this->json('Profile is successfully updated.');
        }

        return $this->redirect();
    }

    /**
     * View for security
     *
     * @return JsonResponse|Response
     */
    public function securityAction()
    {
        return $this->view('security', ['user' => __me()]);
    }

    /**
     * Update security
     *
     * @param AccountSecurity $request
     * @return bool|JsonResponse
     */
    public function securityUpdateAction(AccountSecurity $request)
    {
        User::clearBoolean();
        User::edit(__me()->id, $request->only(['email', 'password']));

        if ($this->request->ajax()) {
            return $this->json('Profile is successfully updated.');
        }

        return $this->redirect();
    }
}
