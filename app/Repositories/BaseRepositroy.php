<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class BaseRepositroy {
    private Model $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function create(array $payload = []) {
        return $this->model->create($payload)->fresh();
    }

    public function update(int $id,array $payload = []) {
        if($this->model->where('id', $id)->update($payload) > 0) {
            return $this->model->find($id);
        }
        throw new \Exception('Failed to update data with id ' . $id);
    }

    public function findByField(string $field = '',mixed $value = null) {
        return $this->model->where($field, $value)->first();
    }

    public function findByld(int $id,array $relation = []) {
        return $this
                ->model
                ->where($relation)
                ->find($id);
    }

    public function save(array $payload = [], mixed $id = null) {
        return ($id) ? $this->update($id, $payload) : $this->create($payload);
    }

    public function delete(int $id) {
        if($this->model->where('id', $id)->delete() === 0)
            throw new \Exception('Failed to delete data with id ' . $id);
    }

    public function deleteWhereIn(array $id = []) {
        return $this->model->whereIn('id', $id)->delete();        
    }

    public function show(int $id) {
        $data = $this->model->find($id);
        if ( $data == null ) throw new \Exception(message: 'Data not found');
        return $data;
    }

    public function all() {
        return $this->model->all();
    }

    public function paginate(array $specs = []) {
        // dd($specs);
        return $this->model
                ->keyword($specs['keyword'] ?? [])
                ->orderBy($specs['sortBy'][0], $specs['sortBy'][1])
                ->simpleFilter($specs['filters']['simple'] ?? [])
                ->complexFilter($specs['filters']['complex'] ?? [])
                ->dateFilter($specs['filters']['date'] ?? [])
                ->paginate($specs['perpage']);
    }

    public function checkExist(string $field = '', mixed $value = null) : bool {
        return $this->model->where($field, $value)->first() != null;
    }

}
