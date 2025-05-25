<?php

namespace App\Services;

use App\Enums\CommentStatus;
use App\Repositories\CommentRepositoryInterface;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function create($model, $data)
    {
        return $model->comments()->create($data);
    }

    public function index($model, $data, $perPage=15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $model->comments()
            ->when(isset($data['status']), function ($query) use ($data) {
                $query->where('status', $data["status"]);
            })
            ->when(isset($data['mobile']), function ($query) use ($data) {
                $query->where('title', "LIKE", "%".$data["mobile"]."%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function approvedComment($model, $perPage=15)
    {
        return $model->comments()
            ->where("status", CommentStatus::Approved->value)
            ->where("parent_id", null)
            ->paginate($perPage);
    }

    public function findById($model, $id, $with=[])
    {
        return $model->comments()->with($with)->where("id", $id)->first();
    }

    public function update($id, $data)
    {
        return $this->commentRepository->update($id, $data);
    }

    public function replyByAdmin($model, $comment, $content)
    {
        return $model->comments()->create([
            "is_admin" => true,
            "parent_id" => $comment->id,
            "content" => $content
        ]);
    }

    public function changeStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
