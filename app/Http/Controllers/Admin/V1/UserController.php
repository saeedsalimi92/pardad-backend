<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\User\UserChangeStatusRequest;
use App\Http\Requests\Admin\V1\User\UserDestoryRequest;
use App\Http\Requests\Admin\V1\User\UserIndexRequest;
use App\Http\Requests\Admin\V1\User\UserShowRequest;
use App\Http\Requests\Admin\V1\User\UserStoreRequest;
use App\Http\Requests\Admin\V1\User\UserUpdateRequest;
use App\Http\Requests\Admin\V1\User\UserChangePasswordRequest;
use App\Http\Resources\Admin\V1\User\UserRoleIndexResource;
use App\Http\Resources\Admin\V1\User\UserIndexResource;
use App\Http\Resources\PaginationResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Admin User",
 *     description="Admin User operations"
 * )
 */
class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * @OA\Get(
     *     path="/admin/v1/users",
     *     summary="Get paginated list of users with their roles",
     *     operationId="adminUserIndex",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=25)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of users",
     *         @OA\JsonContent(ref="#/components/schemas/PaginationResource")
     *     )
     * )
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        $perPage = $request->input('per_page', 25);
        $users = User::with('roles')->paginate($perPage);

        return $this->apiResponse(data: $users, paginationData: PaginationResource::make($users));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/users",
     *     summary="Create a new user",
     *     operationId="adminUserStore",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserIndexResource")
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(UserStoreRequest $request)
    {
        $user = $this->userService->create($request);
        if(!$user) {
            return $this->apiResponse(success: false, message: "response.user.error", httpStatusCode: 500);
        }

        return $this->apiResponse(message: __("response.user.created"), data: UserIndexResource::make($user));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/users/{id}",
     *     summary="Get a user by ID",
     *     operationId="adminUserShow",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200, description="User found",
     *         @OA\JsonContent(ref="#/components/schemas/UserIndexResource")
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id, UserShowRequest $request)
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        return $this->apiResponse(data: UserIndexResource::make($user));
    }

    /**
     * @OA\Put(
     *     path="/admin/v1/users/{id}",
     *     summary="Update a user",
     *     operationId="adminUserUpdate",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserIndexResource")
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function update($id, UserUpdateRequest $request)
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->userService->update($user, $request);
        $user->refresh();
        return $this->apiResponse(data: UserIndexResource::make($user));
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/users/{id}",
     *     summary="Delete a user",
     *     operationId="adminUserDestroy",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="User deleted successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id, UserDestoryRequest $request)
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->userService->destroy($user);
        return $this->apiResponse(message: __("response.user.destroyed"));
    }

    /**
     * @OA\Patch(
     *     path="/admin/v1/users/{id}/status",
     *     summary="Change user status",
     *     operationId="adminUserChangeStatus",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserChangeStatusRequest")
     *     ),
     *     @OA\Response(response=200, description="Status updated successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function changeStatus($id, UserChangeStatusRequest $request): JsonResponse
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->userService->changeStatus($user->id, $request->status);

        $user->refresh();
        return $this->apiResponse(message: __("response.user.change_status", ['status' => $user->status_translate]));
    }

    /**
     * @OA\Patch(
     *     path="/admin/v1/users/{id}/password",
     *     summary="Change user password",
     *     operationId="adminUserChangePassword",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="NewPassword123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password changed successfully"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function changePassword($id, UserChangePasswordRequest $request): JsonResponse
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->userService->changePassword($user->id, $request->password);
        $user->refresh();
        return $this->apiResponse(message: __("response.user.change_password"));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/users/permissions",
     *     summary="Get authenticated user's permissions",
     *     operationId="adminUserPermissions",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of permissions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string", example="blog.create")
     *         )
     *     )
     * )
     */
    public function permissions(): JsonResponse
    {
        return $this->apiResponse(data: [
            auth()->user()->getAllPermissions()->pluck('name')
        ]);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/users/{id}/roles",
     *     summary="Get roles of a specific user",
     *     operationId="adminUserRoles",
     *     tags={"Admin User"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function roles($id): JsonResponse
    {
        $user = $this->userService->findById($id);
        if(!$user) {
            return $this->apiResponse(httpStatusCode: 404);
        }


        $resource = UserRoleIndexResource::collection( $user->roles);// assuming 'name' is the role name

        return $this->apiResponse(data: $resource);
    }
}
