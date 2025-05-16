<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'locale',
        'content',
        'props'
    ];

    protected $casts = [
        'props' => 'array',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
