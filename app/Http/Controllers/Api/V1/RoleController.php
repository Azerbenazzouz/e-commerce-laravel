<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Requests\Role\DeleteRequest;
use App\Http\Resources\RoleResource;
use App\Service\Impl\RoleService;

class RoleController extends BaseController {

    protected $roleService;
    protected $resource = RoleResource::class;
    public function __construct(
        RoleService $roleService
    ) {
        parent::__construct($roleService);
    }

    public function index() {
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully'
        ], 200);
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
}