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
Route::post('password/forgot', 'Module\Authentication\ResetController@processForgotAction')->name('auth.password.process.forgot');
Route::get('password/recover/{token}', 'Module\Authentication\ResetController@recoverAction')->name('auth.password.recover');
Route::post('password/recover', 'Module\Authentication\ResetController@processRecoveryAction')->name('auth.password.process.recover');

// verify
Route::get('verify/email', 'Module\Authentication\VerifyController@emailAction')->name('auth.verify.email');
Route::get('verify/phone', 'Module\Authentication\VerifyController@phoneAction')->name('auth.verify.phone');
Route::post('verify/phone', 'Module\Authentication\VerifyController@processPhoneAction')->name('auth.verify.phone.process');

// resend verification
Route::get('verify/resend/{type}', 'Module\Authentication\VerifyController@resendVerificationViewAction')->name('auth.verify.resend.view');
Route::post('verify/resend/{type}', 'Module\Authentication\VerifyController@resendVerificationProcessAction')->name('auth.verify.resend');

// views
Route::get('view/{type}', 'Module\Application\ViewController@viewAction')->name('application.view');

// stream
Route::get('stream/video/{file}', 'Module\Application\FileController@streamVideoAction')->name('module.file.video.stream');
Route::get('stream/audio/{file}', 'Module\Application\FileController@streamAudioAction')->name('module.file.audio.stream');
Route::get('stream/image/{file}', 'Module\Application\FileController@streamImageAction')->name('module.file.image.stream');

// location
Route::get('location/cities/{country_id}', 'Module\Application\LocationController@citiesAction')->name('module.location.cities');

// development
Route::get('dev', 'Module\Page\DevelopmentController@applicationAction')->name('development.application');

