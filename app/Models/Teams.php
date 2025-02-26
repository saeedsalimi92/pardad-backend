<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
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
        'instagram',
        'telegram',
        'email',
        'whatsapp',
        'meta_keywords',
        'meta_title',
        'canonical',
        'no_index',
        'url'
    ];
}
