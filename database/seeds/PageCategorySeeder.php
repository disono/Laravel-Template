<?php

use Illuminate\Database\Seeder;

class PageCategorySeeder extends Seeder
{
    private $categories = [
        ['Pages', 'pages'],
        ['Blog', 'blog'],
        ['News', 'news']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->categories as $category) {
            DB::table('page_categories')->insert([
                'name' => $category[0],
                'slug' => $category[1],
                'created_at' => sqlDate()
            ]);
        }
    }
}
