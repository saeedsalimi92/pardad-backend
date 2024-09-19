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
        'images',
        'instagram',
        'telegram',
        'author',
        'whatsapp',
        'status',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'parent_id',
        'logos',
        'canonical',
        'mo_index',
    ];
}
