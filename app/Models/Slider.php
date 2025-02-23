<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{

    protected $fillable = [
        'title',
        'en_title',
        'subtitle',
        'en_subtitle',
        'description',
        'en_description',
        'images',
        'status',
        'theme',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'priority',
        'canonical',
        'no_index',
    ];
}
