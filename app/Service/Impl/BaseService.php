<?php 
namespace App\Service\Impl;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseService {
    protected $repository;
    protected $payload;

    abstract protected function requestPayload(): array;

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