<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// home
Route::get('/', 'Web\Page\PageController@getHome')->name('web-page-home');

// login
Route::get('login', 'Web\Authentication\LoginController@loginView')->name('web-auth-login');
Route::post('login', 'Web\Authentication\LoginController@process')->name('web-auth-login-process');
Route::get('logout', 'Web\Authentication\LoginController@logoutProcess')->name('web-auth-logout');

// social login facebook
Route::get('auth/social/facebook', 'Web\Authentication\Social\FacebookController@facebook')->name('web-auth-facebook');
Route::get('auth/social/facebook/callback', 'Web\Authentication\Social\FacebookController@facebookCallback')->name('web-auth-facebook-callback');

// register
Route::get('register', 'Web\Authentication\RegisterController@registerView')->name('web-auth-register');
Route::post('register', 'Web\Authentication\RegisterController@process')->name('web-auth-register-process');

// email verify
Route::get('email/verify', 'Web\Authentication\RegisterController@verifyEmail')->name('web-email-verify');

// password reset
Route::get('password/recover', 'Web\Authentication\ResetController@getRecover')->name('web-auth-password-get-recover');
Route::post('password/recover', 'Web\Authentication\ResetController@postRecover')->name('web-auth-password-post-recover');
Route::get('password/reset/{token}', 'Web\Authentication\RecoveryController@getReset')->name('password.reset');
Route::post('password/reset', 'Web\Authentication\RecoveryController@postReset')->name('web-auth-password-post-reset');

// event
Route::get('event/{slug}', 'Web\Event\EventController@show')->name('web-event');

// subscriber
Route::post('subscriber/store', 'Web\Page\SubscriberController@store')->name('web-subscriber-store');

// video stream
Route::get('stream/video/{file}', 'Web\Page\PageController@streamVideo')->name('web-page-stream-video');

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Authenticated
 * ---------------------------------------------------------------------------------------------------------------------
 */
