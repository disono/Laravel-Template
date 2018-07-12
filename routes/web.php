<?php

// page
Route::get('/', 'Module\Page\PageController@homeAction')->name('page.home');
Route::get('p/{slug}', 'Module\Page\PageController@showAction')->name('page.show');
Route::get('p/category/{name}', 'Module\Page\PageController@categoryAction')->name('page.category');
Route::get('p/archive/{year}/{month}', 'Module\Page\PageController@archiveAction')->name('page.archive');

// login
Route::get('login', 'Module\Authentication\LoginController@loginAction')->name('auth.login');
Route::post('login', 'Module\Authentication\LoginController@processAction')->name('auth.login.process');
Route::get('logout', 'Module\Authentication\LoginController@logoutAction')->name('auth.logout');

// social login facebook
Route::get('auth/social/facebook', 'Module\Authentication\Social\FacebookController@facebookAction')->name('auth.facebook');
Route::get('auth/social/facebook/callback', 'Module\Authentication\Social\FacebookController@facebookCallbackAction')->name('auth.facebook.callback');

// register
Route::get('register', 'Module\Authentication\RegisterController@showAction')->name('auth.register');
Route::post('register', 'Module\Authentication\RegisterController@processAction')->name('auth.register.process');

// password reset
Route::get('password/forgot', 'Module\Authentication\ResetController@forgotAction')->name('auth.password.forgot');
Route::post('password/forgot', 'Module\Authentication\ResetController@processForgotAction')->name('auth.password.processForgot');
Route::get('password/recover/{token}', 'Module\Authentication\ResetController@recoverAction')->name('auth.password.recover');
Route::post('password/recover', 'Module\Authentication\ResetController@processRecoveryAction')->name('auth.password.processRecover');

// verify
Route::get('verify/email', 'Module\Authentication\VerifyController@emailAction')->name('auth.verify.email');
Route::get('verify/phone', 'Module\Authentication\VerifyController@phoneAction')->name('auth.verify.phone');
Route::post('verify/phone', 'Module\Authentication\VerifyController@processPhoneAction')->name('auth.verify.phone.process');

// resend verification
Route::get('verify/resend/{type}', 'Module\Authentication\VerifyController@resendVerificationViewAction')->name('auth.verify.resend.view');
Route::post('verify/resend/{type}', 'Module\Authentication\VerifyController@resendVerificationProcessAction')->name('auth.verify.resend');

// profile
Route::get('u/{username}', 'Module\User\ProfileController@showAction')->name('user.show');

// views
Route::get('view/{type}', 'Module\Application\ViewController@viewAction')->name('application.view');

// video stream
Route::get('stream/video/{file}', 'Module\Application\FileController@streamVideoAction')->name('module.file.video_stream');

// development
Route::get('dev', 'Module\Page\DevelopmentController@applicationAction')->name('development.application');

/*
 * ------------------------------------
 * Authenticated
 * ------------------------------------
 */
