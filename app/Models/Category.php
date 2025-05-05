<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    protected $fillable = [
        'title',
        'en_title',
        'subtitle',
        'en_subtitle',
        'description',
        'en_description',
        'status',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'canonical',
        'no_index',
        'url'
    ];
}
