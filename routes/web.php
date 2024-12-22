<?php

use App\Http\Controllers\web\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/property/show/{propertyId}', [PropertyController::class, 'show'])->name('property.show');



