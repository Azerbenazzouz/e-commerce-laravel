<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Permsission\DeleteMultipleRequest;
use App\Http\Requests\Permsission\DeleteRequest;
use App\Http\Requests\Permsission\StoreRequest;
use App\Http\Requests\Permsission\UpdateRequest;
use App\Http\Resources\PermsissionResource;
use App\Service\Impl\PermsissionService;

class PermsissionController extends BaseController{
    protected $userService;
    protected $resource = PermsissionResource::class;
    public function __construct(
        PermsissionService $userService
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
