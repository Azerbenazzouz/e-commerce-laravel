<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;

class AuthController extends Controller {

    public function authenticate(AuthRequest $request) {
        return response()->json(['message' => 'Hello World!']);
    }
}
