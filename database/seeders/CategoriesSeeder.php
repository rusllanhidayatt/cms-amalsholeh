<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 'test-categories'],
            [
                'title'         => 'Test Categories',
                'description'   => 'Test Description In Categories',
                'icon'          => 'AMSOL-1.png'
            ]
        );
    }
}
