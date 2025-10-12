<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends \Spatie\Permission\Models\Role
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'guard_name',
        'name_fa', // اضافه کردن این خط
    ];


      public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

}
