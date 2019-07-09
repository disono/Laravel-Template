<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Vendor\Facades;

use App\Models\ActivityLog;
use App\Models\App\PageReport;
use App\Models\App\PageReportMessage;
use App\Models\App\PageReportReason;
use App\Models\AuthenticationHistory;
use App\Models\AuthorizationRole;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatGroupMember;
use App\Models\Chat\ChatMessage;
use App\Models\City;
use App\Models\Country;
use App\Models\CSV\User\UsersExport;
use App\Models\CSV\User\UsersImport;
use App\Models\File;
use App\Models\FirebaseNotification;
use App\Models\FirebaseToken;
use App\Models\Page;
use App\Models\PageCategory;
use App\Models\PageClassification;
use App\Models\PageView;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SettingCategory;
use App\Models\SocialAuthentication;
use App\Models\State;
use App\Models\Token;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPhone;
use App\Models\UserTracker;
use App\Models\Vendor\SMSIntegrator;
use App\Models\Verification;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->_models() as $key => $model) {
            $this->app->singleton($key, function () use ($model) {
                return new $model;
            });
        }
    }

    private function _models()
    {
        return [
            'PageReport' => PageReport::class,
            'PageReportMessage' => PageReportMessage::class,
            'PageReportReason' => PageReportReason::class,

            'ChatGroup' => ChatGroup::class,
            'ChatGroupMember' => ChatGroupMember::class,
            'ChatMessage' => ChatMessage::class,

            'UsersExport' => UsersExport::class,
            'UsersImport' => UsersImport::class,

            'ActivityLog' => ActivityLog::class,
            'AuthenticationHistory' => AuthenticationHistory::class,
            'AuthorizationRole' => AuthorizationRole::class,
            'City' => City::class,
            'Country' => Country::class,
            'File' => File::class,
            'FirebaseNotification' => FirebaseNotification::class,
            'FirebaseToken' => FirebaseToken::class,
            'Page' => Page::class,
            'PageCategory' => PageCategory::class,
            'PageClassification' => PageClassification::class,
            'PageView' => PageView::class,
            'PasswordReset' => PasswordReset::class,
            'Role' => Role::class,
            'Setting' => Setting::class,
            'SettingCategory' => SettingCategory::class,
            'SocialAuthentication' => SocialAuthentication::class,
            'State' => State::class,
            'Token' => Token::class,
            'User' => User::class,
            'UserAddress' => UserAddress::class,
            'UserPhone' => UserPhone::class,
            'UserTracker' => UserTracker::class,
            'Verification' => Verification::class,

            'SMSIntegrator' => SMSIntegrator::class,
        ];
    }
}