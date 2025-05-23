<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function create($data)
    {
        return $this->commentRepository->create($data);
    }

    public function index($data, $perPage=15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->commentRepository->index($data, $perPage);
    }

    public function findById($id, $with=[])
    {
        return $this->commentRepository->findById($id, $with);
    }

    public function update($id, $data)
    {
        return $this->commentRepository->update($id, $data);
    }

    public function replyByAdmin($comment, $content)
    {
        return $this->create([
            "is_admin" => true,
            "parent_id" => $comment->id,
            "commentable_id" => $comment->commentable_id,
            "commentable_type" => $comment->commentable_type,
            "content" => $content
        ]);
    }

    public function changeStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
