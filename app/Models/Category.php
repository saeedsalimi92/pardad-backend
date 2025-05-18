<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasImage, SoftDeletes;

    const TYPE_NORMAL = "NORMAL";
    const TYPE_EXPERT = "EXPERT";

    const TYPES = [
        self::TYPE_NORMAL,
        self::TYPE_EXPERT,
    ];

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    protected $fillable = [
        'id',
        'author_id',
        'code',
        'status',
        'url'
    ];

    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.category.status.{$this->status}"),
        );
    }

    public function typeTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.category.type.{$this->type}"),
        );
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CategoryContent::class);
    }
}
