<?php

// page
Route::get('/', 'Module\Page\PageController@homeAction')->name('module.page.home');
Route::get('p/{slug}', 'Module\Page\PageController@showAction')->name('module.page.show.browse');
Route::get('p/category/{slug}', 'Module\Page\PageController@categoryAction')->name('module.page.category.browse');
Route::get('p/tag/{tag}', 'Module\Page\PageController@tagAction')->name('module.page.tag.browse');
Route::get('p/archive/{year}/{month}', 'Module\Page\PageController@archiveAction')->name('module.page.archive.browse');

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
Route::get('view/{type}', 'Module\Application\ViewController@viewAction')->name('module.application.view');

// stream
Route::get('stream/video/{file}', 'Module\Application\FileController@streamVideoAction')->name('module.file.video.stream');
Route::get('stream/audio/{file}', 'Module\Application\FileController@streamAudioAction')->name('module.file.audio.stream');
Route::get('stream/image/{file}', 'Module\Application\FileController@streamImageAction')->name('module.file.image.stream');

// location
Route::get('location/cities/{country_id}', 'Module\Application\LocationController@citiesAction')->name('module.location.cities');

// development
Route::get('dev', 'Module\Page\DevelopmentController@applicationAction')->name('development.application');
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
    Route::get('u/search', 'Module\User\ProfileController@searchAction')->name('module.user.browse');
    Route::get('u/{username}', 'Module\User\ProfileController@showAction')->name('module.user.details');
    Route::get('u/security/token', 'Module\User\ProfileController@tokenAction')->name('module.user.token');

    // user
    Route::get('dashboard', 'Module\User\DashboardController@showAction')->name('module.user.dashboard');
    Route::get('user/settings', 'Module\User\SettingsController@generalAction')->name('module.user.setting.general');
    Route::post('user/setting/update', 'Module\User\SettingsController@generalUpdateAction')->name('module.user.setting.general.update');
    Route::get('user/security', 'Module\User\SettingsController@securityAction')->name('module.user.setting.security');
    Route::post('user/security/update', 'Module\User\SettingsController@securityUpdateAction')->name('module.user.setting.security.update');

    // address
    Route::get('user/setting/addresses', 'Module\User\AddressController@indexAction')->name('module.user.setting.address.browse');
    Route::get('user/setting/address/create', 'Module\User\AddressController@createAction')->name('module.user.setting.address.create');
    Route::post('user/setting/addresses/store', 'Module\User\AddressController@storeAction')->name('module.user.setting.address.store');
    Route::get('user/setting/addresses/edit/{id}', 'Module\User\AddressController@editAction')->name('module.user.setting.address.edit');
    Route::post('user/setting/addresses/update', 'Module\User\AddressController@updateAction')->name('module.user.setting.address.update');
    Route::delete('user/setting/addresses/destroy/{id}', 'Module\User\AddressController@destroyAction')->name('module.user.setting.address.destroy');

    // phone
    Route::get('user/setting/phones', 'Module\User\PhoneController@indexAction')->name('module.user.setting.phone.browse');
    Route::get('user/setting/phone/create', 'Module\User\PhoneController@createAction')->name('module.user.setting.phone.create');
    Route::post('user/setting/phone/store', 'Module\User\PhoneController@storeAction')->name('module.user.setting.phone.store');
    Route::get('user/setting/phone/edit/{id}', 'Module\User\PhoneController@editAction')->name('module.user.setting.phone.edit');
    Route::post('user/setting/phone/update', 'Module\User\PhoneController@updateAction')->name('module.user.setting.phone.update');
    Route::delete('user/setting/phone/destroy/{id}', 'Module\User\PhoneController@destroyAction')->name('module.user.setting.phone.destroy');

    // page reports
    Route::get('page-reports', 'Module\User\PageReportController@indexAction')->name('module.pageReport.browse');
    Route::get('page-report/show/{id}', 'Module\User\PageReportController@showAction')->name('module.pageReport.details');
    Route::post('page-report/message/store', 'Module\User\PageReportController@messageAction')->name('module.pageReport.sendMessage.store');
    Route::get('page-report/status/{id}', 'Module\User\PageReportController@statusAction')->name('module.pageReport.status.update');
    Route::delete('page-report/destroy/{id}', 'Module\User\PageReportController@destroyAction')->name('module.pageReport.destroy');

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

    // application report
    Route::post('application/report', 'Module\Application\ReportController@storeAction')->name('module.application.report.store');

    // application files
    Route::get('files', 'Module\Application\FileController@indexAction')->name('module.application.file.browse');
    Route::post('file/store', 'Module\Application\FileController@createAction')->name('module.application.file.store');
    Route::post('file/update', 'Module\Application\FileController@updateAction')->name('module.application.file.update');
    Route::delete('file/destroy/{id}', 'Module\Application\FileController@destroyAction')->name('module.application.file.destroy');

    // application settings
    Route::get('application/settings', 'Module\Application\SettingController@detailsAction')->name('module.application.settings.details');

    /*
     * ------------------------------------
     * Administrator
     * ------------------------------------
     */
    Route::group(['middleware' => 'admin.access'], function () {
        // users
        Route::get('admin/users', 'Admin\User\UserController@indexAction')->name('admin.user.browse');
        Route::get('admin/user/create', 'Admin\User\UserController@createAction')->name('admin.user.create');
        Route::post('admin/user/store', 'Admin\User\UserController@storeAction')->name('admin.user.store');
        Route::get('admin/user/edit/{id}', 'Admin\User\UserController@editAction')->name('admin.user.edit');
        Route::post('admin/user/update', 'Admin\User\UserController@updateAction')->name('admin.user.update');
        Route::get('admin/user/update/{id}/{column}/{value}', 'Admin\User\UserController@updateColumnAction')->name('admin.user.column.update');
        Route::delete('admin/user/destroy/{id}', 'Admin\User\UserController@destroyAction')->name('admin.user.destroy');

        // activity logs
        Route::get('admin/activity-logs', 'Admin\User\ActivityLogController@indexAction')->name('admin.activityLog.browse');
        Route::get('admin/activity-logs/details/{id}', 'Admin\User\ActivityLogController@detailsAction')->name('admin.activityLog.details');
        Route::delete('admin/activity-logs/destroy/{id}', 'Admin\User\ActivityLogController@destroyAction')->name('admin.activityLog.destroy');

        // user tracker
        Route::get('admin/user/tracker', 'Admin\User\TrackerController@indexAction')->name('admin.userTracker.browse');
        Route::delete('admin/user/tracker/destroy/{id}', 'Admin\User\TrackerController@destroyAction')->name('admin.userTracker.destroy');

        // authentication history
        Route::get('admin/user/authentication/history', 'Admin\User\AuthenticationHistoryController@indexAction')->name('admin.userAuthenticationHistory.browse');
        Route::delete('admin/user/authentication/destroy/{id}', 'Admin\User\AuthenticationHistoryController@destroyAction')->name('admin.userAuthenticationHistory.destroy');

        // page categories
        Route::get('admin/page-categories', 'Admin\Page\PageCategoryController@indexAction')->name('admin.pageCategory.browse');
        Route::get('admin/page-category/create', 'Admin\Page\PageCategoryController@createAction')->name('admin.pageCategory.create');
        Route::post('admin/page-category/store', 'Admin\Page\PageCategoryController@storeAction')->name('admin.pageCategory.store');
        Route::get('admin/page-category/edit/{id}', 'Admin\Page\PageCategoryController@editAction')->name('admin.pageCategory.edit');
        Route::post('admin/page-category/update', 'Admin\Page\PageCategoryController@updateAction')->name('admin.pageCategory.update');
        Route::get('admin/page-category/update/{column}/{status}/{id}', 'Admin\Page\PageCategoryController@updateStatusAction')->name('admin.pageCategory.status.update');
        Route::delete('admin/page-category/destroy/{id}', 'Admin\Page\PageCategoryController@destroyAction')->name('admin.pageCategory.destroy');

        // pages
        Route::get('admin/pages', 'Admin\Page\PageController@indexAction')->name('admin.page.browse');
        Route::get('admin/page/create', 'Admin\Page\PageController@createAction')->name('admin.page.create');
        Route::post('admin/page/store', 'Admin\Page\PageController@storeAction')->name('admin.page.store');
        Route::get('admin/page/edit/{id}', 'Admin\Page\PageController@editAction')->name('admin.page.edit');
        Route::post('admin/page/update', 'Admin\Page\PageController@updateAction')->name('admin.page.update');
        Route::get('admin/page/update/{column}/{status}/{id}', 'Admin\Page\PageController@updateStatusAction')->name('admin.page.status.update');
        Route::delete('admin/page/destroy/{id}', 'Admin\Page\PageController@destroyAction')->name('admin.page.destroy');

        // page views
        Route::get('admin/page/views', 'Admin\Page\PageViewController@indexAction')->name('admin.pageView.browse');
        Route::delete('admin/page/view/destroy/{id}', 'Admin\Page\PageViewController@destroyAction')->name('admin.pageView.destroy');

        // report reasons
        Route::get('admin/report-reasons', 'Admin\Application\ReportReasonController@indexAction')->name('admin.reportReason.browse');
        Route::get('admin/report-reason/create', 'Admin\Application\ReportReasonController@createAction')->name('admin.reportReason.create');
        Route::post('admin/report-reason/store', 'Admin\Application\ReportReasonController@storeAction')->name('admin.reportReason.store');
        Route::get('admin/report-reason/edit/{id}', 'Admin\Application\ReportReasonController@editAction')->name('admin.reportReason.edit');
        Route::post('admin/report-reason/update', 'Admin\Application\ReportReasonController@updateAction')->name('admin.reportReason.update');
        Route::delete('admin/report-reason/destroy/{id}', 'Admin\Application\ReportReasonController@destroyAction')->name('admin.reportReason.destroy');

        // page reports
        Route::get('admin/page-reports', 'Admin\Application\PageReportController@indexAction')->name('admin.pageReport.browse');
        Route::get('admin/page-report/submitted/by', 'Admin\Application\PageReportController@searchSubmittedByAction')->name('admin.pageReport.searchSubmittedBy.browse');
        Route::get('admin/page-report/processed/by', 'Admin\Application\PageReportController@searchProcessedByAction')->name('admin.pageReport.searchProcessedBy.browse');
        Route::get('admin/page-report/show/{id}', 'Admin\Application\PageReportController@showAction')->name('admin.pageReport.details');
        Route::post('admin/page-report/message/store', 'Admin\Application\PageReportController@messageAction')->name('admin.pageReport.sendMessage.store');
        Route::get('admin/page-report/status/{id}/{status}', 'Admin\Application\PageReportController@statusAction')->name('admin.pageReport.status.update');
        Route::delete('admin/page-report/destroy/{id}', 'Admin\Application\PageReportController@destroyAction')->name('admin.pageReport.destroy');

        // files
        Route::get('admin/files', 'Admin\Application\FileController@indexAction')->name('admin.file.browse');
        Route::delete('admin/file/destroy/{id}', 'Admin\Application\FileController@destroyAction')->name('admin.file.destroy');

        // csv
        Route::get('admin/csv/import', 'Admin\Application\CSVController@csvImportAction')->name('admin.csvImport.create');
        Route::post('admin/csv/import/store', 'Admin\Application\CSVController@csvImportStoreAction')->name('admin.csvImport.store');
        Route::get('admin/csv/export', 'Admin\Application\CSVController@csvExportAction')->name('admin.csvExport.browse');
        Route::get('admin/csv/template', 'Admin\Application\CSVController@csvTemplateAction')->name('admin.csvTemplate.browse');

        // role
        Route::get('admin/roles', 'Admin\Setting\RoleController@indexAction')->name('admin.role.browse');
        Route::get('admin/role/create', 'Admin\Setting\RoleController@createAction')->name('admin.role.create');
        Route::post('admin/role/store', 'Admin\Setting\RoleController@storeAction')->name('admin.role.store');
        Route::get('admin/role/edit/{id}', 'Admin\Setting\RoleController@editAction')->name('admin.role.edit');
        Route::post('admin/role/update', 'Admin\Setting\RoleController@updateAction')->name('admin.role.update');
        Route::delete('admin/role/destroy/{id}', 'Admin\Setting\RoleController@destroyAction')->name('admin.role.destroy');

        // authorization role
        Route::get('admin/auth/role/{role_id}', 'Admin\Setting\AuthorizationRoleController@editAction')->name('admin.authRole.edit');
        Route::post('admin/auth/role/update', 'Admin\Setting\AuthorizationRoleController@updateAction')->name('admin.authRole.update');

        // FCM Notifications
        Route::get('admin/fcm-notifications', 'Admin\Notification\FCMController@indexAction')->name('admin.fcmNotification.browse');
        Route::get('admin/fcm-notification/tokens', 'Admin\Notification\FCMController@tokenListAction')->name('admin.fcmNotification.token.browse');
        Route::get('admin/fcm-notification/create', 'Admin\Notification\FCMController@createAction')->name('admin.fcmNotification.create');
        Route::post('admin/fcm-notification/store', 'Admin\Notification\FCMController@storeAction')->name('admin.fcmNotification.store');
        Route::get('admin/fcm-notification/edit/{id}', 'Admin\Notification\FCMController@editAction')->name('admin.fcmNotification.edit');
        Route::post('admin/fcm-notification/update', 'Admin\Notification\FCMController@updateAction')->name('admin.fcmNotification.update');
        Route::delete('admin/fcm-notification/destroy/{id}', 'Admin\Notification\FCMController@destroyAction')->name('admin.fcmNotification.destroy');

        // SocketIO Notifications
        Route::get('admin/socket-io-notifications', 'Admin\Notification\SocketIOController@indexAction')->name('admin.socketIoNotification.browse');
        Route::get('admin/socket-io-notification/tokens', 'Admin\Notification\SocketIOController@tokenListAction')->name('admin.socketIoNotification.token.browse');
        Route::get('admin/socket-io-notification/create', 'Admin\Notification\SocketIOController@createAction')->name('admin.socketIoNotification.create');
        Route::post('admin/socket-io-notification/store', 'Admin\Notification\SocketIOController@storeAction')->name('admin.socketIoNotification.store');
        Route::get('admin/socket-io-notification/edit/{id}', 'Admin\Notification\SocketIOController@editAction')->name('admin.socketIoNotification.edit');
        Route::post('admin/socket-io-notification/update', 'Admin\Notification\SocketIOController@updateAction')->name('admin.socketIoNotification.update');
        Route::delete('admin/socket-io-notification/destroy/{id}', 'Admin\Notification\SocketIOController@destroyAction')->name('admin.socketIoNotification.destroy');

        // country
        Route::get('admin/setting/countries', 'Admin\Setting\Location\CountryController@indexAction')->name('admin.settingCountry.browse');
        Route::get('admin/setting/country/create', 'Admin\Setting\Location\CountryController@createAction')->name('admin.settingCountry.create');
        Route::post('admin/setting/country/store', 'Admin\Setting\Location\CountryController@storeAction')->name('admin.settingCountry.store');
        Route::get('admin/setting/country/edit/{id}', 'Admin\Setting\Location\CountryController@editAction')->name('admin.settingCountry.edit');
        Route::post('admin/setting/country/update', 'Admin\Setting\Location\CountryController@updateAction')->name('admin.settingCountry.update');
        Route::delete('admin/setting/country/destroy/{id}', 'Admin\Setting\Location\CountryController@destroyAction')->name('admin.settingCountry.destroy');

        // city
        Route::get('admin/setting/cities', 'Admin\Setting\Location\CityController@indexAction')->name('admin.settingCity.browse');
        Route::get('admin/setting/city/create', 'Admin\Setting\Location\CityController@createAction')->name('admin.settingCity.create');
        Route::post('admin/setting/city/store', 'Admin\Setting\Location\CityController@storeAction')->name('admin.settingCity.store');
        Route::get('admin/setting/city/edit/{id}', 'Admin\Setting\Location\CityController@editAction')->name('admin.settingCity.edit');
        Route::post('admin/setting/city/update', 'Admin\Setting\Location\CityController@updateAction')->name('admin.settingCity.update');
        Route::delete('admin/setting/city/destroy/{id}', 'Admin\Setting\Location\CityController@destroyAction')->name('admin.settingCity.destroy');

        // settings
        Route::get('admin/settings', 'Admin\Setting\SettingController@showAction')->name('admin.setting.details');
        Route::post('admin/setting/save', 'Admin\Setting\SettingController@saveSettings')->name('admin.setting.save');
        Route::get('admin/setting/application', 'Admin\Setting\SettingController@indexAction')->name('admin.setting.browse');
        Route::get('admin/setting/application/create', 'Admin\Setting\SettingController@createAction')->name('admin.setting.create');
        Route::post('admin/setting/application/store', 'Admin\Setting\SettingController@storeAction')->name('admin.setting.store');
        Route::get('admin/setting/application/edit/{id}', 'Admin\Setting\SettingController@editAction')->name('admin.setting.edit');
        Route::post('admin/setting/application/update', 'Admin\Setting\SettingController@updateAction')->name('admin.setting.update');
        Route::delete('admin/setting/application/destroy/{id}', 'Admin\Setting\SettingController@destroyAction')->name('admin.setting.destroy');

        // setting category
        Route::get('admin/setting/categories', 'Admin\Setting\SettingCategoryController@indexAction')->name('admin.settingCategory.browse');
        Route::get('admin/setting/category/create', 'Admin\Setting\SettingCategoryController@createAction')->name('admin.settingCategory.create');
        Route::post('admin/setting/category/store', 'Admin\Setting\SettingCategoryController@storeAction')->name('admin.settingCategory.store');
        Route::get('admin/setting/category/edit/{id}', 'Admin\Setting\SettingCategoryController@editAction')->name('admin.settingCategory.edit');
        Route::post('admin/setting/category/update', 'Admin\Setting\SettingCategoryController@updateAction')->name('admin.settingCategory.update');
        Route::delete('admin/setting/category/destroy/{id}', 'Admin\Setting\SettingCategoryController@destroyAction')->name('admin.settingCategory.destroy');
    });
});