Route::group(['middleware' => 'auth'], function () {
    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Client / Customer / Public Profile
     * -----------------------------------------------------------------------------------------------------------------
     */

    // email verify
    Route::get('email/resend/verification', 'Web\Authentication\RegisterController@resendVerification')->name('web-email-resent-verification');

    // user
    Route::get('user', 'Web\User\UserController@index')->name('web-user-index');
    Route::get('dashboard', 'Web\User\DashboardController@getIndex')->name('web-user-dashboard');
    Route::get('user/settings', 'Web\User\SettingsController@getIndex')->name('web-user-settings');
    Route::post('user/update/setting', 'Web\User\SettingsController@postUpdate')->name('web-user-update-settings');
    Route::get('user/security', 'Web\User\SettingsController@security')->name('web-user-security');
    Route::post('user/update/security', 'Web\User\SettingsController@updateSecurity')->name('web-user-update-security');

    // images
    Route::get('images', 'Web\File\ImageController@index')->name('web-image');
    Route::post('image/upload', 'Web\File\ImageController@upload')->name('web-image-upload');

    // messaging
    Route::get('messenger', 'Web\Message\MessageController@index')->name('web-message');
    Route::get('message/inbox', 'Web\Message\MessageController@inbox')->name('web-message-inbox');
    Route::get('message/reading/{from_id}', 'Web\Message\MessageController@reading')->name('web-message-reading');
    Route::get('message/group/{group_id}', 'Web\Message\MessageController@group')->name('web-message-group');
    Route::post('message/send/{to_id}', 'Web\Message\MessageController@send')->name('web-message-send');

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Administrator / Employee
     * -----------------------------------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => 'admin.access'], function () {
        // users
        Route::get('admin/users', 'Admin\UserController@index')->name('admin-users');
        Route::get('admin/user/create', 'Admin\UserController@create')->name('admin-user-create');
        Route::post('admin/user/store', 'Admin\UserController@store')->name('admin-user-store');
        Route::get('admin/user/edit/{id}', 'Admin\UserController@edit')->name('admin-user-edit');
        Route::post('admin/user/update', 'Admin\UserController@update')->name('admin-user-update');
        Route::get('admin/user/confirm', 'Admin\UserController@confirm')->name('admin-user-confirm');
        Route::get('admin/user/login/{id}', 'Admin\UserController@login')->name('admin-user-login');
        Route::get('admin/user/password/edit/{id}', 'Admin\UserController@passwordEdit')->name('admin-user-password-edit');
        Route::post('admin/user/password/update', 'Admin\UserController@passwordUpdate')->name('admin-user-password-update');
        Route::post('admin/user/destroy/{id}', 'Admin\UserController@destroy')->name('admin-user-destroy');
        Route::get('admin/user/map', 'Admin\UserController@map')->name('admin-user-map');

        // page category
        Route::get('admin/page-categories', 'Admin\Page\PageCategoryController@index')->name('admin-page-categories');
        Route::get('admin/page-category/create', 'Admin\Page\PageCategoryController@create')->name('admin-page-category-create');
        Route::post('admin/page-category/store', 'Admin\Page\PageCategoryController@store')->name('admin-page-category-store');
        Route::get('admin/page-category/edit/{id}', 'Admin\Page\PageCategoryController@edit')->name('admin-page-category-edit');
        Route::post('admin/page-category/update', 'Admin\Page\PageCategoryController@update')->name('admin-page-category-update');
        Route::post('admin/page-category/destroy/{id}', 'Admin\Page\PageCategoryController@destroy')->name('admin-page-category-destroy');

        // page
        Route::get('admin/pages', 'Admin\Page\PageController@index')->name('admin-pages');
        Route::get('admin/page/create', 'Admin\Page\PageController@create')->name('admin-page-create');
        Route::post('admin/page/store', 'Admin\Page\PageController@store')->name('admin-page-store');
        Route::get('admin/page/edit/{id}', 'Admin\Page\PageController@edit')->name('admin-page-edit');
        Route::post('admin/page/update', 'Admin\Page\PageController@update')->name('admin-page-update');
        Route::post('admin/page/destroy/{id}', 'Admin\Page\PageController@destroy')->name('admin-page-destroy');

        // subscriber
        Route::get('admin/subscriber', 'Admin\Page\SubscriberController@index')->name('admin-subscriber');
        Route::post('admin/subscriber/destroy/{id}', 'Admin\Page\SubscriberController@destroy')->name('admin-subscriber-destroy');

        // message
        Route::get('admin/message', 'Admin\MessageController@index')->name('admin-message');
        Route::get('admin/message/show/{id}', 'Admin\MessageController@show')->name('admin-message-show');

        // page views
        Route::get('admin/page/view', 'Admin\Page\PageViewController@index')->name('admin-page-view');

        // event
        Route::get('admin/events', 'Admin\Page\EventController@index')->name('admin-events');
        Route::get('admin/event/create', 'Admin\Page\EventController@create')->name('admin-event-create');
        Route::post('admin/event/store', 'Admin\Page\EventController@store')->name('admin-event-store');
        Route::get('admin/event/edit/{id}', 'Admin\Page\EventController@edit')->name('admin-event-edit');
        Route::post('admin/event/update', 'Admin\Page\EventController@update')->name('admin-event-update');
        Route::post('admin/event/destroy/{id}', 'Admin\Page\EventController@destroy')->name('admin-event-destroy');

        // images
        Route::get('admin/images', 'Admin\Application\Image\ImageController@index')->name('admin-images');
        Route::post('admin/image/destroy/{id}', 'Admin\Application\Image\ImageController@destroy')->name('admin-image-destroy');

        // image album
        Route::get('admin/albums', 'Admin\Application\Image\ImageAlbumController@index')->name('admin-albums');
        Route::get('admin/album/create', 'Admin\Application\Image\ImageAlbumController@create')->name('admin-album-create');
        Route::post('admin/album/store', 'Admin\Application\Image\ImageAlbumController@store')->name('admin-album-store');
        Route::get('admin/album/edit/{id}', 'Admin\Application\Image\ImageAlbumController@edit')->name('admin-album-edit');
        Route::post('admin/album/update', 'Admin\Application\Image\ImageAlbumController@update')->name('admin-album-update');
        Route::post('admin/album/destroy/{id}', 'Admin\Application\Image\ImageAlbumController@destroy')->name('admin-album-destroy');

        // image album (uploads)
        Route::get('admin/album/upload/create/{album_id}', 'Admin\Application\Image\ImageAlbumUploadController@create')->name('admin-album-upload-create');
        Route::post('admin/album/upload/store', 'Admin\Application\Image\ImageAlbumUploadController@store')->name('admin-album-upload-store');

        // authorization
        Route::get('admin/authorizations', 'Admin\Application\Settings\AuthorizationController@index')->name('admin-authorizations');
        Route::get('admin/authorization/histories', 'Admin\Application\Settings\AuthorizationController@getHistory')->name('admin-authorization-history');
        Route::get('admin/authorization/create', 'Admin\Application\Settings\AuthorizationController@create')->name('admin-authorization-create');
        Route::post('admin/authorization/store', 'Admin\Application\Settings\AuthorizationController@store')->name('admin-authorization-store');
        Route::get('admin/authorization/edit/{id}', 'Admin\Application\Settings\AuthorizationController@edit')->name('admin-authorization-edit');
        Route::post('admin/authorization/update', 'Admin\Application\Settings\AuthorizationController@update')->name('admin-authorization-update');
        Route::post('admin/authorization/destroy/{id}', 'Admin\Application\Settings\AuthorizationController@destroy')->name('admin-authorization-destroy');
        Route::get('admin/authorization/reset', 'Admin\Application\Settings\AuthorizationController@reset')->name('admin-authorization-reset');

        // role
        Route::get('admin/roles', 'Admin\Application\Settings\RoleController@index')->name('admin-roles');
        Route::get('admin/role/create', 'Admin\Application\Settings\RoleController@create')->name('admin-role-create');
        Route::post('admin/role/store', 'Admin\Application\Settings\RoleController@store')->name('admin-role-store');
        Route::get('admin/role/edit/{id}', 'Admin\Application\Settings\RoleController@edit')->name('admin-role-edit');
        Route::post('admin/role/update', 'Admin\Application\Settings\RoleController@update')->name('admin-role-update');
        Route::post('admin/role/destroy/{id}', 'Admin\Application\Settings\RoleController@destroy')->name('admin-role-destroy');

        // authorization roles
        Route::get('admin/authorization-roles/{role_id}', 'Admin\Application\Settings\AuthorizationRoleController@index')->name('admin-authorization-roles');
        Route::post('admin/authorization-role/store', 'Admin\Application\Settings\AuthorizationRoleController@store')->name('admin-authorization-roles-store');
        Route::post('admin/authorization-role/destroy/{id}', 'Admin\Application\Settings\AuthorizationRoleController@destroy')->name('admin-authorization-role-destroy');

        // settings
        Route::get('admin/settings', 'Admin\Application\Settings\SettingsController@index')->name('admin-settings');
        Route::post('admin/setting/update', 'Admin\Application\Settings\SettingsController@update')->name('admin-setting-update');

        // activity logs
        Route::get('admin/activity', 'Admin\Application\Settings\ActivityLogController@index')->name('admin-activity');
    });
});

Route::get('dev', function () {

})->name('web-dev');

// profile
Route::get('user/{username}', 'Web\User\ProfileController@show')->name('web-user-profile-show');

// page
Route::get('{slug}', 'Web\Page\PageController@getShow')->name('web-page-show');
// page with page category and page slug
Route::get('{type}/{slug}', 'Web\Page\PageController@getShow')->name('web-page-show-type');