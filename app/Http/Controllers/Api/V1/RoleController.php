<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Service\Impl\RoleService;

class RoleController extends BaseController {

    protected $roleService;

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
}