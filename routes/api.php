<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\ListingOfferController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RealtorListingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{listing}', [ListingController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::post(
        '/listings/{listing}/offers',
        [ListingOfferController::class, 'store']
    );

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put(
        '/notifications/{notification}/seen',
        [NotificationController::class, 'seen']
    );

    Route::prefix('realtor')->group(function () {
        Route::apiResource('listings', RealtorListingController::class);
        Route::put(
            '/listings/{listing}/restore',
            [RealtorListingController::class, 'restore']
        );
        Route::put(
            '/offers/{offer}/accept',
            [RealtorListingController::class, 'accept']
        );
    });
});
