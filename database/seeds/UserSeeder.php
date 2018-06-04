<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = App\Models\Role::all();
        foreach ($roles as $role) {
            DB::table('users')->insert([
                'first_name' => str_random(4),
                'last_name' => str_random(4),
                'role_id' => $role->id,
                'username' => $role->slug,
                'email' => $role->slug . '@gmail.com',
                'password' => bcrypt('password'),
                'is_email_verified' => 1,
                'is_account_activated' => 1,
                'is_account_enabled' => 1,
                'created_at' => sqlDate()
            ]);
        }
    }
}
