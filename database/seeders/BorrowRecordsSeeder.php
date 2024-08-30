<?php

namespace Database\Seeders;

use App\Models\BorrowRecords;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowRecords::create([
            'user_id' => 1,
            'book_id' => 1,
            'borrowed_at' => '2024-08-12',
            'due_date' => '2024-08-26',
            'returned_at' => '2024-08-22',
        ]);
        BorrowRecords::create([
            'user_id' => 2,
            'book_id' => 2,
            'borrowed_at' => '2024-07-12',
            'due_date' => '2024-07-26',
            'returned_at' => '2024-07-22',
        ]);
        BorrowRecords::create([
            'user_id' => 2,
            'book_id' => 3,
            'borrowed_at' => '2024-08-11',
            'due_date' => '2024-08-25',
            'returned_at' => '2024-08-21',
        ]);
    }
}
