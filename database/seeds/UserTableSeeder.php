<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = DB::table('users')->insertGetId([
            'first_name' => str_random(10),
            'last_name' => str_random(10),

            'birthday' => sql_date('January 1 1995', true),

            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),

            'enabled' => 1,
            'email_confirmed' => 1,

            'role' => 'admin',
            'created_at' => sql_date()
        ]);
        DB::table('slugs')->insertGetId([
            'source_id' => $id,
            'source_type' => 'user',
            'name' => 'admin',
            'created_at' => sql_date()
        ]);

        $id = DB::table('users')->insertGetId([
            'first_name' => str_random(10),
            'last_name' => str_random(10),

            'birthday' => sql_date('January 1 1995', true),

            'email' => 'client@gmail.com',
            'password' => bcrypt('password'),

            'enabled' => 1,
            'email_confirmed' => 1,

            'role' => 'client',
            'created_at' => sql_date()
        ]);
        DB::table('slugs')->insertGetId([
            'source_id' => $id,
            'source_type' => 'user',
            'name' => 'client',
            'created_at' => sql_date()
        ]);
    }
}
