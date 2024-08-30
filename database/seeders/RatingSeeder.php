<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rating::create([
            'user_id' => 1,
            'book_id' => 1,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 1,
            'book_id' => 2,
            'rating' => 4,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 1,
            'book_id' => 3,
            'rating' => 3,
            'review' => 'Nice',
        ]);
        Rating::create([
            'user_id' => 1,
            'book_id' => 4,
            'rating' => 1,
            'review' => 'Bad',
        ]);
        Rating::create([
            'user_id' => 1,
            'book_id' => 5,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 1,
            'book_id' => 6,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 1,
            'rating' => 5,
            'review' => 'Amazing',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 2,
            'rating' => 4,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 7,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 8,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 9,
            'rating' => 5,
            'review' => 'Good',
        ]);
        Rating::create([
            'user_id' => 2,
            'book_id' => 10,
            'rating' => 5,
            'review' => 'Good',
        ]);
    }
}
