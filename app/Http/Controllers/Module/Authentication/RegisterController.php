<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function showAction()
    {
        if (__settings('authUserRegistration')->value != 'enabled') {
            abort(404);
        }

        return $this->view('auth.register');
    }

    /**
     * Process the registration
     *
     * @param RegisterRequest $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function processAction(RegisterRequest $request)
    {
        if (__settings('authUserRegistration')->value != 'enabled') {
            abort(404);
        }

        $user = User::register($request->all());
        if ($user) {
            Auth::loginUsingId($user->id, true);
            if ($request->ajax()) {
                return successJSONResponse([
                    'redirect' => $this->redirectPath()
                ]);
            }
        }

        if ($request->ajax()) {
            return failedJSONResponse('Failed to register, please try again later.');
        }

        return $this->redirect($this->redirectPath());
    }
}
