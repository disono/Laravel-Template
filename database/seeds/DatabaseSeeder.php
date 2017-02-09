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
        $this->call(UserTableSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(AuthorizationSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AuthorizationRoleSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(ExcludeSlugSeeder::class);
        $this->call(PageCategorySeeder::class);
        $this->call(PageSeeder::class);
    }
}
