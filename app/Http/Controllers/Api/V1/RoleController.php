<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Requests\Role\DeleteRequest;
use App\Http\Resources\RoleResource;
use App\Service\Interfaces\RoleServiceInterface as RoleService;

class RoleController extends BaseController {

    protected $roleService;
    protected $resource = RoleResource::class;
    public function __construct(
        RoleService $roleService
    ) {
        parent::__construct($roleService);
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