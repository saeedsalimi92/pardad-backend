<?php

namespace App\Services;

use App\Repositories\BlogRepositoryInterface;

readonly class BlogService
{
    public function __construct(private readonly BlogRepositoryInterface $blogRepository)
    {
    }
}
