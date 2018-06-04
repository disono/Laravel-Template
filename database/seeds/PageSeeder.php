<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    private $pages = [
        ['About', 'about'],
        ['Contact', 'contact'],
        ['Frequently Ask Questions', 'faq'],
        ['Career', 'career'],
        ['Privacy', 'privacy'],
        ['Terms and Condition', 'terms_and_condition']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->pages as $page) {
            DB::table('pages')->insert([
                'page_category_id' => 1,
                'user_id' => 1,
                'name' => $page[0],
                'content' => str_random(),
                'slug' => $page[1],
                'created_at' => sqlDate()
            ]);
        }
    }
}
