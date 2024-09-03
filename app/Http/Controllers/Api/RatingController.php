<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\RatingService;
use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Http\Resources\RatingResource;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;

class RatingController extends Controller
{

    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Display a listing of ratings for a specific book.
     */
    public function index($bookId)
    {
        try {
            $ratings = $this->ratingService->listBookRatings($bookId);

            return ApiResponseService::success(RatingResource::collection($ratings), 'Ratings retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created rating.
     */
    public function store(StoreRatingRequest $request)
    {
        $validated = $request->validated();
        $rating = $this->ratingService->createRating($validated);

        if ($rating) {
            return ApiResponseService::success(new RatingResource($rating), 'Rating created successfully', 201);
        } else {
            return ApiResponseService::error('Rating creation failed', 400);
        }
    }

    /**
     * Display the specified rating.
     */
    public function show($bookId, $ratingId)
    {
        try {
            $rating = $this->ratingService->showRating($bookId, $ratingId);

            return ApiResponseService::success(new RatingResource($rating), 'Rating retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 404);
        }
    }


    /**
     * Update the specified rating.
     */
    public function update(UpdateRatingRequest $request, $bookId, $ratingId)
    {
        try {
            $validated = $request->validated();
            $userId = $request->user()->id; 
            $rating = $this->ratingService->updateRating($bookId, $ratingId, $validated, $userId);
            return ApiResponseService::success(new RatingResource($rating), 'Rating updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }


    /**
     * Remove the specified rating.
     */
    public function destroy($bookId, $ratingId)
    {
        try {
            $userId = request()->user()->id; 
            $this->ratingService->deleteRating($bookId, $ratingId, $userId);
            return ApiResponseService::success(null, 'Rating deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }
}
