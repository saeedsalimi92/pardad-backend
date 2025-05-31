<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'slug',
        'locale',
        'content',
        'props'
    ];

    protected $casts = [
        'props' => 'array',
    ];

    public function getUrl(): string
    {
        return "{$this->blog->code}/{$this->slug}";
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
