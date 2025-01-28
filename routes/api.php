<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::middleware('jwt')->group(function() {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('v1')->middleware(['jwt'])->group(function () {
    Route::group(          ['prefix' => 'roles'], function(){
        Route::get('all', [RoleController::class, 'all']);
        Route::resource('', RoleController::class)->except(['create', 'edit']);
    });
});
