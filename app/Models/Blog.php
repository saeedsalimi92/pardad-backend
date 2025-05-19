<?php

namespace App\Models;

use App\Traits\HasFaq;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $status
 * @property int $id
 * @property string $status_translate
 */
class Blog extends Model
{
    use HasFactory, HasImage, HasFaq, SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'status',
        'props',
        'published_at'
    ];

    protected $casts = [
        'props' => 'array',
        'published_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.blog.status.{$this->status}"),
        );
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogContent::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('replies');
    }

    public function relatedBlogs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Blog::class,
            'blog_relations',
            'blog_id',
            'related_blog_id'
        );
    }
}
