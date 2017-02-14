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
            ['Application Name', 'title', 'WebMons Project Template', 'default'],
            ['Description', 'description', 'Project Template', 'default'],
            ['Author', 'author', 'Archie Disono', 'default'],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate', 'default'],
            ['CSS Version', 'css_version', '1.0', 'default'],

            ['Subscriber Form', 'subscriber_form', 'enabled', 'default'],
            ['Auth Facebook', 'auth_social_facebook', 'enabled', 'default'],
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->insert([
                'name' => ucfirst($value[0]),
                'key' => $value[1],
                'value' => $value[2],

                'type' => $value[3],
                'created_at' => sql_date()
            ]);
        }
    }
}
