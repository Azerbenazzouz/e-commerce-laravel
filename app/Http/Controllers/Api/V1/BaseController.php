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
    abstract protected function getDeleteRequest() : string;

    public function __construct($service = null) {
        $this->service = $service;
    }


    public function index(Request $request) {
        try {
            $data = $this->service->paginate($request);
            $data->through(function($item){
                return new $this->resource($item);
            });
            return ApiResource::ok($data, 'Data retrieved successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResource::message('Error: '.$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }    
    }

    public function all(Request $request) {
        try {
            $result = $this->service->getList();
            if ($result['flag']) {
                $objectResource = $this->resource::collection($result['data']);
                return ApiResource::ok($objectResource->toArray($request), 'Data retrieved successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return ApiResource::message('Error: '.$e->getMessage(), Response::HTTP_BAD_REQUEST);
        } 
    }

    private function handleRequest(string $requestAction = '') {
        $storeRequest = app($requestAction);
        $storeRequest->validated();
    }

    public function store(Request $request) {
        $this->handleRequest($this->getStoreRequest());
        $result = $this->service->save($request);
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data']);
            return ApiResource::ok($objectResource->toArray($request), 'Data created successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to create', Response::HTTP_BAD_REQUEST);
    }


    public function update(Request $request, $id) {
        $this->handleRequest($this->getUpdateRequest());

        $result = $this->service->save($request, $id);
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data']);
            return ApiResource::ok($objectResource->toArray($request), 'Data updated successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to update', Response::HTTP_BAD_REQUEST);
    }

    public function destroy($id) {
        $this->handleRequest($this->getDeleteRequest());
        $result = $this->service->delete($id);
        if ($result['flag']) {
            return ApiResource::ok($result, 'Data deleted successfully', Response::HTTP_OK);
        }
        return ApiResource::error($result, 'Failed to delete', Response::HTTP_BAD_REQUEST);
    }
}