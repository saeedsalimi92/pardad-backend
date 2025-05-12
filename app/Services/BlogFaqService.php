<?php

namespace App\Services;

use App\Repositories\BlogFaqRepositoryInterface;

readonly class BlogFaqService
{
    public function __construct(private readonly BlogFaqRepositoryInterface $blogFaqRepository)
    {
    }
}
