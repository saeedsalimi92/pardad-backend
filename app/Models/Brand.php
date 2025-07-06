<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, HasImage, SoftDeletes;

    protected $fillable = [
        'id',
        'author_id',
        'parent_id',
        'code',
        'status',
        'type',
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
        return $this->hasMany(BrandContent::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, "parent_id", "id");
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Brand::class, "parent_id", "id");
    }
}
