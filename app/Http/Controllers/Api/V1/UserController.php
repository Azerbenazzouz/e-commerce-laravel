<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\User\DeleteMultipleRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Service\Interfaces\UserServiceInterface as UserService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *    name="User",
 *    description="User API"
 * )
 * @OA\Info(
 *   title="Laravel 11 Api: Ecommerce Project Documentation",
 *   version="1.0.0",
 *   description="User API Documentation"
 * )
 * @OA\Schema(
 *   schema="User",
 *   @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="User id"
 *   ),
 *   @OA\Property(
 *      property="name",
 *      type="string",
 *      description="User name"
 *   ),
 *   @OA\Property(
 *      property="publish",
 *      type="integer",
 *      description="User Publish"
 *   )
 * )
 * @OA\SecurityScheme(
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 *    securityScheme="bearerAuth"
 * )
 */
class UserController extends BaseController {

    protected $userService;
    protected $resource = UserResource::class;
    public function __construct(
        UserService $userService
    ) {
        parent::__construct($userService);
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
     *    path="/api/v1/users/all",
     *    operationId="getAllUsers",
     *    summary="Get All User Record(s)",
     *    security={{"bearerAuth":{}}},
     *    tags={"User"},
     *    @OA\Response(
     *        response=200,
     *        description="List of User retrieved successfully",
     *        @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/User")
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
     *     path="/api/v1/users",
     *     operationId="createRole",
     *     summary="Créer un nouveau User",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(
     *                property="name",
     *                type="string",
     *                description="Nom du User",
     *                example="Admin"
     *            ),
     *            @OA\Property(
     *               property="publish",
     *               type="integer",
     *               description="Publier le User",
     *               example=2
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User créé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rôle créé avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="code", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        return parent::store($request);
    }


    /**
     * Afficher les détails d'un rôle spécifique
     *
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     operationId="showRole",
     *     summary="Afficher un User spécifique",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du rôle",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User récupéré avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rôle récupéré avec succès"),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="code", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Rôle non trouvé")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function show($id) {
        return parent::show($id);
    }
}
