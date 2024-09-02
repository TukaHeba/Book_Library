<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
    /**
     * Retrieve all books with pagination.
     *
     * Eager load the ratings relationship and select the average rating.
     * Use the scope for filtering
     * @return LengthAwarePaginator
     */
    public function getAllBooks(array $filters = []): LengthAwarePaginator
    {
        try {
            return Book::with(['ratings' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
                ->withAvg('ratings', 'rating')
                ->filter($filters)
                ->paginate(10);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve books: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve books');
        }
    }

    /**
     * Create a new book.
     *
     * @param array $data The validated book data.
     * @return Book|null The newly created book or null if creation fails.
     */
    public function createBook(array $data): ?Book
    {
        try {
            return Book::create($data);
        } catch (\Exception $e) {
            Log::error('Book creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Show the details of a specific book by its ID.
     *
     * Eager load the ratings and calculate average rating for the book
     * Order reviews by latest
     * 
     * @param mixed $id The ID of the book to retrieve.
     * @return Book|null The retrieved book or null if not found.
     */
    public function showBook($id): ?Book
    {
        try {
            return Book::with(['ratings' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->withAvg('ratings', 'rating')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Book retrieval failed: ' . $e->getMessage());
            throw new \Exception('Book not found');
        }
    }

    /**
     * Update the given book with the provided data.
     *
     * @param int $id The ID of the book to update.
     * @param array $data The validated book data.
     * @return Book The updated book.
     */
    public function updateBook($id, array $data): Book
    {
        try {
            $book = Book::findOrFail($id);
            $book->update($data);
            return $book;
        } catch (\Exception $e) {
            Log::error('Book update failed: ' . $e->getMessage());
            throw new \Exception('Failed to update book');
        }
    }

    /**
     * Delete the given book from the database.
     *
     * @param int $id The ID of the book to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteBook($id): bool
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Book deletion failed: ' . $e->getMessage());
            throw new \Exception('Failed to delete book');
        }
    }
}
