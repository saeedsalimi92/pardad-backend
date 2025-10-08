<?php

namespace App\Repositories\Eloquent;

use App\Models\Image;
use App\Repositories\BaseRepository;
use App\Repositories\ImageRepositoryInterface;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function model(): string
    {
        return Image::class;
    }
}
