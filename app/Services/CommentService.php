<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function index()
    {
        return $this->commentRepository->index();
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
