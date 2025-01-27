<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseController extends Controller {
    protected $service;
    protected $resource;
    abstract protected function getStoreRequest() : string;
    abstract protected function getUpdateRequest() : string;

    public function __construct($service = null) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $storeRequest = app($this->getStoreRequest());
        $storeRequest->validated();
    
        $result = $this->service->create($request);
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data']);
            return ApiResource::ok($objectResource->toArray($request), 'Data created successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to create', Response::HTTP_BAD_REQUEST);
    }
}