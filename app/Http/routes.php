<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'app'], function () {
    // home
    Route::get('/', 'Web\Page\PageController@getHome')->name('web-page-home');

    // login
    Route::get('login', 'Web\Authentication\LoginController@getLogin')->name('web-auth-get-login');
    Route::post('login', 'Web\Authentication\LoginController@postLogin')->name('web-auth-post-login');
    Route::get('logout', 'Web\Authentication\LoginController@getLogout')->name('web-auth-get-logout');

    // social login
    Route::get('auth/social/facebook', 'Web\Authentication\Social@facebook')->name('web-auth-facebook');
    Route::get('auth/social/facebook/callback', 'Web\Authentication\Social@facebookCallback')->name('web-auth-facebook-callback');

    // register
    Route::get('register', 'Web\Authentication\RegisterController@getRegister')->name('web-auth-get-register');
    Route::post('register', 'Web\Authentication\RegisterController@postRegister')->name('web-auth-post-register');

    // email verify
    Route::get('email/verify', 'Web\Authentication\RegisterController@verifyEmail')->name('web-email-verify');

    // password reset
    Route::get('password/reset', 'Web\Authentication\RecoveryController@getEmail')->name('web-auth-password-get-reset');
    Route::get('password/reset/{token}', 'Web\Authentication\RecoveryController@getReset')->name('web-auth-password-get-token');
    Route::post('password/reset', 'Web\Authentication\RecoveryController@postReset')->name('web-auth-password-post-reset');

    Route::group(['middleware' => 'auth'], function () {
        /*
         * -------------------------------------------------------------------------------------------------------------
         * Web
         * -------------------------------------------------------------------------------------------------------------
         */

        // email verify
        Route::get('email/resend/verification', 'Web\Authentication\RegisterController@resendVerification')->name('web-email-resent-verification');

        // user
        Route::get('dashboard', 'Web\User\DashboardController@getIndex')->name('web-user-dashboard');
        Route::get('user/settings', 'Web\User\SettingsController@getIndex')->name('web-user-settings');
        Route::post('user/update/setting', 'Web\User\SettingsController@postUpdate')->name('web-user-update-settings');
        Route::get('user/security', 'Web\User\SettingsController@security')->name('web-user-security');
        Route::post('user/update/security', 'Web\User\SettingsController@updateSecurity')->name('web-user-update-security');

        // ajax calls
        Route::group(['middleware' => 'ajax'], function () {

        });

        /*
         * -------------------------------------------------------------------------------------------------------------
         * Admin
         * -------------------------------------------------------------------------------------------------------------
         */

        // users
        Route::get('admin/users', 'Admin\UserController@index')->name('admin-users');
        Route::get('admin/user/create', 'Admin\UserController@create')->name('admin-user-create');
        Route::post('admin/user/store', 'Admin\UserController@store')->name('admin-user-store');
        Route::get('admin/user/login/{id}', 'Admin\UserController@login')->name('admin-user-login');
        Route::get('admin/user/password/edit/{id}', 'Admin\UserController@passwordEdit')->name('admin-user-password-edit');
        Route::post('admin/user/password/update', 'Admin\UserController@passwordUpdate')->name('admin-user-password-update');

        // settings
        Route::get('admin/settings', 'Admin\SettingsController@index')->name('admin-settings');
        Route::post('admin/setting/update', 'Admin\SettingsController@update')->name('admin-setting-update');

        // images
        Route::get('admin/images', 'Admin\ImageController@index')->name('admin-images');

        // authorization
        Route::get('admin/authorizations', 'Admin\AuthorizationController@index')->name('admin-authorizations');
        Route::get('admin/authorization/create', 'Admin\AuthorizationController@create')->name('admin-authorization-create');
        Route::post('admin/authorization/store', 'Admin\AuthorizationController@store')->name('admin-authorization-store');
        Route::get('admin/authorization/edit/{id}', 'Admin\AuthorizationController@edit')->name('admin-authorization-edit');
        Route::post('admin/authorization/update', 'Admin\AuthorizationController@update')->name('admin-authorization-update');

        // role
        Route::get('admin/roles', 'Admin\RoleController@index')->name('admin-roles');
        Route::get('admin/role/create', 'Admin\RoleController@create')->name('admin-role-create');
        Route::post('admin/role/store', 'Admin\RoleController@store')->name('admin-role-store');
        Route::get('admin/role/edit/{id}', 'Admin\RoleController@edit')->name('admin-role-edit');
        Route::post('admin/role/update', 'Admin\RoleController@update')->name('admin-role-update');

        // authorization roles
        Route::get('admin/authorization-roles/{role_id}', 'Admin\AuthorizationRoleController@index')->name('admin-authorization-roles');
        Route::post('admin/authorization-role/store', 'Admin\AuthorizationRoleController@store')->name('admin-authorization-roles-store');

        // ajax calls
        Route::group(['middleware' => 'ajax'], function () {
            // users
            Route::post('admin/ajax/user/destroy/{id}', 'Admin\UserController@ajaxDestroy')->name('admin-ajax-user-destroy');

            // images
            Route::post('admin/ajax/image/destroy/{id}', 'Admin\ImageController@ajaxDestroy')->name('admin-ajax-image-destroy');

            // roles
            Route::post('admin/ajax/role/destroy/{id}', 'Admin\RoleController@ajaxDestroy')->name('admin-ajax-role-destroy');

            // authorization
            Route::post('admin/ajax/authorization/destroy/{id}', 'Admin\AuthorizationController@ajaxDestroy')->name('admin-ajax-authorization-destroy');

            // authorization-role
            Route::post('admin/ajax/authorization-role/destroy/{id}', 'Admin\AuthorizationRoleController@ajaxDestroy')->name('admin-ajax-authorization-role-destroy');
        });
    });
});

// API V1
Route::group(['middleware' => 'api'], function () {
    /*
     * -------------------------------------------------------------------------------------------------------------
     * API V1
     * -------------------------------------------------------------------------------------------------------------
     */

    // login
    Route::get('api/v1/auth/login', 'API\V1\Authenticate\LoginController@login')->name('api-v1-auth-login');

    // register
    Route::post('api/v1/auth/register', 'API\V1\Authenticate\RegisterController@register')->name('api-v1-auth-register');
});

Route::get('dev', function () {
    foreach (Route::getRoutes() as $value) {
        var_dump($value);
        return;
    }
})->name('dev');