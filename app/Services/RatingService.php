<?php

namespace App\Services;

use App\Models\Rating;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RatingService
{
    /**
     * Retrieve all ratings for a specific book.
     *
     * Eager load the user relationship and order ratings by latest.
     *
     * @param int $bookId The ID of the book.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listBookRatings($bookId)
    {
        try {
            return Rating::with('user')->where('book_id', $bookId)
                ->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve ratings: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve ratings');
        }
    }

    /**
     * Create a new rating.
     *
     * @param array $data The validated rating data.
     * @return Rating|null The newly created rating or null if creation fails.
     */
    public function createRating(array $data): ?Rating
    {
        try {
            return Rating::create($data);
        } catch (\Exception $e) {
            Log::error('Rating creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Show the details of a specific rating by its ID.
     *
     * Eager load the user relationship.
     *
     * @param mixed $id The ID of the rating to retrieve.
     * @return Rating|null The retrieved rating or null if not found.
     */
    public function showRating($bookId, $ratingId)
    {
        try {
            return Rating::with('user')->where('book_id', $bookId)
                ->where('id', $ratingId)->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Failed to retrieve rating: ' . $e->getMessage());
            throw new \Exception('Rating not found');
        }
    }


    /**
     * Update the given rating with the provided data.
     *
     * @param int $id The ID of the rating to update.
     * @param array $data The validated rating data.
     * @return Rating The updated rating.
     */
    public function updateRating($bookId, $ratingId, array $data, $userId): Rating
    {
        try {
            $rating = Rating::where('book_id', $bookId)
                ->where('id', $ratingId)->firstOrFail();

            // Check if the authenticated user is the owner of the rating
            if ($rating->user_id !== $userId) {
                throw new \Exception('Unauthorized action');
            }

            $rating->update($data);

            return $rating;
        } catch (ModelNotFoundException $e) {
            Log::error('Failed to update rating: ' . $e->getMessage());
            throw new \Exception('Failed to update rating');
        } catch (\Exception $e) {
            Log::error('Unauthorized update attempt: ' . $e->getMessage());
            throw new \Exception('Unauthorized action');
        }
    }

    /**
     * Delete the given rating from the database.
     *
     * @param int $id The ID of the rating to delete.
     * @return bool True if deletion is successful, false otherwise.
     */
    public function deleteRating($bookId, $ratingId, $userId): bool
    {
        try {
            $rating = Rating::where('book_id', $bookId)
                ->where('id', $ratingId)
                ->firstOrFail();

            // Check if the authenticated user is the owner of the rating
            if ($rating->user_id !== $userId) {
                throw new \Exception('Unauthorized action');
            }

            $rating->delete();

            return true;
        } catch (ModelNotFoundException $e) {
            Log::error('Failed to delete rating: ' . $e->getMessage());
            throw new \Exception('Failed to delete rating');
        } catch (\Exception $e) {
            Log::error('Unauthorized delete attempt: ' . $e->getMessage());
            throw new \Exception('Unauthorized action');
        }
    }
}
