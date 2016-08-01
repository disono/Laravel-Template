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
            'blog',
            'page'
        ];

        foreach ($slugs as $q) {
            $id = DB::table('page_categories')->insertGetId([
                'name' => ucfirst($q),
                'created_at' => sql_date()
            ]);
            DB::table('slugs')->insertGetId([
                'source_id' => $id,
                'source_type' => 'page_category',
                'name' => str_random(),
                'created_at' => sql_date()
            ]);
        }
    }
}
