<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\User\UserIndexRequest;
use App\Http\Resources\PaginationResource;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Admin User",
 *     description="Admin User operations"
 * )
 */
class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/admin/v1/users",
     *     summary="Get paginated list of users with their roles",
     *     operationId="adminUserIndex",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated users list with roles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data",
     *                 type="object",
     *                 @OA\Property(),
     *                 @OA\Property(
     *                     property="pagination",
     *                     ref="#/components/schemas/PaginationResource"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(UserIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $users = User::with('roles')
            ->paginate(10);

        return $this->apiResponse(data: $users, paginationData: PaginationResource::make($users));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/users/permissions",
     *     summary="Get user permissions list",
     *     description="Returns all direct permissions and permissions via roles that user has",
     *     operationId="adminUserPermission",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string", example="blog.create"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     )
     * )
     */
    public function permissions(): \Illuminate\Http\JsonResponse
    {
        return $this->apiResponse(data: [
            auth()->user()->getAllPermissions()->pluck('name')
        ]);
    }
}
