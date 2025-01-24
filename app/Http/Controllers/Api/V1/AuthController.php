<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Response;

class AuthController extends Controller {

    public function __construct() {

    }


    public function authenticate(AuthRequest $request) {
        $credentials = [
            'email' => $request->string('email'), 
            'password' => $request->string('password')
        ];

        if (! $token = auth('api')->attempt($credentials)) {
            $resource = ApiResource::message('Unauthoruzed', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }

        $resource = ApiResource::ok($this->respondWithToken($token), 'SUCCESS', Response::HTTP_OK);
        return response()->json($resource, Response::HTTP_OK);
    }

    protected function respondWithToken($token) {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    public function me() {
        $auth = auth('api')->user();
        $resource = ApiResource::ok(['auth' => $auth], 'SUCCESS', Response::HTTP_OK);
        return response()->json($resource, Response::HTTP_OK);
    }

    public function logout() {
        auth('api')->logout();
        $resource = ApiResource::ok([], 'SUCCESS', Response::HTTP_OK);
        return response()->json($resource, Response::HTTP_OK);
    }
}
