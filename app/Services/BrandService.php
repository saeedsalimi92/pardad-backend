<?php

namespace App\Services;

use App\Repositories\BrandRepositoryInterface;

readonly class BrandService
{
    public function __construct(private readonly BrandRepositoryInterface $brandRepository)
    {
    }
}
