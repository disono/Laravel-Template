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
                'created_at' => sqlDate()
            ]);
        }
    }

    private function _settings()
    {
        return [
            ['Application Name', 'title', 'Webmons Development Studio', 'text', null],
            ['Description', 'description', 'Project Template', 'textarea', null],
            ['Author', 'author', 'Archie Disono', 'text', null],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'textarea', null],
            ['Theme Version', 'themeVersion', '1.0', 'text', null],
            ['Theme Name', 'theme', 'master', 'text', null],

            ['File size limit(image)', 'fileSizeLimitImage', '3000', 'text', null],
            ['File size limit(default)', 'fileSizeLimit', '3000', 'text', null],
            ['Minimum age for registration', 'minimumAgeForRegistration', '13', 'text', null],
            ['Maximum age for registration', 'maximumAgeForRegistration', '120', 'text', null],
            ['Items per page(pagination)', 'pagination', '12', 'text', null],

            ['Facebook Page', 'socialFacebook', '#', 'text', null],
            ['Twitter Page', 'socialTwitter', '#', 'text', null],
            ['IG Page', 'socialIG', '#', 'text', null],

            ['Subscriber Form', 'subscriberForm', 'enabled', 'select', 'enabled,disabled'],

            ['Auth Facebook', 'authSocialFacebook', 'enabled', 'select', 'enabled,disabled'],

            ['Email Verification', 'user_email_verification', 'enabled', 'select', 'enabled,disabled'],
            ['Phone Verification', 'user_phone_verification', 'disabled', 'select', 'enabled,disabled'],
            ['Account Enabled', 'user_account_enabled', 'enabled', 'select', 'enabled,disabled'],
        ];
    }
}
