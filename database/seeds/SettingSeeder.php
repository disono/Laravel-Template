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

                'is_public' => $value[7],
                'created_at' => sqlDate()
            ]);
        }
    }

    private function _settings()
    {
        return [
            ['Application Name', 'title', 'Webmons Development Studio', 'text', NULL, 1, NULL, 1],
            ['Default App URL', 'appUrl', 'http://domain.com', 'text', NULL, 1, NULL, 1],
            ['Description', 'description', 'Project Template', 'textarea', NULL, 1, NULL, 0],
            ['Author', 'author', 'Archie Disono', 'text', NULL, 1, NULL, 0],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'textarea', NULL, 1, NULL, 0],
            ['Theme Version', 'themeVersion', '1.0', 'text', NULL, 1, NULL, 0],
            ['Theme Name', 'theme', 'master', 'text', NULL, 1, NULL, 0],
            ['Items per page(pagination)', 'pagination', '12', 'text', NULL, 1, NULL, 1],
            ['Subscriber Form', 'subscriberForm', 'enabled', 'checkbox_single', 'enabled', 1, NULL, 0],
            ['Chat/Messaging', 'chat', 'enabled', 'checkbox_single', 'enabled', 1, NULL, 1],
            ['Allow user to delete page reports', 'allowDelPageReport', 'enabled', 'checkbox_single', 'enabled', 1, NULL, 1],

            ['Minimum age for registration', 'minimumAgeForRegistration', '13', 'text', NULL, 2, NULL, 1],
            ['Maximum age for registration', 'maximumAgeForRegistration', '120', 'text', NULL, 2, NULL, 1],

            ['File size limit(image)', 'fileSizeLimitImage', '3000', 'text', NULL, 3, NULL, 1],
            ['File size limit(default)', 'fileSizeLimit', '3000', 'text', NULL, 3, NULL, 1],

            ['Facebook Page', 'socialFacebook', '#', 'text', NULL, 4, NULL, 1],
            ['Twitter Page', 'socialTwitter', '#', 'text', NULL, 4, NULL, 1],
            ['IG Page', 'socialIG', '#', 'text', NULL, 4, NULL, 1],

            ['Enable User Registration', 'authUserRegistration', 'enabled', 'checkbox_single', 'enabled', 5, NULL, 1],
            ['Auth Facebook', 'authSocialFacebook', 'enabled', 'checkbox_single', 'enabled', 5, NULL, 1],
            ['JWT Authentication', 'jwtAuthentication', 'enabled', 'checkbox_single', 'enabled', 5, NULL, 0],

            ['Account Enabled', 'accountEnabled', 'enabled', 'checkbox_single', 'enabled', 5, NULL, 1],
            ['Email verification', 'emailVerification', 'enabled', 'checkbox_single', 'enabled', 5, NULL, 1],
            ['Phone verification', 'phoneVerification', NULL, 'checkbox_single', 'enabled', 5, NULL, 1],
            ['Address verification', 'addressVerification', NULL, 'checkbox_single', 'enabled', 5, NULL, 1],
            ['Threshold for address verification', 'addressVerificationThreshold', '3', 'text', NULL, 5, 'Zero means no threshold.', 0],
            ['Threshold for phone verification', 'phoneVerificationThreshold', '3', 'text', NULL, 5, 'Zero means no threshold.', 0],
            ['Threshold for email verification', 'emailVerificationThreshold', '0', 'text', NULL, 5, 'Zero means no threshold.', 0],
            ['Email verification expiration', 'emailVerificationExpiration', '1440', 'text', NULL, 5, 'Expiration is in minutes.', 0],
            ['Phone verification expiration', 'phoneVerificationExpiration', '1440', 'text', NULL, 5, 'Expiration is in minutes.', 0],

            ['FCM', 'fcm', NULL, 'checkbox_single', 'enabled', 6, NULL, 1],
            ['FCM Topics', 'fcm_topics', 'news', 'checkbox_multiple', 'news,marketing', 6, NULL, 1],

            ['SocketIO', 'socketIO', NULL, 'checkbox_single', 'enabled', 6, NULL, 1],
            ['SocketIO App Name', 'socketIOAppName', 'appName', 'text', NULL, 6, NULL, 1],
            ['SocketIO Server', 'socketIOServer', 'http://localhost:4000', 'text', NULL, 6, NULL, 1],
            ['SocketIO Secret Key', 'socketIOSecretKey', '*TWsJFnA6wvJr-K3hzzqZn%A-W!2mKWw', 'text', NULL, 6, NULL, 0],
            ['SocketIO Key Expiration', 'socketIOExpiration', '1', 'text', NULL, 6, 'Expired in minutes.', 1],
        ];
    }
}
