<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Permission\CreateModuleRequest;
use App\Http\Requests\Permission\DeleteMultipleRequest;
use App\Http\Requests\Permission\DeleteRequest;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\PermissionResource;
use App\Service\Interfaces\PermissionServiceInterface as PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends BaseController{
    protected $userService;
    protected $resource = PermissionResource::class;
    public function __construct(
        PermissionService $userService
    ) {
        parent::__construct($userService);
    }

    protected function getStoreRequest(): string {
        return StoreRequest::class;
    }

    protected function getUpdateRequest(): string {
        return UpdateRequest::class;
    }

    protected function getDeleteRequest(): string {
        return DeleteRequest::class;
    }

    protected function getDeleteMultipleRequest(): string {
        return DeleteMultipleRequest::class;
    }

    public function createModulePermission(Request $request){
        $this->handleRequest(CreateModuleRequest::class);
        $result = $this->service->createModulePermission($request);
        if ($result['flag']) {
            $permissions = array_map(function($permission) {
                return new $this->resource($permission);
            }, $result['data']['permissions']);
            return ApiResource::ok($permissions, 'Data created successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to create', Response::HTTP_BAD_REQUEST);
    }
}
