<?php

namespace App\Models;

use App\Traits\ActivityLog;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $slug
 * @property mixed $id
 * @property array|\Illuminate\Database\Eloquent\Casts\Attribute[]|mixed $props
 * @property mixed $code
 * @property Blog $blog
 */
class BlogContent extends Model
{
    use HasFactory, ActivityLog;

    protected $fillable = [
        'id',
        'blog_id',
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
        $blog = $this->blog;
        $category = $blog->category;
        $category_slug = $category->contents()->first()->slug ?? null;

        return "{$category_slug}/{$this->blog->code}/{$this->slug}";
    }

    public function blog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

     
}
