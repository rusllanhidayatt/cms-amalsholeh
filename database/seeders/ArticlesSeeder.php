<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticlesSeeder extends Seeder
{
    public function run(): void
    {
        Article::updateOrCreate(
            ['slug'         => 'test-article'],
            [
                'title'         => 'Test Article',
                'content'       => '<b>Test Content in Article</b>',
                'cover'         => 'AMSOL-1.png',
                'status'        => 'published',
                'category_id'   => '1'
            ]
        );

        DB::table('article_tag')->updateOrInsert(
            ['article_id'       => '1'],
            ['tag_id'           => '1'],
        );
    }
}
