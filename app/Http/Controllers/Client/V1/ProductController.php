<?php

namespace App\Http\Controllers\Client\V1;


use App\Enums\Status;
use App\Http\Controllers\Controller;

use App\Http\Requests\Client\V1\Product\ProductIndexRequest;
use App\Http\Requests\Client\V1\Product\ProductBlogsIndexRequest;
use App\Http\Requests\Client\V1\Product\ProductShowRequest;
use App\Http\Resources\Client\V1\Product\ProductIndexResource;
use App\Http\Resources\Client\V1\Blog\BlogIndexResource;
use App\Http\Resources\Client\V1\Product\ProductShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


/**
 * @OA\Tag(
 *     name="Client Product",
 *     description="Client product operations"
 * )
 */
class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product",
     *     tags={"Product"},
     *     summary="Get paginated list of published products",
     *     description="Returns paginated list of published products with filters",
     *     operationId="productIndex",
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=1
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Items per page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=15
     *          )
     *      ),
     *      @OA\Parameter(
     *           name="category_id",
     *           in="query",
     *           description="Product Category",
     *           required=false,
     *           @OA\Schema(
     *               type="integer",
     *           )
     *       ),
     *      @OA\Parameter(
     *               name="locale",
     *               in="query",
     *               description="Filter by locale",
     *               required=false,
     *               @OA\Schema(
     *                   type="string",
     *                   enum={"fa", "en", "ar"}
     *               )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ApiV1ProductIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     )
     * )
     */
    public function index(ProductIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $products = $this->productService->getProductsPaginated($data);
        $resource = ProductIndexResource::collection($products);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($products));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/{code}/{slug}/",
     *     tags={"Product"},
     *     summary="Get specific published product",
     *     description="Returns a single published product if exists and is publicly available",
     *     operationId="productShow",
     *     @OA\Parameter(
     *          name="code",
     *          in="path",
     *          description="Unique code of product to get comments for",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Slug of the product (optional)",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *                name="locale",
     *                in="query",
     *                description="Filter by locale",
     *                required=false,
     *                @OA\Schema(
     *                    type="string",
     *                    enum={"fa", "en", "ar"}
     *                )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/ApiV1ProductShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found or not published"
     *     )
     * )
     */
    public function show(ProductShowRequest $request, $code)
    {
        $data = $request->validated();




        $product = $this->productService->findByLocale($code, $data);




        if(is_null($product)) {

            return $this->apiResponse(httpStatusCode: 404);
        }

        if($product->status !== Status::Active->value) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        if(Carbon::parse($product->published_at)->isFuture()) {
            return $this->apiResponse(httpStatusCode: 404);
        }








        $resource = ProductShowResource::make($product);

        return $this->apiResponse(data: $resource);
    }


    public function blogs(ProductBlogsIndexRequest $request , $code): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $product = $this->productService->findByLocale($code, $data);




        if(is_null($product)) {

            return $this->apiResponse(httpStatusCode: 404);
        }

        if($product->status !== Status::Active->value) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        if(Carbon::parse($product->published_at)->isFuture()) {
            return $this->apiResponse(httpStatusCode: 404);
        }


        $products = $this->productService->getBlogs($code,$data);
        $resource = BlogIndexResource::collection($products);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($products));
    }



}
