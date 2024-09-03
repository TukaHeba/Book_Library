<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\BorrowRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BorrowRecordsService
{
    /**
     * List borrowing records. If the user is an admin, display all records.
     * Otherwise, display only the records of the authenticated user.
     *
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function listBorrowingRecords(): LengthAwarePaginator
    {
        try {
            $user = Auth::user();

            if ($user && $user->is_admin) {
                return BorrowRecords::with(['book', 'user'])->paginate(10);
            } else {
                return BorrowRecords::with(['book', 'user'])
                    ->where('user_id', $user->id)->paginate(10);
            }
        } catch (\Exception $e) {
            Log::error('Failed to retrieve borrowing records: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve borrowing records');
        }
    }

    /**
     * Borrow a book for the authenticated user.
     *
     * @param int $bookId
     * @return BorrowRecords|null
     * @throws \Exception
     */
    public function borrowBook(array $data): ?BorrowRecords
    {
        try {
            $bookId = $data['book_id'];

            $book = Book::findOrFail($bookId);

            if (!$book->isAvailable()) {
                throw new \Exception('Book is not available for borrowing.');
            }

            $book->markAsBorrowed();

            $borrowRecord = BorrowRecords::create([
                'user_id' => Auth::id(),
                'book_id' => $bookId,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
                'returned_at' => null,
            ]);

            return $borrowRecord;
        } catch (\Exception $e) {
            Log::error('Borrowing book failed: ' . $e->getMessage());
            throw new \Exception('Failed to borrow book');
        }
    }

    /**
     * Show a specific borrow record.
     * Find the borrow record by ID with eager loading
     * Admin can show any record while client can show it if he owner it 
     * 
     * @param int $borrowRecordId
     * @return BorrowRecords|null
     * @throws \Exception
     */
    public function showBorrowRecord(int $borrowRecordId): ?BorrowRecords
    {
        try {
            $user = Auth::user();

            $borrowRecord = BorrowRecords::with(['book', 'user'])->findOrFail($borrowRecordId);

            if (!$user->is_admin && $borrowRecord->user_id !== $user->id) {
                throw new \Exception('You do not have permission to view this record.');
            }

            return $borrowRecord;
        } catch (\Exception $e) {
            Log::error('Failed to show borrow record: ' . $e->getMessage());
            throw new \Exception('Failed to show borrow record');
        }
    }

    /**
     * Update a borrow record, including handling the return of a book.
     *
     * @param int $borrowRecordId
     * @param array $data
     * @return BorrowRecords|null
     * @throws \Exception
     */
    public function updateBorrowRecord(int $borrowRecordId, array $data): ?BorrowRecords
    {
        try {
            $borrowRecord = BorrowRecords::with(['book', 'user'])->findOrFail($borrowRecordId);

            if (isset($data['returned_at']) && $data['returned_at']) {
                if ($borrowRecord->returned_at) {
                    throw new \Exception('Book has already been returned.');
                }

                $borrowRecord->markAsReturned();
            } else {
                if (isset($data['due_date'])) {
                    $borrowRecord->due_date = Carbon::parse($data['due_date']);
                }

                if (isset($data['borrowed_at'])) {
                    $borrowRecord->borrowed_at = Carbon::parse($data['borrowed_at']);
                }

                $borrowRecord->update($data);
            }

            return $borrowRecord;
        } catch (\Exception $e) {
            Log::error('Failed to update borrow record: ' . $e->getMessage());
            throw new \Exception('Failed to update borrow record');
        }
    }


    /**
     * Delete a borrow record by ID.
     *
     * @param int $borrowRecordId
     * @return bool
     * @throws \Exception
     */
    public function deleteBorrowRecord(int $borrowRecordId): bool
    {
        try {
            $borrowRecord = BorrowRecords::findOrFail($borrowRecordId);
            $borrowRecord->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete borrow record: ' . $e->getMessage());
            throw new \Exception('Failed to delete borrow record');
        }
    }
}
