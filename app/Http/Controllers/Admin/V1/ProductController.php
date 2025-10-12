<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Product\ProductChangeStatusRequest;
use App\Http\Requests\Admin\V1\Product\ProductDestroyRequest;

use App\Http\Requests\Admin\V1\Product\ProductIndexRequest;
use App\Http\Requests\Admin\V1\Product\ProductListRequest;
use App\Http\Requests\Admin\V1\Product\ProductShowRequest;
use App\Http\Requests\Admin\V1\Product\ProductStoreRequest;
use App\Http\Requests\Admin\V1\Product\ProductUpdateRequest;


use App\Http\Resources\Admin\V1\Product\ProductIndexResource;
use App\Http\Resources\Admin\V1\Product\ProductListResource;
use App\Http\Resources\Admin\V1\Product\ProductShowResource;

use App\Http\Resources\PaginationResource;
use App\Models\Product;

use App\Enums\Status;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Products",
 *     description="Admin product operations"
 * )
 */
class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/product",
     *     summary="Get paginated list of products",
     *     description="Returns a paginated list of products with filtering options",
     *     operationId="adminProductIndex",
     *     tags={"Product"},
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
     *          name="category_id",
     *          in="query",
     *          description="Product Category",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by product status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"draft", "archived", "published"}
     *         )
     *     ),
     *     @OA\Parameter(
     *               name="type",
     *               in="query",
     *               description="Filter by category type",
     *               required=false,
     *               @OA\Schema(
     *                   type="string",
     *                   enum={"normal", "expert"}
     *               )
     *     ),
     *     @OA\Parameter(
     *              name="locale",
     *              in="query",
     *              description="Filter by locale",
     *              required=false,
     *              @OA\Schema(
     *                  type="string",
     *                  enum={"fa", "en", "ar"}
     *              )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AdminV1ProductIndexResource")
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
    public function index(ProductIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = auth()->user();

        if ($user->can('SuperIndex', Product::class)) {
            // هیچ محدودیتی اعمال نمی‌کنیم
        } elseif ($user->can('index', Product::class)) {
            // اگر فقط دسترسی index دارد، محدود به بلاگ‌های خودش شود
            $data['user_id'] = $user->id;
        }
        $perPage = $request->input('per_page', 15);
        $products = $this->productService->index($data , $perPage);

        $resource = ProductIndexResource::collection($products);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($products));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/product/list",
     *     summary="Get list of products",
     *     description="Returns a list of products with pagination",
     *     operationId="adminProductList",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *               name="locale",
     *               in="query",
     *               description="Filter by locale",
     *               required=true,
     *               @OA\Schema(
     *                   type="string",
     *                   enum={"fa", "en", "ar"}
     *               )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AdminV1ProductListResource")
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
    public function list(ProductListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $products = $this->productService->getList($data);

        $resource = ProductListResource::collection($products);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/product",
     *     summary="Create a new product post",
     *     description="Creates a new product post with featured image",
     *     operationId="adminProductStore",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/ProductStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/AdminV1ProductShowResource"
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
    public function store(ProductStoreRequest $request)
    {
        $product = $this->productService->create($request);
        if(!$product) {
            return $this->apiResponse(success: false, message: __("response.product.error"), httpStatusCode: 404);
        }
        $resource = ProductShowResource::make($product);

        return $this->apiResponse(message: __("response.product.created"), data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/product/{id}",
     *     summary="Get single product details",
     *     description="Returns full details of a specific product including multilingual contents",
     *     operationId="adminProductShow",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
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
     *                 ref="#/components/schemas/AdminV1ProductShowResource"
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
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product not found"
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
    public function show(Product $product, ProductShowRequest $request): \Illuminate\Http\JsonResponse
    {
        $product->load(["contents" , "relatedBlogs"]);

        $resource = ProductShowResource::make($product);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/product/{id}/update",
     *     summary="Update a product post",
     *     description="Updates an existing product post with multilingual content, images, and related data",
     *     operationId="adminProductUpdate",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product post to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product update data including multilingual content and optional image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/ProductUpdateRequest")
     *         ),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/ProductUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product updated successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/AdminV1ProductShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid input data"
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
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "contents.fa.title": {"The persian title field is required."}
     *                 }
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
    public function update(Product $product, ProductUpdateRequest $request): \Illuminate\Http\JsonResponse
    {


        $this->productService->update($product, $request);



        $product->refresh();

        $resource = ProductShowResource::make($product);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/product/{id}",
     *     summary="Delete a product post",
     *     description="Permanently deletes a product post and its related data",
     *     operationId="adminProductDestroy",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product post to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product deleted successfully"
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
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product not found"
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
    public function destroy(Product $product, ProductDestroyRequest $request): \Illuminate\Http\JsonResponse
    {
        $product->load("contents");

        $this->productService->destroy($product);

        return $this->apiResponse(message: __("response.product.destroyed"));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/product/{id}/status/change",
     *     summary="Change product status",
     *     description="Updates the status of a product post using form data",
     *     operationId="adminProductChangeStatus",
     *     tags={"Product"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the product post to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Status update form data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"status"},
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     enum={"draft", "published", "archived"},
     *                     example="draft",
     *                     description="Select status from dropdown"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Product status changed to Published"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="published"
     *                 ),
     *                 @OA\Property(
     *                     property="status_translated",
     *                     type="string",
     *                     example="Published"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The selected status is invalid."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function changeStatus($id, ProductChangeStatusRequest $request): \Illuminate\Http\JsonResponse
    {
        $product = $this->productService->findById($id);
        if(is_null($product)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->productService->changeStatus($product->id, $request->status);

        return $this->apiResponse(message: __("response.product.change_status", ['status' => $product->status_translate]));
    }



}
