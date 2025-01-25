<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\ApiResource;
use App\Service\Impl\RefreshTokenService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AuthController extends Controller {

    private $refreshTokenService;

    public function __construct(RefreshTokenService $refreshTokenService) {
        $this->refreshTokenService = $refreshTokenService;
    }


    public function authenticate(AuthRequest $request) {
        $credentials = [
            'email' => $request->string('email'), 
            'password' => $request->string('password')
        ];

        if (! $token = auth('api')->attempt($credentials)) {
            $resource = ApiResource::message('Unauthorized', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }
        // create Refresh Token
        $refreshTokenPayload = [
            'refresh_token' => Str::uuid(),
            'user_id' => auth('api')->user()->id,
            'expires_at' => now()->addDay() // expired in 1 day
        ];

        if($this->refreshTokenService->create($refreshTokenPayload)) {
            $resource = ApiResource::ok($this->respondWithToken($token, $refreshTokenPayload), 'SUCCESS', Response::HTTP_OK);
            return response()->json($resource, Response::HTTP_OK);
        }
        
        $resource = ApiResource::message('Unauthorized', Response::HTTP_UNAUTHORIZED);
        return response()->json($resource, Response::HTTP_UNAUTHORIZED);
    }

    protected function respondWithToken($token, $refreshTokenPayload) {
        return [
            'accessToken' => $token,
            'refreshToken' => $refreshTokenPayload['refresh_token'],
            'tokenType' => 'bearer',
            'expiresIn' => auth('api')->factory()->getTTL() * 60
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
