<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\Post\PostCatalogueController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1/auth'], function ($router) {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::middleware('jwt')->group(function() {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('v1')->middleware(['jwt' , 'checkPermission'])->group(function () {
    /* Roles Route */ 
    Route::group(['prefix' => 'roles'], function(){
        Route::get('all', [RoleController::class, 'all']);
        Route::delete('delete-multiple', [RoleController::class, 'deleteMultiple']);
    });
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    /* ----------- */
    /* Users Route */
    Route::group(['prefix' => 'users'], function(){
        Route::get('all', [UserController::class, 'all']);
        Route::delete('delete-multiple', [UserController::class, 'deleteMultiple']);
    });
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    /* ----------- */

    /* Permission Route */
    Route::group(['prefix' => 'permissions'], function(){
        Route::get('all', [PermissionController::class, 'all']);
        Route::delete('delete-multiple', [PermissionController::class, 'deleteMultiple']);
        Route::post('create-module-permission', [PermissionController::class, 'createModulePermission']);
    });
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    /* ----------- */

    /* Post Catalogues Route */
    Route::group(['prefix' => 'post_catalogues'], function(){
        Route::get('all', [PostCatalogueController::class, 'all']);
        Route::delete('delete-multiple', [PostCatalogueController::class, 'deleteMultiple']);
        Route::post('create-module-permission', [PostCatalogueController::class, 'createModulePermission']);
    });
    Route::resource('post_catalogues', PostCatalogueController::class)->except(['create', 'edit']);
    /* ----------- */
});
