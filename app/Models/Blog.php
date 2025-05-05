<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'en_title',
        'subtitle',
        'en_subtitle',
        'description',
        'en_description',
        'instagram',
        'telegram',
        'author',
        'whatsapp',
        'status',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'parent_id',
        'parent_type',
        'canonical',
        'no_index',
        'url'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('replies');
    }

    public function faqs()
    {
        return $this->hasMany(BlogFaq::class)->where('is_active', true);
    }
}
