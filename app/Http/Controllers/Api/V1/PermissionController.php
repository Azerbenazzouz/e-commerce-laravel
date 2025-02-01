<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Permission\DeleteMultipleRequest;
use App\Http\Requests\Permission\DeleteRequest;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Service\Interfaces\PermissionServiceInterface as PermissionService;


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
}
