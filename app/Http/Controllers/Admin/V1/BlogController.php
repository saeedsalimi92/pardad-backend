<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Blog\BlogChangeStatusRequest;
use App\Http\Requests\Admin\V1\Blog\BlogDestroyRequest;
use App\Http\Requests\Admin\V1\Blog\BlogFaqDestroyRequest;
use App\Http\Requests\Admin\V1\Blog\BlogFaqIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogFaqStoreRequest;
use App\Http\Requests\Admin\V1\Blog\BlogFaqUpdateRequest;
use App\Http\Requests\Admin\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Admin\V1\Blog\BlogListRequest;
use App\Http\Requests\Admin\V1\Blog\BlogShowRequest;
use App\Http\Requests\Admin\V1\Blog\BlogStoreRequest;
use App\Http\Requests\Admin\V1\Blog\BlogUpdateRequest;
use App\Http\Requests\Admin\V1\Blog\BlogStatusesRequest;
use App\Http\Requests\BlogFaqShowRequest;
use App\Http\Resources\Admin\V1\Blog\BlogIndexResource;
use App\Http\Resources\Admin\V1\Blog\BlogListResource;
use App\Http\Resources\Admin\V1\Blog\BlogShowResource;
use App\Http\Resources\Admin\V1\Faq\FaqIndexResource;
use App\Http\Resources\Admin\V1\Faq\FaqShowResource;
use App\Http\Resources\PaginationResource;
use App\Models\Blog;
use App\Services\BlogService;
use App\Services\FaqService;
use App\Enums\BlogStatus;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin blog operations"
 * )
 */
