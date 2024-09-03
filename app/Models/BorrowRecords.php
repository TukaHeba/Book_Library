<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRecords extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Get the book associated with the borrowing record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user associated with the borrowing record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the user can borrow a specific book.
     *
     * @param int $userId
     * @param int $bookId
     * @return bool
     */
    public static function canBorrow($userId, $bookId)
    {
        return !self::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->exists();
    }

    /**
     * Check if the book is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return !$this->returned_at && $this->due_date->isPast();
    }

    /**
     * Mark the book as returned.
     *
     * @return void
     */
    public function markAsReturned()
    {
        $this->returned_at = now();
        $this->save();
        $this->book->markAsAvailable();
    }
}
