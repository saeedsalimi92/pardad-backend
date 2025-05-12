<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;
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
        'pdf_file',
    ];
}
