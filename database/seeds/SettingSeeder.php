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
        $settings = [
            ['Application Name', 'title', 'Webmons', 'default', null],
            ['Description', 'description', 'Project Template', 'default', null],
            ['Author', 'author', 'Archie Disono', 'default', null],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'default', null],
            ['CSS Version', 'css_version', '1.0', 'default', null],

            ['File size limit', 'file_size_limit', '3000', 'default', null],
            ['File size limit(image)', 'file_size_limit_image', '3000', 'default', null],
            ['File size limit(default)', 'file_size_limit', '3000', 'default', null],
            ['Minimum age for registration', 'minimum_age_for_registration', '13', 'default', null],
            ['Maximum age for registration', 'maximum_age_for_registration', '120', 'default', null],
            ['Items per page(pagination)', 'pagination', '12', 'default', null],
            ['Theme', 'theme', 'main', 'default', null],

            ['Facebook Page', 'social_fb', '', 'default', null],
            ['Twitter Page', 'social_twitter', '', 'default', null],
            ['Instagram Page', 'social_ig', '', 'default', null],

            ['Subscriber Form', 'subscriber_form', 'enabled', 'default', 'select'],
            ['Auth Facebook', 'auth_social_facebook', 'enabled', 'default', 'select'],
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->insert([
                'name' => ucfirst($value[0]),
                'key' => $value[1],
                'value' => $value[2],

                'input_type' => $value[4],
                'type' => $value[3],
                'created_at' => sql_date()
            ]);
        }
    }
}
