<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\V1\Comment\CommentChangeStatusRequest;
use App\Http\Requests\Admin\V1\Comment\CommentIndexRequest;
use App\Http\Requests\Admin\V1\Comment\CommentShowRequest;
use App\Http\Resources\Admin\V1\Comment\CommentShowResource;
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

    public function index(CommentIndexRequest $request)
    {
    }

    public function show(CommentShowRequest $request, Comment $comment)
    {
        $resource = CommentShowResource::make($comment);

        return $this->apiResponse(data: $resource);
    }

    public function changeStatus(CommentChangeStatusRequest $request, Comment $comment)
    {
        $this->commentService->changeStatus($comment->id, $request->status);

        return $this->apiResponse(message: __("response.comment.change_status", ['status' => $comment->status_translate]));
    }
}


