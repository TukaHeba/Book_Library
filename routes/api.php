<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowRecordsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    // User Routes
    Route::apiResource('users', UserController::class);

    // Category Routes
    Route::apiResource('categories', CategoryController::class);

    // Book Routes
    Route::apiResource('books', BookController::class);

    // Borrow Records Routes
    // Route::apiResource('borrow-records', BorrowRecordsController::class);
    Route::post('/borrow-records', [BorrowRecordsController::class, 'store']);
});




// Route::controller(BorrowRecordsController::class)->group(function () {
//     Route::get('borrow-records', 'index')->middleware(['auth:api', 'role:admin']);
//     Route::post('borrow-records/book_id', 'store')->middleware(['auth:api', 'role:admin|client']);
//     Route::get('borrow-records/{id}', 'show')->middleware(['auth:api', 'role:admin|client']);
//     Route::put('borrow-records/{id}', 'update')->middleware(['auth:api', 'role:admin']);
//     Route::delete('borrow-records/{id}', 'delete')->middleware(['auth:api', 'role:admin']);
// });
