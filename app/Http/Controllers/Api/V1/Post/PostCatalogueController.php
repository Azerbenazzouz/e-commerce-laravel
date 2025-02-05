<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Post\Catalogue\DeleteMultipleRequest;
use App\Http\Requests\Post\Catalogue\StoreRequest;
use App\Http\Requests\Post\Catalogue\UpdateRequest;
use App\Http\Requests\Post\Catalogue\DeleteRequest;
use App\Http\Resources\Post\Catalogue\PostCatalogueResource;
use App\Service\Interfaces\Post\PostCatalogueServiceInterface as PostCatalogueService;
use Illuminate\Http\Request;

class PostCatalogueController extends BaseController {

    protected $roleService;
    protected $resource = PostCatalogueResource::class;
    public function __construct(
        PostCatalogueService $roleService
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

    protected function getDeleteMultipleRequest(): string {
        return DeleteMultipleRequest::class;
    }

    public function all(Request $request) {
        return parent::all($request);
    }

    public function store(Request $request) {
        return parent::store($request);
    }

    public function show($id) {
        return parent::show($id);
    }
}