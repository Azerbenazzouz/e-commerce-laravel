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
        $routeAction = $request->route()->getActionName();
        [$controllerFull, $actionMethod] = explode('@', $routeAction);
        $controller = class_basename($controllerFull);
        $model = strtolower(str_replace('Controller', '', $controller));
    
        $user = $this->auth->user();
        $permissions = $user->getJWTCustomClaims()['permissions'] ?? [];
        
        $permissionName = "{$model}:{$actionMethod}";
        if (!in_array($permissionName, $permissions)) {
            return ApiResource::message('Permission denied', HttpResponse::HTTP_FORBIDDEN);
        }
    
        $request->merge([
            'viewScope' => in_array("{$model}:viewAll", $permissions) ? 'all' : 'own',
            'actionScope' => in_array("{$model}:actionAll", $permissions) ? 'all' : 'own',
        ]);
    
        return $next($request);
    }
}
