<?php

namespace App\Models;

use App\Traits\HasComment;
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
class Product extends Model
{
    use HasFactory, HasImage, HasComment, SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'category_id',
        'status',
        'author_id'
    ];



    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.product.status.{$this->status}"),
        );
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductContent::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedBlogs(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Blog::class,
            'product_blog_relations',
            'product_id',
            'blog_id'
        );
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
