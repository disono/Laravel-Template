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

                'description' => $value[6],

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
            ['Application Name', 'title', 'Webmons Development Studio', 'text', NULL, 1, NULL],
            ['Default App URL', 'appUrl', 'http://domain.com', 'text', NULL, 1, NULL],
            ['Description', 'description', 'Project Template', 'textarea', NULL, 1, NULL],
            ['Author', 'author', 'Archie Disono', 'text', NULL, 1, NULL],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'textarea', NULL, 1, NULL],
            ['Theme Version', 'themeVersion', '1.0', 'text', NULL, 1, NULL],
            ['Theme Name', 'theme', 'master', 'text', NULL, 1, NULL],
            ['Items per page(pagination)', 'pagination', '12', 'text', NULL, 1, NULL],
            ['Subscriber Form', 'subscriberForm', 'enabled', 'checkbox_single', 'enabled', 1, NULL],
            ['Chat/Messaging', 'chat', 'enabled', 'checkbox_single', 'enabled', 1, NULL],

            ['Minimum age for registration', 'minimumAgeForRegistration', '13', 'text', NULL, 2, NULL],
            ['Maximum age for registration', 'maximumAgeForRegistration', '120', 'text', NULL, 2, NULL],

            ['File size limit(image)', 'fileSizeLimitImage', '3000', 'text', NULL, 3, NULL],
            ['File size limit(default)', 'fileSizeLimit', '3000', 'text', NULL, 3, NULL],

            ['Facebook Page', 'socialFacebook', '#', 'text', NULL, 4, NULL],
            ['Twitter Page', 'socialTwitter', '#', 'text', NULL, 4, NULL],
            ['IG Page', 'socialIG', '#', 'text', NULL, 4, NULL],

            ['Enable User Registration', 'authUserRegistration', 'enabled', 'checkbox_single', 'enabled', 5, NULL],
            ['Auth Facebook', 'authSocialFacebook', 'enabled', 'checkbox_single', 'enabled', 5, NULL],
            ['JWT Authentication', 'jwtAuthentication', 'enabled', 'checkbox_single', 'enabled', 5, NULL],

            ['Account Enabled', 'accountEnabled', 'enabled', 'checkbox_single', 'enabled', 5, NULL],
            ['Email verification', 'emailVerification', 'enabled', 'checkbox_single', 'enabled', 5, NULL],
            ['Phone verification', 'phoneVerification', NULL, 'checkbox_single', 'enabled', 5, NULL],
            ['Address verification', 'addressVerification', NULL, 'checkbox_single', 'enabled', 5, NULL],
            ['Threshold for address verification', 'addressVerificationThreshold', '3', 'text', NULL, 5, 'Zero means no threshold.'],
            ['Threshold for phone verification', 'phoneVerificationThreshold', '3', 'text', NULL, 5, 'Zero means no threshold.'],
            ['Threshold for email verification', 'emailVerificationThreshold', '0', 'text', NULL, 5, 'Zero means no threshold.'],

            ['FCM', 'fcm', NULL, 'checkbox_single', 'enabled', 6, NULL],
            ['FCM Topics', 'fcm_topics', 'news', 'checkbox_multiple', 'news,marketing', 6, NULL],
        ];
    }
}
