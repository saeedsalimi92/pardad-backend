<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        'status',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'logos',
        'canonical',
        'mo_index',
    ];
}
