<?php

namespace App\Http\Controllers\Client\V1;


use App\Enums\BlogStatus;
use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\V1\Blog\BlogCommentIndexRequest;
use App\Http\Requests\Client\V1\Blog\BlogCommentStoreRequest;
use App\Http\Requests\Client\V1\Blog\BlogIndexRequest;
use App\Http\Requests\Client\V1\Blog\BlogShowRequest;
use App\Http\Resources\Client\V1\Blog\BlogCommentIndexResource;
use App\Http\Resources\Client\V1\Blog\BlogIndexResource;
use App\Http\Resources\Client\V1\Blog\BlogShowResource;
use App\Http\Resources\PaginationResource;
use App\Services\BlogService;
use App\Services\CommentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Client Blogs",
 *     description="Client blog operations"
 * )
 */
class BlogController extends Controller
{
    public function __construct(private readonly BlogService $blogService,
                                private readonly CommentService $commentService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/blog",
     *     tags={"Blog"},
     *     summary="Get paginated list of published blogs",
     *     description="Returns paginated list of published blogs with filters",
     *     operationId="blogIndex",
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
     *           description="Blog Category",
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
     *                 @OA\Items(ref="#/components/schemas/ApiV1BlogIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     )
     * )
     */
    public function index(BlogIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $blogs = $this->blogService->getPublishedBlogsPaginated($data);
        $resource = BlogIndexResource::collection($blogs);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($blogs));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/blog/{code}/{slug}/",
     *     tags={"Blog"},
     *     summary="Get specific published blog",
     *     description="Returns a single published blog if exists and is publicly available",
     *     operationId="blogShow",
     *     @OA\Parameter(
     *          name="code",
     *          in="path",
     *          description="Unique code of blog to get comments for",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Slug of the blog (optional)",
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
     *                 ref="#/components/schemas/ApiV1BlogShowResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found or not published"
     *     )
     * )
     */
    public function show(BlogShowRequest $request, $code)
    {
        $data = $request->validated();




        $blog = $this->blogService->findByLocale($code, $data);




        if(is_null($blog)) {

            return $this->apiResponse(httpStatusCode: 404);
        }

        if($blog->status !== BlogStatus::Published->value) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        if(Carbon::parse($blog->published_at)->isFuture()) {
            return $this->apiResponse(httpStatusCode: 404);
        }








        $resource = BlogShowResource::make($blog);

        return $this->apiResponse(data: $resource);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/blog/{code}/comments",
     *     tags={"Blog"},
     *     summary="Get comments for a blog",
     *     description="Returns paginated list of comments for a specific blog",
     *     operationId="blogComments",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Unique code of blog to get comments for",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ApiV1BlogCommentIndexResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found"
     *     )
     * )
     */
    public function comments($code, BlogCommentIndexRequest $request): \Illuminate\Http\JsonResponse
    {


        $blog = $this->blogService->findByCode($code);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $data = $request->validated();
        $data["success"] = CommentStatus::Approved->value;

        $comments = $this->commentService->approvedComment($blog);

        $resource = BlogCommentIndexResource::collection($comments);

        return $this->apiResponse(message:__("responses.comment.created"),data: $resource, paginationData: PaginationResource::make($comments));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/blog/{code}/comments",
     *     tags={"Blog"},
     *     summary="Create a new comment for a blog",
     *     description="Stores a new comment for the specified blog",
     *     operationId="blogCommentStore",
     *     @OA\Parameter(
     *          name="code",
     *          in="path",
     *          description="Unique code of blog to get comments for",
     *          required=true,
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Comment data",
     *         @OA\JsonContent(ref="#/components/schemas/ApiV1CommentStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Comment created successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function commentStore($code, BlogCommentStoreRequest $request): \Illuminate\Http\JsonResponse
    {



        $data = $request->validated();
        $blog = $this->blogService->findByCode($code);

        Log::info($blog);
        if(is_null($blog)) {
            return $this->apiResponse(httpStatusCode: 404);
        }

        $this->commentService->create($blog, $data);

        return $this->apiResponse(message: __("responses.comment.created"));
    }

}
