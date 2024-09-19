<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'en_title',
        'subtitle',
        'en_subtitle',
        'description',
        'en_description',
        'status'
    ];
}
