<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::middleware('jwt')->group(function() {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});