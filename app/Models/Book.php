<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'category_id',
        'available',
        'published_at',
        'price',
    ];

    protected $casts = [
        'available' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the borrowing records for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowingRecords()
    {
        return $this->hasMany(BorrowRecords::class);
    }

    /**
     * Get the ratings for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the category of the book.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
