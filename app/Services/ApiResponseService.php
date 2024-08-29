<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginationLengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseService
{
    /**
     * Return a successful JSON response.
     *
     * @param mixed $data The data to be returned in the response.
     * @param string $message The success message.
     * @param int $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'Done Successfully!', $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => trans($message),
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error JSON response.
     *
     * @param mixed $data The data to be returned in the response.
     * @param string $message The error message.
     * @param int $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($data = null, $message = 'Operation failed!', $status = 400)
    {
        return response()->json([
            'status' => 'error',
            // 'message' => __($message), 
            'message' => trans($message),
            'data' => $data,
        ], $status);
    }
    /**
     * Summary of paginated
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function paginated(LengthAwarePaginator $paginator, $message = '', $status)
    {
        return response()->json([
            'status' => 'success',
            'message' => trans($message), 
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(), 
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
            ],
        ], $status);
    }
}
