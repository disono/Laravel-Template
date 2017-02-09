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
Route::get('auth/social/facebook', 'Web\Authentication\Social@facebook')->name('web-auth-facebook');
Route::get('auth/social/facebook/callback', 'Web\Authentication\Social@facebookCallback')->name('web-auth-facebook-callback');

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
    Route::get('dashboard', 'Web\User\DashboardController@getIndex')->name('web-user-dashboard');
    Route::get('user/settings', 'Web\User\SettingsController@getIndex')->name('web-user-settings');
    Route::post('user/update/setting', 'Web\User\SettingsController@postUpdate')->name('web-user-update-settings');
    Route::get('user/security', 'Web\User\SettingsController@security')->name('web-user-security');
    Route::post('user/update/security', 'Web\User\SettingsController@updateSecurity')->name('web-user-update-security');

    // images
    Route::get('images', 'Web\File\ImageController@index')->name('web-image');
    Route::post('image/upload', 'Web\File\ImageController@upload')->name('web-image-upload');

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

        // page views
        Route::get('admin/page/view', 'Admin\Page\PageViewController@index')->name('admin-page-view');

        // event
        Route::get('admin/events', 'Admin\Page\EventController@index')->name('admin-events');
        Route::get('admin/event/create', 'Admin\Page\EventController@create')->name('admin-event-create');
        Route::post('admin/event/store', 'Admin\Page\EventController@store')->name('admin-event-store');
        Route::get('admin/event/edit/{id}', 'Admin\Page\EventController@edit')->name('admin-event-edit');
        Route::post('admin/event/update', 'Admin\Page\EventController@update')->name('admin-event-update');
        Route::post('admin/event/destroy/{id}', 'Admin\Page\EventController@destroy')->name('admin-event-destroy');

        // product category
        Route::get('admin/product/category', 'Admin\ECommerce\ProductCategoryController@index')->name('admin-product-category');
        Route::get('admin/product/category/create', 'Admin\ECommerce\ProductCategoryController@create')->name('admin-product-category-create');
        Route::post('admin/product/category/store', 'Admin\ECommerce\ProductCategoryController@store')->name('admin-product-category-store');
        Route::get('admin/product/category/edit/{id}', 'Admin\ECommerce\ProductCategoryController@edit')->name('admin-product-category-edit');
        Route::post('admin/product/category/update', 'Admin\ECommerce\ProductCategoryController@update')->name('admin-product-category-update');
        Route::post('admin/product/category/destroy/{id}', 'Admin\ECommerce\ProductCategoryController@destroy')->name('admin-product-category-destroy');

        // product
        Route::get('admin/branch', 'Admin\ECommerce\BranchController@index')->name('admin-product-branch');
        Route::get('admin/branch/create', 'Admin\ECommerce\BranchController@create')->name('admin-branch-create');
        Route::post('admin/branch/store', 'Admin\ECommerce\BranchController@store')->name('admin-branch-store');
        Route::get('admin/branch/edit/{id}', 'Admin\ECommerce\BranchController@edit')->name('admin-branch-edit');
        Route::post('admin/branch/update', 'Admin\ECommerce\BranchController@update')->name('admin-branch-update');
        Route::post('admin/branch/destroy/{id}', 'Admin\ECommerce\BranchController@destroy')->name('admin-branch-destroy');

        // product inventory
        Route::get('admin/inventory/{product_id}', 'Admin\ECommerce\InventoryController@index')->name('admin-product-inventory');
        Route::post('admin/inventory/store/{product_id}', 'Admin\ECommerce\InventoryController@store')->name('admin-product-inventory-store');

        // product
        Route::get('admin/product', 'Admin\ECommerce\ProductController@index')->name('admin-product-product');
        Route::get('admin/product/create', 'Admin\ECommerce\ProductController@create')->name('admin-product-create');
        Route::post('admin/product/store', 'Admin\ECommerce\ProductController@store')->name('admin-product-store');
        Route::get('admin/product/edit/{id}', 'Admin\ECommerce\ProductController@edit')->name('admin-product-edit');
        Route::post('admin/product/update', 'Admin\ECommerce\ProductController@update')->name('admin-product-update');
        Route::post('admin/product/destroy/{id}', 'Admin\ECommerce\ProductController@destroy')->name('admin-product-destroy');

        // voucher
        Route::get('admin/voucher', 'Admin\ECommerce\VoucherController@index')->name('admin-product-voucher');
        Route::get('admin/voucher/create', 'Admin\ECommerce\VoucherController@create')->name('admin-voucher-create');
        Route::post('admin/voucher/store', 'Admin\ECommerce\VoucherController@store')->name('admin-voucher-store');
        Route::get('admin/voucher/edit/{id}', 'Admin\ECommerce\VoucherController@edit')->name('admin-voucher-edit');
        Route::post('admin/voucher/update', 'Admin\ECommerce\VoucherController@update')->name('admin-voucher-update');
        Route::post('admin/voucher/destroy/{id}', 'Admin\ECommerce\VoucherController@destroy')->name('admin-voucher-destroy');

        // order
        Route::get('admin/order', 'Admin\ECommerce\OrderController@index')->name('admin-product-order');
        Route::get('admin/order/show/{id}', 'Admin\ECommerce\OrderController@show')->name('admin-order-show');
        Route::post('admin/order/item/{id}/{status}', 'Admin\ECommerce\OrderController@status')->name('admin-order-status');
        Route::post('admin/order/transaction/status/{id}/{status}', 'Admin\ECommerce\OrderController@transactionStatus')->name('admin-order-transaction-status');

        // sales
        Route::get('admin/sales', 'Admin\ECommerce\POSController@sales')->name('admin-pos-sales');

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
Route::get('{type}/{slug}', 'Web\Page\PageController@getShow')->name('web-page-show-type');