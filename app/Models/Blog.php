<?php

namespace App\Models;

use App\Traits\HasComment;
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
 * @property string $code
 */
class Blog extends Model
{
    use HasFactory, HasImage, HasFaq, HasComment, SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'category_id',
        'status',
        'props',
        'published_at',
        'author_id'
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

    public function category()
    {
        return $this->belongsTo(Category::class);
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
    public function relatedProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_blog_relations', // ✅ باید دقیقاً همین باشه
            'blog_id',
            'product_id'
        );
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
