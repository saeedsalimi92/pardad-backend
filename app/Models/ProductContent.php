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
 * @property Product $product
 */
class ProductContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
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
        $product = $this->product;
        $category = $product->category;
        $category_slug = $category->contents()->first()->slug ?? null;

        return "{$category_slug}/{$this->product->code}/{$this->slug}";
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
