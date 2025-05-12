<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogFaq extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'blog_id', 'question', 'answer', 'is_active'
    ];

    public function blog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
