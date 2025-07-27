<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Category\CategoryDestroyRequest;
use App\Http\Requests\Admin\V1\Category\CategoryIndexRequest;
use App\Http\Requests\Admin\V1\Category\CategoryListRequest;
use App\Http\Requests\Admin\V1\Category\CategoryShowRequest;
use App\Http\Requests\Admin\V1\Category\CategoryStoreRequest;
use App\Http\Requests\Admin\V1\Category\CategoryUpdateRequest;

use App\Http\Resources\Admin\V1\Category\CategoryListResource;

use App\Http\Resources\Admin\V1\Category\CategoryShowResource;

use App\Http\Resources\PaginationResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/categories",
     *     summary="Get paginated list of categories",
     *     description="Returns a paginated list of categories with filtering options",
     *     operationId="adminCategoryIndex",
     *     tags={"Category"},
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
     *                 @OA\Items(ref="#/components/schemas/AdminV1CategoryIndexResource")
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
    public function index(CategoryIndexRequest $request)
    {
        $data = $request->validated();
        $categories = $this->categoryService->index($data);

        $resource = CategoryIndexRequest::collection($categories);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($categories));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/categories",
     *     summary="Create a new category post",
     *     description="Creates a new category post with featured image",
     *     operationId="adminCategoryStore",
     *     tags={"Category"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/CategoryStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CategoryShowResource"
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
    public function store(CategoryStoreRequest $request)
    {
        $category = $this->categoryService->create($request);
        if(!$category) {
            return $this->apiResponse(success: false, message: "response.category.error", httpStatusCode: 500);
        }

        $resource = CategoryShowResource::make($category);

        return $this->apiResponse(message: __("response.category.created"), data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/categories/{id}",
     *     summary="Get single category details",
     *     description="Returns full details of a specific category including multilingual contents",
     *     operationId="adminCategoryShow",
     *     tags={"Category"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
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
     *                 ref="#/components/schemas/AdminV1CategoryShowResource"
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
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Category not found"
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
    public function show($id, CategoryShowRequest $request)
    {
        $category = $this->categoryService->findById($id, ["contents"]);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = CategoryShowResource::make($category);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/categories/{id}/update",
     *     summary="Update category",
     *     description="Update category",
     *     operationId="adminCategoryUpdate",
     *     tags={"Category"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Category ID",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/CategoryStoreRequest")
     *         ),
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/CategoryStoreRequest")
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Category updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/CategoryShowResource"
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
    public function update($id, CategoryUpdateRequest $request)
    {
        $category = $this->categoryService->findById($id, ["contents"]);
        if(is_null($category)) {
            return $this->apiResponse(httpStatusCode: 404);
        }
        $this->categoryService->update($category, $request);

        $category->refresh();

        $resource = CategoryShowResource::make($category);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/categories/{id}",
     *     summary="Delete a Category post",
     *     description="Permanently deletes a category post and its related data",
     *     operationId="adminCategoryDestroy",
     *     tags={"Category"},
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
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Category deleted successfully"
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
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Category not found"
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
    public function destroy($id, CategoryDestroyRequest $request)
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
     *     path="/admin/v1/categories/list",
     *     summary="Get list of categories",
     *     description="Returns a list of Categorys with pagination",
     *     operationId="adminCategoryList",
     *     tags={"Category"},
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
     *                 @OA\Items(ref="#/components/schemas/AdminV1CategoryListResource")
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
    public function getList(CategoryListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $categories = $this->categoryService->getList($data);

        $resource = CategoryListResource::collection($categories);

        return $this->apiResponse(data: $resource);
    }
}
