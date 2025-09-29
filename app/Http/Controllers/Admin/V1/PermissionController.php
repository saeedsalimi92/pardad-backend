<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\V1\RolePermission\PermissionIndexResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
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
    public function index(): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::all();

        $resource = PermissionIndexResource::collection($permissions);

        return $this->apiResponse(data: $resource);
    }
}
