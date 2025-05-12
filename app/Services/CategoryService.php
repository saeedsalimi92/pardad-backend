<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;

readonly class CategoryService
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }
}
