<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\ActivityLog\ActivityLogIndexRequest;
use App\Http\Resources\Admin\V1\ActivityLog\ActivityLogIndexResource;
use App\Http\Resources\PaginationResource;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLogService) {}

     /**
     * @OA\Get(
     *     path="/admin/v1/activity-logs",
     *     summary="Get paginated list of activity logs",
     *     description="Returns a paginated list of activity logs with filtering options",
     *     operationId="adminActvityLogIndex",
     *     tags={"ActvityLog"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=15
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AdminV1ActvityLogIndexResource")
     *             ),
     *         @OA\Property(
     *                  property="pagination_info",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/PaginationResource")
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
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
     *                     property="field_name",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         ),
     *         @OA\Response(
     *          response=500,
     *          description="Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *      )
     *
     *     )
     * )
     */

    public function index(ActivityLogIndexRequest $request)
    {
        $data = $request->validated();
        $categories = $this->activityLogService->index($data);

        $resource = ActivityLogIndexResource::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($categories));
    }
}
