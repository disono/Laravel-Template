<?php

use Illuminate\Database\Seeder;

class AuthorizationRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authorization = DB::table('authorizations');

        // admin
        foreach ($authorization->get() as $row) {
            DB::table('authorization_roles')->insert([
                'role_id' => 1,
                'authorization_id' => $row->id,
                'created_at' => sql_date()
            ]);
        }

        // employee
        foreach ($authorization->get() as $row) {
            DB::table('authorization_roles')->insert([
                'role_id' => 2,
                'authorization_id' => $row->id,
                'created_at' => sql_date()
            ]);
        }
    }
}
