<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Technology']);
        Category::create(['name' => 'Art']);
        Category::create(['name' => 'Philosophy']);
        Category::create(['name' => 'Novels ']);
        Category::create(['name' => 'Science']);
        Category::create(['name' => 'Literature']);
    }
}
