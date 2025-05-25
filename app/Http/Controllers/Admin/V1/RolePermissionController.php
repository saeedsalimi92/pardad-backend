<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\User\UserAssignRoleRequest;
use App\Http\Resources\Admin\V1\RolePermission\PermissionIndexResource;
use App\Http\Resources\Admin\V1\RolePermission\RoleIndexResource;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @OA\Tag(
 *     name="Admin Role Permission",
 *     description="Admin images operations"
 * )
 */
class RolePermissionController extends Controller
{

    /**
     * @OA\Get(
     *     path="/admin/v1/roles",
     *     summary="Get all roles",
     *     operationId="adminRoleIndex",
     *     tags={"Role"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of roles with standard response format",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"success","message","data","pagination_info"},
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RoleIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 type="array",
     *                 description="Empty array if no pagination",
     *                 @OA\Items()
     *             )
     *         )
     *     )
     * )
     */
    public function roles(): \Illuminate\Http\JsonResponse
    {
        $roles = Role::all();

        $resource = RoleIndexResource::collection($roles);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/roles/assign",
     *     summary="Assign a role to a user",
     *     operationId="adminRoleAssignToUser",
     *     tags={"Role"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","role"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="role", type="string", example="seo manager")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role assigned successfully with standard response format",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"success","message","data","pagination_info"},
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role 'seo manager' assigned to user '1' successfully."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Empty array in this response",
     *                 @OA\Items()
     *             ),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 type="array",
     *                 description="Empty array in this response",
     *                 @OA\Items()
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function assignRole(UserAssignRoleRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->findOrFail($request->user_id);

        $user->syncRoles([$request->role]);

        return $this->apiResponse(message: "Role '{$request->role}' assigned to user '{$user->id}' successfully.");
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/permissions",
     *     summary="Get all permissions",
     *     operationId="adminPermissionIndex",
     *     tags={"Permission"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of roles with standard response format",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"success","message","data","pagination_info"},
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example=""),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PermissionIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 type="array",
     *                 description="Empty array if no pagination",
     *                 @OA\Items()
     *             )
     *         )
     *     )
     * )
     */
    public function permissions(): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::all();

        $resource = PermissionIndexResource::collection($permissions);

        return $this->apiResponse(data: $resource);
    }


}
