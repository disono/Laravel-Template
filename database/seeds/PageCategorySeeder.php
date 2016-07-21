<?php

use Illuminate\Database\Seeder;

class PageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slugs = [
            'event',
            'blog',
            'page'
        ];

        foreach ($slugs as $q) {
            $check = DB::table('page_categories')->where('slug', $q);

            if (!$check->count()) {
                DB::table('page_categories')->insert([
                    'name' => ucfirst($q),
                    'slug' => $q,
                    'created_at' => sql_date()
                ]);
            }
        }
    }
}
