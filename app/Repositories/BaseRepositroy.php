<?php
namespace App\Repositories;

class BaseRepositroy {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function create(array $payload = []) {
        return $this->model->create($payload);
    }

    public function findByField(string $field = '',mixed $value = null) {
        return $this->model->where($field, $value)->first();
    }
}
