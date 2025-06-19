<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        Tag::updateOrCreate(
            ['slug'         => 'test-tags'],
            [
                'title'         => 'Test Tags',
                'description'   => '<b>Test Description in Tag</b>'
            ]
        );
    }
}
