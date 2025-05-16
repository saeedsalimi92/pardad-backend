<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Blog\BlogChangeStatusRequest;
use App\Http\Requests\Admin\V1\Blog\BlogDestroyRequest;
use App\Http\Requests\Admin\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListRequest;
use App\Http\Requests\Admin\V1\Blog\BlogShowRequest;
use App\Http\Requests\Admin\V1\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\V1\Blog\BlogUpdateRequest;
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
     *     tags={"Blog"},
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
    public function index(BlogIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->index($data);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: generatePaginateData($blogs));
    }

    /**
     * @OA\Get(
     *     path="/v1/admin/blog/list",
     *     summary="Get list of blogs",
     *     description="Returns a list of blogs with pagination",
     *     operationId="adminBlogList",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BlogListResource")
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
    public function list(BlogListRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $blogs = $this->blogService->getList($data);

        $resource = BlogListResource::collection($blogs);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/v1/admin/blogs",
     *     summary="Create a new blog post",
     *     description="Creates a new blog post with featured image",
     *     operationId="adminBlogStore",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Blog data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BlogStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Blog created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BlogShowResource"
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
    public function store(BlogStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $blog = $this->blogService->create($request);
        if(!$blog) {
            return $this->apiResponse(success: false, message: "response.blog.error", httpStatusCode: 500);
        }

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(message: __("response.blog.created"), data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/v1/admin/blog/{id}",
     *     summary="Get single blog details",
     *     description="Returns full details of a specific blog including multilingual contents",
     *     operationId="adminBlogShow",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Blog ID",
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
     *                 ref="#/components/schemas/BlogShowResource"
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
     *         description="Blog not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Blog not found"
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
    public function show(BlogShowRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $blog->load(['contents', 'relationBlogs']);

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Put(
     *     path="/v1/admin/blogs/{id}",
     *     summary="Update a blog post",
     *     description="Updates an existing blog post with multilingual content, images, and related data",
     *     operationId="adminBlogUpdate",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the blog post to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Blog update data including multilingual content and optional image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BlogUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Blog updated successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/BlogShowResource"
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
     *         description="Blog not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Blog not found"
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
    public function update(BlogUpdateRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $this->blogService->update($blog->id, $data);

        if($request->hasFile('image')) {
            $blog->storeFeaturedImage($request->file('image'), 'blogs');
        }

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/v1/admin/blogs/{id}",
     *     summary="Delete a blog post",
     *     description="Permanently deletes a blog post and its related data",
     *     operationId="adminBlogDestroy",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the blog post to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Blog deleted successfully"
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
     *         description="Blog not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Blog not found"
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
    public function destroy(BlogDestroyRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $this->blogService->destroy($blog);

        return $this->apiResponse(message: __("response.blog.destroyed"));
    }

    /**
     * @OA\Patch(
     *     path="/v1/admin/blogs/{id}/change-status",
     *     summary="Change blog status",
     *     description="Updates the status of a blog post using form data",
     *     operationId="adminBlogChangeStatus",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the blog post to update",
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
     *                     enum={"DRAFT", "PUBLISH", "ARCHIVE"},
     *                     example="DRAFT",
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
     *                 example="Blog status changed to Published"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="PUBLISH"
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
    public function changeStatus(BlogChangeStatusRequest $request, Blog $blog): \Illuminate\Http\JsonResponse
    {
        $this->blogService->update($blog->id, ['status' => $request->status]);

        return $this->apiResponse(message: __("response.blog.change_status", ['status' => $blog->status_translate]));
    }

}
