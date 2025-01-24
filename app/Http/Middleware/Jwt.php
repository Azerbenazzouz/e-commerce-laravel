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

class Jwt
{

    public function handle(Request $request, Closure $next): Response {
        try {

            if (!$request->hasHeader('Authorization') || !$request->bearerToken()) {
                $resource = ApiResource::message('Token not found', Response::HTTP_UNAUTHORIZED);
                return response()->json($resource, Response::HTTP_UNAUTHORIZED);
            } 

            $user = JWTAuth::parseToken()->authenticate();

            if(!$user) {
                $resource = ApiResource::message('User not found', Response::HTTP_NOT_FOUND);
                return response()->json($resource, Response::HTTP_NOT_FOUND);
            }
        } catch (TokenExpiredException $e) {
            $resource = ApiResource::message('Token expired', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException $e) {
            $resource = ApiResource::message('Token invalid', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        } catch (JWTException $e) {
            $resource = ApiResource::message('Token not found', Response::HTTP_UNAUTHORIZED);
            return response()->json($resource, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
