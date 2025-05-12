<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;

readonly class CommentService
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }
}
