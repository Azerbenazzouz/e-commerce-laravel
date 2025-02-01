<?php 
namespace App\Service\Impl;

use App\Service\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseService implements BaseServiceInterface{
    /**
     * $repository : mixed
     * $repository elle contient le repository qui sera utilisé pour les opérations CRUD
     */
    protected $repository;
    /**
     * $payload : array
     * $payload elle contient les données envoyées par le client
     */
    protected $payload;
    /**
     * $operators : array
     * $operators elle contient les opérateurs qui sont utilisés pour les filtres complexes
     */
    protected $operators = ['gt', 'gte', 'lt', 'lte'];

    /**
     * requestPayload() : array
     * requestPayload elle retourne un tableau des champs qui sont envoyés par le client
     */
    abstract protected function requestPayload(): array;
    /**
     * getSearchFieald() : array
     * getSearchFieald elle retourne un tableau des champs qui sont utilisés pour la recherche
     */
    abstract protected function getSearchFieald(): array;
    /**
     * getPerpage() : int
     * getPerpage elle retourne le nombre des éléments par page
     */
    abstract protected function getPerpage(): int;
    /**
     * getSimpleFilter() : array
     * getSimpleFilter elle retourne un tableau des champs qui sont utilisés pour les filtres simples
     * exemple : ?name=azer
     */
    abstract protected function getSimpleFilter(): array;
    /**
     * getComplexFilter() : array
     * getComplexFilter elle retourne un tableau des champs qui sont utilisés pour les filtres complexes
     * exemple : ?age[gt]=18
     */
    abstract protected function getComplexFilter(): array;
    /**
     * getDateFilter() : array
     * getDateFilter elle retourne un tableau des champs qui sont utilisés pour les filtres de date
     * exemple : ?created_at=2021-01-01
     */
    abstract protected function getDateFilter(): array;
    

    /**
     * __construct($repository)
     * __construct elle permet d'initialiser le repository
     * @param mixed $repository
     */
    public function __construct($repository){
        $this->repository = $repository;
    }

    /**
     * show(int $id) : array
     * show elle retourne un tableau qui contient les données d'un élément
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function show(int $id){
        try {
            return [
                'data' => $this->repository->show($id),
                'flag' => true
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'flag' => false
            ];
        }
    }

    /**
     * getList() : array
     * getList elle retourne un tableau qui contient la liste des éléments
     * @return array
     * @throws \Exception
     */
    public function getList() {
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

    /**
     * paginate(Request $request) : array
     * paginate elle retourne un tableau qui contient la liste des éléments paginée
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    private function buildFilter(Request $request, array $filters = []) {
        $conditions = [];
        if(count($filters)) {
            foreach ($filters as $filter) {
                if ($request->has($filter)) {
                    $conditions[$filter] = $request->input($filter);
                }
            }
        }
        return $conditions;
    }

    /**
     * specifications(Request $request) : array
     * specifications elle retourne un tableau qui contient les spécifications pour la pagination
     * @param Request $request
     * @return array
     */
    private function specifications(Request $request){
        return [
            'keyword' => [
                'q' => $request->input('keyword'),
                'field' => $this->getSearchFieald()
            ],
            'sortBy' => ($request->input('sortBy')) ? explode(',', $request->input('sortBy')) : ['id', 'desc'],
            'perpage' => ($request->input('perpage')) ? $request->input('perpage') : $this->getPerpage(),
            'filters' => [
                'simple' => $this->buildFilter($request, $this->getSimpleFilter()),
                'complex' => $this->buildFilter($request, $this->getComplexFilter()),
                'date' => $this->buildFilter($request, $this->getDateFilter())
            ]
        ];
    }

    /**
     * paginate(Request $request) : array
     * paginate elle retourne un tableau qui contient la liste des éléments paginée
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function paginate(Request $request) {
        $specification = $this->specifications($request);
        return $this->repository->paginate($specification);
    }

    /**
     * setPayload(Request $request) : BaseService
     * setPayload elle permet de récupérer les données envoyées par le client
     * @param Request $request
     * @return BaseService
     */
    protected function setPayload(Request $request) {
        $this->payload = $request->only($this->requestPayload());
        return $this;
    }

    /**
     * buildPayload() : array
     * buildPayload elle retourne les données qui seront enregistrées
     * @return array
     */
    public function buildPayload() {
        return $this->payload;
    }

    /**
     * processPayload() : BaseService
     * processPayload elle permet de traiter les données avant de les enregistrer
     * @return BaseService
     */
    protected function processPayload() {
        return $this;
    }

    /**
     * save(Request $request, mixed $id = null) : array
     * save elle permet d'enregistrer ou de mettre à jour un élément
     * @param Request $request
     * @param mixed $id
     * @return array
     * @throws \Exception
     */
    // public function save(Request $request, mixed $id = null): array {
    //     DB::beginTransaction();
    //     try {
    //         $payload = $this
    //             ->setPayload($request)
    //             ->processPayload()
    //             ->buildPayload();
            
    //         $model = $this->repository->save($payload, $id);
    //         $this->handleManyToManyRelation($model, $payload);
    //         DB::commit();
    //         return [
    //             'data' => $model,
    //             'flag' => true
    //         ];
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return [
    //             'error' => $e->getMessage(),
    //             'flag' => false
    //         ];
    //     }
    // }

    public function save(Request $request, mixed $id = null): array {
        DB::beginTransaction();
        try {
            $payload = $this
                ->setPayload($request)
                ->processPayload()
                ->buildPayload();
    
            $extract = $this->extractManyToManyRelation($payload);
            $payload = $extract['payload'];
            $relationsPayload = $extract['relations'];
            
            $model = $this->repository->save($payload, $id);
            $this->handleManyToManyRelation($model, $relationsPayload);
    
            DB::commit();
            return [
                'data' => $model,
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

    /**
     * delete(int $id) : array
     * delete elle permet de supprimer un élément
     * @param int $id
     * @return array
     * @throws \Exception
     */
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

    /**
     * deleteMultiple(array $ids) : array
     * deleteMultiple elle permet de supprimer plusieurs éléments
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function deleteMultiple(array $ids) {
        DB::beginTransaction();
        try {      
            $this->repository->deleteWhereIn($ids);

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
    /**
     * getManyToManyRelationship() : array
     * getManyToManyRelationship elle retourne un tableau des relations many to many
     */
    protected function getManyToManyRelationship() : array {
        return [];
    }

    private function extractManyToManyRelation(array $payload = []) : array{
        // Extract roles and other many-to-many relations from the payload
        $relations = $this->getManyToManyRelationship();
        $relationsPayload = [];

        foreach ($relations as $relation) {
            if (isset($payload[$relation])) {
                $relationsPayload[$relation] = $payload[$relation];
                unset($payload[$relation]);
            }
        }
        return [
            'payload' => $payload,
            'relations' => $relationsPayload
        ];
    }

    private function handleManyToManyRelation(Model $model, array $relationsPayload = []) {
        // Sync the many-to-many relationships
        foreach ($relationsPayload as $relation => $relationData) {
            $model->$relation()->sync($relationData);
        }
    }
}