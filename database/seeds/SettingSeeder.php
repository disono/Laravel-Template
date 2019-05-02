<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->_settings() as $value) {
            DB::table('settings')->insert([
                'name' => ucfirst($value[0]),
                'key' => $value[1],
                'value' => $value[2],

                'input_type' => $value[3],
                'input_value' => $value[4],
                'category' => $value[5],
                'created_at' => sqlDate()
            ]);
        }
    }

    private function _settings()
    {
        return [
            ['Application Name', 'title', 'Webmons Development Studio', 'text', null, 'Application Settings'],
            ['Default App URL', 'appUrl', 'http://domain.com', 'text', null, 'Application Settings'],
            ['Description', 'description', 'Project Template', 'textarea', null, 'Application Settings'],
            ['Author', 'author', 'Archie Disono', 'text', null, 'Application Settings'],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'textarea', null, 'Application Settings'],
            ['Theme Version', 'themeVersion', '1.0', 'text', null, 'Application Settings'],
            ['Theme Name', 'theme', 'master', 'text', null, 'Application Settings'],
            ['Subscriber Form', 'subscriberForm', 'enabled', 'select', 'enabled,disabled', 'Application Settings'],
            ['Items per page(pagination)', 'pagination', '12', 'text', null, 'Application Settings'],
            ['Chat/Messaging', 'chat', 'enabled', 'select', 'enabled,disabled', 'Application Settings'],

            ['Minimum age for registration', 'minimumAgeForRegistration', '13', 'text', null, 'User Registration'],
            ['Maximum age for registration', 'maximumAgeForRegistration', '120', 'text', null, 'User Registration'],

            ['File size limit(image)', 'fileSizeLimitImage', '3000', 'text', null, 'File Options'],
            ['File size limit(default)', 'fileSizeLimit', '3000', 'text', null, 'File Options'],

            ['Facebook Page', 'socialFacebook', '#', 'text', null, 'Social Media Links'],
            ['Twitter Page', 'socialTwitter', '#', 'text', null, 'Social Media Links'],
            ['IG Page', 'socialIG', '#', 'text', null, 'Social Media Links'],

            ['Enable User Registration', 'authUserRegistration', 'enabled', 'select', 'enabled,disabled', 'Authentication'],
            ['Auth Facebook', 'authSocialFacebook', 'enabled', 'select', 'enabled,disabled', 'Authentication'],
            ['Email Verification', 'user_email_verification', 'enabled', 'select', 'enabled,disabled', 'Authentication'],
            ['Phone Verification', 'user_phone_verification', 'disabled', 'select', 'enabled,disabled', 'Authentication'],
            ['Account Enabled', 'user_account_enabled', 'enabled', 'select', 'enabled,disabled', 'Authentication'],

            ['FCM', 'fcm', 'disabled', 'select', 'enabled,disabled', 'Broadcast'],
            ['FCM Topics', 'fcm_topics', 'news', 'checkbox', 'news,marketing', 'Broadcast'],
        ];
    }
}
