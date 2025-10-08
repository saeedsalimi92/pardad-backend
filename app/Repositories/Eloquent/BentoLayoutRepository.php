<?php

namespace App\Repositories\Eloquent;

use App\Models\BentoLayout;
use App\Repositories\BaseRepository;
use App\Repositories\BentoLayoutRepositoryInterface;

class BentoLayoutRepository extends BaseRepository implements BentoLayoutRepositoryInterface
{
    public function model(): string
    {
        return BentoLayout::class;
    }

    public function index()
    {
        return $this->query()->get();
    }
}
