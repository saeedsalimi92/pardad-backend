<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'src',
        'isActive',
        'status',
        'type',
        'position',
        'parent_type',
        'parent_id',
    ];
}
