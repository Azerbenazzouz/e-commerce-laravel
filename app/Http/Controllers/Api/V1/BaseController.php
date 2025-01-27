<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class BaseController extends Controller {
    protected $service;

    abstract protected function getStoreRequest() : string;
    abstract protected function getUpdateRequest() : string;

    public function __construct($service = null) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $validator = app($this->getStoreRequest());
        $storeRequest = $validator->validate($validator->rules());
        if($data = $this->service->create($request)){
            return ApiResource::ok($data, 'Successfully created', Response::HTTP_CREATED);
        }
        return ApiResource::error($data, 'Failed to create', Response::HTTP_BAD_REQUEST);
    }
}