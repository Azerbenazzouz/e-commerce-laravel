<?php
namespace App\Repositories\Post\Catalogue;

use App\Models\PostCatalogue;
use App\Repositories\BaseRepositroy;

class PostCatalogueRepository extends BaseRepositroy {
    public function __construct(PostCatalogue $model) {
        parent::__construct($model);
    }
}
