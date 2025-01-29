<?php 
namespace App\Service\Impl;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseService {
    protected $repository;
    protected $payload;
    protected $operators = ['gt', 'gte', 'lt', 'lte'];

    abstract protected function requestPayload(): array;
    abstract protected function getSearchFieald(): array;
    abstract protected function getPerpage(): int;
    abstract protected function getSimpleFilter(): array;
    abstract protected function getComplexFilter(): array;
    abstract protected function getDateFilter(): array;

    public function __construct($repository){
        $this->repository = $repository;
    }

    public function all() {
        try {
            return [
                'data' => $this->repository->all(),
                'flag' => true
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }
    }

    private function simpleFilter(Request $request ,array $filters = []){
        $simpleFilter = [];
        if(count($filters)) {
            foreach($filters as $filter) {
                if($request->has($filter)) {
                    $simpleFilter[$filter] = $request->input($filter);
                }
            }
        }
        return $simpleFilter;
    }

    private function complexFilter(Request $request, array $complexFilters = []){
        $conditions = [];
        foreach($complexFilters as $filter) {
            if($request->has($filter)){
                $conditions[$filter] = $request->input($filter);
            }
        }
        return $conditions;
    }

    private function dateFilter(Request $request, array $complexFilters = []) {
        $conditions = [];
        foreach ($complexFilters as $field) {
            if ($request->has($field)) {
                $conditions[$field] = $request->input($field);
            }
        }
        return $conditions;
    }
    private function specifications(Request $request){
        return [
            'keyword' => [
                'q' => $request->input('keyword'),
                'field' => $this->getSearchFieald()
            ],
            'sortBy' => ($request->input('sortBy')) ? explode(',', $request->input('sortBy')) : ['id', 'desc'],
            'perpage' => ($request->input('perpage')) ? $request->input('perpage') : $this->getPerpage(),
            'simpleFilter' => $this->simpleFilter($request, $this->getSimpleFilter()),
            'complexFilter' => $this->complexFilter($request, $this->getComplexFilter()),
            'dateFilter' => $this->dateFilter($request, $this->getDateFilter()),
        ];
    }

    public function paginate($request) {
        $specification = $this->specifications($request);
        return $this->repository->paginate($specification);
    }

    protected function setPayload(Request $request) {
        $this->payload = $request->only($this->requestPayload());
        return $this;
    }

    public function buildPayload() {
        return $this->payload;
    }

    protected function processPayload() {
        return $this;
    }

    public function save(Request $request, mixed $id = null): array {
        DB::beginTransaction();
        try {
            $payload = $this
                ->setPayload($request)
                ->processPayload()
                ->buildPayload();
            
            $result = $this->repository->save($payload, $id);

            DB::commit();
            return [
                'data' => $result,
                'flag' => true
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }
    }

    public function delete(int $id) {
        DB::beginTransaction();
        try {      
            $this->repository->delete($id);

            DB::commit();
            return [
                'flag' => true
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }
    }
}