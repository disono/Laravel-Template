<?php

use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Route::getRoutes() as $value) {
            $name = $value->getName();
            if ($name) {
                DB::table('authorizations')->insert([
                    'name' => str_replace('_', ' ', str_replace('-', ' ', ucfirst($name))),
                    'identifier' => $name,
                    'created_at' => sql_date()
                ]);
            }
        }
    }
}
