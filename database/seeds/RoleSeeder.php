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
        $this->_addRoles();
        $this->_addAuthRoles();
    }

    private function _addRoles()
    {
        foreach ($this->_roles() as $role) {
            DB::table('roles')->insert([
                'name' => ucfirst($role[0]),
                'slug' => $role[1],
                'created_at' => sqlDate()
            ]);
        }
    }

    private function _roles()
    {
        return [
            ['Administrator (Super user)', 'admin'],
            ['Employee', 'employee'],
            ['Client / Customer', 'client']
        ];
    }

    private function _addAuthRoles()
    {
        foreach (\App\Models\Role::fetchAll(['exclude' => ['id' => [3]]]) as $row) {
            foreach (Route::getRoutes() as $value) {
                $name = $value->getName();

                if ($name) {
                    DB::table('authorization_roles')->insert([
                        'route' => $name,
                        'role_id' => $row->id,
                        'created_at' => sqlDate()
                    ]);
                }
            }
        }
    }
}
