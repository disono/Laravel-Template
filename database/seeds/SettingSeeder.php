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
            ['Application Name', 'title', 'WebMons Project Template'],
            ['Description', 'description', 'Project Template'],
            ['Author', 'author', 'Archie Disono'],
            ['Keywords', 'keywords', 'webmons, project template, boiler plate'],
            ['CSS Version', 'css_version', '1.0']
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->insert([
                'name' => ucfirst($value[0]),
                'key' => $value[1],
                'value' => $value[2],
                'created_at' => sql_date()
            ]);
        }
    }
}
