<?php

namespace App\Models;

use App\Traits\ActivityLog;

class Role extends \Spatie\Permission\Models\Role
{
    use ActivityLog;
    
    protected $fillable = [
        'name',
        'guard_name',
        'name_fa', // اضافه کردن این خط
    ];


       

}
