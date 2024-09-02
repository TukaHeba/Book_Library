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

    /**
     * Query scope to filter books

     * @param mixed $query
     * @param array $filters
     * @return mixed
     */
    /**
     * Apply filtering to the books query based on provided filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder instance.
     * @param array $filters An array of filters to apply (author, category, availability, price order).
     *                       - author: Filter by author name (partial matches).
     *                       - category: Filter by category name (partial matches).
     *                       - available: Filter by availability status.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder instance with applied filters.
     */
    public function scopeFilter($query, array $filters)
    {
        // Author
        if (!empty($filters['author'])) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }

        // Category
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%');
            });
        }

        // Available
        if (isset($filters['available'])) {
            $query->where('available', $filters['available']);
        }

        return $query;
    }
}
