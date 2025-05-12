<?php

namespace App\Repositories\Eloquent;

use App\Models\BlogFaq;
use App\Repositories\BaseRepository;
use App\Repositories\BlogFaqRepositoryInterface;

class BlogFaqRepository extends BaseRepository implements BlogFaqRepositoryInterface
{
    public function model(): string
    {
        return BlogFaq::class;
    }
}