Route::group(['middleware' => ['auth', 'auth.checker']], function () {
    /*
     * ------------------------------------
     * Customers
     * ------------------------------------
     */
    // user
    Route::get('dashboard', 'Module\User\DashboardController@showAction')->name('user.dashboard.show');
    Route::get('user/settings', 'Module\User\SettingsController@settingsAction')->name('user.settings');
    Route::post('user/setting/update', 'Module\User\SettingsController@settingsUpdateAction')->name('user.settingsUpdate');
    Route::get('user/security', 'Module\User\SettingsController@securityAction')->name('user.security');
    Route::post('user/security/update', 'Module\User\SettingsController@securityUpdateAction')->name('user.securityUpdate');

    // files
    Route::get('files', 'Module\Application\FileController@indexAction')->name('module.file.index');
    Route::post('file/store', 'Module\Application\FileController@createAction')->name('module.file.store');
    Route::post('file/update', 'Module\Application\FileController@updateAction')->name('module.file.update');
    Route::delete('file/destroy/{id}', 'Module\Application\FileController@destroyAction')->name('admin.file.destroy');

    /*
     * ------------------------------------
     * Administrator
     * ------------------------------------
     */
    Route::group(['middleware' => 'admin.access'], function () {
        // page categories
        Route::get('admin/page-categories', 'Admin\Page\PageCategoryController@indexAction')->name('admin.pageCategory.index');
        Route::get('admin/page-category/create', 'Admin\Page\PageCategoryController@createAction')->name('admin.pageCategory.create');
        Route::post('admin/page-category/store', 'Admin\Page\PageCategoryController@storeAction')->name('admin.pageCategory.store');
        Route::get('admin/page-category/edit/{id}', 'Admin\Page\PageCategoryController@editAction')->name('admin.pageCategory.edit');
        Route::post('admin/page-category/update', 'Admin\Page\PageCategoryController@updateAction')->name('admin.pageCategory.update');
        Route::delete('admin/page-category/destroy/{id}', 'Admin\Page\PageCategoryController@destroyAction')->name('admin.pageCategory.destroy');

        // pages
        Route::get('admin/pages', 'Admin\Page\PageController@indexAction')->name('admin.page.index');
        Route::get('admin/page/create', 'Admin\Page\PageController@createAction')->name('admin.page.create');
        Route::post('admin/page/store', 'Admin\Page\PageController@storeAction')->name('admin.page.store');
        Route::get('admin/page/edit/{id}', 'Admin\Page\PageController@editAction')->name('admin.page.edit');
        Route::post('admin/page/update', 'Admin\Page\PageController@updateAction')->name('admin.page.update');
        Route::delete('admin/page/destroy/{id}', 'Admin\Page\PageController@destroyAction')->name('admin.page.destroy');

        // users
        Route::get('admin/users', 'Admin\User\UserController@indexAction')->name('admin.user.index');
        Route::get('admin/user/create', 'Admin\User\UserController@createAction')->name('admin.user.create');
        Route::post('admin/user/store', 'Admin\User\UserController@storeAction')->name('admin.user.store');
        Route::get('admin/user/edit/{id}', 'Admin\User\UserController@editAction')->name('admin.user.edit');
        Route::post('admin/user/update', 'Admin\User\UserController@updateAction')->name('admin.user.update');
        Route::get('admin/user/update/{id}/{column}/{value}', 'Admin\User\UserController@updateColumnAction')->name('admin.user.update.column');
        Route::delete('admin/user/destroy/{id}', 'Admin\User\UserController@destroyAction')->name('admin.user.destroy');

        // authentication history
        Route::get('admin/user/authentication/history', 'Admin\User\AuthenticationHistoryController@indexAction')->name('admin.user.authentication.history');

        // files
        Route::get('admin/files', 'Admin\Application\FileController@indexAction')->name('admin.file.index');
        Route::delete('admin/file/destroy/{id}', 'Admin\Application\FileController@destroyAction')->name('admin.file.destroy');

        // role
        Route::get('admin/roles', 'Admin\Setting\RoleController@indexAction')->name('admin.role.index');
        Route::get('admin/role/create', 'Admin\Setting\RoleController@createAction')->name('admin.role.create');
        Route::post('admin/role/store', 'Admin\Setting\RoleController@storeAction')->name('admin.role.store');
        Route::get('admin/role/edit/{id}', 'Admin\Setting\RoleController@editAction')->name('admin.role.edit');
        Route::post('admin/role/update', 'Admin\Setting\RoleController@updateAction')->name('admin.role.update');
        Route::delete('admin/role/destroy/{id}', 'Admin\Setting\RoleController@destroyAction')->name('admin.role.destroy');

        // authorization role
        Route::get('admin/auth/role/{role_id}', 'Admin\Setting\AuthorizationRoleController@editAction')->name('admin.auth.role.edit');
        Route::post('admin/auth/role/update', 'Admin\Setting\AuthorizationRoleController@updateAction')->name('admin.auth.role.update');

        // csv
        Route::get('admin/csv/import', 'Admin\Application\CSVController@csvImportAction')->name('admin.csv.import');
        Route::post('admin/csv/import/store', 'Admin\Application\CSVController@csvImportStoreAction')->name('admin.csv.import.store');
        Route::get('admin/csv/export', 'Admin\Application\CSVController@csvExportAction')->name('admin.csv.export');
        Route::get('admin/csv/template', 'Admin\Application\CSVController@csvTemplateAction')->name('admin.csv.template');

        // FCM Notifications
        Route::get('admin/fcm-notifications', 'Admin\Notification\FCMController@indexAction')->name('admin.fcmNotification.index');
        Route::get('admin/fcm-notification/create', 'Admin\Notification\FCMController@createAction')->name('admin.fcmNotification.create');
        Route::post('admin/fcm-notification/store', 'Admin\Notification\FCMController@storeAction')->name('admin.fcmNotification.store');
        Route::get('admin/fcm-notification/edit/{id}', 'Admin\Notification\FCMController@editAction')->name('admin.fcmNotification.edit');
        Route::post('admin/fcm-notification/update', 'Admin\Notification\FCMController@updateAction')->name('admin.fcmNotification.update');
        Route::delete('admin/fcm-notification/destroy/{id}', 'Admin\Notification\FCMController@destroyAction')->name('admin.fcmNotification.destroy');

        // settings
        Route::get('admin/settings', 'Admin\Setting\SettingController@showAction')->name('admin.setting.show');
        Route::post('admin/setting/save', 'Admin\Setting\SettingController@saveSettings')->name('admin.setting.save');
        Route::get('admin/setting/application', 'Admin\Setting\SettingController@indexAction')->name('admin.setting.index');
        Route::get('admin/setting/application/create', 'Admin\Setting\SettingController@createAction')->name('admin.setting.create');
        Route::post('admin/setting/application/store', 'Admin\Setting\SettingController@storeAction')->name('admin.setting.store');
        Route::get('admin/setting/application/edit/{id}', 'Admin\Setting\SettingController@editAction')->name('admin.setting.edit');
        Route::post('admin/setting/application/update', 'Admin\Setting\SettingController@updateAction')->name('admin.setting.update');
        Route::delete('admin/setting/application/destroy/{id}', 'Admin\Setting\SettingController@destroyAction')->name('admin.setting.destroy');
    });
});

