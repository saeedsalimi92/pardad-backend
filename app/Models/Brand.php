<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'file',
        'canonical',
        'no_index',
        'pdf',
    ];
}
