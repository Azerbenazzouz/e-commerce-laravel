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
    abstract protected function getDeleteMultipleRequest() : string;

    public function __construct($service = null) {
        $this->service = $service;
    }


    public function index(Request $request) {
        try {
            $data = $this->service->paginate($request);

            $data['data']->through(function($item){
                return new $this->resource($item);
            });
            return ApiResource::ok($data, 'Data retrieved successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ApiResource::message('Error: '.$e->getMessage(), Response::HTTP_BAD_REQUEST);
        }    
    }

    public function all(Request $request) {
        try {
            $result = $this->service->paginate($request, 'list');
            if ($result['flag']) {
                $objectResource = $this->resource::collection($result['data']);
                return ApiResource::ok($objectResource->toArray($request), 'Data retrieved successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return ApiResource::message('Error: '.$e->getMessage(), Response::HTTP_BAD_REQUEST);
        } 
    }

    public function show($id) {
        try {
            $result = $this->service->show($id);
            if ($result['flag']) {
                $objectResource = new $this->resource($result['data']);
                return ApiResource::ok($objectResource->toArray(request()), 'Data retrieved successfully', Response::HTTP_OK);
            } else {
                return ApiResource::error($result, 'Data not found', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return ApiResource::message('Error: '.$e->getMessage(), $result['code']);
        } 
    }

    protected function handleRequest(string $requestAction = '') {
        $storeRequest = app($requestAction);
        $storeRequest->validated();
    }

    public function store(Request $request) {
        $this->handleRequest($this->getStoreRequest());
        $result = $this->service->save($request, null, 'create');
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data']);
            return ApiResource::ok($objectResource->toArray($request), 'Data created successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to create', Response::HTTP_BAD_REQUEST);
    }


    public function update(Request $request, $id) {
        $this->handleRequest($this->getUpdateRequest());

        $result = $this->service->save($request, $id, 'update');
        if ($result['flag']) {
            $objectResource = new $this->resource($result['data']);
            return ApiResource::ok($objectResource->toArray($request), 'Data updated successfully', Response::HTTP_CREATED);
        }
        return ApiResource::error($result, 'Failed to update', $result['code']);
    }

    public function destroy(Request $request, $id) {
        $this->handleRequest($this->getDeleteRequest());
        $result = $this->service->delete($request, $id);
        if ($result['flag']) {
            return ApiResource::ok($result, 'Data deleted successfully', Response::HTTP_OK);
        }
        return ApiResource::error($result, 'Failed to delete', $result['code']); 
    }

    public function deleteMultiple(Request $request){
        $this->handleRequest(requestAction: $this->getDeleteMultipleRequest());
        $result = $this->service->deleteMultiple($request->ids);
        if ($result['flag']) {
            return ApiResource::ok($result, 'Data deleted successfully', Response::HTTP_OK);
        }
        return ApiResource::error($result, 'Failed to delete', Response::HTTP_BAD_REQUEST);
    }
}