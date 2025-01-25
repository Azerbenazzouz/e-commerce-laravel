<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepositroy {
    private $model;

    public function __construct(User $model) {
        parent::__construct($model);
        $this->model = $model;
    }

    public function findById($id) {
        return $this->model
            ->where('id', $id)
            ->first();
    }
}
