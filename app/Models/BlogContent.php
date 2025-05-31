<?php

namespace App\Models;

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
    use HasFactory;

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
        return "{$this->blog->code}/{$this->slug}";
    }

    public function blog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
