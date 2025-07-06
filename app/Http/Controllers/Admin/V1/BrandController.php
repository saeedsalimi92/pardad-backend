<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Brand\BrandDestroyRequest;
use App\Http\Requests\Admin\V1\Brand\BrandIndexRequest;
use App\Http\Requests\Admin\V1\Brand\BrandListRequest;
use App\Http\Requests\Admin\V1\Brand\BrandShowRequest;
use App\Http\Requests\Admin\V1\Brand\BrandStoreRequest;
use App\Http\Requests\Admin\V1\Brand\BrandUpdateRequest;
use App\Http\Resources\Admin\V1\Brand\BrandIndexResource;
use App\Http\Resources\Admin\V1\Brand\BrandListResource;
use App\Http\Resources\Admin\V1\Brand\BrandShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\BrandService;

class BrandController extends Controller
{

    public function __construct(private readonly BrandService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/brands",
     *     summary="Get paginated list of brands",
     *     description="Returns a paginated list of brands with filtering options",
     *     operationId="adminBrandIndex",
     *     tags={"Brand"},
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
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by category status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"active", "inactive"}
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
     *                 @OA\Items(ref="#/components/schemas/AdminV1BrandIndexResource")
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
    public function index(BrandIndexRequest $request)
    {
        $data = $request->validated();
        $brands = $this->categoryService->index($data);

        $resource = BrandIndexResource::collection($brands);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($brands));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/brands",
     *     summary="Create a new category post",
     *     description="Creates a new category post with featured image",
     *     operationId="adminBrandStore",
     *     tags={"Brand"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Brand data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BrandStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BrandShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Forbidden")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *      ),
     * )
     */
    public function store(BrandStoreRequest $request)
    {
        $category = $this->categoryService->create($request);
        if(!$category) {
            return $this->apiResponse(success: false, message: "response.category.error", httpStatusCode: 500);
        }

        $resource = BrandShowResource::make($category);

        return $this->apiResponse(message: __("response.category.created"), data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/brands/{id}",
     *     summary="Get single category details",
     *     description="Returns full details of a specific category including multilingual contents",
     *     operationId="adminBrandShow",
     *     tags={"Brand"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Brand ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/AdminV1BrandShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
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
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Internal Server Error"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "error": "Error details"
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function show($id, BrandShowRequest $request)
    {
        $category = $this->categoryService->findById($id, ["contents"]);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = BrandShowResource::make($category);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/brands/{id}/update",
     *     summary="Update category",
     *     description="Update category",
     *     operationId="adminBrandUpdate",
     *     tags={"Brand"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Brand ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Brand data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BrandStoreRequest")
     *         ),
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/BrandStoreRequest")
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Brand updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Brand updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BrandShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Forbidden")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *      ),
     * )
     */
    public function update($id, BrandUpdateRequest $request)
    {
        $category = $this->categoryService->findById($id, ["contents"]);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->categoryService->update($category, $request);

        $category->refresh();

        $resource = BrandShowResource::make($category);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/brands/{id}",
     *     summary="Delete a Brand post",
     *     description="Permanently deletes a category post and its related data",
     *     operationId="adminBrandDestroy",
     *     tags={"Brand"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Brand deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Brand deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Forbidden"
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
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Internal Server Error"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Error details"
     *             )
     *         )
     *     )
     * )
     */
    public function destroy($id, BrandDestroyRequest $request)
    {
        $category = $this->categoryService->findById($id, ["contents"]);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->categoryService->destroy($category);

        return $this->apiResponse(message: __("response.blog.destroyed"));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/brands/list",
     *     summary="Get list of brands",
     *     description="Returns a list of Brands with pagination",
     *     operationId="adminBrandList",
     *     tags={"Brand"},
     *     security={{"sanctum": {}}},
     *          @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filter by category status",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              enum={"active", "inactive"}
     *          )
     *      ),
     *      @OA\Parameter(
     *           name="type",
     *           in="query",
     *           description="Filter by category type",
     *           required=false,
     *           @OA\Schema(
     *               type="string",
     *               enum={"normal", "expert"}
     *           )
     *       ),
     *      @OA\Parameter(
     *            name="scope",
     *            in="query",
     *            description="Filter by category scope",
     *            required=false,
     *            @OA\Schema(
     *                type="string",
     *                enum={"all", "main", "children"}
     *            )
     *        ),
     *        @OA\Parameter(
     *             name="locale",
     *             in="query",
     *             description="Filter by locale",
     *             required=false,
     *             @OA\Schema(
     *                 type="string",
     *                 enum={"fa", "en", "ar"}
     *             )
     *         ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AdminV1BrandListResource")
     *             ),
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
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getList(BrandListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $brands = $this->categoryService->getList($data);

        $resource = BrandListResource::collection($brands);

        return $this->apiResponse(data: $resource);
    }
}
