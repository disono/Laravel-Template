<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication\Social;

use App\Http\Controllers\API\APIController;
use App\Models\File;
use App\Models\SocialAuthentication;
use App\Models\User;
use Facebook\Exceptions\FacebookSDKException;

class FacebookController extends APIController
{
    public function loginAction()
    {
        if (__settings('authSocialFacebook')->value != 'enabled') {
            return $this->json('Facebook authentication is not allowed, please try again later.');
        }

        // check the token if valid
        $isTokenValid = $this->_isTokenValid();
        if ($isTokenValid !== true) {
            return $this->json('Invalid Facebook token: ' . $isTokenValid, 422);
        }

        // is user already authenticated using Facebook
        $social = $this->_facebookRegistration();
        if ($social) {
            return $this->json($this->_profile($social->user_id));
        }

        // register new user
        $registration = $this->_createUser();
        if ($registration) {
            // download the profile photo
            $this->_downloadFacebookPhoto($registration);

            return $this->json($this->_profile($registration->id));
        }

        return $this->json('Failed to create new user.', 422);
    }

    private function _isTokenValid()
    {
        if (!$this->request->get('social_token')) {
            return false;
        }

        $FB_ID = env('FB_APP_ID');
        $FB_SECRET = env('FB_APP_SECRET');

        $token = $this->request->get('social_token');
        $app_access_token = $FB_ID . "|" . $FB_SECRET;
        try {
            $fb = new \Facebook\Facebook(['app_id' => $FB_ID,
                'app_secret' => $FB_SECRET,
                'default_graph_version' => 'v2.8',
                'default_access_token' => $app_access_token]);

            $oauth = $fb->getOAuth2Client();
            $meta = $oauth->debugToken($app_access_token);

            try {
                $meta->validateAppId($FB_ID);
                $resp = $fb->get('/me', $token);
                $user = $resp->getGraphUser();

                if ($user->getId() !== null) {
                    return true;
                }

                return 'Invalid Facebook token or authentication login is expired.';
            } catch (FacebookSDKException $e) {
                return $e->getMessage();
            }
        } catch (FacebookSDKException $e) {
            return $e->getMessage();
        }
    }

    private function _facebookRegistration()
    {
        return SocialAuthentication::where('social_id', $this->request->get('social_id'))
            ->where('type', 'Facebook')->first();
    }

    /**
     * Download avatar
     *
     * @param $user
     */
    private function _downloadFacebookPhoto($user)
    {
        $filename = httpDownloadImage($this->request->get('social_id') . '-' . time() . '-' . str_random(),
            'http://graph.facebook.com/' . $this->request->get('social_id') . '/picture?type=large');

        if (!$filename) {
            File::store([
                'user_id' => $user->id,
                'file_name' => $filename,
                'type' => 'photo',
                'title' => $user->first_name,
                'table_name' => 'users',
                'table_id' => $user->id,
                'tag' => 'profile'
            ]);
        }
    }

    /**
     * Fetch profile details
     *
     * @param string $id
     * @return null
     */
    private function _profile($id)
    {
        return User::crateToken(User::single($id));
    }

    /**
     * Create new user
     *
     * @return bool
     */
    private function _createUser()
    {
        $email = ($this->request->get('email')) ? $this->request->get('email') :
            urlTitle(__settings('title')->value, '.') . time() . str_random(4) . '@' . env('APP_DOMAIN');
        $username = str_random(8) . time();

        $create = User::store([
            'first_name' => $this->request->get('first_name'),
            'last_name' => $this->request->get('last_name'),

            'email' => $email,
            'username' => $username,
            'password' => bcrypt(str_random()),

            'role_id' => 3,
            'is_email_verified' => 1,
            'is_account_enabled' => 1,
            'is_account_activated' => 1
        ]);

        // create social auth
        if ($create) {
            // social auth
            SocialAuthentication::insert([
                'user_id' => $create->id,
                'social_id' => $this->request->get('social_id'),
                'type' => 'Facebook',
                'created_at' => sqlDate()
            ]);
        }

        return $create;
    }
}
