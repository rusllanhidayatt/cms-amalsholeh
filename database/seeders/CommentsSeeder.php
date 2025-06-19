<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsSeeder extends Seeder
{
    public function run(): void
    {
        Comment::updateOrCreate(
            ['article_id'   => '1'],
            ['parent_id'    => '3'],
            [
                'content'       => 'Test Comment',
                'name'          => 'Viewer',
                'email'         => 'viewer@gmail.com'
            ]
        );
    }
}
