<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\NeighborhoodController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {

    // Registeration of user
    Route::post('/register', [AuthController::class, 'register']);

    // login of the user
    Route::post('/login', [AuthController::class, 'login']);

    // Logout of the user
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::apiResource('/apartments' , ApartmentController::class);
Route::get('/search/apartments', [SearchController::class, 'search']);


Route::apiResource('/amenities' , AmenityController::class)->middleware('auth');

Route::apiResource('/neighborhoods' , NeighborhoodController::class)->middleware('auth');

Route::apiResource('/users' , UserProfileController::class)->middleware('auth');

