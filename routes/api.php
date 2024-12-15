<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\Agent\Auth\AgentRegistrationController;
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





// Agent Routes
Route::post('/agents', [AgentController::class , 'store'])->middleware('auth:sanctum');
// Agent Registration
Route::post('/agents/register', [AgentRegistrationController::class, 'register']);
Route::post('/agents/login', [AgentRegistrationController::class, 'login']);

Route::prefix('/agents')->middleware(\App\Http\Middleware\AllowAgent::class)->group(function () {

    // Agent authentication
    Route::post('/logout', [AgentRegistrationController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/password/forgot', [AgentRegistrationController::class, 'forgotPassword'])->middleware('auth:sanctum');
    Route::post('/password/reset', [AgentRegistrationController::class, 'resetPassword'])->middleware('auth:sanctum');

    // Agent- amenities
    Route::get('/amenities' , [AmenityController::class , 'index'])->middleware('auth:sanctum');
    Route::post('/amenities' , [AmenityController::class , 'store'])->middleware('auth:sanctum');
    Route::put('/amenities/{id}' , [AmenityController::class , 'update'])->middleware('auth:sanctum');
    Route::delete('/amenities/{id}' , [AmenityController::class , 'destroy'])->middleware('auth:sanctum');
    // Agent - properties
    Route::get('/properties' , [PropertyController::class , 'index'])->middleware('auth:sanctum');
    Route::get('/properties/{id}' , [PropertyController::class , 'show'])->middleware('auth:sanctum');
    Route::post('/properties' , [PropertyController::class , 'store'])->middleware('auth:sanctum');
    Route::put('/properties/{id}' , [PropertyController::class , 'update'])->middleware('auth:sanctum');
    Route::delete('/properties/{id}' , [PropertyController::class , 'destroy'])->middleware('auth:sanctum');
    #agent properties-amenities
    Route::put('/properties/attach-amenity/{propertyId}/{amenityId}' , [PropertyController::class , 'attachAmenity'])->middleware('auth:sanctum');
    Route::delete('/properties/detach-amenity/{propertyId}/{amenityId}' , [PropertyController::class , 'detachAmenity'])->middleware('auth:sanctum');

    // Agent - feedback
    Route::get('/properties/{propertyId}/feedback', [FeedbackController::class, 'getFeedbackForProperty'])->middleware('auth:sanctum');

    // Agent - count property feedbacks and visitRequests
    Route::get('/count-property/{propertyId}/feedbacks' , [PropertyController::class , 'countPropertyFeedbacks'])->middleware('auth:sanctum');
    Route::get('/count-property/{propertyId}/visitRequests' , [PropertyController::class , 'countPropertyVisitRequests'])->middleware('auth:sanctum');

    // agent -  image upload
    Route::post('/upload-image', [ImageController::class, 'store'])->middleware('auth:sanctum'); // For image upload
    // agent - visitRequests
    Route::get('/visit-requests', [VisitRequestController::class, 'list'])->middleware('auth:sanctum'); // List all visit requests for authenticated user

    // agent  - seraching
    Route::get('/search/properties', [SearchController::class, 'search'])->middleware('auth:sanctum');
});








// Client routes
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

// amenities

Route::get('/amenities' , [AmenityController::class , 'index'])->middleware('auth:sanctum');

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



// messages
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->middleware('auth:sanctum');
    Route::get('/messages/{receiverId}', [MessageController::class, 'getMessages'])->middleware('auth:sanctum');
    Route::patch('/messages/{id}/read', [MessageController::class, 'markAsRead'])->middleware('auth:sanctum');
});
//properties

Route::get('/properties', [PropertyController::class , 'index'])->middleware('auth:sanctum');
Route::get('/properties/{id}' , [PropertyController::class , 'show'])->middleware('auth:sanctum');


Route::get('/properties/nearby/{latitude}/{longitude}/{radius?}', [PropertyController::class, 'nearbyProperties'])->middleware('auth:sanctum');

// Recommendation System
Route::get('/recommendations/{propertyId}', [RecommendationController::class, 'fetchRecommendations'])->middleware('auth:sanctum');
Route::post('/recommendations/{propertyId}/update', [RecommendationController::class, 'updateRecommendations'])->middleware('auth:sanctum');


// seraching
Route::get('/search/properties', [SearchController::class, 'search'])->middleware('auth:sanctum');

// user profile
Route::apiResource('/users' , UserProfileController::class)->middleware('auth:sanctum');


// Visit Requests Routes
Route::get('/visit-requests', [VisitRequestController::class, 'list'])->middleware('auth:sanctum'); // List all visit requests for authenticated user
Route::post('/visit-requests', [VisitRequestController::class, 'store'])->middleware('auth:sanctum'); // Create a new visit request
Route::delete('visit-requests/{id}', [VisitRequestController::class, 'destroy'])->middleware('auth:sanctum'); // Delete a specific visit request






