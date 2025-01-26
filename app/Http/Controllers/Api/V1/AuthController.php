<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Resources\ApiResource;
use App\Repositories\RefreshTokenRepositroy;
use App\Repositories\UserRepository;
use App\Service\Impl\RefreshTokenService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller {

    private $refreshTokenService;
    private $refreshTokenRepository;
    private $userRepository;

    public function __construct(RefreshTokenService $refreshTokenService, RefreshTokenRepositroy $refreshTokenRepository, UserRepository $userRepository) {
        $this->refreshTokenService = $refreshTokenService;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->userRepository = $userRepository;
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
            'expires_at' => now()->addMonth()// expired in 1 day
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
            'expiresIn' => auth('api')->factory()->getTTL()
        ];
    }

    public function me() {
        $auth = auth('api')->user();
        $resource = ApiResource::ok(['auth' => $auth], 'SUCCESS', Response::HTTP_OK);
        return response()->json($resource, Response::HTTP_OK);
    }

    public function refreshToken(RefreshTokenRequest $request) {
        $refreshToken = $this->refreshTokenRepository->findRefreshTokenValid($request->input('refreshToken'));
        $user = $this->userRepository->findById($refreshToken->user_id);
        if(!$user){
            $resource = ApiResource::message('Unauthorized', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }

        try {
            auth('api')->invalidate(true);
        } catch (TokenExpiredException $e) {
            $resource = ApiResource::message('Token Expired', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            $resource = ApiResource::message('Token Invalid', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            $resource = ApiResource::message('Fail to invalidate token', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }


        $token = auth('api')->login($user); // login user with new token
        if($token) {
            $resource = ApiResource::ok($this->respondWithToken($token, $refreshToken), 'SUCCESS', Response::HTTP_OK);
            return response()->json($resource, Response::HTTP_OK);
        }
        
        $resource = ApiResource::message('Server Error...', Response::HTTP_INTERNAL_SERVER_ERROR);
        return response()->json($resource, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function logout() {
        try {
            $user = auth('api')->user();
            $this->refreshTokenRepository->deleteByUserId($user->id);
            
            auth('api')->invalidate(true);
            auth('api')->logout();

            $resource = ApiResource::message('Logout Success', Response::HTTP_OK);
            return response()->json($resource, Response::HTTP_OK);
        } catch (\Exception $e) {
            $resource = ApiResource::message('Server Error...', Response::HTTP_INTERNAL_SERVER_ERROR);
            return response()->json($resource, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
