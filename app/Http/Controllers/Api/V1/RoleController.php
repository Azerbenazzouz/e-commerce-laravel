<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Role\DeleteMultipleRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Requests\Role\DeleteRequest;
use App\Http\Resources\RoleResource;
use App\Service\Interfaces\RoleServiceInterface as RoleService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *    name="Role",
 *    description="Role API"
 * )
 * @OA\Info(
 *   title="Laravel 11 Api: Ecommerce Project Documentation",
 *   version="1.0.0",
 *   description="Role API Documentation"
 * )
 * @OA\Schema(
 *   schema="Role",
 *   @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="Role id"
 *   ),
 *   @OA\Property(
 *      property="name",
 *      type="string",
 *      description="Role name"
 *   ),
 *   @OA\Property(
 *      property="publish",
 *      type="integer",
 *      description="Role Publish"
 *   )
 * )
 * @OA\SecurityScheme(
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 *    securityScheme="bearerAuth"
 * )
 */
class RoleController extends BaseController {

    protected $roleService;
    protected $resource = RoleResource::class;
    public function __construct(
        RoleService $roleService
    ) {
        parent::__construct($roleService);
    }

    protected function getStoreRequest(): string {
        return StoreRequest::class;
    }

    protected function getUpdateRequest(): string {
        return UpdateRequest::class;
    }

    protected function getDeleteRequest(): string {
        return DeleteRequest::class;
    }

    protected function getDeleteMultipleRequest(): string {
        return DeleteMultipleRequest::class;
    }

    // Override all method
    /**
     * @OA\Get(
     *    path="/api/v1/roles/all",
     *    operationId="getAllRoles",
     *    summary="Get All Roles Record(s)",
     *    security={{"bearerAuth":{}}},
     *    tags={"Role"},
     *    @OA\Response(
     *        response=200,
     *        description="List of roles retrieved successfully",
     *        @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Role")
     *        ),
     *    ),
     *    @OA\Response(
     *        response=500,
     *        description="Internal server error",
     *        @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="message", type="string", example="Internal server error")
     *       )
     *   )
     * )
     */
    public function all(Request $request) {
        return parent::all($request);
    }

    /**
     * Créer un nouveau rôle
     *  
     * @OA\Post(
     *     path="/api/v1/roles",
     *     operationId="createRole",
     *     summary="Créer un nouveau rôle",
     *     security={{"bearerAuth":{}}},
     *     tags={"Role"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(
     *                property="name",
     *                type="string",
     *                description="Nom du rôle",
     *                example="Admin"
     *            ),
     *            @OA\Property(
     *               property="publish",
     *               type="integer",
     *               description="Publier le rôle",
     *               example=2
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rôle créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Données non valides"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé"
     *     )
     * )
     */
    public function store(Request $request) {
        return parent::store($request);
    }
}