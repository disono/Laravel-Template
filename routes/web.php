<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

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

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Authenticated
 * ---------------------------------------------------------------------------------------------------------------------
 */
Route::group(['middleware' => 'auth'], function () {
    // email verify
    Route::get('email/resend/verification', 'Web\Authentication\RegisterController@resendVerification')->name('web-email-resent-verification');

    // user
    Route::get('dashboard', 'Web\User\DashboardController@getIndex')->name('web-user-dashboard');
    Route::get('user/settings', 'Web\User\SettingsController@getIndex')->name('web-user-settings');
    Route::post('user/update/setting', 'Web\User\SettingsController@postUpdate')->name('web-user-update-settings');
    Route::get('user/security', 'Web\User\SettingsController@security')->name('web-user-security');
    Route::post('user/update/security', 'Web\User\SettingsController@updateSecurity')->name('web-user-update-security');

    /*
     * -----------------------------------------------------------------------------------------------------------------
     * Admin
     * -----------------------------------------------------------------------------------------------------------------
     */

    // users
    Route::get('admin/users', 'Admin\UserController@index')->name('admin-users');
    Route::get('admin/user/create', 'Admin\UserController@create')->name('admin-user-create');
    Route::post('admin/user/store', 'Admin\UserController@store')->name('admin-user-store');
    Route::get('admin/user/login/{id}', 'Admin\UserController@login')->name('admin-user-login');
    Route::get('admin/user/password/edit/{id}', 'Admin\UserController@passwordEdit')->name('admin-user-password-edit');
    Route::post('admin/user/password/update', 'Admin\UserController@passwordUpdate')->name('admin-user-password-update');
    Route::post('admin/user/destroy/{id}', 'Admin\UserController@destroy')->name('admin-user-destroy');

    // settings
    Route::get('admin/settings', 'Admin\SettingsController@index')->name('admin-settings');
    Route::post('admin/setting/update', 'Admin\SettingsController@update')->name('admin-setting-update');

    // page category
    Route::get('admin/page-categories', 'Admin\PageCategoryController@index')->name('admin-page-categories');
    Route::get('admin/page-category/create', 'Admin\PageCategoryController@create')->name('admin-page-category-create');
    Route::post('admin/page-category/store', 'Admin\PageCategoryController@store')->name('admin-page-category-store');
    Route::get('admin/page-category/edit/{id}', 'Admin\PageCategoryController@edit')->name('admin-page-category-edit');
    Route::post('admin/page-category/update', 'Admin\PageCategoryController@update')->name('admin-page-category-update');
    Route::post('admin/page-category/destroy/{id}', 'Admin\PageCategoryController@destroy')->name('admin-page-category-destroy');

    // page
    Route::get('admin/pages', 'Admin\PageController@index')->name('admin-pages');
    Route::get('admin/page/create', 'Admin\PageController@create')->name('admin-page-create');
    Route::post('admin/page/store', 'Admin\PageController@store')->name('admin-page-store');
    Route::get('admin/page/edit/{id}', 'Admin\PageController@edit')->name('admin-page-edit');
    Route::post('admin/page/update', 'Admin\PageController@update')->name('admin-page-update');
    Route::post('admin/page/destroy/{id}', 'Admin\PageController@destroy')->name('admin-page-destroy');

    // event
    Route::get('admin/events', 'Admin\EventController@index')->name('admin-events');
    Route::get('admin/event/create', 'Admin\EventController@create')->name('admin-event-create');
    Route::post('admin/event/store', 'Admin\EventController@store')->name('admin-event-store');
    Route::get('admin/event/edit/{id}', 'Admin\EventController@edit')->name('admin-event-edit');
    Route::post('admin/event/update', 'Admin\EventController@update')->name('admin-event-update');
    Route::post('admin/event/destroy/{id}', 'Admin\EventController@destroy')->name('admin-event-destroy');

    // images
    Route::get('admin/images', 'Admin\ImageController@index')->name('admin-images');
    Route::post('admin/image/destroy/{id}', 'Admin\ImageController@destroy')->name('admin-image-destroy');

    // image album
    Route::get('admin/albums', 'Admin\ImageAlbumController@index')->name('admin-albums');
    Route::get('admin/album/create', 'Admin\ImageAlbumController@create')->name('admin-album-create');
    Route::post('admin/album/store', 'Admin\ImageAlbumController@store')->name('admin-album-store');
    Route::get('admin/album/edit/{id}', 'Admin\ImageAlbumController@edit')->name('admin-album-edit');
    Route::post('admin/album/update', 'Admin\ImageAlbumController@update')->name('admin-album-update');
    Route::post('admin/album/destroy/{id}', 'Admin\ImageAlbumController@destroy')->name('admin-album-destroy');

    // image album (uploads)
    Route::get('admin/album/upload/create/{album_id}', 'Admin\ImageAlbumUploadController@create')->name('admin-album-upload-create');
    Route::post('admin/album/upload/store', 'Admin\ImageAlbumUploadController@store')->name('admin-album-upload-store');

    // authorization
    Route::get('admin/authorizations', 'Admin\AuthorizationController@index')->name('admin-authorizations');
    Route::get('admin/authorization/histories', 'Admin\AuthorizationController@getHistory')->name('admin-authorization-history');
    Route::get('admin/authorization/create', 'Admin\AuthorizationController@create')->name('admin-authorization-create');
    Route::post('admin/authorization/store', 'Admin\AuthorizationController@store')->name('admin-authorization-store');
    Route::get('admin/authorization/edit/{id}', 'Admin\AuthorizationController@edit')->name('admin-authorization-edit');
    Route::post('admin/authorization/update', 'Admin\AuthorizationController@update')->name('admin-authorization-update');
    Route::post('admin/authorization/destroy/{id}', 'Admin\AuthorizationController@destroy')->name('admin-authorization-destroy');

    // role
    Route::get('admin/roles', 'Admin\RoleController@index')->name('admin-roles');
    Route::get('admin/role/create', 'Admin\RoleController@create')->name('admin-role-create');
    Route::post('admin/role/store', 'Admin\RoleController@store')->name('admin-role-store');
    Route::get('admin/role/edit/{id}', 'Admin\RoleController@edit')->name('admin-role-edit');
    Route::post('admin/role/update', 'Admin\RoleController@update')->name('admin-role-update');
    Route::post('admin/role/destroy/{id}', 'Admin\RoleController@destroy')->name('admin-role-destroy');

    // authorization roles
    Route::get('admin/authorization-roles/{role_id}', 'Admin\AuthorizationRoleController@index')->name('admin-authorization-roles');
    Route::post('admin/authorization-role/store', 'Admin\AuthorizationRoleController@store')->name('admin-authorization-roles-store');
    Route::post('admin/authorization-role/destroy/{id}', 'Admin\AuthorizationRoleController@destroy')->name('admin-authorization-role-destroy');
});

// page
Route::get('{slug}', 'Web\Page\PageController@getShow')->name('web-page-show');
Route::get('{type}/{slug}', 'Web\Page\PageController@getShow')->name('web-page-show-type');