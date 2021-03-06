<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SettingCategorySeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(PageCategorySeeder::class);
        $this->call(PageSeeder::class);
        $this->call(PageReportReasonSeeder::class);
        $this->call(LocationSeeder::class);
    }
}