class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService,
                                private readonly FaqService $faqService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/blog",
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
     *          name="category_id",
     *          in="query",
     *          description="Blog Category",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by blog status",
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
     *                 @OA\Items(ref="#/components/schemas/AdminV1BlogIndexResource")
     *             ),
     *         @OA\Property(
     *                  property="pagination_info",
     *                  ref="#/components/schemas/PaginationResource"
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
        $user = auth()->user();
        /*if (!$user->can('super_index', Blog::class) && $user->can('index', Blog::class)) {
            $data['user_id'] = $user->id;
        }*/

        if ($user->can('SuperIndex', Blog::class)) {
            // هیچ محدودیتی اعمال نمی‌کنیم
        } elseif ($user->can('index', Blog::class)) {
            // اگر فقط دسترسی index دارد، محدود به بلاگ‌های خودش شود
            $data['user_id'] = $user->id;
        }
        Log::info($request->input('per_page'));
        $perPage = $request->input('per_page', 15);
        $blogs = $this->blogService->index($data , $perPage);

        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($blogs));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/blog/list",
     *     summary="Get list of blogs",
     *     description="Returns a list of blogs with pagination",
     *     operationId="adminBlogList",
     *     tags={"Blog"},
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
     *                 @OA\Items(ref="#/components/schemas/AdminV1BlogListResource")
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
     *     path="/admin/v1/blog",
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
     *                 ref="#/components/schemas/AdminV1BlogShowResource"
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
    public function store(BlogStoreRequest $request)
    {
        $blog = $this->blogService->create($request);
        if(!$blog) {
            return $this->apiResponse(success: false, message: __("response.blog.error"), httpStatusCode: 404);
        }
        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(message: __("response.blog.created"), data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/blog/{id}",
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
     *                 ref="#/components/schemas/AdminV1BlogShowResource"
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
    public function show(Blog $blog, BlogShowRequest $request): \Illuminate\Http\JsonResponse
    {
        $blog->load(["contents", "relatedBlogs" , "relatedProducts"]);

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/blog/{id}/update",
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
     *         ),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/BlogUpdateRequest")
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
     *                 ref="#/components/schemas/AdminV1BlogShowResource"
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
    public function update(Blog $blog, BlogUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->blogService->update($blog, $request);

        $blog->refresh();

        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/blog/{id}",
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
    public function destroy(Blog $blog, BlogDestroyRequest $request): \Illuminate\Http\JsonResponse
    {
        $blog->load("contents");

        $this->blogService->destroy($blog);

        return $this->apiResponse(message: __("response.blog.destroyed"));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/blog/{id}/status/change",
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
     *                 example="Blog status changed to Published"
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
    public function changeStatus($id, BlogChangeStatusRequest $request): \Illuminate\Http\JsonResponse
    {
        $blog = $this->blogService->findById($id);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->blogService->changeStatus($blog->id, $request->status);

        return $this->apiResponse(message: __("response.blog.change_status", ['status' => $blog->status_translate]));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/blog/statuses",
     *     summary="Get available blog statuses based on user permissions",
     *     description="Returns list of available statuses that user can access",
     *     operationId="adminBlogStatuses",
     *     tags={"Blog"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="value", type="string", example="draft"),
     *                     @OA\Property(property="label", type="string", example="Draft")
     *                 )
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
     *     )
     * )
     */
    public function statuses(BlogStatusesRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $statuses = [];

        if ($user->can('admin.blog.super_statuses')) {
            // ادمین اصلی همه وضعیت‌ها رو می‌بینه
            $statuses = [
                ['value' => BlogStatus::Draft->value, 'label' => __('attributes.blog.status.draft')],
                ['value' => BlogStatus::Published->value, 'label' => __('attributes.blog.status.published')],
                ['value' => BlogStatus::Archived->value, 'label' => __('attributes.blog.status.archived')],
            ];
        } elseif ($user->can('admin.blog.statuses')) {
            // محتوا نویس فقط draft می‌بینه
            $statuses = [
                ['value' => BlogStatus::Draft->value, 'label' => __('attributes.blog.status.draft')],
            ];
        }

        return $this->apiResponse(data: $statuses);
    }


    /**
     * @OA\Get(
     *     path="/admin/v1/blog/{blog_id}/faq/",
     *     summary="Get paginated list of blog faqs",
     *     description="Returns a paginated list of blog faqs",
     *     operationId="adminBlogFaqIndex",
     *     tags={"Blog Faq"},
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
     *          name="blog_id",
     *          in="path",
     *          description="Blog Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              default=1
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
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
     *                 @OA\Items(ref="#/components/schemas/BlogFaqIndexRequest")
     *             ),
     *         @OA\Property(
     *                  property="pagination_info",
     *                  ref="#/components/schemas/PaginationResource"
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
    public function faqIndex($id, BlogFaqIndexRequest $request)
    {
        $data = $request->validated();

        $blog = $this->blogService->findById($id);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $items = $this->faqService->index($blog, $data);

        $resource = FaqIndexResource::collection($items);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($items));
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/blog/{blog_id}/faq/",
     *     summary="Create a new blog post",
     *     description="Creates a new blog post with featured image",
     *     operationId="adminBlogFaqStore",
     *     tags={"Blog Faq"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *           name="blog_id",
     *           in="path",
     *           description="Blog Id",
     *           required=true,
     *           @OA\Schema(
     *               type="integer",
     *               default=1
     *           )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Blog data and featured image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BlogFaqStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Blog created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/AdminV1BlogShowResource"
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
    public function faqStore($id, BlogFaqStoreRequest $request)
    {
        $data = $request->validated();
        $blog = $this->blogService->findById($id);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $faq = $this->faqService->store($blog, $data);

        $resource = FaqShowResource::make($faq);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/blog/{blogId}/faq/{faqId}",
     *     tags={"Blog Faq"},
     *     summary="Display specific FAQ of a blog",
     *     description="Returns a single FAQ item from specified blog",
     *     operationId="adminBlogFaqShow",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="blogId",
     *         in="path",
     *         description="ID of the blog",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="faqId",
     *         in="path",
     *         description="ID of the FAQ item",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/FaqShowResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Resource not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"field_name": {"Error message"}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function faqShow($blogId, $faqId, BlogFaqShowRequest $request)
    {
        $blog = $this->blogService->findById($blogId);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $faq = $this->faqService->findById($blog, $faqId);
        if(is_null($faq)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $resource = FaqShowResource::make($faq);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Post(
     *     path="/admin/v1/blog/{blog_id}/faq/{faq_id}/update",
     *     summary="Update a blog post",
     *     description="Updates an existing faq with multilingual content, images, and related data",
     *     operationId="adminBlogFaqUpdate",
     *     tags={"Blog Faq"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="blog_id",
     *         in="path",
     *         description="ID of the blog",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="faq_id",
     *          in="path",
     *          description="ID of the faq",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=1
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Blog update data including multilingual content and optional image",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/BlogFaqUpdateRequest")
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
     *                 ref="#/components/schemas/FaqShowResource"
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
    public function faqUpdate($blogId, $faqId, BlogFaqUpdateRequest $request)
    {
        $data = $request->validated();

        $blog = $this->blogService->findById($blogId);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->faqService->update($blog, $faqId, $data);

        $faq = $this->faqService->findById($blog, $faqId);

        $resource = FaqShowResource::make($faq);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Delete(
     *     path="/admin/v1/blog/{blog_id}/faq/{faq_id}",
     *     summary="Delete a blog post",
     *     description="Permanently deletes a blog faq",
     *     operationId="adminBlogFaqDestroy",
     *     tags={"Blog Faq"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="blog_id",
     *         in="path",
     *         description="ID of the blog",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *    @OA\Parameter(
     *          name="faq_id",
     *          in="path",
     *          description="ID of the faq to delete",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=1
     *          )
     *      ),
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
    public function faqDestroy($blogId, $faqId, BlogFaqDestroyRequest $request)
    {
        $blog = $this->blogService->findById($blogId);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->faqService->destroy($blog, $faqId);

        return $this->apiResponse();
    }

}
