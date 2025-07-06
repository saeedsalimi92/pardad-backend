<?php

namespace App\Http\Controllers\Client\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\V1\Brand\BrandIndexRequest;
use App\Http\Requests\Client\V1\Brand\BrandShowRequest;
use App\Http\Resources\Client\V1\Brand\BrandIndexResource;
use App\Http\Resources\Client\V1\Brand\BrandShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\BrandService;

/**
 * @OA\Tag(
 *     name="Client Brands",
 *     description="Client brand operations"
 * )
 */
class BrandController extends Controller
{
    public function __construct(private readonly BrandService $brandService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Get paginated list of categories",
     *     description="Returns a paginated list of categories with filtering options",
     *     operationId="adminBrandIndex",
     *     tags={"Brand"},
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
     *     @OA\Parameter(
     *          name="parent_id",
     *          in="query",
     *          description="Filter by parent id",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="type",
     *          in="query",
     *          description="Filter by brand type",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"normal", "expert"}
     *          )
     *      ),
     *     @OA\Parameter(
     *           name="scope",
     *           in="query",
     *           description="Filter by brand scope",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               enum={"all", "main", "children"}
     *           )
     *       ),
     *       @OA\Parameter(
     *            name="locale",
     *            in="query",
     *            description="Filter by locale",
     *            required=false,
     *            @OA\Schema(
     *                type="string",
     *                enum={"fa", "en", "ar"}
     *            )
     *        ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BrandIndexResource")
     *             ),
     *         @OA\Property(
     *                  property="pagination_info",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/PaginationResource")
     *              ),
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
    public function index(BrandIndexRequest $request)
    {
        $data = $request->validated();
        $data["status"] = Status::Active->value;

        $categories = $this->brandService->index($data);

        $resource = BrandIndexResource::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($resource));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}/{slug}",
     *     tags={"Brand"},
     *     summary="Get detailed information about a specific brand",
     *     description="Returns full details of a brand including its parent, children, and contents",
     *     operationId="getBrandById",
     *      @OA\Parameter(
     *           name="code",
     *           in="path",
     *           description="Unique code of blog to get comments for",
     *           required=true,
     *           @OA\Schema(type="string")
     *       ),
     *     @OA\Parameter(
     *           name="slug",
     *           in="path",
     *           description="Slug of the brand (optional)",
     *           required=false,
     *           @OA\Schema(type="string")
     *       ),
     *     @OA\Parameter(
     *            name="locale",
     *            in="query",
     *            description="Filter by locale",
     *            required=false,
     *             @OA\Schema(
     *                type="string",
     *                enum={"fa", "en", "ar"}
     *             )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BrandShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Brand not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Brand not found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(BrandShowRequest $request, $code, $slug=null): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $brand = $this->brandService->findByLocale($code, $data);
        if(is_null($brand)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = BrandShowResource::make($brand);

        return $this->apiResponse(data: $resource);
    }
}
