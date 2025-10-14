<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Image extends Model
{
    use HasFactory, ActivityLog;

    const TYPE_FEATURED = 'featured';
    const TYPE_GALLERY = 'gallery';

    const TYPES = [
        self::TYPE_FEATURED,
        self::TYPE_GALLERY,
    ];

    protected $fillable = [
        "imageable_id",
        "imageable_type",
        "path",
        "disk",
        "mime_type",
        "size",
        "type",
    ];

    public function imageable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

       

}
