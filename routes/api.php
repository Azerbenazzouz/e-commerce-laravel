<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/auth/login', [AuthController::class, 'authenticate']);