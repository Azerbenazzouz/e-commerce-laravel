<?php
namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends BaseRepositroy {
    public function __construct(Permission $model) {
        parent::__construct($model);
    }
}
