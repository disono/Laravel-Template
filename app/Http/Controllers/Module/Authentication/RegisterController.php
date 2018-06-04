<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Auth\RegisterRequest;
use App\Models\User;
use App\Notifications\RegisterNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
        $id = $this->create($request->all());

        if ($id) {
            Auth::loginUsingId($id, true);

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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        // create new user
        $user = User::create([
            'first_name' => ucfirst($data['first_name']),
            'last_name' => ucfirst($data['last_name']),

            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),

            'role_id' => 3,
            'is_account_enabled' => 1,
            'is_account_activated' => 1
        ]);

        // send email
        if ($user) {
            // send email for email verification
            Notification::send($user, new RegisterNotification($user));
        }

        return $user->id;
    }
}
