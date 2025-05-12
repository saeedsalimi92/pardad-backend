<?php

namespace App\Repositories\Eloquent;

use App\Models\SubCategory;
use App\Repositories\BaseRepository;
use App\Repositories\SubCategoryRepositoryInterface;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    public function model(): string
    {
        return SubCategory::class;
    }
}
