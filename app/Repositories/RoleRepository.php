<?php
namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepositroy {
    public function __construct(Role $model) {
        parent::__construct($model);
    }
}
