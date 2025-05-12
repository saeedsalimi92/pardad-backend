<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $status
 * @property int $id
 */
class Blog extends Model
{
    use HasFactory, HasImage, SoftDeletes;

    const STATUS_ACTIVE = "active";
    const STATUS_INACTIVE = "inactive";

    const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    protected $fillable = [
        'title',
        'en_title',
        'description',
        'en_description',
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

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('replies');
    }

    public function faqs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogFaq::class)->where('is_active', true);
    }

    public function relatedBlogs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Blog::class,
            'related_blogs',
            'blog_id',
            'related_blog_id'
        );
    }
}
