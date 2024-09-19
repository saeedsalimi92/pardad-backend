<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'token',
        'status',
        'description',
        'images',
        'otp',
        'otp_expire_time',
        'isRegister',
    ];
}
