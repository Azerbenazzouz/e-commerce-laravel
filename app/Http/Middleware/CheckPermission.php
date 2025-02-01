<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission {

    private $auth;

    public function __construct() {
        $this->auth = auth('api');
    }

    public function handle(Request $request, Closure $next): Response {
        $controller = class_basename(explode('@', $request->route()->getActionName())[0]);
        $model = strtolower(str_replace('Controller', '', $controller));
        $method = $request->route()->getActionMethod();
        /**@var User $user */
        $user = $this->auth->user();
        $permissions =$user->getJWTCustomClaims()['permissions'];
        $permissionName = $model . ':' . $method;
        if(!in_array($permissionName, $permissions)){
            return ApiResource::message('Permission denied', HttpResponse::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
