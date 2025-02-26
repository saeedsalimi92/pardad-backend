<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
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
        'theme',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'address',
        'address2',
        'en_address2',
        'en_address',
        'phone',
        'phone2',
        'mobile',
        'mobile2',
        'email',
        'email2',
        'canonical',
        'no_index',
    ];
}
