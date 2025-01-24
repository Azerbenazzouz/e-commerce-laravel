<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller {

    public function authenticate() {
        return response()->json(['message' => 'Hello World!']);
    }
}
