<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowRecordsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RatingController;
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

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
});

// User Routes
Route::apiResource('users', UserController::class)->middleware('auth:api');

// Category Routes
Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
    Route::get('categories/{id}', 'show');
    Route::post('categories', 'store')->middleware(['auth:api', 'admin']);
    Route::put('categories/{id}', 'update')->middleware(['auth:api', 'admin']);;
    Route::delete('categories/{id}', 'destroy')->middleware(['auth:api', 'admin']);;
});

// Book Routes
Route::controller(BookController::class)->group(function () {
    Route::get('books', 'index');
    Route::get('books/{id}', 'show');
    Route::post('books', 'store')->middleware(['auth:api', 'admin']);
    Route::put('books/{id}', 'update')->middleware(['auth:api', 'admin']);
    Route::delete('books/{id}', 'destroy')->middleware(['auth:api', 'admin']);
});

// Rating Routes
Route::controller(RatingController::class)->group(function () {
    Route::get('books/{bookId}/ratings', 'index');
    Route::get('books/{bookId}/ratings/{ratingId}', 'show');
    Route::post('books/{bookId}/ratings', 'store')->middleware('auth:api');
    Route::put('books/{bookId}/ratings/{ratingId}', 'update')->middleware('auth:api');
    Route::delete('books/{bookId}/ratings/{ratingId}', 'destroy')->middleware('auth:api');
});

// Borrow Records Routes
Route::controller(BorrowRecordsController::class)->group(function () {
    Route::get('books/{bookId}/borrow-records', 'index');
    Route::get('books/{bookId}/borrow-records/{borrowRecordId}', 'show');
    Route::post('books/{bookId}/borrow-records', 'store')->middleware('auth:api');
    Route::put('books/{bookId}/borrow-records/{borrowRecordId}', 'update')->middleware('auth:api');
    Route::delete('books/{bookId}/borrow-records/{borrowRecordId}', 'destroy')->middleware('auth:api');
});
