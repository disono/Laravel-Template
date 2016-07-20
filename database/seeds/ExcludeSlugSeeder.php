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
            $name = $value->getName();

            if ($name) {
                
            }
        }
    }
}
