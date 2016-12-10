<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        $roles = ['admin', 'employee'];

        foreach ($roles as $role) {
            $find_role = \App\Models\Role::where('slug', $role)->first();

            if ($find_role) {
                foreach ($authorization->get() as $row) {
                    DB::table('authorization_roles')->insert([
                        'role_id' => $find_role->id,
                        'authorization_id' => $row->id,
                        'created_at' => sql_date()
                    ]);
                }
            }
        }
    }
}
