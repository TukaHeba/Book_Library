<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'title1',
            'author' => 'author1',
            'description' => 'description1',
            'category_id' => 1,
            'available' => true,
            'published_at' => '2024-02-12',
            'price' => 20.99,
        ]);
        Book::create([
            'title' => 'title2',
            'author' => 'author2',
            'description' => 'description2',
            'category_id' => 1,
            'available' => true,
            'published_at' => '2022-02-12',
            'price' => 22.99,
        ]);
        Book::create([
            'title' => 'title3',
            'author' => 'author3',
            'description' => 'description3',
            'category_id' => 2,
            'available' => true,
            'published_at' => '2004-02-11',
            'price' => 40.99,
        ]);
        Book::create([
            'title' => 'title4',
            'author' => 'author4',
            'description' => 'description4',
            'category_id' => 3,
            'available' => true,
            'published_at' => '2020-04-12',
            'price' => 10.99,
        ]);
        Book::create([
            'title' => 'title5',
            'author' => 'author5',
            'description' => 'description5',
            'category_id' => 4,
            'available' => true,
            'published_at' => '1993-02-12',
            'price' => 30.99,
        ]);
        Book::create([
            'title' => 'title6',
            'author' => 'author6',
            'description' => 'description6',
            'category_id' => 5,
            'available' => true,
            'published_at' => '2000-09-19',
            'price' => 90.99,
        ]);
        Book::create([
            'title' => 'title7',
            'author' => 'author7',
            'description' => 'description7',
            'category_id' => 6,
            'available' => true,
            'published_at' => '1999-09-20',
            'price' => 25.99,
        ]);
        Book::create([
            'title' => 'title8',
            'author' => 'author8',
            'description' => 'description8',
            'category_id' => 6,
            'available' => true,
            'published_at' => '2024-02-12',
            'price' => 20.99,
        ]);
        Book::create([
            'title' => 'title9',
            'author' => 'author9',
            'description' => 'description9',
            'category_id' => 5,
            'available' => true,
            'published_at' => '2024-02-12',
            'price' => 20.99,
        ]);
        Book::create([
            'title' => 'title10',
            'author' => 'author10',
            'description' => 'description10',
            'category_id' => 4,
            'available' => true,
            'published_at' => '2024-02-12',
            'price' => 20.99,
        ]);
        Book::create([
            'title' => 'title11',
            'author' => 'author11',
            'description' => 'description11',
            'category_id' => 3,
            'available' => true,
            'published_at' => '2024-02-12',
            'price' => 20.99,
        ]);
    }
}
