<?php 
namespace App\Service\Impl;

use App\Service\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;

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
     * $auth : mixed
     * $auth elle contient les informations de l'utilisateur authentifié
     */
    protected $auth;

    /**
     * requestPayload() : array
     * requestPayload elle retourne un tableau des champs qui sont envoyés par le client
     */
    abstract protected function requestPayload(): array;
    /**
     * getSearchFieald() : array
     * getSearchFieald elle retourne un tableau des champs qui sont utilisés pour la recherche
     */
    abstract protected function getSearchField(): array;
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
    

    public function __construct($repository){
        $this->repository = $repository;
        /**
         * @var \Tymon\JWTAuth\JWTGuard
         */
        $this->auth = auth('api');
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
                'field' => $this->getSearchField()
            ],
            'sortBy' => ($request->input('sortBy')) ? explode(',', $request->input('sortBy')) : ['id', 'desc'],
            'perpage' => ($request->input('perpage')) ? $request->input('perpage') : $this->getPerpage(),
            'filters' => [
                'simple' => $this->buildFilter($request, $this->getSimpleFilter()),
                'complex' => $this->buildFilter($request, $this->getComplexFilter()),
                'date' => $this->buildFilter($request, $this->getDateFilter())
            ],
            'scope' => [
                'view' => $request->input('viewScope'),
                'action' => $request->input('actionScope')
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
    public function paginate(Request $request, string $recordType = 'paginate') {
        $specification = $this->specifications($request);
        try {
            return [
                'data' => $this->repository->paginate($specification, $recordType),
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
     * processPayload(Request $request) : BaseService | processPayload() : BaseService
     * processPayload elle permet de traiter les données envoyées par le client
     * @param Request $request
     * @return BaseService
     */
    protected function processPayload(?Request $request = null) {
        return $this;
    }

    protected function setUserId() {
        // $id = ;
        $this->payload['user_id'] = $this->auth->user()->id;
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
    public function save(Request $request, mixed $id = null, $method = 'create'): array {
        DB::beginTransaction();
        try {

            if($method == 'update') {
                $this->validatePermission($request, $id);
            }

            $payload = $this
                ->setPayload($request)
                ->processPayload($request)
                ->buildPayload();

            dd("123");
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
        } catch(AuthorizationException $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false,
                'code' => 403
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false,
                'code' => 400
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
    public function delete(Request $request ,int $id) {
        DB::beginTransaction();
        try {      
            $this->validatePermission($request, $id);
            $this->repository->delete($id);

            DB::commit();
            return [
                'flag' => true
            ];
        } catch(AuthorizationException $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false,
                'code' => 403
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'flag' => false,
                'code' => 400
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

    /**
     * extractManyToManyRelation(array $payload = []) : array
     * extractManyToManyRelation elle permet d'extraire les relations many to many
     * @param array $payload
     * @return array
     */
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

    /**
     * handleManyToManyRelation(Model $model, array $relationsPayload = []) : void
     * handleManyToManyRelation elle permet de gérer les relations many to many
     * @param Model $model
     * @param array $relationsPayload
     */
    private function handleManyToManyRelation(Model $model, array $relationsPayload = []) {
        // Sync the many-to-many relationships
        foreach ($relationsPayload as $relation => $relationData) {
            $model->$relation()->sync($relationData);
        }
    }

    private function validatePermission(Request $request, $id) {
        $action = $request->input('actionScope') === 'all';

        if(!$action) {
            $model = $this->repository->findByld($id);
            
            if(!isset($model->user_id) && $model->user_id != $this->auth->user()->id) {
                throw new AuthorizationException('Permission denied');
            }
        }
    }
}