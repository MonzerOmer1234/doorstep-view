<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Auth\AgentRegistrationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NeighborhoodController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RecommendationController;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {
    // Registeration
    Route::post('/register', [AuthController::class, 'register']);
    // login
    Route::post('/login', [AuthController::class, 'login']);
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Password Reset
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
});

// Removed the middleware temporarily
Route::apiResource('/apartments' , ApartmentController::class);
Route::get('/search/apartments', [SearchController::class, 'search']);

Route::put('/apartments/attach-amenity/{apartmentId}/{amenityId}' , [ApartmentController::class , 'attachAmenity'])->middleware('auth:sanctum');
Route::delete('/apartments/detach-amenity/{apartmentId}/{amenityId}' , [ApartmentController::class , 'detachAmenity'])->middleware('auth:sanctum');

Route::apiResource('/amenities' , AmenityController::class)->middleware('auth:sanctum');

Route::apiResource('/neighborhoods' , NeighborhoodController::class)->middleware('auth:sanctum');

Route::get('/neighborhoods/{id}/apartments' , [NeighborhoodController::class , 'getApartments']);

Route::apiResource('/users' , UserProfileController::class)->middleware('auth');

// Recommendation System
Route::get('/recommendations/{apartmentId}', [RecommendationController::class, 'fetchRecommendations']);
Route::post('/recommendations/{apartmentId}/update', [RecommendationController::class, 'updateRecommendations']);


// feedbacks

Route::post('/feedback', [FeedbackController::class, 'submitFeedback']);
Route::get('/apartments/{apartmentId}/feedback', [FeedbackController::class, 'getFeedbackForApartment']);

// Agents
Route::apiResource('/agents', AgentController::class);

// Agent Registration
Route::post('/agents/register', [AgentRegistrationController::class, 'register']);

//properties
Route::apiResource('/properties', PropertyController::class);

// Favorites
Route::post('favorites', [FavoriteController::class, 'add']); // Add favorite
Route::delete('favorites/{id}', [FavoriteController::class, 'remove']); // Remove favorite
Route::get('favorites', [FavoriteController::class, 'list']); // List favorites
