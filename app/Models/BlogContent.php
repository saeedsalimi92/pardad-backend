<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'blog_id',
        'title',
        'locale',
        'content',
        'props'
    ];

    protected $casts = [
        'props' => 'array',
    ];

    public function blog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
