<?php

namespace App\Repositories\Eloquent;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function model(): string
    {
        return Brand::class;
    }
}
