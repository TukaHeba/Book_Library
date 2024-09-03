<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Services\BookService;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Services\ApiResponseService;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['author', 'category', 'available', 'price_order']);
            $books = $this->bookService->getAllBooks($filters);

            return ApiResponseService::paginated($books, BookResource::class, 'Books retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();
        $book = $this->bookService->createBook($validated);

        if ($book) {
            return ApiResponseService::success(new BookResource($book), 'Book created successfully', 201);
        } else {
            return ApiResponseService::error('Book creation failed', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = $this->bookService->showBook($id);

            return ApiResponseService::success(new BookResource($book), 'Book retrieved successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateBookRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $book = $this->bookService->updateBook($id, $validated);

            return ApiResponseService::success(new BookResource($book), 'Book updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);
            return ApiResponseService::success(null, 'Book deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }
}
