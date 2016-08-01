<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            'about',
            'services',
            'careers',
            'faq',
            'contact',
            'terms',
            'privacy'
        ];

        foreach ($pages as $row) {
            $id = DB::table('pages')->insertGetId([
                'page_category_id' => 2,
                'name' => ucfirst($row),
                'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'created_at' => sql_date()
            ]);
            DB::table('slugs')->insertGetId([
                'source_id' => $id,
                'source_type' => 'page',
                'name' => $row,
                'created_at' => sql_date()
            ]);
        }
    }
}
