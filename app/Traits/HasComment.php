<?php

namespace App\Traits;

use App\Models\Comment;

trait HasComment
{
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
