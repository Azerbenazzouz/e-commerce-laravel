<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Http\Resources\ApiResource;
use Tymon\JWTAuth\Exceptions\JWTException;

class Jwt {

    public function handle(Request $request, Closure $next): Response {
        try {

            if (!$request->hasHeader('Authorization') || !$request->bearerToken()) {
                return ApiResource::message('Token not found', Response::HTTP_UNAUTHORIZED);
            } 

            $user = JWTAuth::parseToken()->authenticate();

            if(!$user) {
                return ApiResource::message('User not found', Response::HTTP_NOT_FOUND);
            }
        } catch (TokenExpiredException $e) {
            return ApiResource::message('Token expired', Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            return ApiResource::message('Token invalid', Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            return ApiResource::message('Token not found', Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
