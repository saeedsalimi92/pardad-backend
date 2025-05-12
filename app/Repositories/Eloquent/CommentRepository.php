<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepositoryInterface;

class CommentRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function model(): string
    {
        return Comment::class;
    }
}
