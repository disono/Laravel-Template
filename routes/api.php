<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * API V1
 * ---------------------------------------------------------------------------------------------------------------------
 */

// initialize token key
init_token_key();

if (env('APP_ENV') === 'local') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS, HEAD');
    header('Access-Control-Allow-Headers: Authorization, token_key, authenticated_id, X-Auth-Token, X-XSRF-TOKEN, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers');
}

// application data
Route::get('v1/application', 'API\V1\ApplicationController@index')->name('api-v1-application');

// login
Route::post('v1/auth/login', 'API\V1\Authenticate\LoginController@login')->name('api-v1-auth-login');

// register
Route::post('v1/auth/register', 'API\V1\Authenticate\RegisterController@register')->name('api-v1-auth-register');

// forgot
Route::post('v1/password/recover', 'API\V1\Authenticate\RecoveryController@postRecovery')->name('api-v1-password-recover');

// authenticated user
Route::group(['middleware' => ['api.auth']], function () {
    // authentication
    Route::get('v1/user/{id}', 'API\V1\User\UserController@getShow')->name('api-v1-user');
    Route::get('v1/user/fcm-token/{id}/{token}', 'API\V1\User\UserController@FCMToken')->name('api-v1-user-fcm-token');

    // user settings
    Route::post('v1/user/update/setting', 'API\V1\User\SettingsController@postSettings')->name('api-v1-user-update-settings');
    Route::post('v1/user/update/security', 'API\V1\User\SettingsController@postSecurity')->name('api-v1-user-update-security');

    // user tokens
    Route::post('v1/user/token/create', 'API\V1\User\UserController@postTokenCreate')->name('api-v1-user-token-create');
    Route::post('v1/user/token/check', 'API\V1\User\UserController@postTokenCheck')->name('api-v1-user-token-check');
});
