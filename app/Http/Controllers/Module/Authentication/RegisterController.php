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
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
     * @return bool|Factory|JsonResponse|View
     */
    public function showAction()
    {
        if (__settings('authUserRegistration')->value !== 'enabled') {
            abort(404);
        }

        return $this->view('auth.register');
    }

    /**
     * Process the registration
     *
     * @param RegisterRequest $request
     * @return bool|JsonResponse
     */
    public function processAction(RegisterRequest $request)
    {
        if (__settings('authUserRegistration')->value !== 'enabled') {
            abort(404);
        }

        $user = (new User())->register($request->all());
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
