<?php

use Illuminate\Database\Seeder;

class ExcludeSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Route::getRoutes() as $value) {
            $names = [
                'login',
                'auth',
                'authentication',
                'register',
                'email',
                'password',
                'dashboard',
                'user',
                'admin',
                'api',
                'dev',
                'development',
                'admin',
                'administrator',
                'images',
                'image',
                'img',
                'store',
                'profile',
                'store',
                'e-commerce',
                'shop',
                'administrator',
            ];

            foreach ($names as $q) {
                $check = DB::table('slug_excludes')->where('name', $q);

                if (!$check->count()) {
                    DB::table('slug_excludes')->insert([
                        'name' => $q,
                        'created_at' => sql_date()
                    ]);
                }
            }
        }
    }
}
