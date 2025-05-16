<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;

readonly class CategoryService
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function index($data, $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->categoryRepository->index($data, $perPage);
    }

    public function getList($data): \Illuminate\Database\Eloquent\Collection
    {
        return $this->categoryRepository->getList($data);
    }
}
