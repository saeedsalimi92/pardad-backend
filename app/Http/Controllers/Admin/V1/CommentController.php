<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Comment\CommentChangeStatusRequest;
use App\Http\Requests\Admin\V1\Comment\CommentIndexRequest;
use App\Http\Requests\Admin\V1\Comment\CommentReplyRequest;
use App\Http\Requests\Admin\V1\Comment\CommentShowRequest;
use App\Http\Resources\Admin\V1\Comment\CommentIndexResource;
use App\Http\Resources\Admin\V1\Comment\CommentShowResource;
use App\Http\Resources\PaginationResource;
use App\Models\Comment;
use App\Services\CommentService;

/**
 * @OA\Tag(
 *     name="Admin Blogs",
 *     description="Admin comment operations"
 * )
 */
class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService)
    {
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/comments",
     *     summary="Get paginated list of comments",
     *     description="Returns a paginated list of comments with filtering options",
     *     operationId="adminCommentIndex",
     *     tags={"Comment"},
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
     *          name="mobile",
     *          in="query",
     *          description="Filter by User mobile",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              default=15
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"pending", "approved", "rejected"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentIndexResource")
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
    public function index(CommentIndexRequest $request)
    {
        $data = $request->validated();
        $comments = $this->commentService->index($data);

        $resource = CommentIndexResource::collection($comments);

        return $this->apiResponse(data: $resource, paginationData: PaginationResource::make($resource));
    }

    /**
     * @OA\Get(
     *     path="/admin/v1/comments/{id}",
     *     summary="Get single comment details",
     *     description="Returns full details of a specific comment",
     *     operationId="adminCommentShow",
     *     tags={"Comment"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Comment Id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CommentShowResource")
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
    public function show(CommentShowRequest $request, Comment $comment)
    {
        $resource = CommentShowResource::make($comment);

        return $this->apiResponse(data: $resource);
    }

    public function reply(CommentReplyRequest $request, Comment $comment)
    {

    }

    public function changeStatus(CommentChangeStatusRequest $request, Comment $comment)
    {
        $this->commentService->changeStatus($comment->id, $request->status);

        return $this->apiResponse(message: __("response.comment.change_status", ['status' => $comment->status_translate]));
    }
}


