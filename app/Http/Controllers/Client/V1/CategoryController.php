<?php

namespace App\Http\Controllers\Client\V1;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\V1\Category\CategoryIndexRequest;
use App\Http\Requests\Client\V1\Category\CategoryShowRequest;
use App\Http\Resources\Client\V1\Category\CategoryIndexResource;
use App\Http\Resources\Client\V1\Category\CategoryShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\CategoryService;

/**
 * @OA\Tag(
 *     name="Client Categories",
 *     description="Client category operations"
 * )
 */
class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Get paginated list of categories",
     *     description="Returns a paginated list of categories with filtering options",
     *     operationId="adminCategoryIndex",
     *     tags={"Category"},
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
     *          description="Filter by category type",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"normal", "expert"}
     *          )
     *      ),
     *     @OA\Parameter(
     *           name="scope",
     *           in="query",
     *           description="Filter by category scope",
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
     *                 @OA\Items(ref="#/components/schemas/CategoryIndexResource")
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
    public function index(CategoryIndexRequest $request)
    {
        $data = $request->validated();
        $data["status"] = Status::Active->value;

        $categories = $this->categoryService->index($data);

        $resource = CategoryIndexResource::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($resource));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}/{slug}",
     *     tags={"Category"},
     *     summary="Get detailed information about a specific category",
     *     description="Returns full details of a category including its parent, children, and contents",
     *     operationId="getCategoryById",
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
     *           description="Slug of the category (optional)",
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
     *                 ref="#/components/schemas/CategoryShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Category not found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(CategoryShowRequest $request, $code, $slug=null): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->findByLocale($code, $data);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = CategoryShowResource::make($category);

        return $this->apiResponse(data: $resource);
    }
}
