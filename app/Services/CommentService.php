<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function index($data, $perPage=15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->commentRepository->index($data, $perPage);
    }

    public function update($id, $data)
    {
        return $this->commentRepository->update($id, $data);
    }

    public function changeStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }
}
