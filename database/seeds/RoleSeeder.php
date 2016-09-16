<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private $roles = [
        ['Administrator (Super user)', 'admin'],
        ['Employee', 'employee'],
        ['Client / Customer', 'client']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $key => $value) {
            DB::table('roles')->insert([
                'name' => ucfirst($value[0]),
                'slug' => $value[1],
                'created_at' => sql_date()
            ]);
        }

        $roles = App\Models\Role::all();
        foreach ($roles as $row) {
            $id = DB::table('users')->insertGetId([
                'first_name' => random_first_names(),
                'last_name' => random_last_names(),

                'birthday' => sql_date('January 1 1995', true),

                'email' => $row->slug . '@gmail.com',
                'password' => bcrypt('password'),
                'phone' => rand_numbers(7),

                'enabled' => 1,
                'email_confirmed' => 1,

                'role' => $row->slug,
                'created_at' => sql_date()
            ]);

            // username
            DB::table('slugs')->insertGetId([
                'source_id' => $id,
                'source_type' => 'user',
                'name' => $row->slug . str_random(2),
                'created_at' => sql_date()
            ]);
        }
    }
}
