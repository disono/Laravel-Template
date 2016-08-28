<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Administrator (Super user)', 'admin'],
            ['Employee', 'employee'],
            ['Client', 'client']
        ];

        foreach ($roles as $key => $value) {
            DB::table('roles')->insert([
                'name' => ucfirst($value[0]),
                'slug' => $value[1],
                'created_at' => sql_date()
            ]);
        }
    }
}
