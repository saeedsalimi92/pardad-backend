<?php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\BaseRepository;
use App\Repositories\BlogRepositoryInterface;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{
    public function model(): string
    {
        return Blog::class;
    }
}
