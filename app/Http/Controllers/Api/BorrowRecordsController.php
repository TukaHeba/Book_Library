<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBorrowRecordsRequest;
use App\Http\Requests\UpdateBorrowRecordsRequest;
use App\Http\Resources\BorrowRecordsResource;
use App\Services\ApiResponseService;
use App\Services\BorrowRecordsService;

class BorrowRecordsController extends Controller
{
    /**
     * The borrow service instance.
     *
     * @var BorrowRecordsService
     */
    protected $borrowRecordsService;

    /**
     * BorrowRecordsController constructor.
     *
     * @param BorrowRecordsService $borrowRecordsService
     */
    public function __construct(BorrowRecordsService $borrowRecordsService)
    {
        $this->borrowRecordsService = $borrowRecordsService;
    }

    /**
     * Display a listing of the borrowing records.
     * Admins see all records, while clients see only their own records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $records = $this->borrowRecordsService->listBorrowingRecords();
            return ApiResponseService::paginated(
                $records,
                BorrowRecordsResource::class,
                'Borrowing records retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created borrow record.
     *
     * @param StoreBorrowRecordsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBorrowRecordsRequest $request)
    {
        $validated = $request->validated(); 

        try {
            $borrowRecord = $this->borrowRecordsService->borrowBook($validated);

            return ApiResponseService::success(
                new BorrowRecordsResource($borrowRecord),
                'Book borrowed successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }


    /**
     * Display the specified borrow record.
     * Admins can view any record, while clients can only view their own records.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $borrowRecord = $this->borrowRecordsService->showBorrowRecord($id);

            return ApiResponseService::success(
                new BorrowRecordsResource($borrowRecord),
                'Borrow record retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified borrow record, including handling returns.
     *
     * @param UpdateBorrowRecordsRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRecordsRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $borrowRecord = $this->borrowRecordsService->updateBorrowRecord($id, $validated);

            return ApiResponseService::success(
                new BorrowRecordsResource($borrowRecord),
                'Borrow record updated successfully',
                200
            );
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }

    /**
     * Delete the specified borrow record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->borrowRecordsService->deleteBorrowRecord($id);

            return ApiResponseService::success(null, 'Borrow record deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), 400);
        }
    }
}
