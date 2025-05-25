<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Role\RoleAssignPermissionRequest;
use App\Http\Requests\Admin\V1\Role\RoleAssignRequest;
use App\Http\Requests\Admin\V1\Role\RoleStoreRequest;
use App\Http\Resources\Admin\V1\RolePermission\RoleIndexResource;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @OA\Tag(
 *     name="Admin Role Permission",
 *     description="Admin images operations"
 * )
 */
class RoleController extends Controller
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $roles = Role::all();

        $resource = RoleIndexResource::collection($roles);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/roles",
     *     summary="Create a new role",
     *     description="Creates a new role in the system",
     *     operationId="adminRoleStore",
     *     tags={"Role"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="support",
     *                 minLength=3,
     *                 maxLength=255,
     *                 description="Unique name for the role"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"success","message","data","pagination_info"},
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Role created successfully"
     *             ),
     *             @OA\Property(),
     *             @OA\Property(
     *                 property="pagination_info",
     *                 type="array",
     *                 @OA\Items()
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
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(RoleStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $role = Role::query()->firstOrCreate(
            ['name' => $request->name],
            ['name' => $request->name, 'guard_name' => 'web'],
        );

        return $this->apiResponse(data: $role);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/roles/assign/users",
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
    public function assignUser(RoleAssignRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->findOrFail($request->user_id);

        $user->syncRoles([$request->role]);

        return $this->apiResponse(message: "Role '{$request->role}' assigned to user '{$user->id}' successfully.");
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/roles/assign/permissions",
     *     summary="Sync permissions to role",
     *     description="Assign or revoke permissions from a role",
     *     operationId="adminRoleGivePermission",
     *     tags={"Role"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         required=true,
     *         description="Role ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permissions"},
     *             @OA\Property(
     *                 property="permissions",
     *                 type="array",
     *                 @OA\Items(type="string", example="blog.create")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissions synced successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Permissions synced successfully"),
     *             @OA\Property()
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function givePermissionToRole(RoleAssignPermissionRequest $request): \Illuminate\Http\JsonResponse
    {
        $role = Role::query()->where("id", $request->role_id)->first();
        if(is_null($role)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $role->syncPermissions($request->permissions);

        return $this->apiResponse(message: __("response.role.permissions_assigned"));
    }

}