// documentation
Route::get('dev/docs', 'Module\Page\DevelopmentController@documentationAction')->name('development.documentation');

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

    // profile
    Route::get('u/search', 'Module\User\ProfileController@searchAction')->name('user.search');
    Route::get('u/{username}', 'Module\User\ProfileController@showAction')->name('user.show');

    // user
    Route::get('dashboard', 'Module\User\DashboardController@showAction')->name('user.dashboard.show');
    Route::get('user/settings', 'Module\User\SettingsController@settingsAction')->name('user.settings');
    Route::post('user/setting/update', 'Module\User\SettingsController@settingsUpdateAction')->name('user.settings.update');
    Route::get('user/security', 'Module\User\SettingsController@securityAction')->name('user.security');
    Route::post('user/security/update', 'Module\User\SettingsController@securityUpdateAction')->name('user.security.update');

    // address
    Route::get('user/setting/addresses', 'Module\User\AddressController@indexAction')->name('user.setting.addresses');
    Route::get('user/setting/address/create', 'Module\User\AddressController@createAction')->name('user.setting.address.create');
    Route::post('user/setting/addresses/store', 'Module\User\AddressController@storeAction')->name('user.setting.address.store');
    Route::get('user/setting/addresses/edit/{id}', 'Module\User\AddressController@editAction')->name('user.setting.address.edit');
    Route::post('user/setting/addresses/update', 'Module\User\AddressController@updateAction')->name('user.setting.address.update');
    Route::delete('user/setting/addresses/destroy/{id}', 'Module\User\AddressController@destroyAction')->name('user.setting.address.destroy');

    // files
    Route::get('files', 'Module\Application\FileController@indexAction')->name('module.file.index');
    Route::post('file/store', 'Module\Application\FileController@createAction')->name('module.file.store');
    Route::post('file/update', 'Module\Application\FileController@updateAction')->name('module.file.update');
    Route::delete('file/destroy/{id}', 'Module\Application\FileController@destroyAction')->name('admin.file.destroy');

    // chat
    Route::get('chat', 'Module\Chat\MessageController@showAction')->name('module.chat.show');
    Route::get('chat/inbox/{id}/{type}', 'Module\Chat\MessageController@inboxAction')->name('module.chat.inbox');
    Route::get('chat/messages/{group_id}', 'Module\Chat\MessageController@messagesAction')->name('module.chat.messages');
    Route::post('chat/send', 'Module\Chat\MessageController@sendAction')->name('module.chat.send');
    Route::get('chat/groups', 'Module\Chat\MessageController@groupsAction')->name('module.chat.groups');
    Route::get('chat/group/show/{group_id}', 'Module\Chat\MessageController@showGroupAction')->name('module.chat.show.group');
    Route::post('chat/group/store', 'Module\Chat\MessageController@storeGroupAction')->name('module.chat.group.store');
    Route::post('chat/group/update', 'Module\Chat\MessageController@updateGroupAction')->name('module.chat.group.update');
    Route::delete('chat/group/leave/{group_id}', 'Module\Chat\MessageController@leaveGroupAction')->name('module.chat.group.leave');
    Route::post('chat/group/add/{group_id}/{user_id}', 'Module\Chat\MessageController@addToGroupAction')->name('module.chat.group.add');
    Route::get('chat/group/admin/add/{group_id}/{user_id}', 'Module\Chat\MessageController@makeAdminAction')->name('module.chat.group.admin.add');
    Route::delete('chat/group/admin/remove/{group_id}/{user_id}', 'Module\Chat\MessageController@removeAdminAction')->name('module.chat.group.admin.remove');
    Route::get('chat/group/archive/{group_id}/{status}', 'Module\Chat\MessageController@archiveAction')->name('module.chat.group.archive');
    Route::delete('chat/delete/conversation/{group_id}', 'Module\Chat\MessageController@deleteConversation')->name('module.chat.message.delete.conversation');

    // application features
    Route::post('application/report', 'Module\Application\ReportController@storeAction')->name('module.report.store');

    /*
     * ------------------------------------
     * Administrator
     * ------------------------------------
     */
    Route::group(['middleware' => 'admin.access'], function () {
        // page categories
        Route::get('admin/page-categories', 'Admin\Page\PageCategoryController@indexAction')->name('admin.page.category.list');
        Route::get('admin/page-category/create', 'Admin\Page\PageCategoryController@createAction')->name('admin.page.category.create');
        Route::post('admin/page-category/store', 'Admin\Page\PageCategoryController@storeAction')->name('admin.page.category.store');
        Route::get('admin/page-category/edit/{id}', 'Admin\Page\PageCategoryController@editAction')->name('admin.page.category.edit');
        Route::post('admin/page-category/update', 'Admin\Page\PageCategoryController@updateAction')->name('admin.page.category.update');
        Route::delete('admin/page-category/destroy/{id}', 'Admin\Page\PageCategoryController@destroyAction')->name('admin.page.category.destroy');

        // pages
        Route::get('admin/pages', 'Admin\Page\PageController@indexAction')->name('admin.page.list');
        Route::get('admin/page/create', 'Admin\Page\PageController@createAction')->name('admin.page.create');
        Route::post('admin/page/store', 'Admin\Page\PageController@storeAction')->name('admin.page.store');
        Route::get('admin/page/edit/{id}', 'Admin\Page\PageController@editAction')->name('admin.page.edit');
        Route::post('admin/page/update', 'Admin\Page\PageController@updateAction')->name('admin.page.update');
        Route::delete('admin/page/destroy/{id}', 'Admin\Page\PageController@destroyAction')->name('admin.page.destroy');

        // page views
        Route::get('admin/page/views', 'Admin\Page\PageViewController@indexAction')->name('admin.page.view');

        // users
        Route::get('admin/users', 'Admin\User\UserController@indexAction')->name('admin.user.list');
        Route::get('admin/user/create', 'Admin\User\UserController@createAction')->name('admin.user.create');
        Route::post('admin/user/store', 'Admin\User\UserController@storeAction')->name('admin.user.store');
        Route::get('admin/user/edit/{id}', 'Admin\User\UserController@editAction')->name('admin.user.edit');
        Route::post('admin/user/update', 'Admin\User\UserController@updateAction')->name('admin.user.update');
        Route::get('admin/user/update/{id}/{column}/{value}', 'Admin\User\UserController@updateColumnAction')->name('admin.user.update.column');
        Route::delete('admin/user/destroy/{id}', 'Admin\User\UserController@destroyAction')->name('admin.user.destroy');

        // user tracker
        Route::get('admin/user/tracker', 'Admin\User\TrackerController@indexAction')->name('admin.user.tracker.list');

        // authentication history
        Route::get('admin/user/authentication/history', 'Admin\User\AuthenticationHistoryController@indexAction')->name('admin.user.authentication.history');

        // files
        Route::get('admin/files', 'Admin\Application\FileController@indexAction')->name('admin.file.list');
        Route::delete('admin/file/destroy/{id}', 'Admin\Application\FileController@destroyAction')->name('admin.file.destroy');

        // report reasons
        Route::get('admin/report-reasons', 'Admin\Application\ReportReasonController@indexAction')->name('admin.report.reason.list');
        Route::get('admin/report-reason/create', 'Admin\Application\ReportReasonController@createAction')->name('admin.report.reason.create');
        Route::post('admin/report-reason/store', 'Admin\Application\ReportReasonController@storeAction')->name('admin.report.reason.store');
        Route::get('admin/report-reason/edit/{id}', 'Admin\Application\ReportReasonController@editAction')->name('admin.report.reason.edit');
        Route::post('admin/report-reason/update', 'Admin\Application\ReportReasonController@updateAction')->name('admin.report.reason.update');
        Route::delete('admin/report-reason/destroy/{id}', 'Admin\Application\ReportReasonController@destroyAction')->name('admin.report.reason.destroy');

        // submitted reports
        Route::get('admin/submitted-reports', 'Admin\Application\SubmittedReportController@indexAction')->name('admin.submitted.report.list');
        Route::get('admin/submitted-report/show/{id}', 'Admin\Application\SubmittedReportController@showAction')->name('admin.submitted.report.show');
        Route::post('admin/submitted-report/message/store', 'Admin\Application\SubmittedReportController@messageAction')->name('admin.submitted.report.message.store');
        Route::get('admin/submitted-report/status/{id}/{status}', 'Admin\Application\SubmittedReportController@statusAction')->name('admin.submitted.report.status');
        Route::delete('admin/submitted-report/destroy/{id}', 'Admin\Application\SubmittedReportController@destroyAction')->name('admin.submitted.report.destroy');

        // role
        Route::get('admin/roles', 'Admin\Setting\RoleController@indexAction')->name('admin.role.list');
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
        Route::get('admin/fcm-notifications', 'Admin\Notification\FCMController@indexAction')->name('admin.fcm.notification.list');
        Route::get('admin/fcm-notification/create', 'Admin\Notification\FCMController@createAction')->name('admin.fcm.notification.create');
        Route::post('admin/fcm-notification/store', 'Admin\Notification\FCMController@storeAction')->name('admin.fcm.notification.store');
        Route::get('admin/fcm-notification/edit/{id}', 'Admin\Notification\FCMController@editAction')->name('admin.fcm.notification.edit');
        Route::post('admin/fcm-notification/update', 'Admin\Notification\FCMController@updateAction')->name('admin.fcm.notification.update');
        Route::delete('admin/fcm-notification/destroy/{id}', 'Admin\Notification\FCMController@destroyAction')->name('admin.fcm.notification.destroy');

        // activity logs
        Route::get('admin/activity-logs', 'Admin\Setting\ActivityLogController@indexAction')->name('admin.activityLog.list');

        // settings
        Route::get('admin/settings', 'Admin\Setting\SettingController@showAction')->name('admin.setting.show');
        Route::post('admin/setting/save', 'Admin\Setting\SettingController@saveSettings')->name('admin.setting.save');
        Route::get('admin/setting/application', 'Admin\Setting\SettingController@indexAction')->name('admin.setting.list');
        Route::get('admin/setting/application/create', 'Admin\Setting\SettingController@createAction')->name('admin.setting.create');
        Route::post('admin/setting/application/store', 'Admin\Setting\SettingController@storeAction')->name('admin.setting.store');
        Route::get('admin/setting/application/edit/{id}', 'Admin\Setting\SettingController@editAction')->name('admin.setting.edit');
        Route::post('admin/setting/application/update', 'Admin\Setting\SettingController@updateAction')->name('admin.setting.update');
        Route::delete('admin/setting/application/destroy/{id}', 'Admin\Setting\SettingController@destroyAction')->name('admin.setting.destroy');

        // setting category
        Route::get('admin/setting/categories', 'Admin\Setting\SettingCategoryController@indexAction')->name('admin.setting.category.list');
        Route::get('admin/setting/category/create', 'Admin\Setting\SettingCategoryController@createAction')->name('admin.setting.category.create');
        Route::post('admin/setting/category/store', 'Admin\Setting\SettingCategoryController@storeAction')->name('admin.setting.category.store');
        Route::get('admin/setting/category/edit/{id}', 'Admin\Setting\SettingCategoryController@editAction')->name('admin.setting.category.edit');
        Route::post('admin/setting/category/update', 'Admin\Setting\SettingCategoryController@updateAction')->name('admin.setting.category.update');
        Route::delete('admin/setting/category/destroy/{id}', 'Admin\Setting\SettingCategoryController@destroyAction')->name('admin.setting.category.destroy');

        // country
        Route::get('admin/setting/countries', 'Admin\Setting\Location\CountryController@indexAction')->name('admin.setting.country.list');
        Route::get('admin/setting/country/create', 'Admin\Setting\Location\CountryController@createAction')->name('admin.setting.country.create');
        Route::post('admin/setting/country/store', 'Admin\Setting\Location\CountryController@storeAction')->name('admin.setting.country.store');
        Route::get('admin/setting/country/edit/{id}', 'Admin\Setting\Location\CountryController@editAction')->name('admin.setting.country.edit');
        Route::post('admin/setting/country/update', 'Admin\Setting\Location\CountryController@updateAction')->name('admin.setting.country.update');
        Route::delete('admin/setting/country/destroy/{id}', 'Admin\Setting\Location\CountryController@destroyAction')->name('admin.setting.country.destroy');

        // city
        Route::get('admin/setting/cities', 'Admin\Setting\Location\CityController@indexAction')->name('admin.setting.city.list');
        Route::get('admin/setting/city/create', 'Admin\Setting\Location\CityController@createAction')->name('admin.setting.city.create');
        Route::post('admin/setting/city/store', 'Admin\Setting\Location\CityController@storeAction')->name('admin.setting.city.store');
        Route::get('admin/setting/city/edit/{id}', 'Admin\Setting\Location\CityController@editAction')->name('admin.setting.city.edit');
        Route::post('admin/setting/city/update', 'Admin\Setting\Location\CityController@updateAction')->name('admin.setting.city.update');
        Route::delete('admin/setting/city/destroy/{id}', 'Admin\Setting\Location\CityController@destroyAction')->name('admin.setting.city.destroy');
    });
});

