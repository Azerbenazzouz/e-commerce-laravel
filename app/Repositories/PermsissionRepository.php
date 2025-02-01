<?php
namespace App\Repositories;

use App\Models\Permsission;

class PermsissionRepository extends BaseRepositroy {
    public function __construct(Permsission $model) {
        parent::__construct($model);
    }
}
