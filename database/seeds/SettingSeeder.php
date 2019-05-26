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
                'category_setting_id' => $value[5],
                'created_at' => sqlDate()
            ]);
        }
    }

    private function _settings()
    {
        return [
            ['Application Name', 'title', 'Webmons Development Studio', 'text', null, 1],
            ['Default App URL', 'appUrl', 'http://domain.com', 'text', null, 1],
            ['Description', 'description', 'Project Template', 'textarea', null, 1],
            ['Author', 'author', 'Archie Disono', 'text', null, 1],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'textarea', null, 1],
            ['Theme Version', 'themeVersion', '1.0', 'text', null, 1],
            ['Theme Name', 'theme', 'master', 'text', null, 1],
            ['Subscriber Form', 'subscriberForm', 'enabled', 'select', 'enabled,disabled', 1],
            ['Items per page(pagination)', 'pagination', '12', 'text', null, 1],
            ['Chat/Messaging', 'chat', 'enabled', 'select', 'enabled,disabled', 1],

            ['Minimum age for registration', 'minimumAgeForRegistration', '13', 'text', null, 2],
            ['Maximum age for registration', 'maximumAgeForRegistration', '120', 'text', null, 2],

            ['File size limit(image)', 'fileSizeLimitImage', '3000', 'text', null, 3],
            ['File size limit(default)', 'fileSizeLimit', '3000', 'text', null, 3],

            ['Facebook Page', 'socialFacebook', '#', 'text', null, 4],
            ['Twitter Page', 'socialTwitter', '#', 'text', null, 4],
            ['IG Page', 'socialIG', '#', 'text', null, 4],

            ['Enable User Registration', 'authUserRegistration', 'enabled', 'select', 'enabled,disabled', 5],
            ['Auth Facebook', 'authSocialFacebook', 'enabled', 'select', 'enabled,disabled', 5],
            ['Email Verification', 'user_email_verification', 'enabled', 'select', 'enabled,disabled', 5],
            ['Phone Verification', 'user_phone_verification', 'disabled', 'select', 'enabled,disabled', 5],
            ['Account Enabled', 'user_account_enabled', 'enabled', 'select', 'enabled,disabled', 5],

            ['FCM', 'fcm', 'disabled', 'select', 'enabled,disabled', 6],
            ['FCM Topics', 'fcm_topics', 'news', 'checkbox', 'news,marketing', 6],
        ];
    }
}
