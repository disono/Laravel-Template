<?php

use Illuminate\Database\Seeder;

class SettingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([
            'Application Settings',
            'User Registration',
            'File Options',
            'Social Media Links',
            'Authentication',
            'Broadcast'
                 ] as $row) {
            DB::table('setting_categories')->insert([
                'name' => ucfirst($row)
            ]);
        }
    }
}
