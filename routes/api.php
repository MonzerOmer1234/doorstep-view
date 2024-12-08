<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\Auth\AgentRegistrationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VisitRequestController;
use App\Models\VisitRequest;
use Illuminate\Auth\Notifications\ResetPassword;
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
    Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.reset');
    Route::post('/password/forgot', [ResetPasswordController::class, 'forgotPassword']);
});

// Agents
Route::apiResource('/agents', AgentController::class)->middleware('auth:sanctum');
// Agent Registration
Route::post('/agents/register', [AgentRegistrationController::class, 'register']);
Route::post('/agents/login', [AgentRegistrationController::class, 'login']);
Route::post('/agents/logout', [AgentRegistrationController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/agents/password/forgot', [AgentRegistrationController::class, 'forgotPassword'])->middleware('auth:sanctum');
Route::post('/agents/password/reset', [AgentRegistrationController::class, 'resetPassword'])->middleware('auth:sanctum');




// amenities
Route::apiResource('/amenities' , AmenityController::class)->middleware('auth:sanctum');
// nearby amenities
Route::get('properties/{propertyId}/amenities', [AmenityController::class, 'getNearbyAmenities'])->middleware('auth:sanctum');

// api key
Route::get('/api-keys' , [ApiKeyController::class , 'index' ])->middleware('auth:sanctum');

// Favorites
Route::post('favorites', [FavoriteController::class, 'add'])->middleware('auth:sanctum'); // Add favorite
Route::delete('favorites/{id}', [FavoriteController::class, 'remove'])->middleware('auth:sanctum'); // Remove favorite
Route::get('favorites', [FavoriteController::class, 'list'])->middleware('auth:sanctum'); // List favorites


// firebase controller
Route::put('update-device-token', [FcmController::class, 'updateDeviceToken'])->middleware('auth:sanctum');
Route::post('send-fcm-notification', [FcmController::class, 'sendFcmNotification'])->middleware('auth:sanctum');

// feedbacks

Route::post('/feedback', [FeedbackController::class, 'submitFeedback'])->middleware('auth:sanctum');
Route::get('/properties/{propertyId}/feedback', [FeedbackController::class, 'getFeedbackForProperty'])->middleware('auth:sanctum');
Route::get('/count-property/{propertyId}/feedbacks' , [PropertyController::class , 'countPropertyFeedbacks'])->middleware('auth:sanctum');
Route::get('/count-property/{propertyId}/visitRequests' , [PropertyController::class , 'countPropertyVisitRequests'])->middleware('auth:sanctum');

// image upload
Route::post('/upload-image', [ImageController::class, 'store'])->middleware('auth:sanctum'); // For image upload
// messages
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->middleware('auth:sanctum');
    Route::get('/messages/{receiverId}', [MessageController::class, 'getMessages'])->middleware('auth:sanctum');
    Route::patch('/messages/{id}/read', [MessageController::class, 'markAsRead'])->middleware('auth:sanctum');
});
//properties
Route::patch( '/properties/feature/{propertyId}' , [PropertyController::class , 'feature'])->middleware('auth:sanctum');
Route::apiResource('/properties', PropertyController::class)->middleware('auth:sanctum');

Route::put('/properties/attach-amenity/{propertyId}/{amenityId}' , [PropertyController::class , 'attachAmenity'])->middleware('auth:sanctum');
Route::delete('/properties/detach-amenity/{propertyId}/{amenityId}' , [PropertyController::class , 'detachAmenity'])->middleware('auth:sanctum');
Route::get('/properties/nearby/{latitude}/{longitude}/{radius?}', [PropertyController::class, 'nearbyProperties'])->middleware('auth:sanctum');

// Recommendation System
Route::get('/recommendations/{propertyId}', [RecommendationController::class, 'fetchRecommendations'])->middleware('auth:sanctum');
Route::post('/recommendations/{propertyId}/update', [RecommendationController::class, 'updateRecommendations'])->middleware('auth:sanctum');


// seraching
Route::get('/search/properties', [SearchController::class, 'search'])->middleware('auth:sanctum');

// user profile
Route::apiResource('/users' , UserProfileController::class)->middleware('auth:sanctum');

Route::get('/properties/{propertyId}/visit-request', [VisitRequestController::class, 'create']);
Route::post('/properties/{propertyId}/visit-request', [VisitRequestController::class, 'store']);







