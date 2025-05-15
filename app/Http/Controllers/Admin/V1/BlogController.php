<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Blog\BlogChangeStatusRequest;
use App\Http\Requests\Admin\V1\Blog\BlogDestroyRequest;
use App\Http\Requests\Admin\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListByParentRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListRequest;
use App\Http\Requests\Admin\V1\Blog\BlogShowRequest;
use App\Http\Requests\Admin\V1\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\V1\Blog\BlogUpdateRequest;
use App\Http\Requests\UpdateLogoRequest;
use App\Http\Resources\Admin\V1\Blog\BlogIndexResource;
use App\Http\Resources\Admin\V1\Blog\BlogListResource;
use App\Http\Resources\Admin\V1\Blog\BlogShowResource;
use App\Models\Blog;
use App\Services\BlogService;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin blog operations"
 * )
 */
class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService)
    {
    }

    /**
     * @OA\Get(
     *     path="/v1/admin/blog",
     *     summary="Get paginated list of blogs",
     *     description="Returns a paginated list of blogs with filtering options",
     *     operationId="adminBlogIndex",
     *     tags={"Admin Blog"},
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
     *         name="title",
     *         in="query",
     *         description="blog title",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by blog status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"active", "inactive"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BlogIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string"),
     *                 @OA\Property(property="next", type="string"),
     *             )
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
     *         )
     *     )
     * )
     */
    public function index(BlogIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->index($data);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: generatePaginateData($blogs));
    }

    public function list(BlogListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->getList($data);

        $resource = BlogListResource::collection($blogs);

        return $this->apiResponse(data: $resource);
    }

    public function listByParent(BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->getList($data);

        $resource = BlogListResource::collection($blogs);

        return $this->apiResponse(data: $resource);
    }

    public function indexByParent(BlogListByParentRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->index($data);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: generatePaginateData($blogs));
    }

    public function store(BlogStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $blog = $this->blogService->create($data);

        $blog->storeFeaturedImage($request->file('image'), 'blogs');

        return $this->apiResponse(message: __("response.blog.created"), data: $blog);
    }

    public function show(BlogShowRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    public function update(BlogUpdateRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $this->blogService->update($blog->id, $data);

        if($request->hasFile('image')) {
            $blog->storeFeaturedImage($request->file('image'), 'blogs');
        }

        return $this->apiResponse(data: $blog);
    }

    public function updateLogo(UpdateLogoRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $logos = $request->logos ?? null;

        $this->blogService->update($blog->id, ['Logos' => $logos]);

        return $this->apiResponse(message: __("response.blog.logo_updated"));
    }

    public function destroy(BlogDestroyRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $this->blogService->destroy($blog);

        return $this->apiResponse(message: __("response.blog.destroyed"));
    }

    public function changeStatus(BlogChangeStatusRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->status = 'active';
        $blog->save();

        return $this->apiResponse(message: __("response.blog.change_status", ['status' => $request->status_translate]));
    }

}
