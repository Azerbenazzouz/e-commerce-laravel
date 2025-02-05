<?php
namespace App\Service\Impl\Post;

use App\Repositories\Post\Catalogue\PostCatalogueRepository;
use App\Service\Impl\BaseService;
use App\Service\Interfaces\Post\Catalogue\PostCatalogueServiceInterface;

class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface{
    
    protected $roleRepo;
    protected $payload;

    public function __construct(
        PostCatalogueRepository $roleRepo
    ) {
        parent::__construct($roleRepo);        
    }

    protected function requestPayload(): array {
        return ['name', 'publish', 'permissions'];
    }

    protected function getSearchField(): array {
        return ['name'];
    }

    protected function getPerpage() : int {
        return 20;
    }

    protected function getSimpleFilter() : array {
        return ['publish'];
    }

    protected function getComplexFilter(): array{
        return ['id'];
    }

    protected function getDateFilter(): array {
        return ['created_at'];
    }


    protected function getManyToManyRelationship() : array {
        return ['permissions'];
    }

}
