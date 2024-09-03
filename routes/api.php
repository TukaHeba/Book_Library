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

// Route::controller(BorrowRecordsController::class)->group(function () {
//     Route::get('borrow-records', 'index')->middleware(['auth:api', 'role:admin']);
//     Route::post('borrow-records/book_id', 'store')->middleware(['auth:api', 'role:admin|client']);
//     Route::get('borrow-records/{id}', 'show')->middleware(['auth:api', 'role:admin|client']);
//     Route::put('borrow-records/{id}', 'update')->middleware(['auth:api', 'role:admin']);
//     Route::delete('borrow-records/{id}', 'delete')->middleware(['auth:api', 'role:admin']);
// });
